<?php
session_start();
require 'config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        $errors[] = 'Database connection failed';
    } else {
        if ($action === 'anon') {
            $_SESSION['user_id'] = 'anon';
            header('Location: survey.php');
            exit;
        } elseif ($action === 'initials') {
            $fi = strtoupper(substr(trim($_POST['first_initial'] ?? ''),0,1));
            $li = strtoupper(substr(trim($_POST['last_initial'] ?? ''),0,1));
            if (!$fi || !$li) {
                $errors[] = 'Please provide initials';
            } else {
                $_SESSION['user_id'] = $fi.$li;
                header('Location: survey.php');
                exit;
            }
        } elseif ($action === 'register') {
            $fname = trim($_POST['fname'] ?? '');
            $lname = trim($_POST['lname'] ?? '');
            $dob = trim($_POST['dob'] ?? '');
            $question = trim($_POST['sec_question'] ?? '');
            $answer = trim($_POST['sec_answer'] ?? '');
            $pass = $_POST['password'] ?? '';
            $pass2 = $_POST['password_confirm'] ?? '';
            if (!$fname || !$lname || !$dob || !$question || !$answer || !$pass) {
                $errors[] = 'All fields required';
            } elseif ($pass !== $pass2) {
                $errors[] = 'Passwords do not match';
            } else {
                $uname = strtoupper(substr($fname,0,3).substr($lname,0,3));
                if (preg_match('/^(\d{4})-\d{2}-\d{2}$/', $dob, $m)) {
                    $uname .= substr($m[1],-2);
                } else {
                    $errors[] = 'Invalid date format';
                }
                if (!$errors) {
                    $stmt = $mysqli->prepare('SELECT id FROM users WHERE username=?');
                    $stmt->bind_param('s', $uname);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        $errors[] = 'Username already exists';
                    } else {
                        $hash = password_hash($pass, PASSWORD_DEFAULT);
                        $ins = $mysqli->prepare('INSERT INTO users (username, first_name, last_name, dob, password_hash, sec_question, sec_answer) VALUES (?,?,?,?,?,?,?)');
                        $ins->bind_param('sssssss', $uname, $fname, $lname, $dob, $hash, $question, $answer);
                        if ($ins->execute()) {
                            $_SESSION['user_id'] = $uname;
                            header('Location: survey.php');
                            exit;
                        } else {
                            $errors[] = 'Failed to create account';
                        }
                        $ins->close();
                    }
                    $stmt->close();
                }
            }
        }
    }
}
$questions = [
    'What was your childhood nickname?',
    'What is the name of your favorite childhood friend?',
    'In what city or town did your parents meet?',
    'What was the make of your first car?',
    'What was the name of your first pet?',
    'What is your mother\'s maiden name?',
    'What was the name of your elementary school?',
    'What is the name of the street you grew up on?',
    'What is your favorite book?',
    'What is your favorite food?'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register / Consent</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
<h1>Participant Consent</h1>
<?php if ($errors): ?>
    <div class="errors">
        <ul>
        <?php foreach ($errors as $e): ?>
            <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<h2>Anonymous</h2>
<form method="post">
    <input type="hidden" name="action" value="anon">
    <button type="submit" class="btn">Continue Anonymously</button>
</form>
<h2>Initials Only</h2>
<form method="post">
    <input type="hidden" name="action" value="initials">
    <label>First Initial <input type="text" name="first_initial" maxlength="1"></label>
    <label>Last Initial <input type="text" name="last_initial" maxlength="1"></label>
    <button type="submit" class="btn">Continue</button>
</form>
<h2>Register with Full Details</h2>
<form method="post">
    <input type="hidden" name="action" value="register">
    <label>First Name <input type="text" name="fname" required></label>
    <label>Last Name <input type="text" name="lname" required></label>
    <label>Date of Birth <input type="date" name="dob" required></label>
    <label>Security Question
        <select name="sec_question" required>
            <?php foreach ($questions as $q): ?>
            <option value="<?php echo htmlspecialchars($q); ?>"><?php echo htmlspecialchars($q); ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Security Answer <input type="text" name="sec_answer" required></label>
    <label>Password <input type="password" name="password" required></label>
    <label>Confirm Password <input type="password" name="password_confirm" required></label>
    <button type="submit" class="btn">Register and Start Survey</button>
</form>
<p><a href="login.php">Already have an account? Login</a></p>
</div>
</body>
</html>
