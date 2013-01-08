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

class OmniPHP_Form
{
//echo "<form name='OmniPHP_Form' id='OmniPHP_Form' method='post' action='index.php'>";

    /**
     * MEMBERS
     */
    //private $wrapper;
    private $form_name;
    private $js_stack; //JavaScript Stack (code to be installed in .js)
    
    /**
     * METHODS 
     */
    function __construct()
    {
        //$this->wrapper = new Wrapper();
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
    public function form_start($dom_name, $method = "post", $action = NULL)
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
    }
    
    /**
     * 
     * @param array $arr_save_button array("name","text","class") for input submit
     */
    public function form_end($arr_save_button = array(NULL, NULL, NULL))
    {
        if(!empty($arr_save_button))
        {
            echo "<input class='{$arr_save_button[2]}' type='submit' name='{$arr_save_button[0]}' id='{$arr_save_button[0]}' value='{$arr_save_button[1]}'>";
        }
        echo "</form>";
    }
    
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
    public function textbox($dom_name, $arr_type_format = array("text", NULL, true, false), $arr_properties = array(NULL, 1, 255, 1, false), $arr_messages = array(NULL, NULL, array("ERROR_REQUIRED" => "This field is required.")), $value = NULL)
    {
        $js_str = "{$dom_name}: \{";
        
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
        $js_str .= "\}";
        array_push($this->js_stack, $js_str);
        
        //add me:
        echo "<input type='text' name='{$dom_name}' id='{$dom_name}' maxlength='{$arr_properties[2]}' title='{$arr_messages[1]}' class='{$arr_properties[0]}' tabindex='{$arr_properties[3]}' value='{$value}'>";
//echo "<input type='text' name='CellPhone' id='CellPhone' maxlength='50' value=''>";
        
        /*$("#OmniPHP_Form").validate({
            rules: {
                    CellPhone: {
                            required: true,
                            phoneUS: true,
                            minlength: 12,
                            maxlength: 12
                    }
            },
            messages: {
                    CellPhone: {
                            required: "Please enter a phone",
                            phoneUS: "Enter phone in correct format",
                            minlength: "Your phone must consist of at least 12 characters",
                            maxlength: "Your phone cannot exceed 12 characters"
                    }
            },
            errorLabelContainer: $("#OmniPHP_Form div.omniphp_validation_errors")
        });*/
    }
/*  
textbox($dom_name, $arr_type_format, $arr_properties, $arr_messages, $value);
    $arr_type_format = array($custom_type, $format, $bRequired, $force_masking)
	$custom_type = text,phone, etc...
	$format = us_phone (any format),us_phone1 (###-###-####),us_phone2 ((###) ###-####), etc...
    $arr_properties = array($size, $min, $max, $tab_index, $bReadonly)
    $arr_messages = array($hint, array($err1 => "Required", $err2 => "Incorrect format (i.e. xxx)", $err3 => "Generic Error"))

textbox_simple($dom_name, $value = NULL, $custom_type = "text", $bRequired = true)
*/
    
    /*
    public function render_js_stack()
    {
        $this->spryWrapper->spry_get_js_stack();
        $this->spryWrapper->jquery_get_js_stack();
    }
    */
}
?>
