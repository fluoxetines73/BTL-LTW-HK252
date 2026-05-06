<?php
/**
 * 404 Error Page Verification Script
 *
 * Usage:
 *   php tests/verify_404.php
 *
 * Or start server and run:
 *   php -S localhost:8000
 *   # Then open browser to: http://localhost:8000/tests/verify_404.php
 */

echo "=================================================\n";
echo "  CGV Cinema - 404 Error Page Verification\n";
echo "=================================================\n\n";

$baseUrl = 'http://localhost:8000';
$passed = 0;
$failed = 0;

function test404($url, $description, $expectedHttpCode = 404) {
    global $baseUrl, $passed, $failed;

    echo "Test: $description\n";
    echo "URL: $url\n";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Accept: text/html',
        ],
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo "  ❌ FAIL: cURL error: $error\n\n";
        $failed++;
        return false;
    }

    $success = ($httpCode === $expectedHttpCode);
    $hasCGVContent = (
        stripos($response, '404') !== false &&
        (stripos($response, 'Không tìm thấy') !== false || stripos($response, 'khong tim thay') !== false) &&
        stripos($response, 'CGV') !== false
    );

    if ($success && $hasCGVContent) {
        echo "  ✅ PASS: HTTP $httpCode (expected $expectedHttpCode)\n";
        echo "         Contains CGV branding and Vietnamese message\n\n";
        $passed++;
        return true;
    } else {
        echo "  ❌ FAIL: HTTP $httpCode (expected $expectedHttpCode)\n";
        if (!$hasCGVContent) {
            echo "         Missing CGV branding or Vietnamese message\n";
        }
        // Show snippet of response for debugging
        $snippet = htmlspecialchars(substr($response, 0, 300));
        echo "         Response preview: $snippet...\n\n";
        $failed++;
        return false;
    }
}

echo "NOTE: Start local server first:\n";
echo "      php -S localhost:8000\n\n";

echo "----------------------------------------------\n";
echo "Running tests...\n";
echo "----------------------------------------------\n\n";

// Test 1: Non-existent controller
test404(
    "$baseUrl/nonexistent-controller-xyz",
    "Non-existent controller triggers 404",
    404
);

// Test 2: Non-existent method on existing controller
test404(
    "$baseUrl/home/nonexistent-method-xyz",
    "Non-existent method triggers 404",
    404
);

// Test 3: Deep non-existent path
test404(
    "$baseUrl/admin/movies/nonexistent/deep/path",
    "Deep non-existent path triggers 404",
    404
);

// Test 4: Verify 404 page has required elements
echo "Test: 404 page has required elements\n";
echo "URL: $baseUrl/test-nonexistent-123\n";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "$baseUrl/test-nonexistent-123",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$checks = [
    'HTTP 404' => ($httpCode === 404),
    'Contains 404 code' => stripos($response, '404') !== false,
    'Vietnamese message' => stripos($response, 'Không tìm thấy') !== false,
    'Home link' => stripos($response, 'Về trang chủ') !== false || stripos($response, 'trang chủ') !== false,
    'CGV branding' => stripos($response, 'CGV') !== false || stripos($response, 'cgvlogo') !== false,
];

$allPassed = true;
foreach ($checks as $name => $result) {
    $icon = $result ? '✅' : '❌';
    echo "  $icon $name\n";
    if (!$result) $allPassed = false;
}

if ($allPassed) {
    echo "\n  ✅ PASS: All required elements present\n\n";
    $passed++;
} else {
    echo "\n  ❌ FAIL: Some required elements missing\n\n";
    $failed++;
}

echo "----------------------------------------------\n";
echo "SUMMARY\n";
echo "----------------------------------------------\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";
echo "Total:  " . ($passed + $failed) . "\n";

if ($failed === 0) {
    echo "\n✅ All 404 tests PASSED!\n";
    exit(0);
} else {
    echo "\n❌ Some tests FAILED. Please check configuration.\n";
    exit(1);
}
