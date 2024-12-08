<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gender = $_POST['gender'];

    // Check if the username already exists
    $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $errorMessage = "نام کاربری قبلا انتخاب شده. لطفا یک مورد دیگه انتخاب کنید";
    } else {
        // Prepare the insert statement
        $stmt = $conn->prepare("INSERT INTO users (name, surname, username, password, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $surname, $username, $password, $gender);

        if ($stmt->execute()) {
            $successMessage = "ثبت نام موفقیت آمیز بود. <a href='login.php'>اینجا وارد شوید</a>.";
        } else {
            $errorMessage = "خطا هنگام ثبت نام رخ داد. لطفا مجدد تلاش کنید";
        }

        // Close the statement
        $stmt->close();
    }

    // Close the check statement
    $checkStmt->close();
}

// Close the connection (optional)
$conn->close();
?>

    <style>
/* Form Styling */
.container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #007BFF;
}

.container input,
.container select,
.container button {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.container button {
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.container button:hover {
    background-color: #0056b3;
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
</style>

<div class="container">
    <form method="POST">
        <h2>ثبت نام حساب جدید</h2>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="success-message">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <input type="text" name="name" placeholder="نام" required>
        <input type="text" name="surname" placeholder="نام خانوادگی" required>
        <input type="text" name="username" placeholder="نام کاربری" required>
        <input type="password" name="password" placeholder="کلمه عبور" required>
        <select name="gender" required>
            <option value="male">مرد</option>
            <option value="female">زن</option>
        </select>
        <button type="submit">ثبت نام</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
