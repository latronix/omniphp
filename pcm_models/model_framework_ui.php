<?php
/**
 * [MODEL]
 * Filename: model_framework_ui.php
 * 
 * Template / Layout UI, CSS/Javascript/jQuery/XML/XHTML/DOM/HTML5 based library to
 * output alerts, windows, widgets, dialogs, or others.
 *
 * @author Carmelo Vargas <cvargas@omniphp.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License 3
 * @version OmniPHP 0.3.0 (non-release)
 * @link http://www.omniphp.com/
 * @copyright 2010 - 2012 to OmniPHP.
 */

class Framework_UI
{
    /**
     * method: output(...)
     * 
     * Outputs given array of strings into chosen uiType (i.e. window, alert, dialog, colorbox, or others).
     * Particularly useful for displaying server-side error notifications.
     * 
     * @param array $uiTC UI Type and Class array(type, class); <b>TYPES: <i>javascript_alert, javascript_window, jquery_dialog, jquery_colorbox, self_page</i></b>
     * @param array $arrStr The array of strings to display (i.e. errors, warnings, notifications, others)
     */
    static public function output($uiTC, $arrStr)
    {
        if($uiTC[0] == "javascript_alert") //Common JavaScript alert function
        {
            $jsStr = "<script type='text/javascript'>alert('Errors:\\n\\n";
            foreach($arrStr as $error)
            {
                $jsStr .= "* " . $error . "\\n";
            }
            $jsStr .= "');</script>" . PHP_EOL;
            echo $jsStr;
        }
        else if($uiTC[0] == "javascript_window") //DOM Window
        {
            //
            echo "error: javascript_window not implemented yet";
        }
        else if($uiTC[0] == "jquery_dialog") //jQuery UI Dialog (Modal)
        {
            $html = "<ul class=\"warning-list\">";
            foreach($arrStr as $error)
            {
                $html .= "\t<li>" . $error . "</li>";
            }
            $html .= "</ul>";
            
            echo "<script type='text/javascript'>";
            echo "$(function(){";
            echo "\$('div." . $uiTC[1] . "').html('" . $html . "');";
            echo "\$('div." . $uiTC[1] . "').dialog('open');";
            echo "});";
            echo "</script>";
        }
        else if($uiTC[0] == "jquery_colorbox") //jQuery UI Dialog (Modal)
        {
            //
            echo "error: jquery_colorbox not implemented yet";
        }
        else if($uiTC[0] == "self_page")
        {
            echo "<div style='border: 1px solid #F00; background-color: #FF0; color: #F00; padding: 5px; margin: 5px; font-family: arial, helvetica, sans-serif; font-size: 10pt; font-weight: bold;'>" . PHP_EOL;
            echo "<ul style='margin-left: 12px;'>" . PHP_EOL;
            foreach($arrStr as $error)
            {
                echo "<li>" . $error . "</li>" . PHP_EOL;
            }
            echo "</ul>" . PHP_EOL;
            echo "</div>" . PHP_EOL;
        }
        else
        {
            echo "Incorrect output format.";
        }
    }
}

?>
