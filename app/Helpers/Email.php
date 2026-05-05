<?php

class Email {
    private string $error = '';

    public function __construct() {
        if (defined('ROOT') && !defined('SMTP_HOST')) {
            require_once ROOT . '/configs/mail.php';
        }
    }

    public function sendRegistrationOtp(string $toEmail, string $fullName, string $otp, int $expireMinutes = 5): bool {
        if (!$this->isSmtpConfigReady()) {
            $this->error = 'Cấu hình SMTP chưa hoàn chỉnh. Hãy cập nhật configs/mail.php trước khi gửi OTP.';
            return false;
        }

        $subject = 'Mã OTP xác thực đăng ký tài khoản';
        $safeName = htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8');
        $safeOtp = htmlspecialchars($otp, ENT_QUOTES, 'UTF-8');
        $logoMeta = $this->buildLogoMarkup();
        $verifyUrl = htmlspecialchars($this->buildVerifyUrl($toEmail), ENT_QUOTES, 'UTF-8');

        $message = "
            <html>
            <head>
                <meta charset=\"UTF-8\">
            </head>
            <body style=\"font-family: Arial, sans-serif; color: #1e293b; background: #f3f4f6; margin: 0; padding: 0;\">
                <div style=\"max-width: 620px; margin: 0 auto; padding: 28px 18px;\">
                    <div style=\"background: linear-gradient(135deg, #111827 0%, #1f2937 100%); padding: 24px 24px 20px; border-radius: 18px 18px 0 0; text-align: center;\">
                        {$logoMeta['markup']}
                        <div style=\"margin-top: 12px; font-size: 13px; letter-spacing: 1.8px; text-transform: uppercase; color: #f9fafb; opacity: 0.9;\">Xác thực đăng ký tài khoản</div>
                    </div>
                    <div style=\"background: #ffffff; border: 1px solid #e5e7eb; border-top: 0; border-radius: 0 0 18px 18px; padding: 30px 26px 28px; box-shadow: 0 14px 40px rgba(15, 23, 42, 0.08);\">
                        <h2 style=\"margin: 0 0 12px; color: #0f172a; font-size: 24px; line-height: 1.25;\">Xin chào {$safeName},</h2>
                        <p style=\"margin: 0 0 12px; line-height: 1.7;\">Cảm ơn bạn đã đăng ký tài khoản. Để hoàn tất, hãy nhập mã OTP bên dưới vào trang xác thực.</p>

                        <div style=\"margin: 22px 0 18px; padding: 18px; border: 1px solid #d1fae5; background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 100%); border-radius: 16px; text-align: center;\">
                            <div style=\"font-size: 12px; font-weight: 700; letter-spacing: 1.6px; text-transform: uppercase; color: #047857; margin-bottom: 10px;\">Mã OTP của bạn</div>
                            <div style=\"display: inline-block; padding: 14px 18px; min-width: 220px; border-radius: 14px; background: #ffffff; border: 2px dashed #10b981; color: #047857; font-size: 34px; font-weight: 800; letter-spacing: 8px; line-height: 1;\">{$safeOtp}</div>
                            <div style=\"margin-top: 12px; font-size: 14px; color: #475569;\">Mã có hiệu lực trong {$expireMinutes} phút.</div>
                        </div>

                        <div style=\"text-align: center; margin: 22px 0 12px;\">
                            <a href=\"{$verifyUrl}\" style=\"display: inline-block; background: #e71a0f; color: #ffffff; text-decoration: none; font-weight: 700; padding: 12px 22px; border-radius: 999px;\">Xác nhận tài khoản</a>
                        </div>

                        <p style=\"margin: 14px 0 0; line-height: 1.7; color: #64748b; font-size: 14px;\">Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        return $this->sendSmtpMail($toEmail, $subject, $message, $logoMeta['path'], $logoMeta['contentId']);
    }

