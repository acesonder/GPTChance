<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$role = ($_SESSION['user_id'] === 'admin') ? 'admin' : 'client';
$config = json_decode(file_get_contents('feature_config.json'), true);
if (!in_array('case_updates', $config[$role] ?? [])) { die('Feature disabled'); }
require 'config.php';
$id = intval($_GET['id'] ?? 0);
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno){ die('DB error'); }
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $status=$_POST['status'] ?? 'open';
    $stmt=$mysqli->prepare('UPDATE cases SET status=? WHERE id=?');
    $stmt->bind_param('si',$status,$id);
    if($stmt->execute()){header('Location: cases.php');exit;} else {$err='Update failed';}
    $stmt->close();
}
$case=$mysqli->query("SELECT id,title,description,status FROM cases WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Case</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h1>Edit Case</h1>
  <?php if($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
  <?php if($case): ?>
    <h2><?php echo htmlspecialchars($case['title']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($case['description'])); ?></p>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="open" <?php if($case['status']=='open') echo 'selected'; ?>>Open</option>
          <option value="closed" <?php if($case['status']=='closed') echo 'selected'; ?>>Closed</option>
        </select>
      </div>
      <button class="btn btn-primary" type="submit">Update</button>
    </form>
  <?php else: ?>
    <p>Case not found.</p>
  <?php endif; ?>
  <a href="cases.php" class="btn btn-link mt-3">Back</a>
</body>
</html>
<?php $mysqli->close(); ?>
