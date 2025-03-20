<?php

// for testing purpose 05.2021
require __DIR__ . '/vendor/autoload.php';


define('MAINFILE_INCLUDED',1);

include('common.php');



if($global['script_name'] && file_exists(CORE_DIR . $global['script_name']))
{
  require_once(CORE_DIR . $global['script_name']);
}

include('template.php');
?>
