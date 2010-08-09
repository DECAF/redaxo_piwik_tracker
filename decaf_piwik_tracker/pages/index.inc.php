<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */

$basedir = dirname(__FILE__);

$page = rex_request('page', 'string');
$subpage = rex_request('subpage', 'string');
$func = rex_request('func', 'string');

require $REX['INCLUDE_PATH'].'/layout/top.php';

rex_title($I18N->msg('piwik_headline'), $REX['ADDON']['pages']['decaf_piwik_tracker']);

$piwik_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/decaf_piwik_tracker/config/config.ini.php', true);
if (!$piwik_config['piwik']['tracker_url'] || !$piwik_config['piwik']['site_id'])
{
  $subpage = 'settings';
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