    private function buildLogoMarkup(): array {
        $logoPath = ROOT . '/public/images/logo/cgvlogo.png';
        $contentId = 'cgv-logo';

        if (!is_file($logoPath) || !is_readable($logoPath)) {
            return [
                'markup' => '<div style="font-size: 30px; font-weight: 800; letter-spacing: 2px; color: #ffffff;">CGV</div>',
                'path' => null,
                'contentId' => null,
            ];
        }

        return [
            'markup' => '<img src="cid:' . $contentId . '" alt="CGV Booking" style="display:block; margin:0 auto; max-width:150px; width:150px; height:auto;">',
            'path' => $logoPath,
            'contentId' => $contentId,
        ];
    }

    private function buildVerifyUrl(string $toEmail): string {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\');
        $path = $basePath === '' ? '' : $basePath;

        return $scheme . '://' . $host . $path . '/auth/verifyOtp?email=' . urlencode($toEmail);
    }

    private function sendSmtpMail(string $toEmail, string $subject, string $htmlBody, ?string $inlineImagePath = null, ?string $inlineContentId = null): bool {
        $fromEmail = SMTP_FROM_EMAIL;
        $fromName = $this->encodeMimeHeader(SMTP_FROM_NAME);
        $subjectEncoded = $this->encodeMimeHeader($subject);
        $boundaryOuter = '=_outer_' . bin2hex(random_bytes(12));
        $boundaryAlt = '=_alt_' . bin2hex(random_bytes(12));

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: multipart/related; boundary="' . $boundaryOuter . '"',
            "From: {$fromName} <{$fromEmail}>",
            "To: <{$toEmail}>",
            "Subject: {$subjectEncoded}",
            'Date: ' . date(DATE_RFC2822),
        ];

        $bodyParts = [];
        $bodyParts[] = '--' . $boundaryOuter;
        $bodyParts[] = 'Content-Type: multipart/alternative; boundary="' . $boundaryAlt . '"';
        $bodyParts[] = '';
        $bodyParts[] = '--' . $boundaryAlt;
        $bodyParts[] = 'Content-Type: text/plain; charset=UTF-8';
        $bodyParts[] = 'Content-Transfer-Encoding: 7bit';
        $bodyParts[] = '';
        $bodyParts[] = 'Xin chào, bạn có một mã OTP mới để xác thực tài khoản. Vui lòng mở email HTML để xem đầy đủ nội dung.';
        $bodyParts[] = '--' . $boundaryAlt;
        $bodyParts[] = 'Content-Type: text/html; charset=UTF-8';
        $bodyParts[] = 'Content-Transfer-Encoding: 7bit';
        $bodyParts[] = '';
        $bodyParts[] = $this->normalizeBody($htmlBody);
        $bodyParts[] = '--' . $boundaryAlt . '--';

        if ($inlineImagePath && $inlineContentId) {
            $bodyParts[] = '';
            $bodyParts[] = '--' . $boundaryOuter;
            $bodyParts[] = 'Content-Type: ' . $this->detectMimeType($inlineImagePath);
            $bodyParts[] = 'Content-Transfer-Encoding: base64';
            $bodyParts[] = 'Content-ID: <' . $inlineContentId . '>';
            $bodyParts[] = 'Content-Disposition: inline; filename="' . basename($inlineImagePath) . '"';
            $bodyParts[] = '';
            $bodyParts[] = chunk_split(base64_encode((string)file_get_contents($inlineImagePath)), 76, "\r\n");
        }

        $bodyParts[] = '--' . $boundaryOuter . '--';

        $body = implode("\r\n", $headers) . "\r\n\r\n" . implode("\r\n", $bodyParts);

        $host = SMTP_HOST;
        $port = (int)SMTP_PORT;
        $transport = (defined('SMTP_ENCRYPTION') && SMTP_ENCRYPTION === 'ssl') ? 'ssl://' : 'tcp://';
        $timeout = defined('SMTP_TIMEOUT') ? (int)SMTP_TIMEOUT : 20;

        $stream = @stream_socket_client(
            $transport . $host . ':' . $port,
            $errno,
            $errstr,
            $timeout
        );

