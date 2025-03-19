<?php
if (!defined('MAINFILE_INCLUDED'))
{
   die('You cannot access this file directly !');exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="language" content="en">
  <meta http-equiv="content-language" content="fr">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--
  <meta name="viewport" content="width=device-width, init5ial-scale=1">
-->

  <meta name="theme-color" content="#33691e">
  <title>Farms Demo</title>
  <meta name="description" content="Here we manipulate interesting data provided from a farm.">
  <link rel="shortcut icon" type="image/png"   href="images/favicon.png">
  <link rel="apple-touch-icon" sizes="144x144" href="images/logo-144-144.png">
  <link rel="apple-touch-icon" sizes="114x114" href="images/logo-114-114.png">
  <link rel="apple-touch-icon" sizes="72x72"   href="images/logo-72-72.png">
  <link rel="apple-touch-icon" sizes="57x57"   href="images/logo-57-57.png">
 <!--
  <base href="" target="_self">
 -->
  <link rel="stylesheet" href="css/custom.css">
  <link rel="stylesheet" href="css/template.css">
  <link rel="stylesheet" href="css/materialize_min.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="canonical"  href="#">
  <link rel="alternate"  type="application/rss+xml" title="Walkap Services" href="/feed.xml">
  <link rel="stylesheet" href="bower_components/chartist/dist/chartist.min.css">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }  
  </style>
  <!-- Ui for calendar (pop up) -->  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- Calendar script (js) -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

</head>

<body>
<div class="navbar">
  <nav>
    <div class="nav-wrapper">
      <div class="col s12">
        <a href="<?php echo "index.php"; ?>" class="brand-logo hide-on-med-and-down">
          <img src="images/machine_farm_color_nobg.png" class="logo-img" alt="Logo" />
          <span class="grey-text text-darken-3">Farms Demo</span>
        </a>

        <ul class="right hide-on-med-and-down">
         <?php
           if (isset($global['module']) && trim($global['module'])!="login") { //Managing the module ("display or not")
          ?>
          
          <li><a href="<?php if (isset($link_graphs)) echo $link_graphs; ?>" >Graphs</a></li>
          <li><a href="<?php if (isset($link_farms)) echo $link_farms; ?>">Farms</a></li>  
          <!--        
          <li><a href="# ?>"><?php //if (isset($tab_menu)) echo $tab_menu["menu2"]; ?></a></li>
           -->

         <?php
          }
         ?>
   

          <li><a class="tooltipped" href="#" data-position="bottom" data-delay="50" data-tooltip="Suomi" ><img title="Suomi" src="images/suomi.png"/></a></li>
          <li><a class="tooltipped" href="<?php if (isset($link_en)) echo $link_en; ?>" data-position="bottom" data-delay="50" data-tooltip="Anglais" ><img  title="English"  src="images/flag-en.png"/></a></li>
        </ul>
        <!-- menu for mobile -->
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <a href="index.php" class="center hide-on-large-only"><img class="responsive-img" src="images/logo-57-57.png" alt="Logo"/></a>

      </div>
    </div>
  </nav>
</div>


<div class="row">
<section id="splash-blue">
  <h4 class="header center section-title">DasHBoard</h4>
</section>
</div>
<section id="benefits">
   
     <?php
      if(isset($_SESSION['message']) && $_SESSION['message'])
      {
        echo $_SESSION['message'];
        $_SESSION['message'] = '';
      }
              // CONTENT GOES HERE

      if($global['script_name'] && file_exists(TEMPLATE_DIR . $global['script_name']))
      {
          //echo $global['script_name'];
          include_once(TEMPLATE_DIR . $global['script_name']);
      }
    ?>
</section>

<!--
 <div id="googleMap" style="width:80%;height:400px; align:center"></div>  
    --> 

<footer class="orange" >
 <div class="footer-copyright">
    <div class="container">
    &copy; Copyright, All rights reserved.</div>
  </div>

  


<?php 
//src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBc2tnDO_myHclHAvgxQUmvTwSdPJnpWDU&callback=getData" async defer
?>

</footer>  
 </body>

</html>
