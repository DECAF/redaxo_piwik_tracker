<?php
/**
 * Piwik Tracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

$subpage = rex_be_controller::getCurrentPagePart(2);
$func = rex_request('func', 'string');
$msg = '';

// page title
echo rex_view::title(rex_i18n::msg('piwik_headline'), ' ');

// clear cache
if ($func == 'clear_cache') {
  rex_file::delete($this->getCachePath('.cache'));
  echo rex_view::success(rex_i18n::msg('piwik_cleared_cache'));
}

// check for config
if (!$piwik_config['piwik']['tracker_url'] || !$piwik_config['piwik']['site_id']) {
  $subpage = 'settings';
}
if (!file_exists($this->getDataPath('.widgets.ini'))) {
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
require __DIR__ . '/' . $subpage . '.inc.php';
