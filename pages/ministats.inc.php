<?php
/**
 * piwikTracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

$mypage = 'decaf_piwik_tracker';
$content_width = 745;
require_once($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/extensions/extension.decaf_piwik_tracker_stats.inc.php');  
$stats_error = false;

if (!ini_get('allow_url_fopen'))
{
  echo rex_warning($piwik_I18N->msg('piwik_allow_url_fopen_off'));
  $stats_error = true;
}
else
{
  require_once($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/classes/decaf_piwik_tracker_config.php');  
  $config = new decaf_piwik_tracker_config($REX['INCLUDE_PATH'] .'/addons/'.$mypage, $piwik_I18N);
  $config->loadWidgetConfig();

  $api_query_urls = array();

  if (!$config->config['piwik']['tracker_url'] || !$config->config['piwik']['site_id'] || !$config->config['piwik']['token_auth']) {
    echo rex_warning($piwik_I18N->msg('piwik_config_incomplete'));
    exit;
  }
  $widgets = array();

  foreach ($config->widget_config as $key => $value) {
    $widgets[$key]['url'] = trim(sprintf(
      '%s/?module=API&idSite=%s&token_auth=%s&method=%s&period=%s&date=%s&format=php&columns=%s',
      $config->config['piwik']['tracker_url'],
      $config->config['piwik']['site_id'],
      $config->config['piwik']['token_auth'],
      'VisitsSummary.get',
      $value['api_period'],
      $value['api_date'],
      $value['columns']
    ));
    $widgets[$key]['config'] = $value;
    $widgets[$key]['title'] = $config->getI18nTitle($value);
    $r = file_get_contents($widgets[$key]['url']);
    $widgets[$key]['stats'] = unserialize($r);
    if (isset($widgets[$key]['stats']['result']) && isset($widgets[$key]['stats']['message']))
    {
      $stats_error = true;
      echo rex_warning('Piwik API: '.$widgets[$key]['stats']['message']);
    }
  }
  if (isset($stats_error) && $stats_error)
  {
    echo rex_warning(sprintf($piwik_I18N->msg('piwik_error_get_stats'),$config->config['piwik']['tracker_url']));   
  }
}

require_once($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/classes/raphaelizerPiwikStats.class.php');
?>
<?php if (!$stats_error): ?>
  <?php $i = 0; ?>
  <?php $w = 0; ?>
  <div id="widgets_wrapper" style="margin-right: -20px; zoom: 1">
  <?php foreach ($widgets as $widget): ?>
    <?php
      $columns['show'] = explode(',',$widget['config']['columns']);
      $options = array_merge($REX['ADDON']['decaf_piwik_tracker']['options'],$columns);
      $raphael = new raphaelizerPiwikStats('stat_'.$i, $widget['config']['width'], $options, $piwik_I18N);
    ?>
    <div style="float: left; width: <?php echo $widget['config']['width'] ?>px; margin-right: 20px;">
      <h2><?php echo $widget['title'] ?></h2>
      <p>&nbsp;</p>
      <?php
        $raphael->setStats($widget['stats']);
        $raphael->canvas($REX['ADDON'][$mypage]['options']['color_background']);
        $i++;
      ?>
      <?php echo $raphael->getJs(); ?>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
    <?php $i++; ?>
  <?php endforeach ?>
  </div>
<?php endif ?>
<div style="clear: both"></div>

<h2>
<?php if ($piwik_config['piwik']['login'] && $piwik_config['piwik']['pass_md5']): ?>
  <a href="<?php echo $piwik_config['piwik']['tracker_url'] ?>/index.php?module=Login&action=logme&login=<?php echo $piwik_config['piwik']['login'] ?>&password=<?php echo $piwik_config['piwik']['pass_md5'] ?>" target="_blank">» <?php echo $piwik_I18N->msg('piwik_link_caption') ?></a>
<?php else: ?>
  <a href="<?php echo $piwik_config['piwik']['tracker_url'] ?>/index.php" target="_blank">» <?php echo $piwik_I18N->msg('piwik_link_caption') ?></a>
<?php endif ?>
</h2>

