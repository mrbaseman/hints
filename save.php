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

require('../../config.php');



/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */



$update_when_modified = true; // Tells script to update when this page was last updated
$admin_header = false; // suppress to print the header, so no new FTAN will be set
require(WB_PATH.'/modules/admin.php');

if (!$admin->checkFTAN()) {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
    $admin->print_footer();
    exit();
}

if ( isset($_POST['content']) ) {


    // Get current values
    $query = "SELECT `content`, `background`, `owner`, `mode`"
        . " FROM `".TABLE_PREFIX."mod_hints`"
        . " WHERE `section_id`= '".$section_id."'";

    $get_content = $database->query($query);

    $content = $get_content->fetchRow( MYSQL_ASSOC );
    $owner = (int)$content['owner'];
    $mode = (int)$content['mode'];
    $background = (int)$content['background'];

    $groups = $admin->get_groups_id();

    if ( in_array(1, $groups) || ($owner == $admin->get_user_id()) || ( $mode & 1 ) ) {

        // for authorized users obtain submitted values
        $tags       = array('<?php', '?>' , '<?');
        $content    = $admin->add_slashes(str_replace($tags, '', $_POST['content']));
        $mode       = intval(isset($_POST['shared']));
        $mode       += 2*intval(isset($_POST['hidden']));
        $background = intval(hexdec($_POST['background']));
        if (($mode < 0) || ($mode > 3)) $mode = 0;
        $fields = array(
            'content'    => $content,
        );

        // admins may change the owner
        if ( in_array(1, $groups)) {
            $owner = $admin->checkIDKEY('owner');
            $details = $admin->get_user_details($owner);
            if(is_array($details) && ($details['username'] != 'unknown')) {
                $fields['owner'] = $owner;
            }
        }

        // owner and admins may change the mode (i.e. unshare a hint)
        if ( in_array(1, $groups) || ($owner == $admin->get_user_id())) {
            $fields['mode'] = $mode;
            $fields['background'] = $background;
        }

        $query = "UPDATE `".TABLE_PREFIX."mod_hints` SET ";
        foreach($fields as $key=>$value) $query .= "`".$key."`=  '".$value."', ";
        $query = substr($query, 0, -2)." where `section_id`='".$section_id."'";

        $database->query($query);

        if ( true === $database->is_error() ) {
            $admin->print_header();
            $admin->print_error($database->get_error(), $js_back, true );
        } else {
            $admin->print_header();
            $admin->print_success($MESSAGE['PAGES_SAVED'],
                ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
        }
    } else {
       $admin->print_header();
       $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
    }
} else {
    $admin->print_header();
    $admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS'], WB_URL);
}

$admin->print_footer();
