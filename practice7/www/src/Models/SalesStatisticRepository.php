<?php

namespace App\Models;

use App\Entities\SalesStatistic;
use App\Infrastructure\Database\DatabaseConnection;

/**
 * Repository для работы со статистикой продаж
 */
class SalesStatisticRepository
{
    private DatabaseConnection $db;

    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
    }

    /**
     * Получение всех записей
     */
    public function findAll(int $limit = null): array
    {
        $sql = "SELECT * FROM sales_statistics ORDER BY sale_date DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $data = $this->db->fetchAll($sql);
        return array_map([$this, 'mapToEntity'], $data);
    }

    /**
     * Сохранение записи
     */
    public function save(SalesStatistic $statistic): SalesStatistic
    {
        if ($statistic->getId()) {
            return $this->update($statistic);
        } else {
            return $this->create($statistic);
        }
    }

    /**
     * Создание новой записи
     */
    private function create(SalesStatistic $statistic): SalesStatistic
    {
        $id = $this->db->insert('sales_statistics', [
            'product_name' => $statistic->getProductName(),
            'category' => $statistic->getCategory(),
            'price' => $statistic->getPrice(),
            'quantity' => $statistic->getQuantity(),
            'revenue' => $statistic->getRevenue(),
            'sale_date' => $statistic->getSaleDate()->format('Y-m-d'),
            'region' => $statistic->getRegion(),
            'customer_name' => $statistic->getCustomerName(),
            'customer_email' => $statistic->getCustomerEmail(),
        ]);

        $statistic->setId($id);
        return $statistic;
    }

    /**
     * Обновление записи
     */
    private function update(SalesStatistic $statistic): SalesStatistic
    {
        $this->db->update('sales_statistics', [
            'product_name' => $statistic->getProductName(),
            'category' => $statistic->getCategory(),
            'price' => $statistic->getPrice(),
            'quantity' => $statistic->getQuantity(),
            'revenue' => $statistic->getRevenue(),
            'sale_date' => $statistic->getSaleDate()->format('Y-m-d'),
            'region' => $statistic->getRegion(),
            'customer_name' => $statistic->getCustomerName(),
            'customer_email' => $statistic->getCustomerEmail(),
        ], ['id' => $statistic->getId()]);

        return $statistic;
    }

    /**
     * Получение выручки по категориям
     */
    public function getRevenueByCategory(): array
    {
        return $this->db->fetchAll(
            "SELECT category, SUM(revenue) as total_revenue 
             FROM sales_statistics 
             GROUP BY category 
             ORDER BY total_revenue DESC"
        );
    }

    /**
     * Получение продаж по регионам
     */
    public function getSalesByRegion(): array
    {
        return $this->db->fetchAll(
            "SELECT region, COUNT(*) as sales_count, SUM(revenue) as total_revenue 
             FROM sales_statistics 
             GROUP BY region 
             ORDER BY sales_count DESC"
        );
    }

    /**
     * Получение динамики продаж по месяцам
     */
    public function getSalesByMonth(): array
    {
        return $this->db->fetchAll(
            "SELECT 
                DATE_FORMAT(sale_date, '%Y-%m') as month,
                COUNT(*) as sales_count,
                SUM(revenue) as total_revenue
             FROM sales_statistics 
             GROUP BY DATE_FORMAT(sale_date, '%Y-%m')
             ORDER BY month"
        );
    }

    /**
     * Получение общего количества продаж
     */
    public function getTotalSales(): int
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM sales_statistics");
        return (int) $result['count'];
    }

    /**
     * Получение общей выручки
     */
    public function getTotalRevenue(): float
    {
        $result = $this->db->fetchOne("SELECT SUM(revenue) as total FROM sales_statistics");
        return (float) ($result['total'] ?? 0);
    }

    /**
     * Очистка всех данных
     */
    public function truncate(): void
    {
        $this->db->query("TRUNCATE TABLE sales_statistics");
    }

    /**
     * Преобразование данных из БД в сущность
     */
    private function mapToEntity(array $data): SalesStatistic
    {
        return new SalesStatistic(
            productName: $data['product_name'],
            category: $data['category'],
            price: (float) $data['price'],
            quantity: (int) $data['quantity'],
            saleDate: new \DateTime($data['sale_date']),
            region: $data['region'],
            customerName: $data['customer_name'],
            customerEmail: $data['customer_email'],
            id: (int) $data['id']
        );
    }
}