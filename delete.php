<?php
/**
 *
 * @category        page
 * @package         Hints
 * @version         0.5.1
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



// Get current values
$query = "SELECT `content`, `owner`, `mode`"
    . " FROM `".TABLE_PREFIX."mod_hints`"
    . " WHERE `section_id`= '".$section_id."'";

$get_content = $database->query($query);

$content = $get_content->fetchRow( MYSQL_ASSOC );
$owner = (int)$content['owner'];
$mode = (int)$content['mode'];

$groups = $admin->get_groups_id();

if ( in_array(1, $groups) || ($owner == $admin->get_user_id()) || ( $mode & 1 ) ) {

    $query =
        "DELETE FROM `".TABLE_PREFIX."mod_hints`"
           . " WHERE `section_id`= ".$section_id;
    if(!in_array(1, $admin->get_groups_id() ))
    $query .=  ' and `owner` = '.(int)$admin->get_user_id();

    $database->query($query);

    $query =
        "DELETE FROM `".TABLE_PREFIX."mod_hints_settings`"
           . " WHERE `section_id`= ".$section_id;
    if(!in_array(1, $admin->get_groups_id() ))
    $query .=  ' and `owner` = '.(int)$admin->get_user_id();

    $database->query($query);

} else {
    if (!headers_sent()) $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'],
                ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
    $admin->print_footer();
    // this is important so that the section table is not modified subsequently
    exit();
}

