<?php
/**
 *
 * @category        page
 * @package         Hints
 * @version         0.6.1
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


if(!function_exists("intHex")) {
        function intHex($background) {
            return(substr("000000".dechex($background),-6));
        }
}

$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );
// now for spectrum color picker
$lang = strtolower( LANGUAGE );
if(!file_exists((dirname(__FILE__))."/js/i18n/jquery.spectrum-". $lang .".js")) $lang = "en";

// Setup template object
if(!class_exists('Template')){ require(WB_PATH.'/include/phplib/template.inc');}
$template = new Template(WB_PATH.'/modules/hints');
$template->set_file('page', 'htt/modify.htt');

// Get page content
$query = "SELECT *"
    . " FROM `".TABLE_PREFIX."mod_hints`"
    . " WHERE `section_id`= '".$section_id."'";

$get_content = $database->query($query);

$content = $get_content->fetchRow( MYSQL_ASSOC );
$owner = (int)$content['owner'];
$mode = (int)$content['mode'];
$readgrps =  explode(',',  $content['readgrps']);
$writegrps = explode(',', $content['writegrps']);
$background = (int)$content['background'];

$groups = $admin->get_groups_id();

$user_id = $admin->get_user_id();

$default_settings = array(
        "display_mode" => 1
);


$in_readgroup = FALSE;
foreach($groups as $cur_gid)
{
    if (in_array($cur_gid, $readgrps))
    {
        $in_readgroup = TRUE;
    }
}

$in_writegroup = FALSE;
foreach($groups as $cur_gid)
{
    if (in_array($cur_gid, $writegrps))
    {
        $in_writegroup = TRUE;
    }
}


$output_mode = ( $mode & 12 ) >> 2; // bits 2^2 and 2^3


if ( ( !($mode & 2)) || (in_array(1, $groups)) || $in_readgroup || ($owner == $user_id)) {

    if ( ( !($mode & 1)) && (!in_array(1, $groups)) && !$in_writegroup && ($owner != $user_id)) {

        $content = $content['content'];

        // default section view
        if ($output_mode == 0) {
                // hide section header for wb < 2.10 and wbce (for wb >= 2.10 see backend.js)
                echo '<style>#wb_'.$section_id.' { display: none } </style>'
                .  '<div class="hints_content_div hints_inner" id="hints'.$section_id.'" '
                . ' style="background:#'.intHex($background).'">'.nl2br($content).'</div>';
        }

        // float to the top (Ruuds method, see backend.js) - the same as above but a different css class
        if ($output_mode == 1) {
                // hide section header for wb < 2.10 and wbce (for wb >= 2.10 see backend.js)
                echo '<style>#wb_'.$section_id.' { display: none } </style>'
                . '<div class="hints_content_float hints_inner" id="hints'.$section_id.'" '
                . ' style="background:#'.intHex($background).'">'.nl2br($content).'</div>';
        }

        // popup a modal
        if ($output_mode == 2) {
                // clear the js cookie when we have a fresh php session (i.e. user has logged out and back in again)
                if (!isset($_SESSION["hints_'.$section_id.'_closed"])||$_SESSION["hints_'.$section_id.'_closed"]!=$_SESSION['session_started'])
                    echo '<script>document.cookie = "hints'.$section_id.'=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"</script>';
                $_SESSION["hints_'.$section_id.'_closed"]=$_SESSION['session_started'];
                echo '<input type="button" id="hints'.$section_id.'_button" value ="'.$MOD_HINTS["SHOW"].'" '
                . ' onclick = \'document.getElementById("hints'.$section_id.'").style.display = "block";\' />'
                . '<div class="hints_modal" id="hints'.$section_id.'">'
                . '<div class="hints_content_popup hints_inner" style="background:#'.intHex($background).'">'
                . '<span class="hints_close" '
                . ' onclick = \'document.getElementById("hints'.$section_id.'").style.display = "none";'
                . '             document.cookie = "hints'.$section_id.'=closed; path=/;" \' >'
                . '&times;</span>'
                . '<p>'.nl2br($content).'</p></div></div>' // when the user clicks anywhere outside the hint close it:
                . '<script>var hints_modal = document.getElementById("hints'.$section_id.'");'
                . ' window.onclick = function(event) {'
                . '   if (event.target == hints_modal) {'
                . '     hints_modal.style.display = "none";'
                . '   }'
                . ' };'   // hide the hint when the user has closed it already before
                . ' var c=document.cookie;'
                . '  if (c){'
                . '    if(c.indexOf("hints'.$section_id.'=closed") != -1){'
                . '       hints_modal.style.display = "none";'
                . '    }'
                . '  }'
                . '</script>';
        }

        // bottom modal - currently it's mostly the same, except for the css classes
        if ($output_mode == 3) {
                // clear the js cookie when we have a fresh php session (i.e. user has logged out and back in again)
                if (!isset($_SESSION["hints_'.$section_id.'_closed"])||$_SESSION["hints_'.$section_id.'_closed"]!=$_SESSION['session_started'])
                    echo '<script>document.cookie = "hints'.$section_id.'=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"</script>';
                $_SESSION["hints_'.$section_id.'_closed"]=$_SESSION['session_started'];
                echo '<input type="button" id="hints'.$section_id.'_button" value ="'.$MOD_HINTS["SHOW"].'" '
                . ' onclick = \'document.getElementById("hints'.$section_id.'").style.display = "block";\' />'
                . '<div class="hints_modal" id="hints'.$section_id.'">'
                . '<div class="hints_content_bottom hints_inner" style="background:#'.intHex($background).'">'
                . '<span class="hints_bottom_close" '
                . ' onclick = \'document.getElementById("hints'.$section_id.'").style.display = "none";'
                . '             document.cookie = "hints'.$section_id.'=closed; path=/;" \' >'
                . '&times;</span>'
                . '<p>'.nl2br($content).'</p></div></div>' // when the user clicks anywhere outside the hint close it:
                . '<script>var hints_modal = document.getElementById("hints'.$section_id.'");'
                . ' window.onclick = function(event) {'
                . '   if (event.target == hints_modal) {'
                . '     hints_modal.style.display = "none";'
                . '   }'
                . ' };'   // hide the hint when the user has closed it already before
                . ' var c=document.cookie;'
                . '  if (c){'
                . '    if(c.indexOf("hints'.$section_id.'=closed") != -1){'
                . '       hints_modal.style.display = "none";'
                . '    }'
                . '  }'
                . '</script>';
        }

    } else {

        $tan = $admin->getFTAN();

        $edit_content = htmlspecialchars($content['content']);

        // Get existing users from database
        $sql  = 'SELECT * FROM `'.TABLE_PREFIX.'users` ' ;
        $sql .= 'WHERE active = 1 ';
        $sql .= 'ORDER BY `display_name`,`username`';

        $results = $database->query($sql);
        if($database->is_error()) {
            $admin->print_error($database->get_error(), 'index.php');
        }

        $owner_options = "";
        if($results && $results->numRows() > 0) {
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
             $owner_disabled = "disabled";
        }

        // Get existing groups from database
        $query = "SELECT group_id,name FROM ".TABLE_PREFIX."groups";
        $results = $database->query($query);
        if($database->is_error()) {
            $admin->print_error($database->get_error(), 'index.php');
        }

        $write_group_options = "";
        $read_group_options = "";
        $grpidx = 0;
        if($results && $results->numRows() > 0) {
            while($grp = $results->fetchRow()) {
                $grpidx++;
                $listyle="";
                if($grp['group_id']==1) $listyle=' style="display:none" ';
                $write_group_options .= '<li'.$listyle.'><input id="'.$section_id.'_wgrp_'.$grpidx.'" '
                    . 'name="wgrp_'.$grp['group_id'].'" type="checkbox" value="checked" '
                    . 'onchange=\'javascript: document.getElementById("'.$section_id.'_wgrp_all").checked &= this.value\' ';
                if(in_array($grp['group_id'], $writegrps) || ($mode & 1)) $write_group_options .= 'checked ';
                $write_group_options .= $owner_disabled.'/><label for="'.$section_id.'_wgrp_'.$grpidx.'">'.$grp['name'].'</label></li>';

                $read_group_options .= '<li'.$listyle.'><input id="'.$section_id.'_rgrp_'.$grpidx.'" '
                    . 'name="rgrp_'.$grp['group_id'].'" type="checkbox" value="checked" '
                    . 'onchange=\'javascript: document.getElementById("'.$section_id.'_rgrp_all").checked &= this.value\' ';
                if(in_array($grp['group_id'], $readgrps) || (!($mode & 2))) $read_group_options .= 'checked ';
                $read_group_options .= $owner_disabled.'/><label for="'.$section_id.'_rgrp_'.$grpidx.'">'.$grp['name'].'</label></li>';

            }
        }


        $output_modes = array (
                0 => $MOD_HINTS["DEFAULT"],
                1 => $MOD_HINTS["FLOATING"],
                2 => $MOD_HINTS["POPUP"],
                3 => $MOD_HINTS["BOTTOM"]
        );

        $mode_options = "";
        foreach ($output_modes as $key => $value){
                $mode_options .= '<option ';
                if($key == $output_mode) $mode_options .= 'selected ';
                $mode_options .= 'value="'.$admin->getIDKEY($key).'">'.$value.'</option>';
        }

        $mode_disabled="";
        if ( (!in_array(1, $groups)) && ($owner != $user_id)) {
             $mode_disabled = "disabled";
        }


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

        if($user_settings["display_mode"] == 5) { // section default
           $user_settings = $owner_settings;
        }

        if($user_settings["display_mode"] == 6) { // user default
           $user_settings = $user_defaults;
        }

        // if still not resolved, use the module default

        if($user_settings["display_mode"] == 5) { // section default
           $user_settings = $default_settings;
        }

        if($user_settings["display_mode"] == 6) { // user default
           $user_settings = $default_settings;
        }

        $display_mode = $user_settings["display_mode"];


        $use_wysiwyg = 0;
        if($display_mode == 3 || $display_mode == 4) $use_wysiwyg = 1;

        $show_preview = 0;
        if($display_mode == 2 || $display_mode == 4) $show_preview = 1;


        if(($show_preview == 1) && isset($_GET['modify']) && (((int) $_GET['modify']) == $section_id)) $show_preview = 0;


        // Insert vars
        $template->set_var(array(
                'PAGE_ID'      => $page_id,
                'SECTION_ID'   => $section_id,
                'WB_URL'       => WB_URL,
                'ADMIN_URL'    => ADMIN_URL,
                'CONTENT'          => $edit_content,
                'BACKGROUND'   => '#'.intHex(strval($background)),
                'OWNER_OPTIONS' => $owner_options,
                'MODE_OPTIONS'  => $mode_options,
                'READ_GROUP_OPTIONS' => $read_group_options,
                'WRITE_GROUP_OPTIONS' => $write_group_options,
                'OWNER_DISABLED' => $owner_disabled,
                'MODE_DISABLED' => $mode_disabled,
                'TEXT_SAVE'    => $TEXT['SAVE'],
                'TEXT_CANCEL'  => $TEXT['CANCEL'],
                'TEXT_MODIFY'  => $TEXT['MODIFY'],
                'TEXT_PREFERENCES' => $MENU['PREFERENCES'],
                'SHARED'       => ( $mode & 1 ) ? "checked" : "",
//              'HIDDEN'       => ( $mode & 2 ) ? "checked" : "",
                'VISIBLE'      => ( $mode & 2 ) ? "" : "checked",
                'TEXT_SHARED'  => $MOD_HINTS["SHARED"],
                'TEXT_HIDDEN'  => $MOD_HINTS["HIDDEN"],
                'TEXT_VISIBLE' => $MOD_HINTS["VISIBLE"],
                'TEXT_OWNER'   => $MOD_HINTS["OWNER"],
                'TEXT_MODE'    => $MOD_HINTS["TEXT_MODE"],
                'TEXT_BACKGROUND' => $MOD_HINTS["BACKGROUND"],
                'TEXT_PREVIEW' => $MOD_HINTS["PREVIEW"],
                'TEXT_WITH_GROUPS' => $MOD_HINTS["WITH_GROUPS"],
                'TEXT_FOR_GROUPS' => $MOD_HINTS["FOR_GROUPS"],
                'TEXT_ALL_GROUPS' => $MOD_HINTS["ALL_GROUPS"],
                'PREVIEW_STYLE' => ( $use_wysiwyg == 1 ) ? "display:none;" : "",
                'LANGUAGE'     => LANGUAGE,
                'LANG'         => $lang,
                'FTAN'         => $tan
            )
        );

        // Parse template object
        $template->set_block('page', 'header_block', 'header');
        $template->parse('header', 'header_block', false, false);

        $template->set_block('page', 'preview_block', 'preview');
        $template->parse('preview', 'preview_block', false, false);

        $template->set_block('page', 'main_block', 'main');
        $template->parse('main', 'main_block', false, false);

        $template->set_block('page', 'footer_block', 'footer');
        $template->parse('footer', 'footer_block', false, false);

        if ($show_preview){
            echo  $template->get_var('preview');
            $content = $content['content'];
            echo '<div class="hints_content_preview hints_inner" style="background:#'.intHex($background).'">'.nl2br($content).'</div>';
        } else {
            echo $template->get_var('header');

            if ($use_wysiwyg){
                echo '<div class="hints_clear">';
                if(!function_exists("show_wysiwyg_editor")){
                    $wysiwyg_editor_loaded=true;
                    if (!\defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none"
                        OR !\file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
                            function show_wysiwyg_editor($name,$id,$content,$width,$height) {
                                echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '
                                    .$width.'; height: '.$height.';">'.$content.'</textarea>';
                            }
                    } else {
                        $id_list = [];
                        require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
                    }
                }
                show_wysiwyg_editor("content$section_id", "content$section_id", $edit_content);
                echo '</div>';
            } else echo  $template->get_var('main');

            echo $template->get_var('footer');
        }
        unset($tan);
    }
} else {
        // content of this section not visible for the current user
        echo '<div class="hints_content_div hints_inner" style="background:#'.intHex($background).'"></div>';
        // hide section header for wb < 2.10 and wbce (for wb >= 2.10 see backend.js)
        echo '<style>#wb_'.$section_id.' { display: none } </style>';
}

