<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */
require_once($REX['INCLUDE_PATH']."/addons/decaf_piwikTracker/extensions/extension.decaf_piwik_tracker_stats.inc.php");  

if (!ini_get('allow_url_fopen'))
{
  echo rex_warning($I18N->msg('piwik_allow_url_fopen_off'));
  $stats_error = true;
}
else
{
  $periods  = explode(',',$piwik_config['ministats']['api_period']);
  $dates    = explode(',',$piwik_config['ministats']['api_date']);

  $api_queries = array();

  for ($i=0; $i<count($periods); $i++)
  {
    $api_queries[$i]['period'] = trim($periods[$i]);
    if (isset($dates[$i])) 
    {
      $api_queries[$i]['date'] = trim($dates[$i]);
    }
    else
    {
      $api_queries[$i]['date'] = trim($dates[0]);
    }
  }

  $url = array();
  foreach ($api_queries as $q)
  {
    $url[] = $piwik_config['piwik']['tracker_url'].
      '/?module=API&method=VisitsSummary.get&idSite='.
      $piwik_config['piwik']['site_id'].
      '&period='.
      $q['period'].
      '&date='.
      $q['date'].
      '&format=php'.
      '&token_auth='.
      $piwik_config['piwik']['token_auth'].
      '&columns=nb_uniq_visitors,nb_visits,nb_actions';
  }
  try
  { // FIXME
    $result = array();
    foreach($url as $u)
    {
      $result[] = file_get_contents($u);
    }
  }
  catch (Exception $e)
  {
    $stats_error = true;
    echo rex_warning(sprintf($I18N->msg('piwik_error_get_stats'),$piwik_config['piwik']['tracker_url']));
  }
  $stats = array();
  foreach($result as $r)
  {
    if ($r)
    {
      $stats[] = unserialize($r);
      if (isset($stats['result']) && isset($stats['message']))
      {
        $stats_error = true;
        echo rex_warning('Piwik API: '.$stats['message']);
      }
    }
  }
}
require_once($REX['INCLUDE_PATH']."/addons/decaf_piwikTracker/classes/raphaelizerPiwikStats.class.php");
$raphaelizerOptions = array();



?>
<?php if (!$stats_error): ?>
  <?php $i = 0; ?>
  <?php foreach ($stats as $stat): ?>
    
    <?php
      $raphael = new raphaelizerPiwikStats('stat_'.$i, array_merge($REX['ADDON']['decaf_piwikTracker']['options'],$raphaelizerOptions), $I18N);
      $raphael->setStats($stat);
      $raphael->canvas('#eff9f9');
      $i++;
    ?>
    <?php echo $raphael->getJs(); ?>
    
    <?php /*
      echo '<pre><xmp>';
      print_r($raphael->getJs());
      print_r($raphael->getData());
      print_r($raphael->getMax());
      echo '</xmp></pre>';
    */ ?>
    <p>&nbsp;</p>
  <?php endforeach ?>
<?php endif ?>

<h2>
  <a href="<?php echo $piwik_config['piwik']['tracker_url'] ?>/index.php?module=Login&action=logme&login=<?php echo $piwik_config['piwik']['login'] ?>&password=<?php echo $piwik_config['piwik']['pass_md5'] ?>">» <?php echo $I18N->msg('piwik_link_caption') ?></a>
</h2>

