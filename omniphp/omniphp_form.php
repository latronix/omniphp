<?php
/**
 * [MODEL]
 * Filename: omniphp/omniphp_form.php
 * 
 * WIP: Core web forms.
 * 
 * @todo WIP
 * 
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.4.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP. 
 */

require_once("omniphp_error_list.php");

//Fix Me: Add this. 
//Note: Only compatible with PHP 5.3+, 
//for PHP 5.2.x+ have a copy of this file without namespace and with class OmniPHP_Form
//namespace OmniPHP
//{

//fix me: after adding namespace the class should be Form, then invocation: $omniphp = new \OmniPHP\Form() or OmniPHP\Form()?
class OmniPHP_Form
{
    /**
     * MEMBERS
     */
    private $form_name;
    private $error_label_container;
    private $js_stack_rules; //jQuery Validation Rules Stack
    private $js_stack_messages; //jQuery Validation Messages Stack
    
    /**
     * METHODS 
     */
    function __construct()
    {
        //
    }
    function __destruct()
    {
        //
    }
    
    /**
     * 
     * @param type $dom_name
     * @param type $method
     * @param type $action 
     */
    public function form_start($dom_name, $method = "post", $action = NULL, $error_label_container = NULL)
    {
        if(empty($action))
        {
            if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on") $http_prefix = "https://"; else $http_prefix = "http://";
            $absolute_url = $http_prefix . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            
            //$relative_url = $_SERVER['PHP_SELF'];
            
            $action = $absolute_url; //choose whether to use full URL path or relative path.
        }
        echo "<form name='{$dom_name}' id='{$dom_name}' method='{$method}' action='{$action}'>";
        
        $this->form_name = $dom_name;
        $this->js_stack_rules = array();
        $this->js_stack_messages = array();
        $this->error_label_container = $error_label_container;
    }
    
    /**
     * 
     * @param array $arr_save_button array("name","text","class") for input submit
     */
    public function form_end($arr_save_button = array(NULL, NULL, NULL))
    {
        if(!empty($arr_save_button))
        {
//fix me: This ought to be much better, maybe just eliminate it altogether and require $omniphp->submit(...) instead.
            echo "<input class='{$arr_save_button[2]}' type='submit' name='{$arr_save_button[0]}' id='{$arr_save_button[0]}' value='{$arr_save_button[1]}'>";
        }
        echo "</form>";
    }
    
