<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */

$mypage = "decaf_piwikTracker";
if ($REX['REDAXO'])
{
  $I18N->appendFile($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
}

$error = false;



// check if /config is writable
if (!is_writable($REX['INCLUDE_PATH'] .'/addons/decaf_piwikTracker/config/'))
{
  echo rex_warning($I18N->msg('piwik_config_dir_locked'));
  $error = true;
} else {
  // check if config.ini exists
  $file = $REX['INCLUDE_PATH'] .'/addons/decaf_piwikTracker/config/config.ini';
  if (!file_exists($file)) {
    $cfg = parse_ini_file($REX['INCLUDE_PATH']. '/addons/decaf_piwikTracker/config/config.template');
    $tpl = rex_get_file_contents($REX['INCLUDE_PATH'] .'/addons/decaf_piwikTracker/config/config.template');
    foreach($cfg as $key => $val)
    {
      $search[]   = '{{'.$key.'}}';
      $replace[]  = '';
    }
    $config_str = str_replace($search, $replace, $tpl);
    file_put_contents($REX['INCLUDE_PATH'] .'/addons/decaf_piwikTracker/config/config.ini', $config_str);
  }
}

if (!$error) {
  $REX['ADDON']['install'][$mypage] = true;
}