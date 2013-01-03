<?php

/**
 * [Model]
 * Filename: model_validations.php
 * 
 * Add server-side validation rules here.
 *
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.3.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

//Uber Hacks (for validations that depend on pcy or acc data (available only for 00), these globals will store the values to be used by claimants)
$policyNumber = ""; 
$accidentDate = "";

interface iValidation
{
    public function get_exceptions();
    public function validate();
    public function save(); //insert or update
    public function delete();
}

class CurrentProjectValidations implements iValidation
{
    /**
     * MEMBERS
     */
    //Class Specific
    public $dbObj;
    public $exObj;
    private $dataObj; //Data Container

    /**
     * METHODS
     */
    public function __construct($data, $type, $claimant_num)
    {
        /**
         * <b>For Debugging<b>
         * <i>Unit Test [Create SQL Connection with Debugging Output]</i>
         */
        if( defined("OMNIPHP_DEBUG") && defined("OMNIPHP_DEBUG_LEVEL") && (OMNIPHP_DEBUG_LEVEL === 2 || OMNIPHP_DEBUG_LEVEL === 1) )
        {
            $this->dbObj = new Framework_SQL(true, true, RDBMS_MYSQL);
        }
        else
        {
            $this->dbObj = new Framework_SQL(true, false, RDBMS_MYSQL);
            //equivalent to: $this->dbObj = new Framework_SQL();
        }
        $this->exObj = new Framework_Exception();
        
        //HACK (for Netbeans), to allow the IDE to recognize the class members and methods
        $this->dataObj = $data;
    }
    public function __destruct()
    {
        //
    }
    
    
    //====================
    //    VALIDATIONS
    //====================
    
    /**
     * method: get_exceptions()
     * 
     * Retrieves all errors found in validation (server-side).
     * 
     * @return array An array list with all server-side validation errors.
     */
    public function get_exceptions()
    {
        return $this->exObj->ex_get();
    }

    public function save()
    {
        $this->validate();
        $arrEx = $this->exObj->ex_get(); //alt: $this->get_exceptions();
        if(!empty($arrEx))
        {
            return false;
        }
        return true;
    }
    public function delete()
    {
        //
    }
    
    
    public function validate()
    {
        global $anyVals; //example
        
        $tsToday = strtotime(date("Y-m-d")); //example
        
        //basic validation
        if(trim($this->dataObj->input_name_here) == '') //change input_name_here for a column name, form input name
        {
            $this->exObj->ex_set("Error: Do not leave this field empty.");
        }
    }
}

?>
