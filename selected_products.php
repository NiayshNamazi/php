<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Add product to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $productId]);
}

// Fetch selected products
$stmt = $pdo->prepare("SELECT products.name, products.price, products.image 
                       FROM cart 
                       JOIN products ON cart.product_id = products.id 
                       WHERE cart.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cartProducts = $stmt->fetchAll();
?>

<div class="container">
    <h2>Selected Products</h2>
    <?php if (count($cartProducts) > 0): ?>
        <?php foreach ($cartProducts as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="Product Image">
                <div>
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Price: $<?php echo $product['price']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <button onclick="alert('Payment processed!')">Proceed to Payment</button>
    <?php else: ?>
        <p>No products selected yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
