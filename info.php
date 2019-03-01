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


/* -------------------------------------------------------- */
// Must include code to stop this file being accessed directly
if(!defined('WB_PATH')) {
        // Stop this file being access directly
        if(!headers_sent()) header("Location: ../index.php",TRUE,301);
        die('<head><title>Access denied</title></head><body><h2 style="color:red;margin:3em auto;text-align:center;">Cannot access this file directly</h2></body></html>');
}
/* -------------------------------------------------------- */


$module_directory       = 'hints';
$module_name            = 'Hints';
$module_function        = 'page';
$module_version         = '0.6.0';
$module_platform        = '2.8.x';
$module_author          = 'Martin Hecht (mrbaseman), Ruud Eisinga (Dev4me)';
$module_license         = 'GNU General Public License v3 - The javascript features are third party software, spectrum color picker and autosize, both licensed under MIT license';
$module_description     = 'This module allows you to add comments in the backend for documentation, tutorials, etc. - useful if you have less experienced authors who need some hints how to use the backend or if a team needs some places to put hints for other team members';

/*
 *      CHANGELOG
 *
 *
 *      0.6.0   2019-03-01      - add backend.js to change font color (Ruud)
 *                              - add a display mode to allow hints to float up to the top (Ruud)
 *                              - remove section headers when hints are shown readonly (Ruud)
 *                              - fix background colors (Ruud)
 *                              - add Dutch language file (Ruud)
 *                              - add two more display modes: popup and bottom
 *
 *      0.5.1   2019-01-10      - fix typo in language file
 *                              - move preview below the buttons
 *
 *      0.5.0   2019-01-09      - support sharing and visibility to groups
 *                              - fix role of section default for owner
 *
 *      0.4.1   2019-01-05      - attempt to fix show_wysiwyg_editor already declared error
 *
 *      0.4.0   2019-01-04      - add settings to adjust different views
 *                              - provide a wysiwyg editor
 *                              - provide a preview mode and an edit button
 *
 *      0.3.4   2018-12-30      - add a preview to assist editing of html
 *
 *      0.3.3   2018-12-28      - more meaningful labels at the checkboxes
 *                              - correct htt template delimiters
 *
 *      0.3.2   2018-12-23      - do not throw users out of backend when attempt to delete is denied (thanks to florian)
 *
 *      0.3.1   2018-12-22      - next attempt to fix IDKEY error (thanks to jacobi22)
 *                              - do not expose usernames (use display names only)
 *
 *      0.3.0   2018-12-21      - offer a color selection palette
 *                              - localization for the color picker
 *                              - keep previously selected colors in palette
 *                              - resize textarea to small heights
 *                              - rework update of end_time in add.php
 *
 *      0.2.1   2018-12-19      - display a hint about hidden sections
 *
 *      0.2.0   2018-12-18      - added color selector and auto-resize textbox
 *                              - disable deletion of section if not owner
 *                              - suppress section anchors in frontend
 *                              - allow html input
 *                              - bug fix checkIDKEY error (hopefully)
 *                              - allow hiding hints for others
 *
 *      0.1.1   2018-12-12      - allow superadmin as owner
 *                              - use div instead of textbox if not shared
 *                              - add a padding inside the hint's div
 *                              - use an additional pre tag inside div
 *                              - use separeate stylings for div and textarea
 *
 *      0.1.0   2018-12-11      - initial version (btw. thanks to Franky)
 *
 */

