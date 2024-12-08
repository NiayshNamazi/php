<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

// Fetch products from the database
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container">
    <h2>View Products</h2>
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
            <div>
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                <form method="POST" action="selected_products.php">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <button type="submit">Select</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
