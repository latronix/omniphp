<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>OmniPHP Demo Basic 1</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	
	<link rel="stylesheet" href="js/vendor/css/cupertino/jquery-ui-1.10.0.custom.min.css">
	<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	
	<style>
	.omniphp_validation_errors
	{
		border: 1px solid #F00;
		background-color: #FF0;
		color: #F00;
		padding: 3px;
		font-family: arial, helvetica, freesans, sans-serif;
		font-size: 12pt;
		font-weight: bold;
		display: none;
	}
	</style>
	
	<script src="js/vendor/jquery-1.9.1.min.js"></script>
	<script src="js/vendor/jquery-ui-1.10.0.custom.min.js"></script>
	<script src="js/vendor/jquery.metadata.js"></script>
	<script src="js/vendor/jquery.validate.min.js"></script> 
	<script src="js/vendor/additional-methods.min.js"></script>
	<script src="js/vendor/jquery.maskedinput-1.3.min.js"></script>
	
	<!--
	<script src="js/vendor/bootstrap.min.js"></script>
	-->
	<script src="js/plugins.js"></script>
	<!--
	<script src="js/main.js"></script>
	-->
	<script>
	$(document).ready(function(){
		//
	});
	</script>
</head>

<body>
	<h2>OmniPHP Demo 1: HTML 5 Basic Page without Layout</h2>

	<?php	
	if(isset($_POST['SaveButton']))
	{
		$arrLastVal = $_POST;
	
		echo "Successfully submitted the following data...<br>";
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		echo "<br>";
	}
	else
	{
		$arrLastVal['CustomerFullName'] = NULL;
	}
	
	require_once("omniphp/omniphp_form.php");
	$omniphp = new OmniPHP_Form();
	$omniphp->form_start("OmniPHP_Form", "post", $_SERVER['PHP_SELF'], "div.omniphp_validation_errors");
	
	echo "<div class='omniphp_validation_errors'></div>"; //to force error output

	echo "Customer Full Name: ";
	$omniphp->input(array("name" => "CustomerFullName", "type" => "text", "required" => true, "minlength" => 2, "maxlength" => 100, "value" => $arrLastVal['CustomerFullName']));
	echo "<br>";
	
	echo "Customer Phone (optional): ";
	$omniphp->input(array("name" => "CustomerPhone", "type" => "phone", "format" => "us_phone_all", "required" => false, "minlength" => 12, "maxlength" => 12));
	echo "<br>";
	
	echo "Customer Email: ";
	$omniphp->input(array("name" => "CustomerEmail", "type" => "email", "required" => true));
	echo "<br>";
	
	$omniphp->form_end(array("SaveButton", "Save", "className"));
	$omniphp->render_js_stack();
	echo "<br><br>";
	?>
	
	<!--
	<?php ?>
	<form name="OmniPHP_Form" id="OmniPHP_Form" method="post" action="html5_template.php">
		Phone 1: <input type="text" name="Phone1" id="Phone1" required="required" value=""><br>
		Email: <input type="email" name="Email" id="Email" value=""><br>
		<br><br>
		<input type="submit" name="Submit" id="Submit" value="Submit">
	</form>
	-->
	
</body>
</html>
