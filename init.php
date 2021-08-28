<?php
include 'conct.php';

$tpl='includes/tamplates/'; // tamplates directory 
$lang='includes/languges/' ; // english directory 
$func='includes/fonctions/'; //functions directory
$css='layout/css/'; //css directry
$js='layout/js/'; //js directory
$nrl='layout/css/'; //normalize directory
$img='layout/image/'; //image directory

//include th imprtant files
 include $func . 'functions.php'; 
 include $lang."english.php";
 include $tpl."header.php";
 if(!isset($nonavbar)){ include $tpl."navbar.php";}
 if(!isset($nosidebar)){ include $tpl."sidebar.php";}
// if(!isset($nofooter)){ include $tpl.'footer.php';}




 

?>