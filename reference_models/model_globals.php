<?php
/**
 * [MODEL]
 * Filename: model_globals.php
 * 
 * Contains all global variables, macros, and configuration
 * files.
 * 
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.3.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

/**
 * DEBUGGING AND PRODUCTION SETTINGS
 */
if($_SERVER['SERVER_NAME'] == "localhost") //Debugging / Testing
{
    define("OMNIPHP_DEBUG", true);
    /**
     * Measures the level for the debugger / unit testing from lowest to highest
     * 0 = basically no debugging, except for very specific tests [Almost Production Level or Production Level]
     * 1 = error level debugging (only display fatal errors) [After Initial Coding Phase, during Alpha Release]
     * 2 = debug, unit test, and output about everything. [Initial Coding Phase]
     * (usually default to 1)
     */
    define("OMNIPHP_DEBUG_LEVEL", 2); //0, 1, 2, 3 (measures level of debugger from lowest to highest)
}
else if($_SERVER['SERVER_NAME'] == "0.0.0.x (production server IP)" || $_SERVER['SERVER_NAME'] == "productionserverdomain.com") //Production Release
{
    //
}
else
{
    //Although hypothetically there shouldn't be any call to this script from any external source, if it were to happen redirect to an invalid notification page.
    header("location: http://productionserver.com/invalid.php");
    exit(1);
}

if(defined("OMNIPHP_DEBUG"))
{
    ini_set("display_errors", "On");
}
else
{
    ini_set("display_errors", "Off");
}


/**
 * LANGUAGE AND TIMEZONE
 */
//define("LANGUAGE", "pr-es"); //Puerto Rico's Spanish
define("LANGUAGE", "us-en"); //U.S. English

//CHOOSE THE TIMEZONE:
if(LANGUAGE === "pr-es")
{
    date_default_timezone_set("America/Puerto_Rico"); //Set Timezone to AST (Puerto Rico's Time Zone)
}
if(LANGUAGE === "us-en")
{
    date_default_timezone_set("US/Eastern");
    //date_default_timezone_set("America/Puerto_Rico"); //Set Timezone to AST (Puerto Rico's Time Zone)

    //Choose the appropriate timezone (for USA or your country)
    //See IX. Appendix, Appendix J. List of Supported Timezones in the PHP Documentation
    //date_default_timezone_set("US/Eastern");
    //date_default_timezone_set("US/Central");
    //date_default_timezone_set("US/Pacific");
    //date_default_timezone_set("US/Mountain");
}


/**
 * GLOBALS
 */
define("OMNIPHP_VERSION", "0.3.0"); //current OmniPHP codebase (for extendability and legacy/backwards-compatibility)
define("PROJECT_TITLE", "OmniPHP - Demo Title"); //for title tag
define("PROJECT_NAME", "OmniPHP - Demo Name"); //for header/title in the body tag
define("CAMPAIGN_NAME", "omniphp"); //for exporting, filenames, config files, and others
define("OMNIPHP_PAGINATION_LIMIT", 25); //pagination limit

/**
 * SESSION
 *
 * Security Note:
 * Set these values to your own, to prevent session
 * hijacking or fixation.
 */
define("OMNIPHP_SESSION_NAME", "OmniPHP Session Name"); //how the cookie will associate the session ID.
define("OMNIPHP_SESSION_SECRET_KEY", "OmniPHP Secret Key"); //some value that will be hashed along with the IP and current date and time to obtain a unique session ID.
define("OMNIPHP_LOGOFF_TIME", 600); //define time in minutes to use as automatic logoff


/**
 * COPYRIGHT NOTICE
 *
 * (Generic, change at will)
 */
$OMNIPHP_COPYRIGHT =<<<COPYRIGHT
&copy; 2010 - 2012 to <a href='http://www.omniphp.com/'>OmniPHP</a>.
COPYRIGHT;


/**
 * DATABASE ENGINE
 *
 * MySQL and SQL Server (for this case, it will only be MySQL)
 */
if(defined("OMNIPHP_DEBUG")) //debugging server
{
    $MySqlHost = "localhost";
    $MySqlUser = "root";
    $MySqlPass = "123456";
    $MySqlDB = "omniphp_test";
}
else //production server
{
    $MySqlHost = "localhost";
    $MySqlUser = "omniphp_user";
    $MySqlPass = "123456";
    $MySqlDB = "omniphp_production";
}

/**
 * Allowed Roles
 * 
 * Permission control (sample, set to your own values)
 */
//[Super Admin]
$arrAllowedRolesAdmin = array('Root', 'Administrator');
//[Accounting]
$arrAllowedRolesAccounting = array_merge($arrAllowedRolesAdmin, array('Accounting'));
//[Supervisor]
$arrAllowedRolesSuper = array_merge($arrAllowedRolesAccounting, array('Supervisor'));
//[Normal Users and Data Entries]
$arrAllowedRolesEdit = array_merge($arrAllowedRolesSuper, array('Normal User', 'Data Entry'));
//[Viewers and Guest Users]
$arrAllowedRolesPoliciesReport = array_merge($arrAllowedRolesEdit, array('Viewer', 'Guest'));

/**
 * class for benchmarking
 */
