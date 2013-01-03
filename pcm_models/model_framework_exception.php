<?php
/**
 * [MODEL]
 * Filename: model_framework_exception.php
 * 
 * Exception-handling library (for web form validation and error output).
 * Not an exception handler in the classic sense of try-catch-finally but
 * an object/array collection to store, retrieve, and handle errors.
 *
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.3.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

class Framework_Exception
{
	private $arrExceptions;
	
	public function __construct()
	{
		$this->ex_flush();
	}
	public function __destruct()
	{
		//
	}
	public function ex_set($str)
	{
		array_push($this->arrExceptions, $str);
	}
	public function ex_get()
	{
		return $this->arrExceptions;
	}
	private function ex_flush()
	{
		$this->arrExceptions = array();
	}
               
//fix me: add this in a more 'pretty' RIA/Web 2.0 layout instead, probably using gradient images, better text, etc.
//Can also verify cms's and others to see what log message boxes or others they use that are Web 2.0 compliant.
        /**
         * static method: ex_fatal_error(...)
         * @param string $str Output fatal error in a yellow box with bold red letters.
         */
        public static function ex_fatal_error($str)
        {
            echo "<div style='margin: 3px; border: 1px solid #F00; padding: 3px; font-family: arial, helvetica, sans-serif; font-weight: bold; font-size: 12pt; background-color: #FF0; color: #F00;'>" . PHP_EOL;
            echo "Fatal Error: " . $str . "<br />" . PHP_EOL;
            echo "</div>" . PHP_EOL;
        }
}

?>
