<?php
require_once 'config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Faker\Factory;

// Проверка, что фикстуры еще не сгенерированы
$stmt = $pdo->query("SELECT COUNT(*) as count FROM sales_statistics");
$count = $stmt->fetch()['count'];

if ($count > 0) {
    die("Fixtures already generated. Total records: $count");
}

$faker = Factory::create('en_US');

// Генерация 50 фикстур
$categories = ['Electronics', 'Clothing', 'Food', 'Furniture', 'Books', 'Sports'];
$regions = ['Moscow', 'St.Petersburg', 'Novosibirsk', 'Yekaterinburg', 'Kazan'];

$stmt = $pdo->prepare("
    INSERT INTO sales_statistics 
    (product_name, category, price, quantity, revenue, sale_date, region, customer_name, customer_email) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

for ($i = 0; $i < 50; $i++) {
    $price = $faker->randomFloat(2, 100, 50000);
    $quantity = $faker->numberBetween(1, 100);
    $revenue = $price * $quantity;
    
    $stmt->execute([
        $faker->word() . ' ' . $faker->word(),
        $faker->randomElement($categories),
        $price,
        $quantity,
        $revenue,
        $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        $faker->randomElement($regions),
        $faker->name(),
        $faker->email()
    ]);
}

echo "Successfully generated 50 fixtures!";
