<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<title>jQuery Validation (Error Container) - Proba 1</title>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
<script src="http://jquery.bassistance.de/validate/lib/jquery.metadata.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/additional-methods.min.js"></script>

<?php
/*
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/additional-methods.min.js"></script>
<!-- fix me: add as real CDN -->
        <script src="http://cloud.github.com/downloads/digitalBush/jquery.maskedinput/jquery.maskedinput-1.3.min.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/plugins.js"></script>
*/
?>

<script type="text/javascript">
$().ready(function() {
//alert("I'm here?");
	$("#form1").validate({
		errorLabelContainer: $("#form1 div.error")
	});
});
</script>
</head>
<body>
<?php
if(isset($_POST['Submit']))
{
	echo "Submitted...!!!<br>";
}
echo "blah...";
?>
<form method="post" class="cmxform" id="form1" action="index2.php">
	<fieldset>
		<legend>Login Form</legend>
		<p>
			<label>Username</label>
			<input name="user" title="Please enter your username (at least 3 characters)" class="{required:true,minlength:3}" />
		</p>
<!--
		<p>
			<label>Password</label>
			<input type="password" maxlength="12" name="password" title="Please enter your password, between 5 and 12 characters" class="{required:true,minlength:5}" />
		</p>
-->
		<div class="error">
blah
		</div>
		<p>
			<input class="submit" type="submit" name='Submit' id='Submit' value="Login"/>
		</p>
	</fieldset>
</form>
</body>

</html>

