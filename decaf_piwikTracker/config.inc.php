<?php
/**
 * piwikTracker Addon
 * 
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */

$mypage                           = "decaf_piwikTracker";

// just in case, we allow url_fopen wrapper
ini_set("allow_url_fopen", "On");

if ($REX['REDAXO'])
{
  // looad localized strings
  $I18N->appendFile($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');

  $REX['ADDON']['name'][$mypage]    = $I18N->msg("piwik_menu");

  // add menu buttons
  $settingsPage = new rex_be_page($I18N->msg('piwik_configuration'), array(
      'page'=>'decaf_piwikTracker',
      'subpage'=>'settings'
    )
  );

  $settingsPage->setHref('index.php?page=decaf_piwikTracker&subpage=settings');
  
  $statsPage = new rex_be_page($I18N->msg('piwik_ministats'), array(
      'page'=>'decaf_piwikTracker',
      'subpage'=> ''
    )
  ); 
  $statsPage->setHref('index.php?page=decaf_piwikTracker');

  $REX['ADDON']['pages'][$mypage] = array (
    $statsPage, $settingsPage
  );
}

$REX['ADDON']['rxid'][$mypage]    = "770";
$REX['ADDON']['page'][$mypage]    = $mypage;
$REX['ADDON']['version'][$mypage] = "0.1";
$REX['ADDON']['author'][$mypage]  = "Sven Kesting &lt;sk@decaf.de&gt;, DECAF&deg; | www.decaf.de";
$REX['ADDON']['perm'][$mypage]    = "decaf_piwiktracker[]";
$REX['PERM'][]                    = "decaf_piwiktracker[]";

// include extension point
if (!$REX['REDAXO']) 
{
  require_once($REX['INCLUDE_PATH']."/addons/decaf_piwikTracker/extensions/extension.decaf_piwik_tracker.inc.php");  
}
