<?php 
if(!defined('tAccess')) die("Unable to continue, direct access not allowed");
global $username, $phone_number, $email, $password, $first_name, $last_name, $messages, $page_errors;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Sign-Up/Login Form</title>
	<link href='css/titillium.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/normalize.min.css">
	<link rel="stylesheet" href="css/style.css">
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
	<div class="form">
		<ul class="tab-group">
			<li class="tab active"><a href="#signup" id="signup_tab">Sign Up</a>
			</li>
			<li class="tab"><a href="#login" id="signin_tab">Log In</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="signup">
				<h1>Create New Account</h1>
				<?php foreach($messages["errors"] as $error): ?>
				<h2 class="error"><?php echo $error ?></h2>
				<?php endforeach; ?>
				<?php foreach($messages["success"] as $success): ?>
				<h2 class="success"><?php echo $success ?></h2>
				<?php endforeach; ?>
				<form action="index.php" method="post">
					<input type="hidden" name="action" value="signup" />
					<input type="hidden" name="captcha_value" id="id_captcha_valud" value="" />
					<!--
					<div class="field-wrap">
						<label>Username<span class="req">*</span>
						</label>
						<input type="text" name="username" id="id_username" required autocomplete="off" value="<?php echo $username ?>" />
						<?php if(showPageErrors('signup_username') != ""): ?><div class="field_error" ><?php echo showPageErrors('signup_username') ?></div><?php endif; ?>
					</div>
					-->
					<div class="field-wrap">
						<label>Email Address<span class="req">*</span>
						</label>
						<input type="text" name="email" id="id_email" required autocomplete="off" value="<?php echo $email ?>"/>
						<?php if(showPageErrors('signup_email') != ""): ?><div class="field_error" ><?php echo showPageErrors('signup_email') ?></div><?php endif; ?>
					</div>
					<div class="field-wrap">
						<label>Phone Number<span class="req">*</span>
						</label>
						<input type="text" name="phone_number" id="id_phone_number" required autocomplete="off" value="<?php echo $phone_number ?>" />
						<?php if(showPageErrors('signup_phone_number') != ""): ?><div class="field_error" ><?php echo showPageErrors('signup_phone_number') ?></div><?php endif; ?>
					</div>
					<div class="field-wrap">
						<label>Set A Password<span class="req">*</span>
						</label>
						<input type="password" name="password" id="id_password" required autocomplete="off" value="<?php echo $password ?>"/>
					</div>
					<div class="top-row">
						<div class="field-wrap">
							<label>First Name<span class="req">*</span>
							</label>
							<input type="text" name="first_name" id="id_first_name" required autocomplete="off" value="<?php echo $first_name ?>"/>
						</div>
						<div class="field-wrap">
							<label>Last Name<span class="req">*</span>
							</label>
							<input type="text" name="last_name" id="id_last_name" required autocomplete="off" value="<?php echo $last_name ?>"/>
						</div>
					</div>
					<div class="field-wrap">
						<label>Domain (Leave empty if unsure)<span class="req">*</span>
						</label>
						<input type="text" name="domain" id="id_domain" autocomplete="off" value=""/>
					</div>
					<div class="text-xs-center">
						<div class="g-recaptcha" id="g-recaptcha" data-sitekey="6LeUwW4UAAAAAGdK7FbRNHOVclbjv2vFBICVPxOi" data-callback="data_callback"></div>
					</div>
					<button id="id_submit_button" type="submit" class="button button-block" disabled="disabled" />Sign Up</button>
				</form>
			</div>
			<div id="login">
				<h1>Log On</h1>
				<?php foreach($messages["login_errors"] as $error): ?>
				<h2 class="error"><?php echo $error ?></h2>
				<?php endforeach; ?>
				<form action="index.php" method="post">
					<input type="hidden" name="action" value="signin" />
					<div class="field-wrap">
						<label>Email Address<span class="req">*</span>
						</label>
						<input type="text" name="email" id="login_username" value="<?php echo $email ?>" required autocomplete="off" />
					</div>
					<div class="field-wrap">
						<label>Password<span class="req">*</span>
						</label>
						<input type="password" name="password" id="login_password" required autocomplete="off" />
					</div>
					<!-- <p class="forgot"><a href="#">Forgot Password?</a> -->
					</p>
					<button class="button button-block" />Log In</button>
				</form>
			</div>
		</div>
		<!-- tab-content -->
	</div>
	<!-- /form -->
	<script src="https://code.jquery.com/jquery-latest.min.js"></script>
	<script src="js/index.js"></script>
	<script type="text/javascript">
	function data_callback() {
		$("#id_submit_button").prop("disabled", false);
	}
	</script>
	<script type="text/javascript">
	$(document).ready(function() {
		var fields = ["id_username","id_phone_number","id_email","id_password","id_first_name","id_last_name"];
		for(var i=0;i < fields.length;i++)
		{
			var fieldname = fields[i];
			if($("#" + fieldname).val() == "") continue; 
			var transform = "translate(300px, 6px)";
			if(fieldname === "id_first_name" || fieldname == "id_last_name")
			transform = "translate(150px, 6px)";
			var thislabel = $("#" + fieldname).parent().children("label");
			thislabel.css("transform", transform);
		}
		<?php if(array_key_exists("login_errors", $messages) AND $messages["login_errors"][0] != NULL) { ?>

		$("#id_username").focus();
		$("#signin_tab").click();

		<?php } ?>
	});
	</script>
</body>
</html>