        if (!$stream) {
            $this->error = 'Không thể kết nối tới SMTP server: ' . $errstr;
            return false;
        }

        stream_set_timeout($stream, $timeout);

        try {
            $this->expectCode($stream, 220);
            $this->writeLine($stream, 'EHLO localhost');
            $this->expectCode($stream, 250);

            if (defined('SMTP_ENCRYPTION') && SMTP_ENCRYPTION === 'tls') {
                $this->writeLine($stream, 'STARTTLS');
                $this->expectCode($stream, 220);
                if (!stream_socket_enable_crypto($stream, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new RuntimeException('Không thể khởi tạo TLS cho SMTP.');
                }
                $this->writeLine($stream, 'EHLO localhost');
                $this->expectCode($stream, 250);
            }

            if (SMTP_USERNAME !== '' && SMTP_PASSWORD !== '') {
                $this->writeLine($stream, 'AUTH LOGIN');
                $this->expectCode($stream, 334);
                $this->writeLine($stream, base64_encode(SMTP_USERNAME));
                $this->expectCode($stream, 334);
                $this->writeLine($stream, base64_encode(SMTP_PASSWORD));
                $this->expectCode($stream, 235);
            }

            $this->writeLine($stream, 'MAIL FROM:<' . SMTP_FROM_EMAIL . '>');
            $this->expectCode($stream, 250);

            $this->writeLine($stream, 'RCPT TO:<' . $toEmail . '>');
            $this->expectCode($stream, 250, 251);

            $this->writeLine($stream, 'DATA');
            $this->expectCode($stream, 354);

            $this->writeLine($stream, $this->dotStuff($body) . "\r\n.");
            $this->expectCode($stream, 250);

            $this->writeLine($stream, 'QUIT');
            fclose($stream);

            return true;
        } catch (Throwable $exception) {
            fclose($stream);
            $this->error = $exception->getMessage();
            return false;
        }
    }

    private function isSmtpConfigReady(): bool {
        $requiredValues = [SMTP_HOST, SMTP_USERNAME, SMTP_PASSWORD, SMTP_FROM_EMAIL, SMTP_FROM_NAME];
        foreach ($requiredValues as $value) {
            if (!is_string($value) || trim($value) === '') {
                return false;
            }
        }

        if (SMTP_HOST === 'smtp.gmail.com' && SMTP_USERNAME === 'your_email@gmail.com') {
            return false;
        }

        if (SMTP_PASSWORD === 'your_app_password') {
            return false;
        }

        return true;
    }

    private function writeLine($stream, string $line): void {
        fwrite($stream, $line . "\r\n");
    }

    private function expectCode($stream, int ...$expectedCodes): void {
        $response = '';
        while (!feof($stream)) {
            $line = fgets($stream, 515);
            if ($line === false) {
                break;
            }
            $response .= $line;
            if (preg_match('/^(\d{3})([\s-])/', $line, $matches) && $matches[2] === ' ') {
                $code = (int)$matches[1];
                if (in_array($code, $expectedCodes, true)) {
                    return;
                }
                throw new RuntimeException('SMTP phản hồi không mong đợi: ' . trim($response));
            }
        }

        throw new RuntimeException('SMTP không phản hồi đúng cách.');
    }

    private function encodeMimeHeader(string $value): string {
        if (function_exists('mb_encode_mimeheader')) {
            return mb_encode_mimeheader($value, 'UTF-8', 'B', "\r\n");
        }

        return '=?UTF-8?B?' . base64_encode($value) . '?=';
    }

    private function normalizeBody(string $htmlBody): string {
        return str_replace(["\r\n", "\r"], "\n", $htmlBody);
    }

    private function dotStuff(string $body): string {
        $lines = explode("\n", $body);
        foreach ($lines as &$line) {
            if (isset($line[0]) && $line[0] === '.') {
                $line = '.' . $line;
            }
            $line = rtrim($line, "\r");
        }

        return implode("\r\n", $lines);
    }

    private function detectMimeType(string $filePath): string {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($extension) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }

    public function getError(): string {
        return $this->error;
    }
}
