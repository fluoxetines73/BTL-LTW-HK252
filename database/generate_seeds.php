<?php
/**
 * Helper script to generate bcrypt hashes for seed.sql
 * Run: php database/generate_seeds.php
 */

$adminHash = password_hash('admin', PASSWORD_DEFAULT);
$memberHash = password_hash('password', PASSWORD_DEFAULT);

echo 'admin hash: ' . $adminHash . PHP_EOL;
echo "member password ('password') hash: " . $memberHash . PHP_EOL;
echo PHP_EOL;
echo 'Update these in database/seed.sql' . PHP_EOL;
