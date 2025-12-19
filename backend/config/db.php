<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

/**
 * Database Connection
 *
 * Sets up a PDO connection to the MySQL database.
 * Provides error handling and returns a JSON message on success or failure.
 */
$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$charset = $_ENV['DB_CHARSET'];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    http_response_code(200);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error connecting to the database', 'details' => $e->getMessage()]);
    exit;
}
?>