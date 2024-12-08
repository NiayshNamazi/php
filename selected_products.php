<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Delete product from cart
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $_SESSION['user_id'], $deleteId);
    if ($stmt->execute()) {
        $message = "Product removed from cart successfully!";
    } else {
        $error = "Failed to remove the product.";
    }
}

// Fetch selected products
$stmt = $conn->prepare("SELECT products.id, products.name, products.price, products.image 
                       FROM cart 
                       JOIN products ON cart.product_id = products.id 
                       WHERE cart.user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$cartProducts = $result->fetch_all(MYSQLI_ASSOC);
?>
<style>/* Delete Button Styling */
.delete-btn {
    background-color: #ff4d4d;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    cursor: pointer;
    font-size: 14px;
    margin-top: 10px;
}

.delete-btn:hover {
    background-color: #e60000;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    text-align: center;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    text-align: center;
}
</style>
<div class="container">
    <h2>Selected Products</h2>
    <?php if (isset($message)): ?>
        <div class="success-message"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (count($cartProducts) > 0): ?>
        <?php foreach ($cartProducts as $product): ?>
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="Product Image">
                <div>
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Price: $<?php echo $product['price']; ?></p>
                    <form method="GET" action="">
                        <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="delete-btn">Remove</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <button onclick="alert('Payment processed!')">Proceed to Payment</button>
    <?php else: ?>
        <p>No products selected yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
