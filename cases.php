<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$role = ($_SESSION['user_id'] === 'admin') ? 'admin' : 'client';
$config = json_decode(file_get_contents('feature_config.json'), true);
if (!in_array('case_updates', $config[$role] ?? [])) { die('Feature disabled'); }
require 'config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno){ die('DB error'); }
$cases = $mysqli->query('SELECT id,title,status FROM cases ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cases</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h1>Cases</h1>
  <table class="table">
    <thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
    <?php while($row=$cases->fetch_assoc()): ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['title']); ?></td>
        <td><?php echo htmlspecialchars($row['status']); ?></td>
        <td><a href="edit_case.php?id=<?php echo $row['id']; ?>">Edit</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  <a href="portal.php" class="btn btn-link mt-3">Back</a>
</body>
</html>
<?php $mysqli->close(); ?>
