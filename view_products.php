<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<div class="container">
    <h2>View Products</h2>
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?php echo $product['image']; ?>" alt="Product Image">
            <div>
                <h3><?php echo $product['name']; ?></h3>
                <p>Price: $<?php echo $product['price']; ?></p>
                <form method="POST" action="selected_products.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit">Select</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
