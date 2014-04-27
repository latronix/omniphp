<?php
//fake commit
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
        <script src="js/main.js"></script>
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

<div class="container">
            
            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">

<?php

echo "<h2>OmniPHP_Form Test 1</h2>";

if(isset($_POST['SaveButton']))
{
    echo "successfully submitted data!...<br><br>";
}

//fix me: use proper include instead.
require_once("omniphp/omniphp_form.php"); //should go at the beginning, would include like this?, also from views the link would be: "../omniphp/omniphp_form.php"
$omniphp = new OmniPHP_Form();
$omniphp->form_start("OmniPHP_Form", "post", $_SERVER['PHP_SELF']);

echo "<div class='omniphp_validation_errors'></div>"; //to force error output

echo "<p>";
//echo "<label for='CellPhone'>Cell Phone: </label>";
echo "Cell Phone: ";
$omniphp->input(array("name" => "CellPhone", "type" => "phone", "format" => "us_phone_all", "required" => true, "minlength" => 12, "maxlength" => 12));
echo "</p>";

echo "<p>";
echo "<label for='Phone2'>Home Phone: </label>";
$omniphp->input(array("name" => "HomePhone", "type" => "phone", "format" => "us_phone_all", "required" => true, "class" => "input-small"));
echo "</p>";

echo "<p>";
echo "<label for='Text1'>Text1: </label>";
$omniphp->input(array("name" => "Text1", "type" => "text", "required" => true));
echo "</p>";

echo "<p><label for='Phone3'>Phone 3 (via HTML5):</label><input name='Phone3' type='text' required='required' id='Phone3'></p>";

echo "<p><label for='Email'>Email: </label>";
$omniphp->input(array("name" => "Email", "type" => "email", "required" => true));
echo "</p>";

$omniphp->form_end(array("SaveButton", "Save", "btn btn-large"));

//fix me: do not add this here:
$omniphp->render_js_stack();
echo "<br><br>";

?>
            
            
            	<!-- 
                <h1>Hello, world!</h1>
                <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                <p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
                -->
            </div>

            <!-- Example row of columns -->
            <!-- 
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
            -->

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
