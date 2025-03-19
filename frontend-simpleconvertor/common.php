<?php
if (!defined('MAINFILE_INCLUDED'))
{
  die('You cannot access this file directly !');exit;
}

define('SITE_MODE', 'LOCAL');

include_once('includes/config.php');

require_once('includes/function2.php');

require_once('includes/api.php');



$global['module']      = isset($_REQUEST['module']) ? $_REQUEST['module'] : 'graphs';
$global['script_name'] = (isset($global['module']) && $global['module']) ? $global['module'].'.php' : '';
$global['action']      = "";if (isset($_GET['action']) && trim($_GET['action'])!="") $global['action']=$_GET['action'];

// link for text transalation flag
$actual_link           = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


/* menu details */
// link for the menu
$lang                  =  "en";
if (isset($_GET["lang"]) && $_GET["lang"]=="fr") $lang  ="fi";              // changing language in french 
$link_farms            =  return_current_url($lang,$actual_link,"farms"); // link to transaction module
$link_graphs           =  return_current_url($lang,$actual_link,"graphs");    // link to analytics module


// UI class for button
$demo_class            = "ui-button";
//$home_class            = "ui-button-active";
$transaction_class     = "ui-button-active";



 // Case 1 : language equals english or nothing
 // Case file : loading xml tag for the page
$file      ="lang/lang_menu.xml";

// Sorting the xml file, which becomes a tree,
// english

// suomi
$pos = strpos($actual_link, "lang=fi");
if ((isset($pos) && $pos!==false))  $tab_menu=language_xml_menu($xml,"suomi");

$web                   = base64_encode("web");


$action = '';
if(isset($_REQUEST['action']))
{
  $action = $_REQUEST['action'];
}
$errors['error'] = 0;


?>
