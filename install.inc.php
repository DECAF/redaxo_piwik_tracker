<?php
/**
 * Piwik Tracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

// init config
$configFile = $this->getDataPath('.config.ini');
$configFileTemplate = $this->getBasePath('config/_config.ini');
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
$widgetsFile = $this->getDataPath('.widgets.ini');
$widgetsFileTemplate = $this->getBasePath('config/_widgets.ini');
if (!file_exists($widgetsFile)) {
  rex_file::copy($widgetsFileTemplate, $widgetsFile);
}
