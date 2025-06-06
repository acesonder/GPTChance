<?php
session_start();
if (!isset($_SESSION['consented'])) {
    header('Location: index.php');
    exit;
}
require 'config.php';

// Create a record for this session if not exists
$sessionId = session_id();
$stmt = $pdo->prepare('SELECT id FROM responses WHERE session_id = ?');
$stmt->execute([$sessionId]);
$response = $stmt->fetch();
if (!$response) {
    $pdo->prepare('INSERT INTO responses (session_id) VALUES (?)')->execute([$sessionId]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Community Survey</title>
<link rel="stylesheet" href="styles.css">
<script>
async function saveField(name, value) {
    const formData = new FormData();
    formData.append('field', name);
    formData.append('value', value);
    await fetch('save_response.php', { method: 'POST', body: formData });
}
function setupAutoSave() {
  document.querySelectorAll('input, select, textarea').forEach(el => {
    el.addEventListener('change', () => {
      let value;
      if (el.type === 'checkbox') {
        const checkboxes = document.querySelectorAll(`input[name="${el.name}"]:checked`);
        value = Array.from(checkboxes).map(c => c.value).join(',');
      } else {
        value = el.value;
      }
      saveField(el.name, value);
    });
  });
}
window.addEventListener('DOMContentLoaded', setupAutoSave);
</script>
</head>
<body>
<h1>Community Survey</h1>
<form id="survey">
<section>
<h2>Section 1: About You</h2>
<label>Age <input type="number" name="age"></label><br>
<label>Gender
<select name="gender" id="gender">
<option value="">--Select--</option>
<option>Man</option>
<option>Woman</option>
<option>Non-binary</option>
<option>Prefer to self-describe</option>
</select></label>
<input type="text" name="gender_self" id="gender_self" placeholder="Self describe" style="display:none;"><br>
<label>Sexual Orientation
<select name="sexual_orientation" id="sexual_orientation">
<option value="">--Select--</option>
<option>Heterosexual</option>
<option>Homosexual</option>
<option>Bisexual</option>
<option>Pansexual</option>
<option>Asexual</option>
<option>Prefer to self-describe</option>
</select></label>
<input type="text" name="sexual_orientation_self" id="sexual_orientation_self" placeholder="Self describe" style="display:none;"><br>
<label>Ethnicity
<select name="ethnicity" id="ethnicity">
<option value="">--Select--</option>
<option>Indigenous</option>
<option>Asian</option>
<option>Black</option>
<option>White</option>
<option>Latino</option>
<option>Prefer to self-describe</option>
</select></label>
<input type="text" name="ethnicity_self" id="ethnicity_self" placeholder="Self describe" style="display:none;"><br>
<label>Birthplace <input type="text" name="birthplace"></label><br>
<label>Years in Cobourg
<select name="years_in_cobourg">
<option value="">--Select--</option>
<option>Less than 1 year</option>
<option>1-5 years</option>
<option>5-10 years</option>
<option>More than 10 years</option>
</select></label>
</section>

<section>
<h2>Section 2: Physical and Mental Health</h2>
<label>Do you identify as having a disability?
<select name="disability">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Prefer not to say</option>
</select></label><br>
<label>Do you take any prescribed medications?
<select name="medications_yn" id="medications_yn">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
</select></label>
<textarea name="medications" id="medications" placeholder="List medications" style="display:none;"></textarea><br>
<label>Do you have consistent access to your medications?
<select name="access_medications">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Sometimes</option>
</select></label><br>
<label>Do you have any dietary needs or restrictions (e.g., allergies, religious, vegetarian)?<br>
<textarea name="dietary_needs"></textarea></label><br>
<label>Are your dietary needs currently being met?
<select name="dietary_met">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Partially</option>
</select></label><br>
<label>Do you have a family doctor?
<select name="family_doctor">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
</select></label><br>
<label>Have you ever been diagnosed with a mental health condition?
<select name="diagnosed_mental_health">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Prefer not to say</option>
</select></label><br>
<label>Do you feel you need mental health support?
<select name="mental_health_support">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Not sure</option>
</select></label><br>
<label>Have you ever attempted suicide?
<select name="attempted_suicide">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Prefer not to say</option>
</select></label><br>
<label>Have you ever experienced an overdose?
<select name="experienced_overdose">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Prefer not to say</option>
</select></label>
</section>

<section>
<h2>Section 3: Family and Childhood</h2>
<label>Did you have any  siblings or step siblings growing up ?
<select name="familu_siblings">
<option value="">--Select--</option>
<option>No Siblings</option>
<option>Step Siblings</option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>Not sure</option>
</select></label><br>
<label>Do you have any children of your own ?  If so, how many ?
<select name="family_offspring" id="family_offspring">
<option value="">--Select--</option>
<option>No Children</option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>Other</option>
</select></label><br>
<label>If "Other" please describe:<input type="text" name="family_offspring_other" id="family_offspring_other" style="display:none;"></label><br>
<label>Is there a history of any of the following in your family? (Select all that apply)<br>
<input type="checkbox" name="family_history" value="Substance abuse">Substance abuse
<input type="checkbox" name="family_history" value="Mental health struggles">Mental health struggles
<input type="checkbox" name="family_history" value="Incarceration">Incarceration
<input type="checkbox" name="family_history" value="Homelessness">Homelessness
</label><br>
<label>Have you lost someone close to you (e.g., family, friend)?
<select name="lost_someone" id="lost_someone">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
</select></label>
<input type="text" name="lost_cause" id="lost_cause" placeholder="Cause of loss" style="display:none;"><br>
<label>Did you experience any of the following during your childhood? (Select all that apply)<br>
<input type="checkbox" name="childhood_experience" value="Abuse">Abuse (physical, emotional, sexual)
<input type="checkbox" name="childhood_experience" value="Neglect">Neglect
<input type="checkbox" name="childhood_experience" value="Foster care">Foster care
<input type="checkbox" name="childhood_experience" value="Parental separation/divorce">Parental separation/divorce
<input type="checkbox" name="childhood_experience" value="Witnessing domestic violence">Witnessing domestic violence
</label>
</section>

<section>
<h2>Section 4: Substance Use</h2>
<label>Do you currently use substances (e.g., alcohol, drugs)?
<select name="current_substance_use">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Prefer not to say</option>
</select></label><br>
<label>If yes, which substances do you use? (Select all that apply)<br>
<input type="checkbox" name="substances_used" value="Alcohol">Alcohol
<input type="checkbox" name="substances_used" value="Cannabis">Cannabis
<input type="checkbox" name="substances_used" value="Opioids">Opioids
<input type="checkbox" name="substances_used" value="Stimulants">Stimulants
<input type="checkbox" name="substances_used" value="Other">Other
</label><br>
<label>How often do you use substances?
<select name="substance_use_frequency">
<option value="">--Select--</option>
<option>Daily</option>
<option>Weekly</option>
<option>Monthly</option>
<option>Occasionally</option>
</select></label><br>
<label>Do you feel you have an addiction?
<select name="addiction" id="addiction">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Not sure</option>
</select></label>
<input type="text" name="addiction_type" id="addiction_type" placeholder="To what?" style="display:none;"><br>
<label>Have you ever sought treatment or rehab for substance use?
<select name="treatment_rehab">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
</select></label><br>
<label>If yes, please describe your experience (optional):<br>
<textarea name="treatment_experience"></textarea></label><br>
<label>Do you use harm reduction supplies (e.g., clean needles, naloxone kits)?
<select name="harm_reduction_supplies">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Sometimes</option>
</select></label><br>
<label>Would you use a safe consumption site if available?
<select name="safe_consumption_site">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
<option>Not sure</option>
</select></label>
</section>

<section>
<h2>Section 5: Housing and Shelter</h2>
<label>Do you have any pets?
<select name="have_pets">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
</select></label><br>
<label>Where are you currently staying?
<select name="current_stay" id="current_stay">
<option value="">--Select--</option>
<option>Shelter bed</option>
<option>Couch surfing</option>
<option>Outdoors</option>
<option>Own place</option>
<option>Transitional housing</option>
<option>Other</option>
</select></label>
<input type="text" name="current_stay_other" id="current_stay_other" placeholder="Other" style="display:none;"><br>
<label>What do you believe are the main causes of your current housing situation? (Select all that apply)<br>
<input type="checkbox" name="homeless_cause" value="Job loss">Job loss
<input type="checkbox" name="homeless_cause" value="Eviction">Eviction
<input type="checkbox" name="homeless_cause" value="Family breakdown">Family breakdown
<input type="checkbox" name="homeless_cause" value="Mental health issues">Mental health issues
<input type="checkbox" name="homeless_cause" value="Substance use">Substance use
<input type="checkbox" name="homeless_cause" value="Lack of affordable housing">Lack of affordable housing
</label><br>
<label>How long have you been experiencing housing insecurity or homelessness?
<select name="homeless_duration">
<option value="">--Select--</option>
<option>Less than 1 month</option>
<option>1-6 months</option>
<option>6 months - 1 year</option>
<option>1-5 years</option>
<option>More than 5 years</option>
</select></label>
</section>

<section>
<h2>Section 6: Employment and Income</h2>
<label>What is your current employment status?
<select name="employment_status" id="employment_status">
<option value="">--Select--</option>
<option>Employed full-time</option>
<option>Employed part-time</option>
<option>Unemployed</option>
<option>Student</option>
<option>Retired</option>
<option>Unable to work</option>
<option>Other</option>
</select></label>
<input type="text" name="employment_status_other" id="employment_status_other" placeholder="Other" style="display:none;"><br>
<label>What is your primary source of income?
<select name="income_source" id="income_source">
<option value="">--Select--</option>
<option>Employment</option>
<option>Social assistance (OW/ODSP)</option>
<option>EI</option>
<option>Pension</option>
<option>No income</option>
<option>Other</option>
</select></label>
<input type="text" name="income_source_other" id="income_source_other" placeholder="Other" style="display:none;"><br>
<label>What forms of identification do you have? (Select all that apply)<br>
<input type="checkbox" name="identification" value="Health card">Health card
<input type="checkbox" name="identification" value="Driver's license">Driver's license
<input type="checkbox" name="identification" value="Birth certificate">Birth certificate
<input type="checkbox" name="identification" value="Passport">Passport
<input type="checkbox" name="identification" value="SIN card">SIN card
<input type="checkbox" name="identification" value="Government-issued ID">Government-issued ID
</label>
</section>

<section>
<h2>Section 7: Education and Skills</h2>
<label>What is your highest level of education completed?
<select name="education_level" id="education_level">
<option value="">--Select--</option>
<option>Some high school</option>
<option>High school diploma or GED</option>
<option>Some college/university</option>
<option>College diploma</option>
<option>University degree</option>
<option>Post-graduate degree</option>
<option>Other</option>
</select></label>
<input type="text" name="education_level_other" id="education_level_other" placeholder="Other" style="display:none;"><br>
<label>What skills or trades do you have?<br>
<textarea name="skills"></textarea></label>
</section>

<section>
<h2>Section 8: Community and Support</h2>
<label>If you have used shelters, what is working well?<br>
<textarea name="shelter_working_well"></textarea></label><br>
<label>If you have used shelters, what could be improved?<br>
<textarea name="shelter_improve"></textarea></label><br>
<label>What community supports or services have you found helpful?<br>
<textarea name="community_supports"></textarea></label><br>
<label>What helps you feel stable or hopeful?<br>
<textarea name="stable_hopeful"></textarea></label><br>
<label>Can you describe a typical day for you?<br>
<textarea name="typical_day"></textarea></label><br>
<label>How do you feel the community views people experiencing homelessness?
<select name="community_views" id="community_views">
<option value="">--Select--</option>
<option>With compassion</option>
<option>With judgment</option>
<option>Indifferently</option>
<option>Mixed</option>
<option>Other</option>
</select></label>
<input type="text" name="community_views_other" id="community_views_other" placeholder="Other" style="display:none;"><br>
</section>

<section>
<h2>Section 9: Moving Forward</h2>
<label>What do you need to move forward?<br>
<textarea name="move_forward"></textarea></label><br>
<label>What are your goals for the future?<br>
<textarea name="future_goals"></textarea></label><br>
<label>What kind of help or services are you looking for? (Select all that apply)<br>
<input type="checkbox" name="help_services" value="Housing">Housing
<input type="checkbox" name="help_services" value="Employment or training">Employment or training
<input type="checkbox" name="help_services" value="Mental health support">Mental health support
<input type="checkbox" name="help_services" value="Substance use support">Substance use support
<input type="checkbox" name="help_services" value="Healthcare">Healthcare
<input type="checkbox" name="help_services" value="Food assistance">Food assistance
<input type="checkbox" name="help_services" value="Legal aid">Legal aid
</label><br>
<label>Would you like to be connected with someone to discuss your situation and needs further?
<select name="want_connection">
<option value="">--Select--</option>
<option>Yes</option>
<option>No</option>
</select></label>
</section>
</form>
<script>
function toggleVisibility(selectId, value, targetId) {
  const select = document.getElementById(selectId);
  const target = document.getElementById(targetId);
  if (!select || !target) return;
  select.addEventListener('change', () => {
    if (select.value === value) {
      target.style.display = 'block';
    } else {
      target.style.display = 'none';
      target.value = '';
      saveField(target.name, '');
    }
  });
}

toggleVisibility('gender', 'Prefer to self-describe', 'gender_self');
toggleVisibility('sexual_orientation', 'Prefer to self-describe', 'sexual_orientation_self');
toggleVisibility('ethnicity', 'Prefer to self-describe', 'ethnicity_self');
toggleVisibility('medications_yn', 'Yes', 'medications');
toggleVisibility('lost_someone', 'Yes', 'lost_cause');
toggleVisibility('addiction', 'Yes', 'addiction_type');
toggleVisibility('current_stay', 'Other', 'current_stay_other');
toggleVisibility('employment_status', 'Other', 'employment_status_other');
toggleVisibility('income_source', 'Other', 'income_source_other');
toggleVisibility('education_level', 'Other', 'education_level_other');
toggleVisibility('community_views', 'Other', 'community_views_other');

toggleVisibility('family_offspring', 'Other', 'family_offspring_other');
</script>
</body>
</html>
