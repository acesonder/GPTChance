<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$role = ($_SESSION['user_id'] === 'admin') ? 'admin' : 'client';
$config = json_decode(file_get_contents('feature_config.json'), true);
$enabled = $config[$role] ?? [];
function hasFeature($f) {global $enabled; return in_array($f, $enabled);}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Case Management Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_id']); ?></h1>
  <nav class="mb-3">
    <a href="logout.php" class="btn btn-secondary">Logout</a>
    <?php if ($role === 'admin'): ?><a href="admin.php" class="btn btn-primary">Admin</a><?php endif; ?>
  </nav>
  <ul class="list-group">
    <?php if (hasFeature('case_creation')): ?>
      <li class="list-group-item"><a href="new_case.php">Create Case</a></li>
    <?php endif; ?>
    <?php if (hasFeature('case_updates')): ?>
      <li class="list-group-item"><a href="cases.php">View Cases</a></li>
    <?php endif; ?>
    <?php if (hasFeature('analytics_reporting')): ?>
      <li class="list-group-item"><a href="report.php">Reports</a></li>
    <?php endif; ?>
    <!-- Additional features could go here -->
  </ul>
</body>
</html>
