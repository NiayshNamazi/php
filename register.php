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

    $stmt = $pdo->prepare("INSERT INTO users (name, surname, username, password, gender) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $surname, $username, $password, $gender]);
    echo "<p>Registration successful. <a href='login.php'>Login here</a>.</p>";
}
?>
<div class="container">
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="surname" placeholder="Surname" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        <button type="submit">Register</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
