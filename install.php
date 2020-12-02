<?php
/**
 *
 * @category        page
 * @package         Hints
 * @version         0.6.2
 * @authors         Martin Hecht (mrbaseman), Ruud Eisinga (Dev4me)
 * @copyright       (c) 2018 - 2020, Martin Hecht
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


$table = TABLE_PREFIX."mod_hints";


$query = "DROP TABLE IF EXISTS `".$table."`";
$database->query($query);


$query  = "CREATE TABLE `".$table."` (";
$query .= "`section_id` INT NOT NULL DEFAULT '0',";
$query .= "`page_id`    INT NOT NULL DEFAULT '0',";
$query .= "`owner`      INT NOT NULL DEFAULT '1',";
$query .= "`mode`       INT NOT NULL DEFAULT '0',";
$query .= "`readgrps`   TEXT NOT NULL,";
$query .= "`writegrps`  TEXT NOT NULL,";
$query .= "`content`    TEXT NOT NULL,";
$query .= "`background` INT NOT NULL DEFAULT ".(int)hexdec("FFFFD2").",";
$query .= " PRIMARY KEY ( `section_id` ) )";

$database->query($query);

$error=$database->get_error();

if($error) $admin->print_error($error, $js_back);


$settingstable = TABLE_PREFIX."mod_hints_settings";


$query = "DROP TABLE IF EXISTS `".$settingstable."`";
$database->query($query);


$query  = "CREATE TABLE `".$settingstable."` (";
$query .= "`id`           INT NOT NULL AUTO_INCREMENT,";
$query .= "`section_id`   INT NOT NULL DEFAULT '0',";
$query .= "`user_id`      INT NOT NULL DEFAULT '0',";
$query .= "`display_mode` INT NOT NULL DEFAULT '0',";
$query .= " PRIMARY KEY ( `id` ) )";

$database->query($query);

$error=$database->get_error();

if($error) $admin->print_error($error, $js_back);

