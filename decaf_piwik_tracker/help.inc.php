<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting | sk@decaf.de
 * @author htttp://www.decaf.de
 * @package redaxo4
 * @version $Id$
 */

switch ($REX['LANG']) {
   case "de_de": 
   case "de_de_utf8":
      $file = dirname( __FILE__) .'/LIESMICH.textile'; 
      break;
   default: 
      $file = dirname( __FILE__) .'README.textile'; 
}

if(is_readable($file)) 
{
  $content = file_get_contents($file);
  if(OOAddon::isAvailable("textile"))
  {
    echo rex_a79_textile($content);
  } 
  else {
    echo nl2br($content);
  }
}

?>
