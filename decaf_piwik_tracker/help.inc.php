<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting | sk@decaf.de
 * @author htttp://www.decaf.de
 * @package redaxo4
 * @version $Id$
 */

$file = dirname( __FILE__) .'/README'; 

if(is_readable($file)) 
{
  echo nl2br(str_replace('\\','',file_get_contents($file)));
}

?>
