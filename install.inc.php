<?php
/**
 * Piwik Tracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

$mypage = 'decaf_piwik_tracker';

// init config
$configFile = rex_path::addonData($mypage, '.config.ini');
$configFileTemplate = rex_path::addon($mypage, 'config/_config.ini');
if (!file_exists($configFile)) {
  $cfg = parse_ini_file($configFileTemplate);
  $tpl = rex_file::get($configFileTemplate);
  foreach($cfg as $k => $v) {
    $search[]   = '@@'.$k.'@@';
    $replace[]  = '';
  }
  $config_str = str_replace($search, $replace, $tpl);
  rex_file::put($configFile, $config_str);
}

// init widgets
$widgetsFile = rex_path::addonData($mypage, '.widgets.ini');
$widgetsFileTemplate = rex_path::addon($mypage, 'config/_widgets.ini');
if (!file_exists($widgetsFile)) {
    $content = rex_file::get($widgetsFileTemplate);
    rex_file::put($widgetsFile, $content);
}
