<?php
require 'config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die('DB connection failed');
}
$result = $mysqli->query("SELECT gender, COUNT(*) as c FROM survey_responses GROUP BY gender");
$labels = [];
$data = [];
while ($row = $result->fetch_assoc()) {
    $labels[] = $row['gender'] ?: 'undefined';
    $data[] = $row['c'];
}
$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Survey Report</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>Gender Distribution</h1>
<canvas id="chart"></canvas>
<script>
const labels = <?php echo json_encode($labels); ?>;
const data = <?php echo json_encode($data); ?>;
new Chart(document.getElementById('chart'), {
    type: 'bar',
    data: { labels: labels, datasets: [{ label: 'Responses', data: data }] }
});
</script>
</body>
</html>
