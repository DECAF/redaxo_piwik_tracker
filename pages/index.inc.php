<?php
/**
 * Piwik Tracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

echo rex_view::title($this->i18n('piwik_headline'), ' ');

$subpage = rex_be_controller::getCurrentPagePart(2);
$func = rex_request('func', 'string');

// clear cache
if ($func == 'clear_cache') {
  rex_file::delete($this->getCachePath('.cache'));
  echo rex_view::success(rex_i18n::msg('piwik_cleared_cache'));
}

// check for config
if ( ( (!$piwik_config['piwik']['tracker_url'] || !$piwik_config['piwik']['site_id'])
  || !file_exists($this->getDataPath('.widgets.ini')) )
  && $subpage != 'settings' ) {
  if (rex::getUser()->hasPerm('piwik_tracker[config]')) {
    // redirect to settings page, if user has permission
    rex_response::sendRedirect(rex_url::backendPage('decaf_piwik_tracker/settings'));
  }
  else {
    // throw error
    echo rex_view::error(rex_i18n::msg('piwik_config_missing'));  
  }
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
