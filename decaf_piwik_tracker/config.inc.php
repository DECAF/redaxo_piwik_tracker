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

$REX['ADDON']['rxid'][$mypage]    = "770";
$REX['ADDON']['page'][$mypage]    = $mypage;
$REX['ADDON']['version'][$mypage] = "1.0.2";
$REX['ADDON']['author'][$mypage]  = "Sven Kesting &lt;sk@decaf.de&gt;, DECAF&deg; | www.decaf.de";
$REX['ADDON']['perm'][$mypage]    = "decaf_piwik_tracker[]";
$REX['PERM'][]                    = "decaf_piwik_tracker[]";
$REX['PERM'][]                    = "decaf_piwik_tracker[config]";

// REDAXO 4.2+
$REX['ADDON'][$mypage]['options']['color_background']     = '#eff9f9';
$REX['ADDON'][$mypage]['options']['color_background_alt'] = '#dfe9e9';
$REX['ADDON'][$mypage]['options']['color_visits']         = '#14568a';
$REX['ADDON'][$mypage]['options']['color_uniq_visitors']  = '#3c9ed0';
$REX['ADDON'][$mypage]['options']['color_actions']        = '#5ab8ef';
$REX['ADDON'][$mypage]['options']['color_text']           = '#000';

// REDAXO 4.1
/*
$REX['ADDON'][$mypage]['options']['color_background']     = '#faf9f5';
$REX['ADDON'][$mypage]['options']['color_background_alt'] = '#f0efeb';
$REX['ADDON'][$mypage]['options']['color_visits']         = '#0c5f00';
$REX['ADDON'][$mypage]['options']['color_uniq_visitors']  = '#298f1a';
$REX['ADDON'][$mypage]['options']['color_actions']        = '#56bf47';
$REX['ADDON'][$mypage]['options']['color_text']           = '#000';
*/

if ($REX['REDAXO'])
{
  // looad localized strings
  $piwik_I18N = new i18n($lang, $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
  $piwik_I18N->loadTexts();

  $REX['ADDON']['name'][$mypage]    = $piwik_I18N->msg("piwik_menu");

  // $piwik_I18N->appendFile($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
  $piwik_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php', true);
  if (!$piwik_config['piwik']['tracker_url'] || !$piwik_config['piwik']['site_id'])
  {
    if($REX_USER && ($REX_USER->isValueOf('rights','admin[]') || $REX_USER->isValueOf('rights','decaf_piwik_tracker[config]') ))
    {
      $REX['ADDON'][$mypage]['SUBPAGES'] = array (
        array ('settings', $piwik_I18N->msg('piwik_configuration')),
      );
    }
  }
  else {
    $REX['ADDON'][$mypage]['SUBPAGES'] = array (
      array ('', $piwik_I18N->msg('piwik_headline')),
    );
    if($REX_USER && ($REX_USER->isValueOf('rights','admin[]') || $REX_USER->isValueOf('rights','decaf_piwik_tracker[config]') ))
    {
      $REX['ADDON'][$mypage]['SUBPAGES'][] = array ('settings', $piwik_I18N->msg('piwik_configuration'));
    }
  }
}

// include extension point only in frontend
if (!$REX['REDAXO'])
{
  require_once($REX['INCLUDE_PATH']."/addons/decaf_piwik_tracker/extensions/extension.decaf_piwik_tracker.inc.php");
}
