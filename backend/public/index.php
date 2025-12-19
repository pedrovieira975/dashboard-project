<?php

/**
 * Public Front Controller
 *
 * This file is the entry point of the application. All HTTP traffic passes through here.
 * Responsibilities:
 *  - Set essential headers for the REST API
 *  - Handle CORS preflight requests (OPTIONS)
 *  - Load application routes
 */

// Set the response content type to JSON
header('Content-Type: application/json');

/**
 * CORS Headers
 *
 * Allows external frontends (even from different domains) to access the API.
 * - Access-Control-Allow-Origin: '*' → allows any origin
 * - Access-Control-Allow-Methods → which HTTP methods are allowed
 * - Access-Control-Allow-Headers → which headers the frontend can send
 */
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

/**
 * CORS Preflight (OPTIONS) Handling
 *
 * Browsers send an OPTIONS request before POST/PUT/DELETE to check permissions.
 * If the request method is OPTIONS, respond with 200 OK and stop execution.
 */
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/**
 * Load Routes
 *
 * Includes the file containing all route definitions (SaleRoutes.php),
 * where the request is dispatched to the correct controller.
 */
require_once __DIR__ . '/../routes/SaleRoutes.php';
?>