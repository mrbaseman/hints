<?php
/**
 *
 * @category        page
 * @package         Hints
 * @version         0.6.2
 * @authors         Martin Hecht (mrbaseman), Ruud Eisinga (Dev4me)
 * @copyright       (c) 2018 - 2021, Martin Hecht
 * @link            https://github.com/mrbaseman/hints
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
// get table to see what needs to be created
$table
    = $database->query(
        "SELECT *"
        . " FROM `".TABLE_PREFIX."mod_hints`"
    );
if($table==NULL) { exit("table not found - is the module installed correctly?"); }

$row = $table->fetchRow();


// If not already there, add new fields to the existing table
echo'<span class="good"><b>Adding new fields to the table</b></span><br />';

if (!isset($row['background'])){
    $query = "ALTER TABLE `".TABLE_PREFIX."mod_hints`"
        . " ADD `background` INT NOT NULL DEFAULT ".(int)hexdec("FFFFD2")." AFTER `content`";
    $database->query($query);
    if($database->is_error()) {
        echo $database->get_error().'<br />';
    } else {
        echo "Added new field `background` successfully<br />";
    }
}

// adding readgrps in version 0.5.0:
if (!isset($row['readgrps'])){
    $query = "ALTER TABLE `".TABLE_PREFIX."mod_hints`"
        . " ADD `readgrps`   TEXT NOT NULL AFTER `mode`";
    $database->query($query);
    if($database->is_error()) {
        echo $database->get_error().'<br />';
    } else {
        echo "Added new field `readgrps` successfully<br />";
    }
}

// adding writegrps in version 0.5.0:
if (!isset($row['writegrps'])){
    $query = "ALTER TABLE `".TABLE_PREFIX."mod_hints`"
        . " ADD `writegrps`   TEXT NOT NULL AFTER `readgrps`";
    $database->query($query);
    if($database->is_error()) {
        echo $database->get_error().'<br />';
    } else {
        echo "Added new field `writegrps` successfully<br />";
    }
}


// adding settings table in version 0.4.0:
// get table to see what needs to be created
$settingstable = TABLE_PREFIX."mod_hints_settings";
$result = $database->query(
        "SELECT *"
        . " FROM `".$settingstable."`"
    );

if($result==NULL) {

    $query  = "CREATE TABLE `".$settingstable."` (";
    $query .= "`id`           INT NOT NULL AUTO_INCREMENT,";
    $query .= "`section_id`   INT NOT NULL DEFAULT '0',";
    $query .= "`user_id`      INT NOT NULL DEFAULT '0',";
    $query .= "`display_mode` INT NOT NULL DEFAULT '0',";
    $query .= " PRIMARY KEY ( `id` ) )";

    $database->query($query);

    if($database->is_error()) {
        echo $database->get_error().'<br />';
    } else {
        echo "Created settings table successfully<br />";
    }

} // else ... in future versions we might have to add new columns
