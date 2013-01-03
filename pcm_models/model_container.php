<?php

/**
 * [MODEL]
 * Filename: model_container.php
 * 
 * Entity for all database columns, to be used for validations
 * 
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.3.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

class CurrentContainer
{
    /**
     * MEMBERS
     */
    //PK
    public $pk_id;

    //FK
    public $fk_id;
    
    //Other
    public $first_name;
    

    /**
     * METHODS
     */
    public function __construct()
    {
        //
    }
    public function __destruct()
    {
        //
    }

    /**
     * Getter / Setter (through magic methods)
     */
    public function __get($property)
    {
        if(!property_exists($this, $property))
        {
            throw new InvalidArgumentException("Error: Failed to retrieve property ({$property})", 1);
        }
        else
        {
            return $this->$property;
        }
    }
    public function __set($property, $value)
    {
        if(property_exists($this, $property))
        {
            throw new InvalidArgumentException("Error: Failed to retrieve property ({$property}) with value ({$value})", 2);
        }
        else
        {
            $this->$property = $value;
        }
    }
}

?>
