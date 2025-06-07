<?php
session_start();
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        $err = 'Database connection failed';
    } else {
        $stmt = $mysqli->prepare('SELECT id, password_hash FROM users WHERE username=?');
        $stmt->bind_param('s', $uname);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($uid, $hash);
            $stmt->fetch();
            if (password_verify($pass, $hash)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $uname;
                header('Location: survey.php');
                exit;
            }
        }
        $err = 'Invalid credentials';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
<h1>Login</h1>
<?php if ($err): ?>
<p class="errors"><?php echo htmlspecialchars($err); ?></p>
<?php endif; ?>
<form method="post">
<label>Username <input type="text" name="username" required></label>
<label>Password <input type="password" name="password" required></label>
<button type="submit" class="btn">Login</button>
</form>
<p><a href="register.php">Need to register?</a></p>
</div>
</body>
</html>
