<?php
session_start();
include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // MySQLi code to check if the credentials are correct
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    echo $user['user_id'];

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else {
        $error = "نام کاربری یا کلمه عبور نادرست است.";
    }
}
?><style>
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
    <div class="form-container">
        <h2>ورود به حساب</h2>
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="نام کاربری" required>
            <input type="password" name="password" placeholder="کلمه عبور" required>
            <button type="submit">ورود</button>
        </form>
        <p>حساب ندارید؟ <a href="register.php">اینجا ثبت نام کنید</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
