<?php
/**
 * [MODEL]
 * Filename: model_spry_wrapper
 * 
 * A wrapper for the outdated/old Adobe Spry libraries.
 * The wrapper is basically for the most common form inputs
 * (i.e. text, textarea, select, radio buttons).
 * It allows advanced configuration, validation, and error output
 * for the client-side webform.
 * 
 * NOTE: This is an OUTDATED legacy library and only serves as reference
 * for new wrappers. Spry is no longer being maintained and jQuery has
 * basically taken over the JavaScript/AJAX library market, hence all the
 * 'newer' wrappers will be based on jQuery.
 * 
 * @todo Recreate this (should be a full jQuery wrapper instead of Spry).
 * @todo Recreate Spry validations (i.e. phone, ssn, cc, etc) as jQuery validations.
 * @todo Full redesign and recreate logic based on better coding practices.
 * 
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.3.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

require_once(dirname(__FILE__) . "/../models/model_globals.php"); //Model (Globals)

/**
 * Spry Errors (macros)
 * NOT USED.
 */
if(LANGUAGE === "pr-es")
{
    //NOTE: Don't use accents or non-ASCII 7-bit characters for this JS code.
    define("SPRY_ERR_NOT_EMPTY", "Error: No deje en blanco.");
    define("SPRY_ERR_INVALID_FORMAT", "Error: Formato invalido.");
    define("SPRY_ERR_VALID_ALT", "Error: Seleccione alternativa valida.");
    define("SPRY_ERR_SPECIFY", "Error: Especifique un valor.");
    define("SPRY_ERR_MIN", "Error: Numero minimo de caracteres no se ha cumplido.");
    define("SPRY_ERR_MAX", "Error: Numero maximo de caracteres excedido.");
}
if(LANGUAGE === "us-en")
{
    define("SPRY_ERR_NOT_EMPTY", "Error: Do not leave blank.");
    define("SPRY_ERR_INVALID_FORMAT", "Error: Invalid format.");
    define("SPRY_ERR_VALID_ALT", "Error: Choose a valid answer.");
    define("SPRY_ERR_SPECIFY", "Error: Specify an answer.");
    define("SPRY_ERR_MIN", "Error: Minimum number of characters not met.");
    define("SPRY_ERR_MAX", "Error: Maximum number of characters exceeded.");
}
define("SPRY_CTRL_DELIMITER", ','); //for generating radio buttons through an argument array (passed to the functions)

/**
 * class: Spry_Wrapper
 */
class Spry_Wrapper
{
	private $Spry_JS_Stack; //array that holds all the JavaScript object instantiations for Spry, these will be installed at the end of the HTML page (exactly before </body>).
	private $jQuery_JS_Stack; //same as above but for jQuery.
	
    //The constructor
	function __construct()
	{
		$this->Spry_JS_Stack = array();
		$this->jQuery_JS_Stack = array();
	}
    //The destructor
	function __destruct()
	{
		$this->Spry_JS_Stack = array();
		$this->jQuery_JS_Stack = array();
	}
	
    /**********************************
     Spry Framework Wrappers
    **********************************/
    
