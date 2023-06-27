<?php
require('encryption.php');
require('password.php');
  if ((!isset($_GET)) || (empty($_GET['fileid']))){
   die("Nebyl specifikovan soubor ke stazeni"); 
  }
  $secret = base64_decode($_GET['fileid']);
  $pole = eddfaktura_decrypt($secret,$key);
  $pole = explode("|",$pole);
  $files = $pole[1];
  $filename = dirname(__FILE__). '/invoices/'.$files.'.pdf';
  
  
  if (file_exists($filename)){
    $date = DateTime::createFromFormat('d.m. Y',$pole[0]);
    $stazeni = 'Content-Disposition:attachment;filename=faktura_' .$date->format('d_m_Y') .'_' .$files.'.pdf';

    header("Content-type:application/pdf");
    header($stazeni);
    readfile($filename);
  }else{
    
    
    die("Soubor nenalezen"); 
  }
?>
