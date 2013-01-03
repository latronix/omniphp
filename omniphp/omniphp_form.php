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
            $action = $_SERVER['PHP_SELF'];
        }
        echo "<form name='{$dom_name}' id='{$dom_name}' method='{$method}' action='{$action}'>";
    }
    
    /**
     *  
     */
    public function form_end()
    {
        echo "</form>";
    }
    
    public function textbox($dom_name, $arr_type_format = array("text", NULL, true, false), $arr_properties = array(20, 1, 255, 1, false), $arr_messages = array(NULL, array("ERROR_REQUIRED" => "This field is required.")), $value = NULL)
    {
        //
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