	/**
	 * method: spry_create_edit_box(...)
	 *
	 * A Spry Validation Text Field (an HTML form input text), customizable. I.e.
	 * can be text, integer, phone numbers, zip codes, ssn, emails, etc.
	 * For more info check the Spry documentation site or the JavaScript libraries.
	 *
	 * @see SpryValidationTextField.js
	 * 
	 * @param string $customType The custom type: integer, date, time, email, zip_code, phone_number, social_security_number, or credit_card.
	 * @param string $format Format for the $customType, see reference(s).
	 * @param string $caption Ignore.
	 * @param string $spryName Name for a span, will be prefixed by frm, do not use arrays.
	 * @param string $frmName Name for the "name" and "id" input attributes. Can use arrays.
	 * @param string $value "value" attribute (will pass the last chosen value or the default if no post/submit has happened).
	 * @param int $size "size" attribute.
	 * @param int $maxlength "maxlength" attribute.
	 * @param array $errMsg An array that displays error messages, ignore for this project.
	 * @param string $hint Ignore.
	 * @param bool $bNewLine Ignore.
	 * @param bool $bIsPassword If the text is a password input.
	 * @param int $tabIdx The tab index as would be used (order to follow) when pressing tab on the keyboard.
	 * @param bool $bReadOnly If the text is read only.
	 * 
	 * @return void Does not return anything.
	 */
    public function spry_create_edit_box($customType, $format = NULL, $caption = NULL, $spryName, $frmName, $value = NULL, $size = 40, $maxlength = 10, $errMsg, $hint = "", $bNewLine = false, $bIsPassword = false, $tabIdx = "", $bReadOnly = false)
    {
        if(is_null($customType))
        {
            $customType = "none";
        }
		if(strpos("[", $frmName) !== false)
		{
			$id = str_replace("[", "_", $frmName);
			$id = str_replace("]", "", $id);
		}
		else
		{
			$id = $frmName;
		}
        echo "<span id='spry" . $spryName . "'>";
        echo $caption . " ";
		if(!is_array($frmName))
		{
			if($bIsPassword === true)
				echo "<input type='password'";
			else
				echo "<input type='text'";
			echo " name='frm" . $frmName . "' id='frm" . $id . "' value='" . htmlspecialchars($value, ENT_QUOTES) . "' size='" . $size . "' maxlength='" . $maxlength . "' tabindex='" . $tabIdx . "' ";
			if($bReadOnly === true)
				echo " readonly='readonly' ";
			echo " />";
		}
		else
		{
			if($bIsPassword === true)
				echo "<input type='password'";
			else
				echo "<input type='text'";
			echo " name='frm" . $frmName . "' id='frm" . $id . "' value='" . htmlspecialchars($value, ENT_QUOTES) . "' size='" . $size . "' maxlength='" . $maxlength . "' tabindex='" . $tabIdx . "' ";
			if($bReadOnly === true)
				echo " readonly='readonly' ";
			echo " />";
		}
        if(is_null($errMsg))
        {
			if($customType == "phone_number") //phone hack (force to ###-###-#### format instead of default phone_us (###) ###-####
			{
				array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationTextField('spry$spryName', 'phone_number', {isRequired:false, format:'phone_custom', pattern:'000-000-0000', validateOn:['blur'], useCharacterMasking:true});");
			}
			else
			{
				if(is_null($format))
				{
					array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:false, hint:'$hint', validateOn:['blur']});");
				}
				else
				{
					if(empty($format))
						array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:false, hint:'$hint', useCharacterMasking:true, validateOn:['blur']});");
					else
						array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:false, hint:'$hint', format:'$format', useCharacterMasking:true, validateOn:['blur']});");
				}
			}
            
            //Display input errors (incorrect format):
            $incorrectFormat = "";
            if(LANGUAGE === "pr-es")
            {
                switch($customType)
                {
                    case "date":
                        $incorrectFormat = "fecha (" . $format . ")";
                    break;
                    case "integer":
                        $incorrectFormat = "numero entero";
                    break;
                    case "social_security_number":
                        $incorrectFormat = "SSN (###-##-####)";
                    break;
                    case "email":
                        $incorrectFormat = "usuario@dominio.xxx";
                    break;
					case "phone_number":
						$incorrectFormat = "###-###-####";
					break;
					case "credit_card":
					{
						if(empty($format) || $format == "all")
						{
							$incorrectFormat = "tarjeta de credito v&aacute;lida (12 - 19 digitos, 16 en la mayor&iacute;a de los casos)";
						}
						else if($format == "visa")
						{
							$incorrectFormat = "tarjeta VISA (13 - 16 digitos, comienza en 4)";
						}
						else if($format == "mastercard")
						{
							$incorrectFormat = "tarjeta MasterCard (16 digitos, comienza en 5), o Maestro (12 - 19 digitos, comienza en 5 o 6)";
						}
						else if($format == "amex")
						{
							$incorrectFormat = "tarjeta AMEX (15 digitos, comienza en 3)";
						}
						else //all others, i.e. discover, dinersclub
						{
							$incorrectFormat = "tarjeta de credito v&aacute;lida (12 - 19 digitos, 16 en la mayor&iacute;a de los casos)";
						}
					}
					break;
                    default:
                        $incorrectFormat = "formato del campo";
                    break;
                }
                if($bNewLine === true)
                {
//                    echo "<br />";
                }
//                echo "<span class='textfieldInvalidFormatMsg'>Error: formato incorrecto, debe ser: " . $incorrectFormat . ".</span>";
echo "<span class='textfieldInvalidFormatMsg'>&nbsp;</span>";
            }
            if(LANGUAGE === "us-en")
            {
                switch($customType)
                {
                    case "date":
                        $incorrectFormat = "date (" . $format . ")";
                    break;
                    case "integer":
                        $incorrectFormat = "an integer number";
                    break;
                    case "social_security_number":
                        $incorrectFormat = "SSN (###-##-####)";
                    break;
                    case "email":
                        $incorrectFormat = "user@domain.xxx";
                    break;
					case "phone_number":
						//$incorrectFormat = "(###) ###-####";
						$incorrectFormat = "###-###-####";
					break;
					case "credit_card":
					{
						if(empty($format) || $format == "all")
						{
							$incorrectFormat = "valid credit card (12 - 19 digits, usually 16)";
						}
						else if($format == "visa")
						{
							$incorrectFormat = "valid VISA (13 - 16 digits, starts with 4)";
						}
						else if($format == "mastercard")
						{
							$incorrectFormat = "valid MasterCard (16 digits, starts with 5), or Maestro (12 - 19 digits, starts with 5 or 6)";
						}
						else if($format == "amex")
						{
							$incorrectFormat = "valid AMEX (15 digits, starts with 3)";
						}
						else //all others, i.e. discover, dinersclub
						{
							$incorrectFormat = "valid credit card (12 - 19 digits, usually 16)";
						}
					}
					break;
                    default:
                        $incorrectFormat = "field's format";
                    break;
                }
                if($bNewLine === true)
                {
//				    echo "<br />";
                }
//                echo "<span class='textfieldInvalidFormatMsg'>Error: incorrect format, should be: " . $incorrectFormat . ".</span>";
echo "<span class='textfieldInvalidFormatMsg'>&nbsp;</span>";
            }
        }
        else
        {
			if($customType == "phone_number") //phone hack (force to ###-###-#### format instead of default phone_us (###) ###-####
			{
				array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationTextField('spry$spryName', 'phone_number', {isRequired:true, format:'phone_custom', pattern:'000-000-0000', validateOn:['blur'], useCharacterMasking:true});");
			}
			else
			{
				if(is_null($format))
				{
					array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:true, hint:'$hint', validateOn:['blur']});");
				}
				else
				{
					if(empty($format))
						array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:true, hint:'$hint', useCharacterMasking:true, validateOn:['blur']});");
					else
						array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:true, hint:'$hint', format:'$format', useCharacterMasking:true, validateOn:['blur']});");
				}
			}
            if($bNewLine === true)
            {
//                echo "<br />";
            }
