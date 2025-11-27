<?php
require_once __DIR__ . '/includes/db.php';
try {
    $stmt = $pdo->query("SELECT id, nombre_corte, imagen FROM productos");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    file_put_contents('debug_products.txt', print_r($products, true));
    echo "Dumped products to debug_products.txt";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
