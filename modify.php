<?php
/**
 *
 * @category        page
 * @package         Hints
 * @version         0.3.3
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



$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );
// now for spectrum color picker
$lang = strtolower( LANGUAGE );
if(!file_exists((dirname(__FILE__))."/js/i18n/jquery.spectrum-". $lang .".js")) $lang = "en";

// Setup template object
if(!class_exists('Template')){ require(WB_PATH.'/include/phplib/template.inc');}
$template = new Template(WB_PATH.'/modules/hints');
$template->set_file('page', 'htt/modify.htt');
$template->set_block('page', 'main_block', 'main');

// Get page content
$query = "SELECT `content`, `owner`, `mode`, `background`"
    . " FROM `".TABLE_PREFIX."mod_hints`"
    . " WHERE `section_id`= '".$section_id."'";

$get_content = $database->query($query);

$content = $get_content->fetchRow( MYSQL_ASSOC );
$owner = (int)$content['owner'];
$mode = (int)$content['mode'];
$background = (int)$content['background'];

$groups = $admin->get_groups_id();

if ( ( !($mode & 2)) || (in_array(1, $groups)) || ($owner == $admin->get_user_id())) {

    if ( ( !($mode & 1)) && (!in_array(1, $groups)) && ($owner != $admin->get_user_id())) {
        $content = $content['content'];
        echo '<div class="hints_content_div" style="background:#'.dechex($background).'">'.nl2br($content).'</div>';
    } else {

        $tan = $admin->getFTAN();

        $content = htmlspecialchars($content['content']);

    // Get existing value from database
    $sql  = 'SELECT * FROM `'.TABLE_PREFIX.'users` ' ;
    $sql .= 'WHERE active = 1 ';
    $sql .= 'ORDER BY `display_name`,`username`';
    //echo $sql;

    $results = $database->query($sql);
    if($database->is_error()) {
        $admin->print_error($database->get_error(), 'index.php');
    }

    $sUserList  = $TEXT['LIST_OPTIONS'].' '.$MENU['USERS'].' '.strtolower($TEXT['ACTIVE']);
    $owner_options = "";
    if($results->numRows() > 0) {
        while($user = $results->fetchRow()) {
            $owner_options .= '<option ';
            if($user['user_id'] == $owner) $owner_options .= 'selected ';
            $owner_options .= 'value="'.$admin->getIDKEY($user['user_id']).'">'
                .($user['display_name']!=''?$user['display_name']:$user['username']).'</option>';
        }
    } else {
            $owner_options = '<option value="">'
                .$TEXT['NONE_FOUND'].'</option>';
    }

    $groups = $admin->get_groups_id();
    $owner_disabled="";
    if ( !in_array(1, $groups) ) {
         $owner_disabled = "disabled=1";
    }

        // Insert vars
        $template->set_var(array(
                'PAGE_ID'      => $page_id,
                'SECTION_ID'   => $section_id,
                'WB_URL'       => WB_URL,
                'CONTENT'          => $content,
                'BACKGROUND'   => '#'.dechex(strval($background)),
                'OWNER_OPTIONS' => $owner_options,
                'OWNER_DISABLED' => $owner_disabled,
                'TEXT_SAVE'    => $TEXT['SAVE'],
                'TEXT_CANCEL'  => $TEXT['CANCEL'],
                'SHARED'       => ( $mode & 1 ) ? "checked" : "",
                'HIDDEN'       => ( $mode & 2 ) ? "checked" : "",
                'TEXT_SHARED'  => $MOD_HINTS["SHARED"],
                'TEXT_HIDDEN'  => $MOD_HINTS["HIDDEN"],
                'TEXT_OWNER'   => $MOD_HINTS["OWNER"],
                'TEXT_BACKGROUND' => $MOD_HINTS["BACKGROUND"],
                'LANGUAGE'     => LANGUAGE,
                'LANG'         => $lang,
                'FTAN'         => $tan
            )
        );

        // Parse template object
        $template->parse('main', 'main_block', false);
        $template->pparse('output', 'page');

        unset($tan);
    }
} else echo "(".$MOD_HINTS["HIDDEN"].")";
