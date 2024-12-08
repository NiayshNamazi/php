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
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $_SESSION['user_id'], $productId);

    if ($stmt->execute()) {
        $successMessage = "Product added to cart successfully!";
    } else {
        $errorMessage = "Failed to add the product to the cart.";
    }
    $stmt->close();
}

// Fetch selected products
$stmt = $conn->prepare("SELECT products.name, products.price, products.image 
                        FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$cartProducts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="container">
    <h2>Selected Products</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="error-message"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
    <?php if (!empty($successMessage)): ?>
        <div class="success-message"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <?php if (count($cartProducts) > 0): ?>
        <?php foreach ($cartProducts as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="Product Image">
                <div>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <button onclick="alert('Payment processed!')">Proceed to Payment</button>
    <?php else: ?>
        <p>No products selected yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
