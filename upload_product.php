<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $productGroup = $_POST['product_group'];
    $productPrice = $_POST['product_price'];
    $productImage = $_FILES['product_image'];

    if ($productImage['error'] === 0) {
        $imagePath = 'uploads/' . time() . '_' . $productImage['name'];
        move_uploaded_file($productImage['tmp_name'], $imagePath);

        $stmt = $pdo->prepare("INSERT INTO products (name, group_name, price, image, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$productName, $productGroup, $productPrice, $imagePath, $_SESSION['user_id']]);
        echo "<p>Product uploaded successfully!</p>";
    } else {
        echo "<p>Error uploading the image.</p>";
    }
}
?>

<div class="container">
    <h2>Upload Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <select name="product_group" required>
            <option value="electronics">Electronics</option>
            <option value="clothing">Clothing</option>
            <option value="books">Books</option>
        </select>
        <input type="number" name="product_price" placeholder="Product Price" required>
        <input type="file" name="product_image" required>
        <button type="submit">Upload Product</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
