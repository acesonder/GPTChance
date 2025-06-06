<?php
require 'config.php';
$data = $pdo->query('SELECT * FROM responses')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Survey Results</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Survey Results</h1>
<canvas id="genderChart"></canvas>
<script>
const data = <?php echo json_encode($data); ?>;
const genderCounts = {};
data.forEach(r => {
  if (!r.gender) return;
  genderCounts[r.gender] = (genderCounts[r.gender] || 0) + 1;
});
const ctx = document.getElementById('genderChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: Object.keys(genderCounts),
    datasets: [{ label: 'Gender', data: Object.values(genderCounts) }]
  }
});
</script>
</body>
</html>
