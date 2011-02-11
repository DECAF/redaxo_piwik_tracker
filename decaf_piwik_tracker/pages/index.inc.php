<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */

$mypage = 'decaf_piwik_tracker';

$basedir = dirname(__FILE__);

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');

require $REX['INCLUDE_PATH'].'/layout/top.php';

if ($REX['VERSION'] == 4 && $REX['SUBVERSION'] <= 1)
{
  // Build Subnavigation
  $subpages = array (
    array ('','Besucherstatistik'),
    array ('settings','Konfiguration')
  );
  rex_title('Image Resize', $subpages);
}
else
{
  rex_title($piwik_I18N->msg('piwik_headline')); // , $REX['ADDON']['pages'][$mypage]);
}

// $piwik_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php', true);
if (!$piwik_config['piwik']['tracker_url'] || !$piwik_config['piwik']['site_id'])
{
  $subpage = 'settings';
}

if (!file_exists($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/widgets.ini.php'))
{
  echo rex_warning($piwik_I18N->msg('piwik_config_missing'));
  exit;
}

// Include Current Page
switch($subpage)
{
  case 'settings' :
    require $basedir .'/settings.inc.php';
    break;
  default:
    $subpage = 'ministats';
    require $basedir .'/ministats.inc.php';
}

require $REX['INCLUDE_PATH'].'/layout/bottom.php';



?>