    /**
     * method: input(...)
     * 
     * Direct OmniPHP equivalent to HTML form input. However this web control has been extended to
     * provide autofill, client-side validation, integration with Libraries & Framework for server-side
     * validations and other advanced functionality. On self-submit forms it also allows retaining the
     * values upon processing an HTTP Request (POST/GET).
     * 
     * NOTE: For checkboxes, radiobuttons, buttons, images, file and other non-text input do not use this.
     *       Use the correspondent methods.
     * 
     * The array variable "params" takes most of HTML5 properties as values, in order to be as compatible
     * as possible to HTML.
     * 
     * Example Call:
     * $omniphp->input(array("name" => "Cellphone", "type" => "phone", "format" => "us_phone_all", "required" => true, "maxlength" => 17)); 
     * 
     * @param array $params Takes the same values as an HTML5 input control would with a few exceptions. 
     * 						Since the value is an array you can call it in any order you want.
     * 
     * 						required = true|false
     * 						readonly = true|false
     * 						minlength = the minimum length of characters required
     * 						mask = true|false (true = forces a mask to apply to automatically populate on a specific format, i.e. for a US SSN when typing the third number, the hyphen appears automatically)
     * 						type : text, password, int or integer, float, email, phone, credit_card, social_security_number, zip_code, date, url, ip_address, ...?
     * 						format : text = NULL
     * 								 password = NULL
     * 								 int or integer = signed, unsigned
     * 								 float = 1 - 15 (precision, from 1 to 15 digits), prefix with u = unsigned, s = signed (i.e. s6, u8) 
     * 								 phone = us_phone_all, us_phone_1, us_phone_2, mx_phone, uk_phone, fr_phone, es_phone, jp_phone, ...?
     * 								 credit_card = cc_all, cc_visa, cc_mastercard, cc_amex, cc_discover
     * 								 zip_code = us_zip_5, us_zip_9, uk_zip, ca_zip, ...?
     * 
     * 						... (keep adding documentation) ...
     */
    public function input($params = array("name" => "", "id" => "", "type" => "text", "format" => "", "min" => NULL, "max" => NULL,
    									  "class" => "OmniPHP_Input_Class", "minlength" => 0, "maxlength" => 255, "tabindex" => 1,
    									  "placeholder" => "", "title" => "",
    									  "readonly" => false, "required" => true, "mask" => false,
    									  "value" => ""))
    {
    	/**
    	 * Differences between HTML5 input control & OmniPHP input control
    	 * 
    	 * +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    	 * HTML5			OmniPHP			OmniPHP Description
    	 * +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    	 * type				type			Only text and password are HTML5 equivalent, the other values are specific to OmniPHP's
    	 * 									validations. See documentation for all available types.
    	 * -				format			Based on the type force a specific format. I.e. type = phone, format = us_phone_1 (nnn-nnn-nnnn) or
    	 * 									format = us_phone_2 ((nnn) nnn-nnnn) or format = uk_phone or format = mx_phone, jp_phone, etc.
    	 * -				minlength		Minimum number of chars (not available in HTML5)
    	 * min				min				Minimum value (same as HTML5), allowable for: integer (HTML5 number), date, and float (not in HTML5)
    	 * max				max				Maximum value (same as HTML5), allowable for: integer (HTML5 number), date, and float (not in HTML5)
    	 * readonly			readonly		Takes value true/false instead of readonly
    	 * required			required		Takes value true/false instead of required
    	 * 
    	 */
    	
    	//different:
    	//HTML5: maxlength
    	//OmniPHP: min, max, type (not different but has NON standard HTML types), format, 
    	//		   readonly and required (not different, but the value is true instead of the name),
    	//		   mask
    	//		NOTE: Also 'name' and 'id' will be the same (if either of them is NOT set)
    	
		/**
		 * FIX ME: Add validation for proper name and id.
		 * i.e. do not allow (-) hyphens as they are invalid for id's in JavaScript.
		 * As for 'array' names, if they are added, I could either forbid them or allow them
		 * for the 'name' and automatically create the id as _value, i.e. name = "PhoneNumber[0]", id = "PhoneNumber_0"
		 * 
		 * For the error, just output something like 
		 * <span class='OmniPHP_Error_Note'>
		 * (error #xxx: phone-number is an invalid name/id for this input control, please substitute the 
		 * 'hyphen' for an 'underscore' or eliminate it)
		 * </span>
		 */
    	if( (isset($params["name"]) && trim($params["name"]) != "") && (!isset($params["id"]) || trim($params["id"]) == "") )
    	{
    		$params["id"] = $params["name"];
    	}
    	else if( (isset($params["id"]) && trim($params["id"]) != "") && (!isset($params["name"]) || trim($params["name"]) == "") )
    	{
    		$params["name"] = $params["id"];
    	}
    	
    	/**
		 * Invalid Types: Do not allow creating input for the types below.
		 * Basically because this method is for textboxes only.
    	 */
    	$arrInvalidTypes = array("checkbox", "radio", "button", "submit", "reset", "image", "file", "color", "datetime", "datetime-local", "month", "number", "range", "search", "tel", "week");
    	if(!isset($params["type"]) || in_array($params["type"], $arrInvalidTypes))
    	{
    		echo "<span class='OmniPHP_Error_Note'>(Error: {$params["name"]} has an invalid type)</span>";
    		return;
    	}
    	
//NOTE: While the most flexible solution is to use REQUEST, maybe I should FORCE the user to use POST instead of GET?
//Mostly because POST is more efficient; less convoluted for the URL, and it allows greater data storage.
//The usual configurations (web server and web browser specific) for the HTTP requests is hypothetically like this:
//HTTP POST up to 2 GB?
//HTTP GET around 8 KB? (yes KILOBYTES not even 1 MB)
//
//For large web forms it is definitely convenient to use POST or force the user to divide the web form in different
//sections or use some other hack.
//
//Maybe I should just encourage the user to use POST but leave it like this to allow full flexibility?
		/**
		 * Retain the value of the input after performing HTTP request.
		 */
    	/*if(!isset($params["value"]) && isset($_POST[$params["name"]]))
    	{
    		$params["value"] = $_POST[$params["name"]];
    	}*/
    	if(!isset($params["value"]) && isset($_REQUEST[$params["name"]]))
    	{
    		$params["value"] = $_REQUEST[$params["name"]];
    	}
    	
		/**
		 * arr_rules and arr_messages
		 */
    	if(!empty($this->js_stack_rules))
    	{
    		$js_str_rules = ",";
    	}
    	else 
    	{
    		$js_str_rules = "";
    	}
    	$js_str_rules .= "{$params["name"]}: {";
    	
    	if(!empty($this->js_stack_messages))
    	{
    		$js_stack_messages = ",";
    	}
    	else 
    	{
    		$js_stack_messages = "";
    	}
    	$js_stack_messages .= "{$params["name"]}: {";

    	$arr_rules = array();
    	$arr_messages = array();
    	if(isset($params["required"]) && $params["required"] === true)
    	{
    		array_push($arr_rules, "required: true");
    		$msg = OMNIPHP_ERROR_PREFIX . str_replace("[name] ", $params["name"] . " ", OMNIPHP_ERROR_REQUIRED);
    		array_push($arr_messages, "required: \"{$msg}\"");
    	}
//    	$minlength = (isset($params["minlength"])) ? (string)$params["minlength"] : NULL;
    	if(isset($params["minlength"]) && ctype_digit((string)$params["minlength"]))
    	{
    		array_push($arr_rules, "minlength: {$params["minlength"]}");
    		$msg = OMNIPHP_ERROR_PREFIX . str_replace("[name] ", $params["name"] . " ", OMNIPHP_ERROR_MINLENGTH);
    		$msg = str_replace("[minlength] ", $params["minlength"] . " ", $msg);
    		array_push($arr_messages, "minlength: \"{$msg}\"");
    	}
    	if(isset($params["maxlength"]) && ctype_digit((string)$params["maxlength"]) && $params["maxlength"] >= $params["minlength"])
    	{
    		array_push($arr_rules, "maxlength: {$params["maxlength"]}");
    		$msg = OMNIPHP_ERROR_PREFIX . str_replace("[name] ", $params["name"] . " ", OMNIPHP_ERROR_MAXLENGTH);
    		$msg = str_replace("[maxlength] ", $params["maxlength"] . " ", $msg);
    		array_push($arr_messages, "maxlength: \"{$msg}\"");
    	}
    	if($params["type"] == "email")
    	{
    		array_push($arr_rules, "email: true");
    		$msg = OMNIPHP_ERROR_PREFIX . str_replace("[name] ", $params["name"] . " ", OMNIPHP_ERROR_EMAIL);
    		array_push($arr_messages, "email: \"{$msg}\"");
    	}
    	if($params["type"] == "phone")
    	{
    		switch($params["format"])
    		{
    			case "us_phone_all": //formats: (000) 000-0000 || 000-000-0000 || 000 000-0000, must be valid US phone numbers (i.e. nnn-555-nnnn not allowed)
    			{
    				array_push($arr_rules, "phoneUS: true");
			    	$msg = OMNIPHP_ERROR_PREFIX . str_replace("[name] ", $params["name"] . " ", OMNIPHP_ERROR_PHONE);
			    	array_push($arr_messages, "phoneUS: \"{$msg}\"");
    			}
    			break;
    			case "us_phone1":
    			{
    				//
    			}
    			break;
    			case "us_phone2":
    			{
    				//
    			}
    			break;
    			default:
    			{
    				array_push($arr_rules, "phoneUS: true"); //default to: us_phone_all
    			}
    		}
    	}

    	$js_str_rules .= implode(",", $arr_rules);
    	$js_str_rules .= "}";
    	array_push($this->js_stack_rules, $js_str_rules);

    	$js_stack_messages .= implode(",", $arr_messages);
    	$js_stack_messages .= "}";
    	array_push($this->js_stack_messages, $js_stack_messages);
    	
    	echo "<input";
    	foreach($params as $property => $value)
    	{
    		if($value == "" || $value === false || $property == "required" || $property == "format")
    		{}
    		else
    		{
    			if($property == "value" && trim($value) != "")
    			{
    				$htmlValue = htmlspecialchars($value, ENT_QUOTES);
    			}
    			else
    			{
	    			//$htmlValue = (($property == "readonly" || $property == "required") && $value == true) ? $property : $value;
    				$htmlValue = (($property == "readonly") && $value == true) ? $property : $value;
    			}
	    		echo " {$property}='{$htmlValue}'";
    		}
    	}
    	echo ">";
    }
    
