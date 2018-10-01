<?php 
if(!defined('tAccess')) die("Unable to continue, direct access not allowed");
global $email, $mySessionKey;
$values = $_SESSION[$mySessionKey . $email];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Sign-Up/Login Form</title>
	<link href='css/titillium.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/normalize.min.css">
	<link rel="stylesheet" href="css/style-complete.css">
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
	<div class="form">
        <div id="signup">
            <h1>Complete Signup</h1>
            <?php foreach($messages["errors"] as $error): ?>
            <h2 class="error"><?php echo $error ?></h2>
            <?php endforeach; ?>
            <?php foreach($messages["success"] as $success): ?>
            <h2 class="success"><?php echo $success ?></h2>
            <?php endforeach; ?>
            <form action="index.php" method="post" >
                <input type="hidden" name="action" value="complete" />
                <input type="hidden" name="email" value="<?php echo $values["email"] ?>" />
                <?php foreach($values as $field => $value): ?>
                <div class="field-wrap">
                    <label><?php echo titleCase($field) ?>
                        <?php if(fieldRequired($field)): ?>
                        <span class="req">*</span>
                        <?php endif; ?>
                    </label>
                    <input type="text" name="<?php echo $field ?>" id="id_<?php echo $field ?>" value="<?php echo $value ?>" <?php echo fieldDisabled($field) ?>autocomplete="off" />
                </div>
                <?php endforeach; ?>
                <div class="text-xs-center">
                    <!-- <div class="g-recaptcha" id="g-recaptcha" data-sitekey="6LeUwW4UAAAAAGdK7FbRNHOVclbjv2vFBICVPxOi"></div> -->
                </div>
                <button type="submit" class="button button-block" />Save</button>
            </form>
        </div>
	</div>
	<!-- /form -->
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="js/index.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		var fields = [ <?php foreach($values as $field => $value) { echo "'id_" . $field . "',"; } ?> ];
		for(var i=0;i < fields.length;i++)
		{
			var fieldname = fields[i];
			if($("#" + fieldname).val() == "") continue; 
			var transform = "translate(370px, 6px)";
			var thislabel = $("#" + fieldname).parent().children("label");
			thislabel.css("transform", transform);
		}
		$("#signin_tab").click();
	});
	</script>
</body>
</html>