<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */

$mypage = "decaf_piwik_tracker";
if ($REX['REDAXO'])
{
  $I18N->appendFile($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
}

$error = false;



// check if /config is writable
if (!is_writable($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/'))
{
  echo rex_warning($I18N->msg('piwik_config_dir_locked'));
  $error = true;
}
else 
{
  // check if config.ini exists
  $file = $REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/config.ini.php';
  if (!file_exists($file)) 
  {
    $cfg = parse_ini_file($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/_config.ini.php');
    $tpl = rex_get_file_contents($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/_config.ini.php');
    foreach($cfg as $key => $val)
    {
      $search[]   = '{{'.$key.'}}';
      $replace[]  = '';
    }
    $config_str = str_replace($search, $replace, $tpl);
    file_put_contents($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/config.ini.php', $config_str);
  }
  // now copy widgets.ini.php (if not exists)
  $file = $REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/widgets.ini.php';
  if (!file_exists($file)) 
  {
    $content = rex_get_file_contents($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/_widgets.ini.php');
    file_put_contents($file, $content);
  }  
}

if (!$error) 
{
  $REX['ADDON']['install'][$mypage] = true;
}