//            echo "<span class='textfieldRequiredMsg'>$errMsg[0]</span>";
//            echo "<span class='textfieldInvalidFormatMsg'>$errMsg[1]</span>";
echo "<span class='textfieldRequiredMsg'>&nbsp;</span>";
echo "<span class='textfieldInvalidFormatMsg'>&nbsp;</span>";
$jQueryName = $spryName;
echo "<span id='jquery" . $jQueryName . "'>&nbsp;</span>";
        }
        echo "</span>";
		
		/*
		//This jQuery code adds a date picker (calendar)
		if( ($customType === "date") && ($format === "mm/dd/yyyy") )
		{
			array_push($this->jQuery_JS_Stack, "jQuery(\"#frm" . $frmName . "\").datepicker();");
		}
		*/
    }
    
    //LIMITATION NOTE: Does not support textarea arrays yet, i.e. <textarea name='frmComment[0]' ...> is NOT supported.
    //Add respective array [] manipulation code like in edit_box to handle this properly.
    /**
     * method: spry_create_text_area(...)
     * 
     * A Spry Validation Textarea.
     * 
     * @see SpryValidationTextarea.js
     * 
     * @param string $domName Name that will go in textarea properties for name and id, and the name for the spry span.
     * @param array $arrProperties array(cols, rows, tabindex)
     * @param type $arrMinMax array(min chars, max chars)
     * @param type $bRequired If the textarea is required or not (true | false)
     * @param type $value The value/text.
     */
    public function spry_create_text_area($domName, $arrProperties = array(45, 5, ""), $arrMinMax = array(1, 2000), $bRequired = false, $value = NULL)
    {
        echo "<span id='spry" . $domName . "'>";        
        
        echo "<textarea name='frm" . $domName . "' id='frm" . $domName . "' cols='" . $arrProperties[0] . "' rows='" . $arrProperties[1] . "' tabindex='" . $arrProperties[2] . "'>" . $value . "</textarea>";

        echo "<span id='countspry" . $domName . "'>&nbsp;</span>";
        /*
        echo "<span class='textareaRequiredMsg'>" . SPRY_ERR_NOT_EMPTY . "</span>";
        echo "<span class='textareaMinCharsMsg'>" . SPRY_ERR_MIN . "</span>";
        echo "<span class='textareaMaxCharsMsg'>" . SPRY_ERR_MAX . "</span>";
        */
//hack:
echo "<span class='textareaRequiredMsg'>&nbsp;</span>";
echo "<span class='textareaMinCharsMsg'>&nbsp;</span>";
echo "<span class='textareaMaxCharsMsg'>&nbsp;</span>";
        
        echo "</span>";
        $isRequired = ($bRequired === false) ? "false" : "true";
        array_push($this->Spry_JS_Stack, "var $domName = new Spry.Widget.ValidationTextarea('spry$domName', {validateOn:['blur','change'], isRequired:{$isRequired}, minChars:{$arrMinMax[0]}, maxChars:{$arrMinMax[1]}, counterType:'chars_remaining', counterId:'countspry$domName'});");
    }
    
    
    
	/**
	 * method: spry_create_list_box(...)
	 *
	 * A Spry Validation Select (a list box or an HTML form select).
	 *
	 * @see SpryValidationSelect.js
	 * 
	 * @param string $caption Ignore.
	 * @param string $spryName Name for a span, will be prefixed by frm, do not use arrays.
	 * @param string $frmName Name for the "name" and "id" input attributes. Can use arrays.
	 * @param array $valuesArr An array that will hold the value-label option pair.
	 * @param int $size "size" attribute.
	 * @param bool $multiple True to set "multiple" attribute.
	 * @param array $errMsg An array that displays error messages, ignore for this project.
	 * @param mixed $lastVal The last selected value.
	 * @param bool $bNewLine Ignore.
	 * @param int $tabIdx The tab index as would be used (order to follow) when pressing tab on the keyboard.
	 * @param bool $bReadOnly If the text is read only.
	 *
	 * @return void Does not return anything.
	 */ 
    public function spry_create_list_box($caption = NULL, $spryName, $frmName, $valuesArr, $size = 1, $multiple = false, $errMsg, $lastVal, $bNewLine = false, $tabIdx = "", $bReadOnly = false)
    {
		if(strpos("[", $frmName) !== false)
		{
			$id = str_replace("[", "_", $frmName);
			$id = str_replace("]", "", $id);
		}
		else
		{
			$id = $frmName;
		}
        echo "<span id='spry$spryName'>";
        if($multiple === false)
        {
            echo "<select name='frm$frmName' id='frm$id' size='$size' tabindex='" . $tabIdx . "' ";
			if($bReadOnly === true)
				echo " readonly='readonly' ";
			echo ">";
        }
        else
        {
            echo "<select name='frm" . $frmName . "[]' id='frm$id' class='frm$id' size='$size' multiple='multiple' tabindex='" . $tabIdx . "' ";
			if($bReadOnly === true)
				echo " readonly='readonly' ";
			echo ">";
        }
        
        if($multiple === false)
        {
			/*
            if(is_null($lastVal))
            {
                if(LANGUAGE === "us-en")
                {
                    echo "<option value='-1' selected='selected'>- Choose an alternative -</option>";                    
                }
                if(LANGUAGE === "pr-es")
                {
                    echo "<option value='-1' selected='selected'>- Escoje una alternativa -</option>";
                }
            }
            else
            {
                if(LANGUAGE === "us-en")
                    echo "<option value='-1'>- Choose an alternative -</option>";
                if(LANGUAGE === "pr-es")
                    echo "<option value='-1'>- Escoje una alternativa -</option>";
            }
			*/
			//NOTE: uber hack, for this project only, re-create this library to allow empty selects/list boxes
            if(is_null($lastVal))
            {
                echo "<option value='' selected='selected'>-</option>";
            }
            else
            {
				echo "<option value=''>-</option>";
            }
        }
        foreach($valuesArr as $i => $v)
        {
            if(is_array($lastVal)) //multiple_selects
            {
                if(in_array($i, $lastVal))
                    echo "<option value='$i' selected='selected'>$v</option>";
                else
                    echo "<option value='$i'>$v</option>";
            }
            else
            {
                if(isset($lastVal) && $lastVal == $i)
                    echo "<option value='$i' selected='selected'>$v</option>";
                else
                    echo "<option value='$i'>$v</option>";
            }
        }
        echo "</select>";
        if($bNewLine === true)
        {
            //echo "<br />";
        }
/*
        echo "<span class='selectInvalidMsg'>$errMsg</span>";
        echo "<span class='selectRequiredMsg'>$errMsg</span>";
*/
echo "<span class='selectInvalidMsg'>&nbsp;</span>";
echo "<span class='selectRequiredMsg'>&nbsp;</span>";
        echo "</span>";
        
        if(is_null($errMsg))
        {
			array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationSelect('spry$spryName', {isRequired:false, invalidValue:'-1', validateOn:['blur']});");
		}
		else
		{
			array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationSelect('spry$spryName', {isRequired:true, invalidValue:'-1', validateOn:['blur']});");
		}
    }
	
	//hack: this select/list box is identical to the original one except it will allow empty selections
	public function spry_create_list_box2($caption = NULL, $spryName, $frmName, $valuesArr, $size = 1, $multiple = false, $errMsg, $lastVal, $bNewLine = false, $tabIdx = "", $bReadOnly = false)
    {
		if(strpos("[", $frmName) !== false)
		{
			$id = str_replace("[", "_", $frmName);
			$id = str_replace("]", "", $id);
		}
		else
		{
			$id = $frmName;
		}
        echo "<span id='spry$spryName'>";
        if($multiple === false)
        {
            echo "<select name='frm$frmName' id='frm$id' size='$size' tabindex='" . $tabIdx . "' ";
			if($bReadOnly === true)
				echo " readonly='readonly' ";
			echo ">";
        }
        else
        {
            echo "<select name='frm" . $frmName . "[]' id='frm$id' size='$size' multiple='multiple' tabindex='" . $tabIdx . "' ";
			if($bReadOnly === true)
				echo " readonly='readonly' ";
			echo ">";
        }
        
        foreach($valuesArr as $i => $v)
        {
            if(is_array($lastVal)) //multiple_selects
            {
                if(in_array($i, $lastVal))
                    echo "<option value='$i' selected='selected'>$v</option>";
                else
                    echo "<option value='$i'>$v</option>";
            }
            else
            {
                if(isset($lastVal) && $lastVal == $i)
                    echo "<option value='$i' selected='selected'>$v</option>";
                else
                    echo "<option value='$i'>$v</option>";
            }
        }
        echo "</select>";
		echo "<span class='selectInvalidMsg'>&nbsp;</span>";
		echo "<span class='selectRequiredMsg'>&nbsp;</span>";
        echo "</span>";
        
        if(is_null($errMsg))
        {
			array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationSelect('spry$spryName', {isRequired:false, invalidValue:'-1', validateOn:['blur']});");
		}
		else
		{
			array_push($this->Spry_JS_Stack, "var $spryName = new Spry.Widget.ValidationSelect('spry$spryName', {isRequired:true, invalidValue:'-1', validateOn:['blur']});");
		}
    }
	
    //A private method that creates a single radio button, called from a loop in spry_generate_radio_buttons(...)
	private function spry_create_radio_btn($caption = NULL, $frmName, $value, $checked = "false")
	{
		if($checked == "true")
		{
			echo "<input type='radio' name='frm$frmName' value='$value' checked='checked' />$caption ";
		}
		else
		{
			echo "<input type='radio' name='frm$frmName' value='$value' />$caption ";
		}
	}
	
    //A private method that creates a checkbox
	public function spry_create_check_box($caption = NULL, $frmName, $value, $checked = false, $tabIdx = "")
	{
		if($checked == true)
		{
			echo "<input type='checkbox' name='frm$frmName' value='$value' checked='checked' style='vertical-align: middle;' tabindex='" . $tabIdx . "' />$caption ";
		}
		else
		{
			echo "<input type='checkbox' name='frm$frmName' value='$value' style='vertical-align: middle;' tabindex='" . $tabIdx . "' />$caption ";
		}
	}
	
	/**
	 * method: spry_get_js_stack()
	 * 
	 * This method is called in the footer area of the html view form as a JavaScript that installs
	 * the Spry controls.
	 */
	public function spry_get_js_stack()
	{
		foreach($this->Spry_JS_Stack as $v)
		{
			echo "\t" . $v . PHP_EOL;
		}
		$v = NULL;
	}
	
	/**
	 * method: jquery_get_js_stack()
	 * 
	 * This method is called in the footer area of the html view form as a JavaScript that installs
	 * the jQuery UI controls.
	 */
	public function jquery_get_js_stack()
	{
		foreach($this->jQuery_JS_Stack as $v)
		{
			echo "\t" . $v . PHP_EOL;
		}
		$v = NULL;
	}
        
        
        
        
        
        
        
        
        
        
        
        
        
    
    /**
     * spry_create_edit_boxX is exactly the same as spry_create_edit_box but will return a string instead of echoing and the size will be CSS style instead of HTML attribute
     */
    public function spry_create_edit_boxX($customType, $format = NULL, $caption = NULL, $spryName, $frmName, $value = NULL, $size = 40, $maxlength = 10, $errMsg, $hint = "", $bNewLine = false, $bIsPassword = false, $tabIdx = "", $bReadOnly = false)
    {
        $ret = "";
        if(is_null($customType))
        {
            $customType = "none";
        }
        if(strpos("[", $frmName) !== false)
        {
            $id = str_replace("[", "_", $frmName);
            $id = str_replace("]", "", $id);
        }
        else
        {
            $id = $frmName;
        }
        $ret .= "<span id='spry" . $spryName . "'>";
        $ret .= $caption . " ";
        if(!is_array($frmName))
        {
            if($bIsPassword === true)
                $ret .= "<input type='password'";
            else
                $ret .= "<input type='text'";
            $ret .= " name='frm" . $frmName . "' id='frm" . $id . "' value='" . htmlspecialchars($value, ENT_QUOTES) . "' style='size: {$size}px;' maxlength='" . $maxlength . "' tabindex='" . $tabIdx . "' ";
            if($bReadOnly === true)
                $ret .= " readonly='readonly' ";
            $ret .= " />";
        }
        else
        {
            if($bIsPassword === true)
                $ret .= "<input type='password'";
            else
                $ret .= "<input type='text'";
            $ret .= " name='frm" . $frmName . "' id='frm" . $id . "' value='" . htmlspecialchars($value, ENT_QUOTES) . "' style='size: {$size}px;' maxlength='" . $maxlength . "' tabindex='" . $tabIdx . "' ";
            if($bReadOnly === true)
                $ret .= " readonly='readonly' ";
            $ret .= " />";
        }
        
        $varSpryName = str_replace("-", "", $spryName); //elimiante (-) from JavaScript variable name, fix parser error.
        
        if(is_null($errMsg))
        {
            if($customType == "phone_number") //phone hack (force to ###-###-#### format instead of default phone_us (###) ###-####
            {
                array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationTextField('spry$spryName', 'phone_number', {isRequired:false, format:'phone_custom', pattern:'000-000-0000', validateOn:['blur'], useCharacterMasking:true});");
            }
            else
            {
                if(is_null($format))
                {
                    array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:false, hint:'$hint', validateOn:['blur']});");
                }
                else
                {
                    if(empty($format))
                        array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:false, hint:'$hint', useCharacterMasking:true, validateOn:['blur']});");
                    else
                        array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:false, hint:'$hint', format:'$format', useCharacterMasking:true, validateOn:['blur']});");
                }
            }
            
            //Display input errors (incorrect format):
            $incorrectFormat = "";
            if(LANGUAGE === "pr-es")
            {
                switch($customType)
                {
                    case "date":
                        $incorrectFormat = "fecha (" . $format . ")";
                    break;
                    case "integer":
                        $incorrectFormat = "numero entero";
                    break;
                    case "social_security_number":
                        $incorrectFormat = "SSN (###-##-####)";
                    break;
                    case "email":
                        $incorrectFormat = "usuario@dominio.xxx";
                    break;
                    case "phone_number":
                        $incorrectFormat = "###-###-####";
                    break;
                    case "credit_card":
                    {
                        if(empty($format) || $format == "all")
                        {
                            $incorrectFormat = "tarjeta de credito v&aacute;lida (12 - 19 digitos, 16 en la mayor&iacute;a de los casos)";
                        }
                        else if($format == "visa")
                        {
                            $incorrectFormat = "tarjeta VISA (13 - 16 digitos, comienza en 4)";
                        }
                        else if($format == "mastercard")
                        {
                            $incorrectFormat = "tarjeta MasterCard (16 digitos, comienza en 5), o Maestro (12 - 19 digitos, comienza en 5 o 6)";
                        }
                        else if($format == "amex")
                        {
                            $incorrectFormat = "tarjeta AMEX (15 digitos, comienza en 3)";
                        }
                        else //all others, i.e. discover, dinersclub
                        {
                            $incorrectFormat = "tarjeta de credito v&aacute;lida (12 - 19 digitos, 16 en la mayor&iacute;a de los casos)";
                        }
                    }
                    break;
                    default:
                        $incorrectFormat = "formato del campo";
                    break;
                }
                if($bNewLine === true)
                {
//                    echo "<br />";
                }
//                $ret .= "<span class='textfieldInvalidFormatMsg'>Error: formato incorrecto, debe ser: " . $incorrectFormat . ".</span>";
$ret .= "<span class='textfieldInvalidFormatMsg'>&nbsp;</span>";
            }
            if(LANGUAGE === "us-en")
            {
                switch($customType)
                {
                    case "date":
                        $incorrectFormat = "date (" . $format . ")";
                    break;
                    case "integer":
                        $incorrectFormat = "an integer number";
                    break;
                    case "social_security_number":
                        $incorrectFormat = "SSN (###-##-####)";
                    break;
                    case "email":
                        $incorrectFormat = "user@domain.xxx";
                    break;
                    case "phone_number":
                        //$incorrectFormat = "(###) ###-####";
                        $incorrectFormat = "###-###-####";
                    break;
                    case "credit_card":
                    {
                        if(empty($format) || $format == "all")
                        {
                            $incorrectFormat = "valid credit card (12 - 19 digits, usually 16)";
                        }
                        else if($format == "visa")
                        {
                            $incorrectFormat = "valid VISA (13 - 16 digits, starts with 4)";
                        }
                        else if($format == "mastercard")
                        {
                            $incorrectFormat = "valid MasterCard (16 digits, starts with 5), or Maestro (12 - 19 digits, starts with 5 or 6)";
                        }
                        else if($format == "amex")
                        {
                            $incorrectFormat = "valid AMEX (15 digits, starts with 3)";
                        }
                        else //all others, i.e. discover, dinersclub
                        {
                            $incorrectFormat = "valid credit card (12 - 19 digits, usually 16)";
                        }
                    }
                    break;
                    default:
                        $incorrectFormat = "field's format";
                    break;
                }
                if($bNewLine === true)
                {
//				    echo "<br />";
                }
//                $ret .= "<span class='textfieldInvalidFormatMsg'>Error: incorrect format, should be: " . $incorrectFormat . ".</span>";
$ret .= "<span class='textfieldInvalidFormatMsg'>&nbsp;</span>";
            }
        }
        else
        {
            if($customType == "phone_number") //phone hack (force to ###-###-#### format instead of default phone_us (###) ###-####
            {
                array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationTextField('spry$spryName', 'phone_number', {isRequired:true, format:'phone_custom', pattern:'000-000-0000', validateOn:['blur'], useCharacterMasking:true});");
            }
            else
            {
                if(is_null($format))
                {
                    array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:true, hint:'$hint', validateOn:['blur']});");
                }
                else
                {
                    if(empty($format))
                        array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:true, hint:'$hint', useCharacterMasking:true, validateOn:['blur']});");
                    else
                        array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationTextField('spry$spryName', '$customType', {isRequired:true, hint:'$hint', format:'$format', useCharacterMasking:true, validateOn:['blur']});");
                }
            }
            if($bNewLine === true)
            {
//                echo "<br />";
            }
