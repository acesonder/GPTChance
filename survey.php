<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = uniqid('resp_', true);
}
$session_id = $_SESSION['session_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Survey</title>
<link rel="stylesheet" href="styles.css">
<script>
var sessionId = "<?php echo $session_id; ?>";
function saveAnswer(field, value) {
    var formData = new FormData();
    formData.append('session_id', sessionId);
    formData.append('field', field);
    formData.append('value', value);
    fetch('save_answer.php', { method: 'POST', body: formData });
}
</script>
</head>
<body>
<div class="container">
<h1>Survey</h1>
<p>Please indicate your consent to participate in this survey.</p>
<label><input type="checkbox" id="consent"> I consent to participate in this survey.</label>
<div id="survey" style="display:none;">
<form id="surveyForm">
<div>
<label>Age: <input type="number" name="age" onchange="saveAnswer('age', this.value)"></label>
</div>
<div>
<label>Gender:
<select name="gender" onchange="saveAnswer('gender', this.value)">
<option value="">--select--</option>
<option value="male">Male</option>
<option value="female">Female</option>
<option value="other">Other</option>
</select>
</label>
</div>
<div>
<label>Self-described gender: <input type="text" name="gender_self" onchange="saveAnswer('gender_self', this.value)"></label>
</div>
<?php for ($i=1; $i<=56; $i++): ?>
<div>
<label>Question <?php echo $i; ?>:
<input type="text" name="q<?php echo $i; ?>" onchange="saveAnswer('q<?php echo $i; ?>', this.value)">
</label>
</div>
<?php endfor; ?>
</form>
</div>
</div>
<script>
document.getElementById('consent').addEventListener('change', function() {
    document.getElementById('survey').style.display = this.checked ? 'block' : 'none';
});
</script>
</body>
</html>
