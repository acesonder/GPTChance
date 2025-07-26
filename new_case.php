<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$role = ($_SESSION['user_id'] === 'admin') ? 'admin' : 'client';
$config = json_decode(file_get_contents('feature_config.json'), true);
if (!in_array('case_creation', $config[$role] ?? [])) { die('Feature disabled'); }
require 'config.php';
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if($mysqli->connect_errno){$err='DB error';}else{
        $stmt=$mysqli->prepare('INSERT INTO cases(title, description, status) VALUES(?,?,"open")');
        $stmt->bind_param('ss',$title,$desc);
        if($stmt->execute()){header('Location: cases.php'); exit;} else {$err='DB insert failed';}
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Case</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h1>Create Case</h1>
  <?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>
    <button class="btn btn-primary" type="submit">Save</button>
  </form>
  <a href="portal.php" class="btn btn-link mt-3">Back</a>
</body>
</html>
