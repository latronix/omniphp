<?php
/**
 * [MODEL]
 * Filename: model_framework_form.php
 * 
 * One of the core libraries of OmniPHP. The web forms library to create
 * all web-controls such as:
 * textboxes (input text, textarea), listboxes (single and multiple select 
 * / drop down menus), checkboxes (check lists), and radio buttons.
 * 
 * The web controls can be created via single method calls to an instance of this
 * class by passing the appropriate configuration parameters. It allows creating
 * a broad set of customized formats for the inputs with pre-built client-side
 * validations, configurable error-output, AJAX communication, and complex logics.
 * 
 * @todo Recreate, will not be based on spry-wrapper but will actually be a wrapper for jQuery.
 * @todo Add new logic and better coding practices.
 * 
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.3.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

require_once("model_spry_wrapper.php");

class Framework_Form
{
    private $spryWrapper;
    
    function __construct()
    {
        $this->spryWrapper = new Spry_Wrapper();
    }
    function __destruct()
    {
        //
    }
    
    //arrTypeFormat = array(customType, format), $arrProperties = (size, maxlength, tabindex)
    public function textbox($domName, $arrTypeFormat = array(NULL, NULL), $arrProperties = array(10, 10, ""), $bRequired = true, $value = NULL, $isPass = false)
    {
        if($bRequired == true)
            $errArr = array(SPRY_ERR_NOT_EMPTY, "");
        else
            $errArr = NULL;
        $this->spryWrapper->spry_create_edit_box($arrTypeFormat[0], $arrTypeFormat[1], "", $domName, $domName, $value, $arrProperties[0], $arrProperties[1], $errArr, "", true, $isPass, $arrProperties[2]);
    }
    //$arrVals = (values, i.e. array("01" => "Yes", "02" => "No"))
    public function listbox($domName, $arrVals, $tabIndex = 1, $bRequired = true, $value = NULL)
    {
        if($bRequired == true)
            $errArr = SPRY_ERR_NOT_EMPTY;
        else
            $errArr = NULL;
        $this->spryWrapper->spry_create_list_box("", $domName, $domName, $arrVals, 1, false, $errArr, $value, true, $tabIndex);
    }
    public function multi_listbox()
    {
        //
    }
    public function textarea($domName, $arrProperties = array(45, 5, ""), $arrMinMax = array(1, 2000), $bRequired = false, $value = NULL)
    {
        $this->spryWrapper->spry_create_text_area($domName, $arrProperties, $arrMinMax, $bRequired, $value);
    }
    
    /**
     * Input hidden (fake, no value)
     * @param string $domName The DOM name for the input. Due to convention the value will be prefixed with 'frm', do not add the frm in the domName.
     */
    public function hidden($domName)
    {
        echo "<input type='hidden' name='frm{$domName}' id='frm{$domName}' value='' />";
    }
    
    /**
     * Input hidden (with value)
     * To simulate data submission (i.e. particularly useful to pass ID's/PK's/FK's and other non-displayable data
     * 
     * @param string $domName The DOM name for the input. Due to convention the value will be prefix3ed with 'frm', do not add the frm in the domName.
     * @param string $value Literal.
     */
    public function hidden_with_value($domName, $value = NULL)
    {
        echo "<input type='hidden' name='frm{$domName}' id='frm{$domName}' value='{$value}' />";
    }

    public function render_js_stack()
    {
        $this->spryWrapper->spry_get_js_stack();
        $this->spryWrapper->jquery_get_js_stack();
    }
    
    
    
    
    //arrTypeFormat = array(customType, format), $arrProperties = (size, maxlength, tabindex)
    /**
     * method: textbox2(...)
     * 
     * Modern version of the textbox, will NOT echo but instead return a string with the html input.
     * Will use HTML5 and CSS3.
     * 
     * @param string $domName The DOM name (input name, id and spry span name)
     * @param array $arrTypeFormat array(customType, format). customType (i.e. NULL, integer, phone_number, etc), format (i.e. phone_us, etc. as applicable)
     * @param array $arrProperties array(size, maxlength, tabindex). Note: size will be CSS not input size attribute which is outdated.
     * @param bool $bRequired true (default) = required, false = not required
     * @param mixed $value The actual value
     * @param bool $isPass true = is password, false (default) = is not password
     */
    public function textbox2($domName, $arrTypeFormat = array(NULL, NULL), $arrProperties = array(10, 10, ""), $bRequired = true, $value = NULL, $isPass = false)
    {
        if($bRequired == true)
            $errArr = array(SPRY_ERR_NOT_EMPTY, "");
        else
            $errArr = NULL;
        return $this->spryWrapper->spry_create_edit_boxX($arrTypeFormat[0], $arrTypeFormat[1], "", $domName, $domName, $value, $arrProperties[0], $arrProperties[1], $errArr, "", true, $isPass, $arrProperties[2]);
    }
    
    /**
     * method: listbox2(...)
     * 
     * Modern version of the listbox, will NOT echo but instead return a string with the html select.
     * Will use HTML5 and CSS3.
     * 
     * @param string $domName The DOM name (input name, id and spry span name)
     * @param array $arrVals The options/values in the select (will be in the format: i.e. array("01" => "Yes", "02" => "No"))
     * @param int|string $tabIndex Literal
     * @param bool $bRequired true (default) = required, false = not required
     * @param mixed $value The actual value to display as selected
     */
    public function listbox2($domName, $arrVals, $tabIndex = 1, $bRequired = true, $value = NULL, $width = 50)
    {
        if($bRequired == true)
            $errArr = SPRY_ERR_NOT_EMPTY;
        else
            $errArr = NULL;
        return $this->spryWrapper->spry_create_list_boxX("", $domName, $domName, $arrVals, 1, false, $errArr, $value, true, $tabIndex, false, $width);
//return "valor = $value";
    }
    
}

?>
