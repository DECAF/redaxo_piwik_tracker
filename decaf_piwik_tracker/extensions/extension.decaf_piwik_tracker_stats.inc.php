<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */

rex_register_extension('OUTPUT_FILTER', 'decaf_piwik_tracker_stats');

/**
 * adds the js code to the html <head> section
 */
function decaf_piwik_tracker_stats($params) 
{
  $mypage = "decaf_piwik_tracker";
  global $REX;
  $piwik_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php', true);
  $content = $params['subject'];
  // Backend - include Rafael.js
  $js = '  <script src="../files/addons/'.$mypage.'/rafael.js" type="text/javascript" charset="utf-8"></script>';
  $content = str_replace("</head>", $js."\n</head>", $content);
  return $content;
}
