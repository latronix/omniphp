<?php
/**
 * OmniPHP Framework
 * 
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.4.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

ob_start("ob_gzhandler");
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        
        <!-- CDN: <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/cupertino/jquery-ui.css"> -->
        <link rel="stylesheet" href="js/vendor/css/cupertino/jquery-ui-1.10.0.custom.min.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <style>
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        
        <?php
        //CDN
        /*
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/additional-methods.min.js"></script>
        <!-- non-CDN -->
		<script src="js/vendor/jquery.metadata.js"></script>
		<script src="js/vendor/jquery.maskedinput-1.3.min.js"></script>
		*/
		?>
		<!-- LOCAL -->
        <script src="js/vendor/jquery-1.9.1.min.js"></script>
        <script src="js/vendor/jquery-ui-1.10.0.custom.min.js"></script>
		<script src="js/vendor/jquery.metadata.js"></script>
        <script src="js/vendor/jquery.validate.min.js"></script> 
        <script src="js/vendor/additional-methods.min.js"></script>
		<script src="js/vendor/jquery.maskedinput-1.3.min.js"></script>
		
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/plugins.js"></script>
<!-- FIX ME: vf how to add this -->
        <!-- <script src="js/main.js"></script> -->
<!-- fix me: add me
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
-->
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Project name</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="#">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#contact">Contact</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li class="nav-header">Nav header</li>
                                    <li><a href="#">Separated link</a></li>
                                    <li><a href="#">One more separated link</a></li>
                                </ul>
                            </li>
                        </ul>
                        <form class="navbar-form pull-right">
                            <input class="span2" type="text" placeholder="Email">
                            <input class="span2" type="password" placeholder="Password">
                            <button type="submit" class="btn">Sign in</button>
                        </form>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
<!--
        <div class="container">
-->
<?php

/*
[REFERENCE]
OmniPHP:
textbox($dom_name, $arr_type_format, $arr_properties, $arr_messages, $value);
    $arr_type_format = array($custom_type, $format, $bRequired, $force_masking)
	$custom_type = text,phone, etc...
	$format = us_phone (any format),us_phone1 (###-###-####),us_phone2 ((###) ###-####), etc...
    $arr_properties = array($size, $min, $max, $tab_index, $bReadonly)
    $arr_messages = array($hint, array($err1 => "Required", $err2 => "Incorrect format (i.e. xxx)", $err3 => "Generic Error"))

textbox_simple($dom_name, $value = NULL, $custom_type = "text", $bRequired = true)
*/

if(isset($_POST['Submit']) || isset($_POST['SaveButton']))
{
    echo "successfully submitted data!...<br><br>";
}

//fix me: use proper include instead.
require_once("omniphp/omniphp_form.php"); //should go at the beginning, would include like this?, also from views the link would be: "../omniphp/omniphp_form.php"
$omniphp = new OmniPHP_Form();
$omniphp->form_start("OmniPHP_Form", "post", $_SERVER['PHP_SELF']);

echo "<div class='omniphp_validation_errors'></div>"; //to force error output

/**
 * PROGRAMMATIC NOTE:
 * This (textbox call) seems rather lengthy, the 'textbox_simple(...)' version must be as complete as possible but considerably
 * smaller. Another alternative could be to 'install/scaffold' the inputs and then proceed to create them.
 * i.e. $phone_params = array of all parameters for all phone fields
 * then...
 * $omniphp->textbox("name", $phone_params);
 * and use the 'full version' as:
 * $omniphp->textbox_raw(...);
*/

echo "<p>";
echo "<label for='CellPhone'>Cell Phone: </label>";
/**
 *
 * @param string $dom_name The DOM name for the input control (will serve as name and id)
 * @param array $arr_type_format array(customType, format, bRequired, bForceMask)
 *              customType = (text, integer, float, phone, credit_card, social_security_number, zip_code, ...?)
 *              format = (phone = us_phone_all, us_phone1, us_phone2, uk_phone, mx_phone, es_phone, ...?),
 *                       (credit_card = cc_all, cc_visa, cc_mastercard, cc_amex, cc_discover),
 *                       (...)
 * @param array $arr_properties array(class, min, max, tabindex, readonly)
 * @param array $arr_messages array(hint, title, array(err1 => "err msg", err2 => "err msg"))
 * @param mixed $value The value of the input control, will most likely be a string or an int/float, but can be others.
*/
//public function textbox($dom_name, $arr_type_format = array("text", NULL, true, false), $arr_properties = array(NULL, 1, 255, 1, false), $arr_messages = array(NULL, NULL, array("ERROR_REQUIRED" => "This field is required.")), $value = NULL)
//$omniphp->textbox("CellPhone", array("phone","us_phone_all",true,false), array("className",10,14,1,false), array("hint", "title", array("ERROR_REQUIRED" => "This field is required.")), NULL);
$omniphp->input(array("name" => "CellPhone", "type" => "phone", "format" => "us_phone_all", "required" => true, "minlength" => 12, "maxlength" => 12));
echo "</p>";

echo "<p>";
echo "<label for='Phone2'>Home Phone: </label>";
$omniphp->input(array("name" => "HomePhone", "type" => "phone", "format" => "us_phone_all", "required" => true));
echo "</p>";

echo "<p>";
echo "<label for='Text1'>Text1: </label>";
$omniphp->input(array("name" => "Text1", "type" => "text", "required" => true));
echo "</p>";

echo "<p><label for='Phone3'>Phone 3 (via HTML5):</label><input name='Phone3' type='text' required='required' id='Phone3'></p>";


echo "<p><label for='Email'>Email: </label>";
$omniphp->input(array("name" => "Email", "type" => "email", "required" => true));
echo "</p>";

//$omniphp->input(array("name" => "Phone3", "type" => "phone", "format" => "us_phone1", "required" => true, "max" => 15, "min" => 10, "tabindex" => 5, ))

$omniphp->form_end(array("SaveButton", "Save", "className"));

//fix me: do not add this here:
$omniphp->render_js_stack();
echo "<br><br>";

//REFERENCE:
/*
echo "Test Form...<br>";

echo "<form name='OmniPHP_Form' id='OmniPHP_Form' method='post' action='index.php'>";

echo "<div class='omniphp_validation_errors'></div>"; //to force error output

echo "<p>";
echo "<label for='CellPhone'>Cell Phone: </label>";
echo "<input type='text' name='CellPhone' id='CellPhone' maxlength='50' value=''>";
//echo "<input type='phone' name='CellPhone' id='CellPhone' maxlength='10' value='' required>";
echo "</p>";

echo "<input type='submit' name='Submit' id='Submit' value='Submit'>";

echo "</form>";

echo "<br><br>";
//*/
?>
<div class="container">
            
            
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Hello, world!</h1>
                <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                <p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
            </div>

            <!-- Example row of columns -->
            <div class="row">
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
                </div>
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
               </div>
                <div class="span4">
                    <h2>Heading</h2>
                    <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                    <p><a class="btn" href="#">View details &raquo;</a></p>
                </div>
            </div>

            <hr>

            <footer>
                <p>&copy; Company 2012</p>
            </footer>

        </div> <!-- /container -->

    </body>
</html>

<?php
ob_end_flush();
?>
