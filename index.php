<?php
session_start();
if (isset($_POST['consent'])) {
    $_SESSION['consented'] = true;
    header('Location: survey.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Consent Form</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Community Survey Consent</h1>
<p>Please read and provide your consent to participate in this survey. Your responses are confidential.</p>
<form method="post">
    <button type="submit" name="consent" value="1">I Consent</button>
</form>
</body>
</html>
