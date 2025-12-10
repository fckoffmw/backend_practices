<?php

namespace App\Entities;

/**
 * Сущность статистики продаж
 */
class SalesStatistic
{
    private ?int $id;
    private string $productName;
    private string $category;
    private float $price;
    private int $quantity;
    private float $revenue;
    private \DateTime $saleDate;
    private string $region;
    private string $customerName;
    private string $customerEmail;

    public function __construct(
        string $productName,
        string $category,
        float $price,
        int $quantity,
        \DateTime $saleDate,
        string $region,
        string $customerName,
        string $customerEmail,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->productName = $productName;
        $this->category = $category;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->revenue = $price * $quantity;
        $this->saleDate = $saleDate;
        $this->region = $region;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getRevenue(): float
    {
        return $this->revenue;
    }

    public function getSaleDate(): \DateTime
    {
        return $this->saleDate;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
        $this->recalculateRevenue();
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->recalculateRevenue();
    }

    private function recalculateRevenue(): void
    {
        $this->revenue = $this->price * $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->productName,
            'category' => $this->category,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'revenue' => $this->revenue,
            'sale_date' => $this->saleDate->format('Y-m-d'),
            'region' => $this->region,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
        ];
    }
}