<?php
class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct() {
        require_once ROOT . '/configs/database.php';
        
        // Build DSN with cross-platform socket support (Windows/macOS/Linux)
        $dsn = $this->buildDsn();
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Use new Pdo\Mysql namespace constant for PHP 8.5+, fallback to old constant for older versions
        // This handles the PDO::MYSQL_ATTR_INIT_COMMAND deprecation
        if (class_exists('Pdo\Mysql')) {
            $options[\Pdo\Mysql::ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
        } elseif (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
            $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci";
        }

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    private function buildDsn(): string {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        
        // Add socket path for Unix-like systems (macOS/Linux)
        // Windows uses TCP connections and doesn't need unix_socket
        if (PHP_OS_FAMILY !== 'Windows') {
            // Try common Unix socket locations
            $sockets = [
                '/var/run/mysqld/mysqld.sock',  // Linux standard
                '/tmp/mysql.sock',               // macOS with MySQL installed via Homebrew
                '/opt/local/var/run/mysqld/mysqld.sock', // macOS with MacPorts
                '/usr/local/mysql/data/mysqld.sock'      // macOS with MySQL from mysql.com
            ];
            
            foreach ($sockets as $socket) {
                if (file_exists($socket)) {
                    $dsn .= ";unix_socket=" . $socket;
                    break;
                }
            }
        }
        
        return $dsn;
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }
}