<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */

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

if (!$stats_error)
{
  $data = array();
  $i=0;
  foreach ($stats as $stat) 
  {
    $j=0;
    $max = 0;
    foreach($stat as $date => $values)
    {
      $data[$i]['header_date'][$j] = $date;
      foreach($values as $k => $v) {
        $data[$i]['header_value'][$k] = $k;
        $data[$i]['data'][$j][$k] = $v;
        if ($v > $max) 
        {
          $max = $v;
        }
      }
      $j++;
    }
    $data[$i]['max']      = $max;
    $data[$i]['columns']  = count($data[$i]['header_date']);
    $i++;
  }
}

?>
<?php if (!$stats_error): ?>
  <?php foreach ($data as $i => $d): ?>
    <?php if (isset($d['data']) && count($d['data'])): ?>
      <?php $cell_width = floor(92 / ($d['columns'] + 1)) ?>
      <table border="0" cellspacing="1" cellpadding="2" width="100%" class="rex-table">
        <tr>
          <td colspan="<?php echo ($d['columns'] + 1) ?>">
            <?php $canvas_id = "canvas_".$i ?>
            <div id="<?php echo $canvas_id ?>"></div>
          </td>
        </tr>
        <script type="text/javascript">
          <?php $ratio_h = 200 / $max;  ?>
          <?php $w = floor(((740-148) / $d['columns']) /3)-2;  ?>
          <?php $x = 149; ?>
          var mycanvas = Raphael("<?php echo $canvas_id ?>", 740, 200  );
          <?php $i=1; ?>
          <?php foreach($d['header_date'] as $k => $v): ?>
            <?php if (isset($d['data'][$k]['nb_uniq_visitors'])): ?>
              var r<?php echo $i ?> = mycanvas.rect(<?php echo $x ?>,<?php echo round(200-($d['data'][$k]['nb_uniq_visitors'] * $ratio_h)) ?>, <?php echo $w ?>,<?php echo round($d['data'][$k]['nb_uniq_visitors'] * $ratio_h) ?>).attr({"stroke-width":"0","fill": "#c00"});
            <?php endif ?>
            <?php $x += $w + 1 ?>
            <?php $i++; ?>
            <?php if (isset($d['data'][$k]['nb_visits'])): ?>
              var r<?php echo $i ?> = mycanvas.rect(<?php echo $x ?>,<?php echo round(200-($d['data'][$k]['nb_visits'] * $ratio_h)) ?>, <?php echo $w ?>,<?php echo round($d['data'][$k]['nb_visits'] * $ratio_h) ?>).attr({"stroke-width":"0","fill": "#0c0"});
            <?php endif ?>
            <?php $x += $w + 1 ?>
            <?php $i++; ?>
            <?php if (isset($d['data'][$k]['nb_actions'])): ?>
              var r<?php echo $i ?> = mycanvas.rect(<?php echo $x ?>,<?php echo round(200-($d['data'][$k]['nb_actions'] * $ratio_h)) ?>, <?php echo $w ?>,<?php echo round($d['data'][$k]['nb_actions'] * $ratio_h) ?>).attr({"stroke-width":"0","fill": "#00c"});
            <?php endif ?>
            <?php $x += $w + 5 ?>
            <?php $i++; ?>
          <?php endforeach ?>

        </script>
        <tr>
          <th style="width: 8%;">&nbsp;</th>
            <?php foreach ($d['header_date'] as $date): ?>
              <th style="width: <?php echo $cell_width ?>%"><?php echo $date ?></th>
            <?php endforeach ?>
          </tr>
          <?php foreach ($d['header_value'] as $key => $value): ?>
            <tr>
              <th style="width: 8;"><?php echo $I18N->msg($key) ?></th>
              <?php foreach($d['header_date'] as $k => $v): ?>
                <td style="width: <?php echo $cell_width ?>%">
                  <?php if (isset($d['data'][$k][$key])): ?>
                    <?php echo $d['data'][$k][$key] ?>
                  <?php else: ?>
                    &nbsp;
                  <?php endif ?>
                </td>
              <?php endforeach ?>
            </tr>
          <?php endforeach ?>
      </table>
      <p>&nbsp;</p>
    <?php endif ?>
  <?php endforeach ?>
<?php endif ?>

<h2>
  <a href="<?php echo $piwik_config['piwik']['tracker_url'] ?>/index.php?module=Login&action=logme&login=<?php echo $piwik_config['piwik']['login'] ?>&password=<?php echo $piwik_config['piwik']['pass_md5'] ?>">» <?php echo $I18N->msg('piwik_link_caption') ?></a>
</h2>