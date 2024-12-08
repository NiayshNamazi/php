<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Add product to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    if (isset($_SESSION['user_id'])) {
        $productId = $_POST['product_id'];
        $userId = $_SESSION['user_id'];

        // Check if the product is already in the cart
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // Add product to cart
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $userId, $productId);
            if ($stmt->execute()) {
                $message = "محصول با موفقیت به سبد خرید شما اضافه شد !";
            } else {
                $error = "خطا در اضافه کردن محصول به سبد خرید";
            }
        } else {
            $message = "محصول از قبل در سبد خرید شماست";
        }
    } else {
        header("Location: login.php");
        exit;
    }
}


// Delete product from cart
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $_SESSION['user_id'], $deleteId);
    if ($stmt->execute()) {
        $message = "محصول با موفقیت از سبد خرید حذف گردید!";
    } else {
        $error = "خطا در حذف محصول از سبد خرید";
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
    <h2>سبد خرید : </h2>
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
                    <p>قیمت : $<?php echo $product['price']; ?></p>
                    <form method="GET" action="">
                        <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="delete-btn">حذف</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <button onclick="alert('پرداخت انجام شد!')">ادامه به پرداخت</button>
    <?php else: ?>
        <p>هنوز هیچ محصولی انتخاب نکردید!</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
