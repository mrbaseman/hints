<?php
/**
 *
 * @category        page
 * @package         Hints
 * @version         0.6.0
 * @authors         Martin Hecht (mrbaseman), Ruud Eisinga (Dev4me)
 * @copyright       (c) 2018 - 2019, Martin Hecht
 * @link            https://github.com/WebsiteBaker-modules/hints
 * @license         GNU General Public License v3 - The javascript features are third party software, spectrum color picker and autosize, both licensed under MIT license
 * @platform        2.8.x
 * @requirements    PHP 7.x
 *
 **/

require('../../config.php');



/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */




// unset page/section IDs defined via GET before including the admin file (we expect POST here)

unset($_GET['page_id']);
unset($_GET['section_id']);

$update_when_modified = false; // Tells script to update when this page was last updated
$admin_header = false; // suppress to print the header, so no new FTAN will be set
// show the info banner
require(WB_PATH.'/modules/admin.php');

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
    $admin->print_footer();
    exit();
}

$print_info_banner = true;
$admin_header = true;
require(WB_PATH.'/modules/admin.php');
$tan = $admin->getFTAN();


// include core functions
include_once(WB_PATH .'/framework/module.functions.php');

// obtain module directory
$mod_dir = basename(dirname(__FILE__));

$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

$user_id = $admin->get_user_id();

$default_settings = array(
        "display_mode" => 1
);

// Get current section owner
$query = "SELECT `owner`"
    . " FROM `".TABLE_PREFIX."mod_hints`"
    . " WHERE `section_id`= '".$section_id."'";

$get_content = $database->query($query);

$content = $get_content->fetchRow( MYSQL_ASSOC );
$owner = (int)$content['owner'];

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

// Get default settings of owner from DB
$query = "SELECT *"
       . " FROM `".TABLE_PREFIX."mod_hints_settings`"
       . " WHERE `section_id` = '0'"
       . " AND `user_id` = '$owner'";

$query_content = $database->query($query);

$owner_defaults = $default_settings;
if($query_content && $query_content->numRows() > 0 ) {
   $owner_defaults = $query_content->fetchRow();
}

// Get owner settings from DB
$query = "SELECT *"
       . " FROM `".TABLE_PREFIX."mod_hints_settings`"
       . " WHERE `section_id` = '$section_id'"
       . " AND `user_id` = '$owner'";

$query_content = $database->query($query);

$user_settings = $user_defaults;
$owner_settings = $owner_defaults;

if($query_content && $query_content->numRows() > 0 ) {
   $owner_settings = $query_content->fetchRow();
}

if($owner_settings["display_mode"] == 5) { // section default
   // if the owner sets this it means to impose owner defaults to the user
   $owner_settings = $owner_defaults;
   $user_settings = $owner_defaults;
}

if($owner_settings["display_mode"] == 6) { // user default
   $owner_settings = $user_defaults;
}

// Get user settings from DB
$query = "SELECT *"
       . " FROM `".TABLE_PREFIX."mod_hints_settings`"
       . " WHERE `section_id` = '$section_id'"
       . " AND `user_id` = '$user_id'";

$query_content = $database->query($query);

if($query_content && $query_content->numRows() > 0 ) {
   $user_settings = $query_content->fetchRow();
}

// see modify.php for the exact mechanism

$display_mode = $user_settings["display_mode"];
$default_display_mode = $user_defaults["display_mode"];


// include template parser class and set template
if (!class_exists('Template') && file_exists(WB_PATH . '/include/phplib/template.inc'))
    require_once(WB_PATH . '/include/phplib/template.inc');
$tpl = new Template(dirname(__FILE__) . '/htt/');


$tpl->set_file('page', 'modify_settings.htt');
$tpl->set_block('page', 'main_block', 'main');


// Insert vars
$tpl->set_var(array(
        'FTAN'                        => $tan,
        'MOD_SAVE_URL'                => WB_URL
                                       . str_replace("\\","/",substr(dirname(__FILE__),strlen(WB_PATH)))
                                       . '/save_settings.php',
        'MOD_CANCEL_URL'              => ADMIN_URL.'/pages/modify.php?page_id='.$page_id,
        'PAGE_ID'                     => (int) $page_id,
        'SECTION_ID'                  => (int) $section_id,
        'TEXT_CANCEL'                 => $TEXT['CANCEL'],
        'TEXT_SAVE'                   => $TEXT['SAVE'],
        'TEXT_DEFAULT_DISPLAY_MODE'   => $MOD_HINTS['TEXT_DEFAULT_DISPLAY_MODE'],
        'TEXT_DISPLAY_MODE'           => $MOD_HINTS['TEXT_DISPLAY_MODE'],
        'TEXT_GLOBAL_PREFERENCES'     => $MOD_HINTS['TEXT_GLOBAL_PREFERENCES'],
        'TEXT_SECTION_PREFERENCES'    => $MOD_HINTS['TEXT_SECTION_PREFERENCES'],
        'TEXT_USE_TEXTAREA'           => $MOD_HINTS['TEXT_USE_TEXTAREA'],
        'TEXT_USE_PREVIEW'            => $MOD_HINTS['TEXT_USE_PREVIEW'],
        'TEXT_USE_WYSIWYG'            => $MOD_HINTS['TEXT_USE_WYSIWYG'],
        'TEXT_USE_PREVIEW_WYSIWYG'    => $MOD_HINTS['TEXT_USE_PREVIEW_WYSIWYG'],
        'TEXT_USE_SECTION_DEFAULT'    => $MOD_HINTS['TEXT_USE_SECTION_DEFAULT'],
        'TEXT_USE_USER_DEFAULT'       => $MOD_HINTS['TEXT_USE_USER_DEFAULT'],
        'default_use_textarea'        => $default_display_mode == 1 ? "checked" : "",
        'default_use_preview'         => $default_display_mode == 2 ? "checked" : "",
        'default_use_wysiwyg'         => $default_display_mode == 3 ? "checked" : "",
        'default_use_preview_wysiwyg' => $default_display_mode == 4 ? "checked" : "",
        'default_use_section_default' => $default_display_mode == 5 ? "checked" : "",
        'default_use_user_default'    => $default_display_mode == 6 ? "checked" : "",
        'use_textarea'                => $display_mode == 1 ? "checked" : "",
        'use_preview'                 => $display_mode == 2 ? "checked" : "",
        'use_wysiwyg'                 => $display_mode == 3 ? "checked" : "",
        'use_preview_wysiwyg'         => $display_mode == 4 ? "checked" : "",
        'use_section_default'         => $display_mode == 5 ? "checked" : "",
        'use_user_default'            => $display_mode == 6 ? "checked" : "",
    )
);




// Parse template objects output
$tpl->parse('main', 'main_block', false);
$tpl->pparse('output', 'page',false, false);

$admin->print_footer();