class Benchmark
{
    public $time_start, $time_end;
    /**
     * method: start()
     * 
     * Start benchmark, calculates the initial time (start time) of script.
     * 
     * @return unsigned int The timestamp
     */
    public function start()
    {
        $this->time_start = microtime(true);
        return $this->time_start;
    }
    /**
     * method: end()
     * 
     * End benchmark, calculatges the finish time (end time) of script.
     * 
     * @return unsigned int The timestamp
     */
    public function end()
    {
        $this->time_end = microtime(true);
        return $this->time_end;
    }
    /**
     * method: result(...)
     * 
     * Calculates the result of the benchmark and either retrieves it as transgressed seconds or
     * optionally outputs it to the screen in HTML5 CSS div format.
     * 
     * @param bool $bOutput true = output to the screen, false = return integer value in seconds passed
     * @return int The elapsed time. 
     */
    public function result($bOutput = false)
    {
        $result = ($this->time_end - $this->time_start);
        if($bOutput)
        {
            echo "<hr />";
            echo "<div style='font-size: 8pt; font-weight: bold; font-family: arial, sans-serif; text-align: center;'>Runtime: " . $result . " s</div>";
            return NULL;
        }
        return $result;
    }
}

/**
 * function: omniphp_debug_array(...)
 * 
 * Basically print_r() prettified for HTML output.
 * 
 * @param array $arr The array to output.
 * @param string $title The title/name of the array.
 */
function omniphp_debug_array($arr, $title = "[ARRAY CONTENT]")
{
    echo "<br />";
    echo $title . "<br />";
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    echo "<br /><br />";
}

/**
 * function: omniphp_create_session(...)
 * 
 * Function that creates a session with the established parameters, hashes a sha1 combination
 * to prevent session hijacking or fixation.
 * 
 * NOTE PRE-REQUISITES: You need to define the OMNIPHP_SESSION_NAME and 
 * OMNIPHP_SESSION_SECRET_KEY in the model_globals.php on the "models" folder.
 * 
 * @param bool $bLoginPage 
 * <p>Set it "true" for login page (where Session ID will be defined).</p>
 * <p>Set it "false" for the rest of the pages that use a session.</p>
 * 
 * @return string 
 * if $bLoginPage is true then it returns a sha1 hashed string containing the generated 
 * session ID for insertion into users_log table otherwise it will return NULL.
 */
function omniphp_create_session($bLoginPage = false)
{
    //NOTE: Do not use the following cache limiter and expire as they corrupt the browser as they are.
    //session_cache_limiter("private_no_expire");
    //session_cache_expire(600);
	
    //Instead use this:
    ini_set("session.gc_maxlifetime", "36000"); //36K secs = 10 hours

    if($bLoginPage === true)
    {
        $hashIP = md5($_SERVER['REMOTE_ADDR']);
        $hashSecretKey = md5(OMNIPHP_SESSION_SECRET_KEY);
        $hashDateTime = md5(date("r")); //remember to set 'date_default_timezone_set(...)' on model_globals.php to your timezone
        $hash = sha1($hashIP . $hashSecretKey . $hashDateTime); //will hold a hash to be used as the session ID.
        session_id($hash);
    }
    session_name(OMNIPHP_SESSION_NAME);
    session_start();
    return (isset($hash)) ? $hash : NULL;
}

/**
 * function: omniphp_destroy_session(...)
 *
 * Function that destroys the created session correctly.
 *
 * @param bool $bLoginPage True to redirect to login page, false to stay in current page.
 *
 * @return Nothing. It just destroys session.
 */
function omniphp_destroy_session($bRedirectLogin = false, $msg = "LOGIN")
{
    //Destroy Session:
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) 
    {
        setcookie(session_name(), '', time()-42000, '/');
    }
    if (isset($_COOKIE[session_name(OMNIPHP_SESSION_NAME)])) 
    {
        setcookie(session_name(OMNIPHP_SESSION_NAME), '', time()-42000, '/');
    }
    session_destroy();
	
    if($bRedirectLogin === true)
    {   
        if( (basename($_SERVER['PHP_SELF']) == "view_home.php") || (basename($_SERVER['PHP_SELF']) == "logoff.php") )
        {
            header("Location: login.php?msg=" . $msg);
        }
        else
        {
            echo "<script type='text/javascript'>parent.$.fn.colorbox.close();</script>";
        }
        exit(1);
    }  
}

/**
 * function: omniphp_is_authorized()
 *
 * Function that validates if the user is authorized (was successfully authenticated) or not.
 * NOTE: Authorization permissions and correspondent rules can be defined here and in other
 * helper functions.
 *
 * @return bool True if user is authorized, false if not.
 */
function omniphp_is_authorized()
{
    return (isset($_SESSION['omniphp_bLogged']) && $_SESSION['omniphp_bLogged'] === true) ? true : false;
}

/**
 * function: omniphp_session_expiration()
 *
 * Function that verfies session expiration, if the session expired then it will log off
 * the user.
 *
 * @return Nothing. It just expires the session.
 */
function omniphp_session_expiration()
{
    $currTime = microtime(true);
    $elapsedMins = (int)(($currTime - $_SESSION['omniphp_MicroTimeStamp']) / 60.0);
    if($elapsedMins > OMNIPHP_LOGOFF_TIME)
    {
        omniphp_destroy_session();
        if(basename($_SERVER['PHP_SELF']) == "view_home.php")
        {
            header("Location: login.php?msg=EXPIRATION");
        }
        else
        {
            echo "<script type='text/javascript'>parent.$.fn.colorbox.close();</script>";
        }
        exit(1);
    }
}

?>
