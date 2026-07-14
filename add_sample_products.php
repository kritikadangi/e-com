
<?php
require_once 'config/database.php';

$products = [
    // Women's Clothing
    [
        'name' => 'Floral Summer Maxi Dress',
        'category' => 'Women',
        'description' => 'Lightweight floral maxi dress perfect for summer days, with adjustable straps and side pockets',
        'price' => 69.99,
        'stock' => 15,
        'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&q=80'
    ],
    [
        'name' => 'Classic White Button-Down Shirt',
        'category' => 'Women',
        'description' => 'Crisp white button-down shirt made with 100% cotton, perfect for office or casual wear',
        'price' => 45.99,
        'stock' => 20,
        'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=400&q=80'
    ],
    [
        'name' => 'High-Waisted Denim Jeans',
        'category' => 'Women',
        'description' => 'High-waisted skinny jeans with stretch fabric for comfort and style',
        'price' => 59.99,
        'stock' => 18,
        'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=400&q=80'
    ],
    [
        'name' => 'Leather Moto Jacket',
        'category' => 'Women',
        'description' => 'Genuine leather moto jacket with silver hardware, fully lined',
        'price' => 199.99,
        'stock' => 8,
        'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&q=80'
    ],
    [
        'name' => 'Cashmere Sweater',
        'category' => 'Women',
        'description' => 'Soft cashmere blend sweater in neutral beige, crew neck',
        'price' => 89.99,
        'stock' => 12,
        'image' => 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400&q=80'
    ],
    // Men's Clothing
    [
        'name' => 'Slim Fit Oxford Shirt',
        'category' => 'Men',
        'description' => 'Classic slim fit oxford shirt in navy blue, button-down collar',
        'price' => 49.99,
        'stock' => 25,
        'image' => 'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=400&q=80'
    ],
    [
        'name' => 'Vintage Denim Jacket',
        'category' => 'Men',
        'description' => 'Distressed vintage style denim jacket with chest pockets',
        'price' => 79.99,
        'stock' => 15,
        'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=400&q=80'
    ],
    [
        'name' => 'Crew Neck T-Shirt 3-Pack',
        'category' => 'Men',
        'description' => 'Set of 3 cotton crew neck t-shirts in black, white, and gray',
        'price' => 34.99,
        'stock' => 30,
        'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&q=80'
    ],
    [
        'name' => 'Chino Pants',
        'category' => 'Men',
        'description' => 'Classic chino pants in khaki, slim fit with stretch',
        'price' => 64.99,
        'stock' => 22,
        'image' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=400&q=80'
    ],
    [
        'name' => 'Hoodie Sweatshirt',
        'category' => 'Men',
        'description' => 'Cozy fleece hoodie with kangaroo pocket, charcoal gray',
        'price' => 54.99,
        'stock' => 18,
        'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=400&q=80'
    ],
    // Accessories
    [
        'name' => 'Leather Belt',
        'category' => 'Accessories',
        'description' => 'Genuine leather belt with silver buckle, adjustable',
        'price' => 29.99,
        'stock' => 25,
        'image' => 'https://images.unsplash.com/photo-1596108973332-fa8808d2c266?w=400&q=80'
    ],
    [
        'name' => 'Canvas Sneakers',
        'category' => 'Accessories',
        'description' => 'Classic white canvas sneakers, unisex design',
        'price' => 44.99,
        'stock' => 35,
        'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80'
    ],
    [
        'name' => 'Wool Beanie',
        'category' => 'Accessories',
        'description' => 'Soft wool blend beanie in black, one size fits all',
        'price' => 19.99,
        'stock' => 40,
        'image' => 'https://images.unsplash.com/photo-1532072437212-400bd5a170e1?w=400&q=80'
    ],
    // Child
    [
        'name' => 'Unicorn Graphic Tee',
        'category' => 'Child',
        'description' => 'Cotton graphic tee with colorful unicorn print for girls',
        'price' => 16.99,
        'stock' => 30,
        'image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400&q=80'
    ],
    [
        'name' => 'Boys Striped Hoodie',
        'category' => 'Child',
        'description' => 'Cozy striped hoodie for boys, blue and gray',
        'price' => 24.99,
        'stock' => 25,
        'image' => 'https://images.unsplash.com/photo-1558171813-4c088753af8f?w=400&q=80'
    ]
];

$insertedCount = 0;

foreach ($products as $product) {
    $stmt = $pdo->prepare("INSERT INTO products (name, category, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $product['name'],
        $product['category'],
        $product['description'],
        $product['price'],
        $product['stock'],
        $product['image']
    ]);
    $insertedCount++;
}

echo "Successfully inserted {$insertedCount} products into the database!";

?>

