<?php
/**
 *
 * @category        page
 * @package         Hints
 * @version         0.3.1
 * @authors         Martin Hecht (mrbaseman)
 * @copyright       (c) 2018 - 2018, Martin Hecht
 * @link            https://github.com/WebsiteBaker-modules/hints
 * @license         GNU General Public License v3 - The javascript features are third party software, spectrum color picker and autosize, both licensed under MIT license
 * @platform        2.8.x
 * @requirements    PHP 7.x
 *
 **/


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */



// adding fields new in version 0.2.0:
//get settings table to see what needs to be created
$settingstable
    = $database->query(
        "SELECT *"
        . " FROM `".TABLE_PREFIX."mod_hints`"
    );
if($settingstable==NULL) { exit("settings table not found - is the module installed correctly?"); }

$settings = $settingstable->fetchRow();


// If not already there, add new fields to the existing settings table
echo'<span class="good"><b>Adding new fields to the settings table</b></span><br />';

if (!isset($settings['background'])){
    $qs = "ALTER TABLE `".TABLE_PREFIX."mod_hints`"
        . " ADD `background` INT NOT NULL DEFAULT ".(int)hexdec("FFFFD2")." AFTER `content`";
    $database->query($qs);
    if($database->is_error()) {
        echo $database->get_error().'<br />';
    } else {
        echo "Added new field `background` successfully<br />";
    }
}
