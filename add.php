<?php
/**
 *
 * @category        page
 * @package         Hints
 * @version         0.4.0
 * @authors         Martin Hecht (mrbaseman)
 * @copyright       (c) 2018 - 2019, Martin Hecht
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



$user_id = $admin->get_user_id();

$sql = 'INSERT INTO `'.TABLE_PREFIX.'mod_hints` '
     . 'SET `page_id` = '.$page_id.', '
     .     '`section_id` = '.$section_id.', '
     .     '`owner` = '.(int)$user_id.', '
     .     '`content` = \'\', '
     .     '`background` = '.(int)hexdec("FFFFD2");
$database->query($sql);

// suppress section anchor
$sql = 'UPDATE `'.TABLE_PREFIX.'sections` '
     . 'SET `publ_end` = '.time().' '
     . 'WHERE `section_id` = '.$section_id;
$database->query($sql);


$default_settings = array( 
        "display_mode" => 1 
);

// Get default settings of current user from DB
$query = "SELECT *"
       . " FROM `".TABLE_PREFIX."mod_hints_settings`"
       . " WHERE `section_id` = '0'"
       . " AND `user_id` = '$user_id'";

$query_content = $database->query($query);

$user_defaults = $default_settings;
if($query_content && $query_content->numRows() > 0 ) {
   $user_defaults = $query_content->fetchRow();
}

$display_mode = $user_defaults["display_mode"];

$query = "INSERT INTO `".TABLE_PREFIX."mod_hints_settings`"
       . " SET `display_mode` = '$display_mode', "
       .     " `section_id` = '$section_id', "
       .     " `user_id` = '$user_id'";

$database->query($query);


