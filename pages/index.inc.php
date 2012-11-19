<?php
/**
 * Piwik Tracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

$mypage = 'decaf_piwik_tracker';

$subpage = rex_be_controller::getCurrentPagePart(2);
$func = rex_request('func', 'string');
$msg = '';

// page title
echo rex_view::title(rex_i18n::msg('piwik_headline'), ' ');

// clear cache
if ($func == 'clear_cache') {
  $cacheFile = rex_path::addonData($mypage, '.cache');
  if (is_file($cacheFile)) unlink($cacheFile);
  echo rex_view::success(rex_i18n::msg('piwik_cleared_cache'));
}

// check for config
if (!$piwik_config['piwik']['tracker_url'] || !$piwik_config['piwik']['site_id']) {
  $subpage = 'settings';
}
if (!file_exists(rex_path::addonData($mypage, '.widgets.ini'))) {
  echo rex_view::error(rex_i18n::msg('piwik_config_missing'));
  $subpage = 'settings';
}

// router
switch ($subpage) {
  case 'settings':
    $subpage = 'settings';
    break;
  default:
    $subpage = 'ministats';
    break;
}
require dirname(__FILE__) . '/' . $subpage . '.inc.php';
