<?php
require_once __DIR__ . '/includes/db.php';

$productos = [
    [
        'nombre_corte' => 'Churrasco',
        'precio_kilo' => 35000.00,
        'stock_kg' => 10.000,
        'descripcion' => 'Corte de res ideal para asar, jugoso y con buen sabor.',
        'id_tipo' => 1 // Res
    ],
    [
        'nombre_corte' => 'Chuleta Valluna',
        'precio_kilo' => 22000.00,
        'stock_kg' => 15.000,
        'descripcion' => 'Corte de cerdo apanado o para freír, típico de la región.',
        'id_tipo' => 2 // Cerdo
    ],
    [
        'nombre_corte' => 'Alitas BBQ',
        'precio_kilo' => 16000.00,
        'stock_kg' => 25.000,
        'descripcion' => 'Alitas de pollo marinadas, listas para asar o freír.',
        'id_tipo' => 3 // Pollo
    ],
    [
        'nombre_corte' => 'Longaniza',
        'precio_kilo' => 18000.00,
        'stock_kg' => 8.000,
        'descripcion' => 'Embutido tradicional, perfecto para acompañar asados.',
        'id_tipo' => 4 // Embutidos
    ]
];

try {
    $stmt = $pdo->prepare("INSERT INTO productos (nombre_corte, precio_kilo, stock_kg, descripcion, id_tipo) VALUES (:nombre_corte, :precio_kilo, :stock_kg, :descripcion, :id_tipo)");

    foreach ($productos as $prod) {
        $stmt->execute([
            ':nombre_corte' => $prod['nombre_corte'],
            ':precio_kilo' => $prod['precio_kilo'],
            ':stock_kg' => $prod['stock_kg'],
            ':descripcion' => $prod['descripcion'],
            ':id_tipo' => $prod['id_tipo']
        ]);
        echo "Producto agregado: " . $prod['nombre_corte'] . "\n";
    }
    echo "Todos los productos fueron agregados exitosamente.";
} catch (PDOException $e) {
    echo "Error al agregar productos: " . $e->getMessage();
}
