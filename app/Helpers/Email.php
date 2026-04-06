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

        $message = "
            <html>
            <head>
                <meta charset=\"UTF-8\">
            </head>
            <body style=\"font-family: Arial, sans-serif; color: #1e293b;\">
                <h2 style=\"margin: 0 0 12px;\">Xin chào {$safeName},</h2>
                <p>Cảm ơn bạn đã đăng ký tài khoản.</p>
                <p>Mã OTP của bạn là:</p>
                <p style=\"font-size: 28px; font-weight: bold; letter-spacing: 3px; color: #0f766e; margin: 10px 0;\">{$safeOtp}</p>
                <p>Mã có hiệu lực trong {$expireMinutes} phút.</p>
                <p>Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.</p>
            </body>
            </html>
        ";

        return $this->sendSmtpMail($toEmail, $subject, $message);
    }

    private function sendSmtpMail(string $toEmail, string $subject, string $htmlBody): bool {
        $fromEmail = SMTP_FROM_EMAIL;
        $fromName = $this->encodeMimeHeader(SMTP_FROM_NAME);
        $subjectEncoded = $this->encodeMimeHeader($subject);

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            "From: {$fromName} <{$fromEmail}>",
            "To: <{$toEmail}>",
            "Subject: {$subjectEncoded}",
            'Date: ' . date(DATE_RFC2822),
        ];

        $body = implode("\r\n", $headers) . "\r\n\r\n" . $this->normalizeBody($htmlBody);

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

    public function getError(): string {
        return $this->error;
    }
}