	//differs from HTML: arrValues equivalent to options, array("option_value" => "label_text")
    public function select($params = array("name" => "", "id" => "", 
    		"class" => "OmniPHP_Select_Class", "tabindex" => 1,
    		"placeholder" => "", "title" => "",
    		"readonly" => false, "required" => true, 
    		"value" => ""), $arrValues = array())
    {
    	//
    }
    
//FIX ME: Add these
    //input shortcuts
    public function hidden() {}
    public function submit() {}
    public function reset() {}
    //public function button() {} 

    
    
//fix me: idea: maybe add a verification based on the browsers we know that support the new HTML5 types
/*
i.e.
//note: vf which versions of FF, Chrome, Safari, and Opera support HTML5, for IE I know it has to be IE 10
if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 10") !== false || strpos($_SERVER['HTTP_USER_AGENT'], "Chrome/20") !== false || 
	strpos($_SERVER['HTTP_USER_AGENT'], "Firefox/10") !== false || strpos($_SERVER['HTTP_USER_AGENT'], "Safari??") !== false ||
	strpos($_SERVER['HTTP_USER_AGENT'], "Opera??") !== false) 
{
	//echo "Error: Unsupported browser detected.";
	//exit 0;
	
	//note: actually what I would do here is to default to OmniPHP's input instead of this one if
	//the browser is unsupported. Kinda what modernizr would do.
}

note: I could officially boycott Safari until Apple provides a PC-friendly alternative to allow
testing Safari on PC.
i.e.
if(strpos($_SERVER['HTTP_USER_AGENT'], "Safari/?") !== false)
{
	echo "Fatal Error: You are using an unsupported browser (Safari). Since Apple is not providing a means ";
	echo "to allow testing Safari on PC's (Linux / Windows) we cannot support their product due to lack of viable testing.";
	echo "<br><br>";
	echo "If you are using a PC, please use a real supported browser.<br>";
	echo "If you are using a Mac, please use a BETTER browser such as: Google Chrome or Mozilla Firefox.<br>";
	exit 1;
}

*/
//fix me: update $params to be equivalent to input(...) but with the HTML5 values instead
    /**
     * method: inputHTML5(...)
     * 
     * Uses the HTML5 form input with the new attributes.
     * Recommendation: Use input(...) instead of this method as this one is more basic and less compatible.
     * 
     * IMPORTANT NOTE: Please note that some browsers in their latest releases 
     * 				   (and most older browsers) are NOT compatible with the new HTML5 types and validations.
     * 				   Also the advanced validations and functionality provided by OmniPHP are highly
     * 				   reduced with this method as it is meant to be the simpler HTML5 input control.
     * 
     * BROWSER COMPATIBILITY LISTS:
     * http://en.wikipedia.org/wiki/Comparison_of_layout_engines_(HTML5)
     * http://html5test.com/
     * http://caniuse.com/
     * http://docs.webplatform.org/wiki/html/elements/input/type
     * http://www.w3schools.com/html/html5_form_input_types.asp
     * 
     * Example call:
     * $omniphp->inputHTML5(array("name" => "CustomerEmail", "type" => "email", "required" => "true"));
     * 
     * @param array $params All of these values are directly equivalent to their HTML5 counterparts.
     */
    public function inputHTML5($params = array("name" => "", "id" => "", "type" => "text", "pattern" => "", 
							    			   "class" => "OmniPHP_Input_Class", "min" => NULL, "max" => NULL, "maxlength" => 255, "tabindex" => 1,
							    			   "readonly" => "", "required" => ""))
    {
//fix me: lots of changes above (in input(...)), add them after input is final
    	/**
    	 * FIX ME: see input(...) above for the notes.
    	 */
    	/*
    	if(trim($params["name"]) != "" && trim($params["id"]) == "")
    	{
    		$params["id"] = $params["name"];
    	}
    	else if(trim($params["id"]) != "" && trim($params["name"]) == "")
    	{
    		$params["name"] = $params["id"];
    	}
    	 
    	echo "<input";
    	foreach($params as $property => $value)
    	{
    		if(($property == "readonly" || $property == "required" || $property == "pattern") && $value == "")
    		{}
    		else
    		{
    			echo " {$property}='{$value}'";
    		}
    	}
    	echo ">";
    	*/
    }
    
    
    
    
    //use textbox for scaffolding
/*
 * KILL ME: The parameters in this function are too tedious to remember and since most of them
 * are basically the same as the HTML classic properties (and the extended HTML5 ones) then 
 * I redesigned this to be an input method with an array of all the HTML properties (with some
 * minor modifications/extensions). It is FAR easier to remember and there will hardly be a need to
 * read the reference API / Documentation.
 */
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
    /*
    public function textbox($dom_name, $arr_type_format = array("text", NULL, true, false), $arr_properties = array(NULL, 1, 255, 1, false), $arr_messages = array(NULL, NULL, array("ERROR_REQUIRED" => "This field is required.")), $value = NULL)
    {
        $js_str = "{$dom_name}: {";
        
        $arr_rules = array();
        if(isset($arr_type_format[2]) && $arr_type_format[2] === true)
        {
            array_push($arr_rules, "required: true");
        }
        if(isset($arr_properties[1]) && ctype_digit($arr_properties[1]))
        {
            array_push($arr_rules, "minlength: {$arr_properties[1]}");
        }
        if(isset($arr_properties[2]) && ctype_digit($arr_properties[2]) && $arr_properties[2] >= $arr_properties[1])
        {
            array_push($arr_rules, "maxlength: {$arr_properties[2]}");
        }
        if($arr_type_format[0] == "phone")
        {
            switch($arr_type_format[1])
            {
                case "us_phone_all": //formats: (000) 000-0000 || 000-000-0000 || 000 000-0000, must be valid US phone numbers (i.e. nnn-555-nnnn not allowed)
                {
                    array_push($arr_rules, "phoneUS: true");
                }
                break;
                case "us_phone1":
                {
                    //
                }
                break;
                case "us_phone2":
                {
                    //
                }
                break;
                default:
                {
                    array_push($arr_rules, "phoneUS: true"); //default to: us_phone_all
                }
            }
        }
        
        $js_str .= implode(",", $arr_rules);
        $js_str .= "}";
        array_push($this->js_stack, $js_str);
        
        //add me:
        echo "<input type='text' name='{$dom_name}' id='{$dom_name}' maxlength='{$arr_properties[2]}' title='{$arr_messages[1]}' class='{$arr_properties[0]}' tabindex='{$arr_properties[3]}' value='{$value}'>";
//echo "<input type='text' name='CellPhone' id='CellPhone' maxlength='50' value=''>";
    }
    */
    

    private function jquery_get_js_stack()
    {
    	foreach($this->js_stack as $v)
    	{
    		echo "\t" . $v . PHP_EOL;
    	}
    	$v = NULL;
    }
    
//fix me: add messages as dynamic, maybe rename (don't love the render_js_stack name), and do not generate as $(document)
    public function render_js_stack()
    {
    	//echo "JS STACK:<br>";
    	//print_r($this->js_stack);
echo <<<JS
<script>
$(document).ready(function(){
    
        $("#{$this->form_name}").validate({
            rules: {
JS;
					foreach($this->js_stack_rules as $v)
					{
						echo $v;
					}
echo <<<JS
            },
            messages: {
JS;
					foreach($this->js_stack_messages as $v)
					{
						echo $v;
					}
echo "            }"; //close messages
if(isset($this->error_label_container) && !empty($this->error_label_container))
{
echo <<<JS
            ,
            errorLabelContainer: $("#{$this->form_name} {$this->error_label_container}")
JS;
}
echo <<<JS
        });

});
</script>

JS;

    }
}

//} //for namespace
?>
