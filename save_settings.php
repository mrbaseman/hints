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
/* This file saves the settings made in the main form of the module in the backend. */

// include global configuration file
require('../../config.php');

// obtain module directory
$mod_dir = basename(dirname(__FILE__));

// unset page/section IDs defined via GET before including the admin file (we expect POST here)
unset($_GET['page_id']);
unset($_GET['section_id']);


$update_when_modified = false; // Tells script to update when this page was last updated
$admin_header = false; // suppress to print the header, so no new FTAN will be set
require(WB_PATH.'/modules/admin.php');

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
    $admin->print_footer();
    exit();
}

$admin->print_header();

$user_id = $admin->get_user_id();

// include core functions
include_once(WB_PATH .'/framework/module.functions.php');

// obtain module directory
$mod_dir = basename(dirname(__FILE__));

$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

$display_mode = (int)$_POST["display_mode"];
$default_display_mode = (int)$_POST["default_display_mode"];

// Get current section owner
$query = "SELECT `owner`"
    . " FROM `".TABLE_PREFIX."mod_hints`"
    . " WHERE `section_id`= '".$section_id."'";

$get_content = $database->query($query);

$content = $get_content->fetchRow();
$owner = (int)$content['owner'];


// check if the current user has already settings for this section
$query = "SELECT *"
       . " FROM `".TABLE_PREFIX."mod_hints_settings`"
       . " WHERE `section_id` = '$section_id'"
       . " AND `user_id` = '$user_id'";

$query_content = $database->query($query);

if($query_content && $query_content->numRows() > 0 ) {
    $query = "UPDATE `".TABLE_PREFIX."mod_hints_settings`"
           . " SET `display_mode` = '$display_mode'"
           . " WHERE `section_id` = '$section_id'"
           . " AND `user_id` = '$user_id'";
} else {
    $query = "INSERT INTO `".TABLE_PREFIX."mod_hints_settings`"
           . " SET `display_mode` = '$display_mode', "
           .     " `section_id` = '$section_id', "
           .     " `user_id` = '$user_id'";
}

$database->query($query);



// check if there is a db error, otherwise say successful
if ($database->is_error()) {
    $admin->print_error($database->get_error(),
        ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
    // print admin footer
    $admin->print_footer();
    exit();
}

// check if the current user has already default settings
$query = "SELECT *"
       . " FROM `".TABLE_PREFIX."mod_hints_settings`"
       . " WHERE `section_id` = '0'"
       . " AND `user_id` = '$user_id'";

$query_content = $database->query($query);

if($query_content && $query_content->numRows() > 0 ) {
    $query = "UPDATE `".TABLE_PREFIX."mod_hints_settings`"
           . " SET `display_mode` = '$default_display_mode'"
           . " WHERE `section_id` = '0'"
           . " AND `user_id` = '$user_id'";
} else {
    $query = "INSERT INTO `".TABLE_PREFIX."mod_hints_settings`"
           . " SET `display_mode` = '$default_display_mode', "
           .     " `section_id` = '0', "
           .     " `user_id` = '$user_id'";
}

$database->query($query);


// check if there is a db error, otherwise say successful
if ($database->is_error()) {
    $admin->print_error($database->get_error(),
        ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
} else {
    $admin->print_success($TEXT['SUCCESS'],
        ADMIN_URL . '/pages/modify.php?page_id=' . $page_id);
}

// print admin footer
$admin->print_footer();
