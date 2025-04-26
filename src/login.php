<?php
session_start();
require_once 'koneksi.php'; // Ganti dengan file koneksi database Anda

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mengambil data pengguna berdasarkan username
    $sql = "SELECT id, username, passw FROM admins WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $db_password);
            $stmt->fetch();
            if (password_verify($password, $db_password)) {
                // Password benar, buat session
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: index.php");
            } else {
                $error = "Password salah.";
            }
            
        } else {
            $error = "Username tidak ditemukan.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <form method="post">
            <div class="user-box">
                <input type="text" placeholder="Username" name="username" required>
            </div>
            <div class="user-box">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <button class="btn-login btn-primary" type="submit">Login</button>
        </form>
    </div>
</body>
</html>
