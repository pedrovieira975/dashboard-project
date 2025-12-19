<?php

require_once __DIR__ . '/../controllers/SaleController.php';
require_once __DIR__ . '/../services/SaleService.php';
require_once __DIR__ . '/../config/db.php';

/**
 * SaleService
 *
 * Handles all business logic related to sales.
 * Receives the PDO instance to execute database operations.
 */
$saleService = new SaleService($pdo);

/**
 * SaleController
 *
 * Handles HTTP requests for sales.
 * Receives the SaleService instance to interact with sales data.
 */
$saleController = new SaleController($saleService);

/**
 * HTTP Request Information
 *
 * - $method: HTTP method used (GET, POST, PUT, DELETE)
 * - $uri: Requested URI path (e.g. /sales, /sales/1)
 */
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/**
 * Application Routes
 *
 * Maps HTTP methods and URI patterns to controller actions.
 * Uses anonymous functions (arrow functions) as route handlers.
 */
$routes = [
    'GET' => [
        '/sales' => fn() => $saleController->getAllSales(),
        '/sales/{id}' => fn($id) => $saleController->getSaleById($id),
    ],
    'POST' => [
        '/sales' => fn($data) => $saleController->postSale(
            $data['product_name'] ?? null,
            $data['amount'] ?? null,
            $data['sale_date'] ?? null
        ),
    ],
    'PUT' => [
        '/sales/{id}' => fn($id, $data) => $saleController->putSale(
            $id,
            $data['product_name'] ?? null,
            $data['amount'] ?? null,
            $data['sale_date'] ?? null
        ),
    ],
    'DELETE' => [
        '/sales/{id}' => fn($id) => $saleController->deleteSale($id),
    ],
];

/**
 * Dispatch function
 *
 * Matches the incoming request with a route definition
 * and executes the corresponding handler.
 *
 * @param array  $routes Registered application routes
 * @param string $method HTTP method
 * @param string $uri    Requested URI
 */
function dispatch($routes, $method, $uri) {
    foreach ($routes[$method] ?? [] as $route => $handler) {
        if (preg_match('#^' . str_replace('{id}', '(\d+)', $route) . '$#', $uri, $matches)) {
            array_shift($matches);
            $data = json_decode(file_get_contents('php://input'), true) ?: [];

            $args = $matches;
            if (in_array($method, ['POST', 'PUT'], true)) {
                $args[] = $data;
            }

            return $handler(...$args);
        }
    }
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}

dispatch($routes, $method, $uri);
?>