<?php
/**
 * Piwik Tracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

$mypage = 'decaf_piwik_tracker';

$this->setProperty('color_background',     '#eff9f9');
$this->setProperty('color_background_alt', '#dfe9e9');
$this->setProperty('color_visits',         '#14568a');
$this->setProperty('color_uniq_visitors',  '#3c9ed0');
$this->setProperty('color_actions',        '#5ab8ef');
$this->setProperty('color_text',           '#000000');

// include extension point in frontend
if (!rex::isBackend()) {
  require_once(rex_path::addon($mypage, 'extensions/extension.decaf_piwik_tracker.inc.php'));
}
