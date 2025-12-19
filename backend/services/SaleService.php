<?php

/**
 * SaleService
 *
 * Class responsible for managing sales in the database.
 * Provides methods to create, read, update, and delete sales records.
 */
class SaleService {
    private $pdo;

    /**
     * Constructor
     *
     * @param PDO $pdo PDO database connection
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Create a new sale.
     *
     * @param string $productName Name of the product sold
     * @param float $amount Sale amount
     * @param string $saleDate Sale date in YYYY-MM-DD format
     * @return int ID of the newly created sale
     */
    public function createSale($productName, $amount, $saleDate) {
        $sql = "INSERT INTO sales (product_name, amount, sale_date) 
                VALUES (:product_name, :amount, :sale_date)";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            'product_name' => $productName,
            'amount'       => $amount,
            'sale_date'    => $saleDate
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
     * Get all sales.
     *
     * @return array Array of sales records (associative arrays)
     */
    public function getAllSales() {
        $query = $this->pdo->query("SELECT * FROM sales ORDER BY sale_date DESC");
        return $query->fetchAll();
    }

    /**
     * Get a specific sale by ID.
     *
     * @param int $id Sale ID
     * @return array|null Sale record as an associative array or null if not found
     */
    public function getSaleById($id) {
        $query = $this->pdo->prepare("SELECT * FROM sales WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    /**
     * Update a sale record.
     *
     * @param int $id Sale ID
     * @param string $productName Name of the product
     * @param float $amount Sale amount
     * @param string $saleDate Sale date in YYYY-MM-DD format
     * @return bool True if updated successfully, False otherwise
     */
    public function updateSale($id, $productName, $amount, $saleDate) {
        $sql = "UPDATE sales SET product_name = :product_name, amount = :amount, sale_date = :sale_date
                WHERE id = :id";
        $query = $this->pdo->prepare($sql);
        return $query->execute([
            'id'           => $id,
            'product_name' => $productName,
            'amount'       => $amount,
            'sale_date'    => $saleDate
        ]);
    }

    /**
     * Delete a sale record.
     *
     * @param int $id Sale ID
     * @return bool True if deleted successfully, False otherwise
     */
    public function deleteSale($id) {
        $query = $this->pdo->prepare("DELETE FROM sales WHERE id = :id");
        return $query->execute(['id' => $id]);
    }
}
?>