//            $ret .= "<span class='textfieldRequiredMsg'>$errMsg[0]</span>";
//            $ret .= "<span class='textfieldInvalidFormatMsg'>$errMsg[1]</span>";
$ret .= "<span class='textfieldRequiredMsg'>&nbsp;</span>";
$ret .= "<span class='textfieldInvalidFormatMsg'>&nbsp;</span>";
$jQueryName = $spryName;
$ret .= "<span id='jquery" . $jQueryName . "'>&nbsp;</span>";
        }
        $ret .= "</span>";
        
        return $ret;
    }
        
    
    /**
     * spry_create_list_boxX is exactly the same as spry_create_list_box but will return a string instead of echoing and the width value will be the size/width in CSS.
     */
    public function spry_create_list_boxX($caption = NULL, $spryName, $frmName, $valuesArr, $size = 1, $multiple = false, $errMsg, $lastVal, $bNewLine = false, $tabIdx = "", $bReadOnly = false, $width = 50)
    {
//$xxx = print_r($valuesArr, true);
//return "arr: {$xxx} | lastVal: <pre>$lastVal</pre><br />";
        
        
        $ret = "";
        if(strpos("[", $frmName) !== false)
        {
            $id = str_replace("[", "_", $frmName);
            $id = str_replace("]", "", $id);
        }
        else
        {
            $id = $frmName;
        }
        $ret .= "<span id='spry$spryName'>";
        if($multiple === false)
        {
            $ret .= "<select name='frm$frmName' id='frm$id' size='$size' class='frm$id' style='size: {$width}px;' tabindex='" . $tabIdx . "' ";
            if($bReadOnly === true)
                $ret .= " readonly='readonly' ";
            $ret .= ">";
        }
        else
        {
            $ret .= "<select name='frm" . $frmName . "[]' id='frm$id' size='$size' class='frm$id' style='size: {$width}px;' multiple='multiple' tabindex='" . $tabIdx . "' ";
            if($bReadOnly === true)
                $ret .= " readonly='readonly' ";
            $ret .= ">";
        }
        
        if($multiple === false)
        {
            if(is_null($lastVal))
            {
                $ret .= "<option value='' selected='selected'>-</option>";
            }
            else
            {
                $ret .= "<option value=''>-</option>";
            }
        }
        foreach($valuesArr as $i => $v)
        {
            if(is_array($lastVal)) //multiple_selects
            {
                if(in_array($i, $lastVal))
                    $ret .= "<option value='$i' selected='selected'>$v</option>";
                else
                    $ret .= "<option value='$i'>$v</option>";
            }
            else
            {
                if(isset($lastVal) && $lastVal == $i)
                    $ret .= "<option value='$i' selected='selected'>$v</option>";
                else
                    $ret .= "<option value='$i'>$v</option>";
            }
        }
        $ret .= "</select>";
        if($bNewLine === true)
        {
            //echo "<br />";
        }
/*
        $ret .= "<span class='selectInvalidMsg'>$errMsg</span>";
        $ret .= "<span class='selectRequiredMsg'>$errMsg</span>";
*/
$ret .= "<span class='selectInvalidMsg'>&nbsp;</span>";
$ret .= "<span class='selectRequiredMsg'>&nbsp;</span>";
        $ret .= "</span>";
        
        $varSpryName = str_replace("-", "", $spryName); //elimiante (-) from JavaScript variable name, fix parser error.
        if(is_null($errMsg))
        {
            array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationSelect('spry$spryName', {isRequired:false, invalidValue:'-1', validateOn:['blur']});");
        }
        else
        {
            array_push($this->Spry_JS_Stack, "var $varSpryName = new Spry.Widget.ValidationSelect('spry$spryName', {isRequired:true, invalidValue:'-1', validateOn:['blur']});");
        }
        
        return $ret;
    }
        
        
        
        
}

?>