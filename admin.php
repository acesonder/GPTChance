<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$features = json_decode(file_get_contents('features.json'), true)['features'];
$config_path = 'feature_config.json';
$config = json_decode(file_get_contents($config_path), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['admin','client'] as $role) {
        $config[$role] = isset($_POST[$role]) ? array_values($_POST[$role]) : [];
    }
    file_put_contents($config_path, json_encode($config, JSON_PRETTY_PRINT));
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Feature Control</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h1>Feature Configuration</h1>
  <form method="post">
    <table class="table">
      <thead>
        <tr><th>Feature</th><th>Admin</th><th>Client</th></tr>
      </thead>
      <tbody>
        <?php foreach ($features as $f): $key=$f['key']; ?>
        <tr>
          <td><?php echo htmlspecialchars($key); ?></td>
          <td><input type="checkbox" name="admin[]" value="<?php echo htmlspecialchars($key); ?>" <?php echo in_array($key, $config['admin']) ? 'checked' : ''; ?>></td>
          <td><input type="checkbox" name="client[]" value="<?php echo htmlspecialchars($key); ?>" <?php echo in_array($key, $config['client']) ? 'checked' : ''; ?>></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <button class="btn btn-primary" type="submit">Save</button>
  </form>
  <a href="portal.php" class="btn btn-link mt-3">Back to Portal</a>
</body>
</html>
