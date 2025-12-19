<?php

/**
 * SaleController
 *
 * Handles HTTP requests related to sales.
 * Acts as an intermediary between HTTP layer and SaleService.
 * Responsible for request handling, response formatting, and HTTP status codes.
 */
class SaleController {
    private $saleService;

    /**
     * Constructor
     *
     * Injects the SaleService dependency.
     *
     * @param SaleService $saleService Service responsible for sales business logic
     */
    public function __construct($saleService) {
        $this->saleService = $saleService;
    }

    /**
     * Create a new sale.
     *
     * @param string $productName Name of the product sold
     * @param float $amount Sale amount
     * @param string $saleDate Sale date in YYYY-MM-DD format
     * @return void Outputs JSON response
     */
    public function postSale($productName, $amount, $saleDate) {
        try {
            $sale = $this->saleService->createSale($productName, $amount, $saleDate);

            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode($sale);

        } catch (\Throwable $th) {
            http_response_code(500);
            echo json_encode(['error' => 'Could not create a new sale']);
        }
    }

    /**
     * Retrieve all sales.
     *
     * @return void Outputs JSON response with list of sales
     */
    public function getAllSales() {
        try {
            $sales = $this->saleService->getAllSales();

            if (!$sales) {
                http_response_code(404);
                echo json_encode(['error' => 'Sales not found']);
                return;
            }

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($sales);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Could not fetch sales']);
        }
    }

    /**
     * Retrieve a single sale by ID.
     *
     * @param int $id Sale ID
     * @return void Outputs JSON response
     */
    public function getSaleById($id) {
        try {
            $sale = $this->saleService->getSaleById($id);

            if (!$sale) {
                http_response_code(404);
                echo json_encode(['error' => 'Sale not found']);
                return;
            }

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($sale);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Could not fetch sale']);
        }
    }

    /**
     * Update an existing sale.
     *
     * @param int $id Sale ID
     * @param string $productName Name of the product
     * @param float $amount Sale amount
     * @param string $saleDate Sale date in YYYY-MM-DD format
     * @return void Outputs JSON response
     */
    public function putSale($id, $productName, $amount, $saleDate) {
        try {
            $sale = $this->saleService->getSaleById($id);

            if (!$sale) {
                http_response_code(404);
                echo json_encode(['error' => 'Sale not found']);
                return;
            }

            $this->saleService->updateSale($id, $productName, $amount, $saleDate);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                'message' => 'Sale updated successfully',
                'sale_id' => $id
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Could not update sale']);
        }
    }

    /**
     * Delete a sale by ID.
     *
     * @param int $id Sale ID
     * @return void Outputs JSON response
     */
    public function deleteSale($id) {
        try {
            $sale = $this->saleService->getSaleById($id);

            if (!$sale) {
                http_response_code(404);
                echo json_encode(['error' => 'Sale not found']);
                return;
            }

            $this->saleService->deleteSale($id);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                'message' => 'Sale deleted successfully',
                'sale_id' => $id
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Could not delete sale']);
        }
    }
}
?>