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
        $imagePath = 'uploads/' . time() . '_' . basename($productImage['name']);

        // Validate file upload (only images)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($productImage['type'], $allowedTypes)) {
            if (move_uploaded_file($productImage['tmp_name'], $imagePath)) {
                $stmt = $conn->prepare("INSERT INTO products (name, group_name, price, image, user_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdsi", $productName, $productGroup, $productPrice, $imagePath, $_SESSION['user_id']);

                if ($stmt->execute()) {
                    $successMessage = "Product uploaded successfully!";
                } else {
                    $errorMessage = "Failed to upload product to the database.";
                }
                $stmt->close();
            } else {
                $errorMessage = "Failed to move uploaded image.";
            }
        } else {
            $errorMessage = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    } else {
        $errorMessage = "Error uploading the image. Please try again.";
    }
}
?>

<div class="container">
    <h2>Upload Product</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="error-message"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
    <?php if (!empty($successMessage)): ?>
        <div class="success-message"><?php echo $successMessage; ?></div>
    <?php endif; ?>
        <style>
            <style>
.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #007BFF;
}

/* Success and Error Messages */
.success-message,
.error-message {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Product Cards */
.product-card {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
}

.product-card img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    margin-right: 20px;
    border-radius: 8px;
}
</style>

        </style>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="product_name" placeholder="Product Name" required>
        <select name="product_group" required>
            <option value="electronics">Electronics</option>
            <option value="clothing">Clothing</option>
            <option value="books">Books</option>
        </select>
        <input type="number" name="product_price" placeholder="Product Price" max="100000" required>
        <input type="file" name="product_image" required>
        <button type="submit">Upload Product</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
