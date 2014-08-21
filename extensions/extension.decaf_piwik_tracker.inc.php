<?php
/**
 * piwikTracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

rex_register_extension('OUTPUT_FILTER', 'decaf_piwik_tracker');

/**
 * adds the tracking code
 */
function decaf_piwik_tracker($params)
{
  $mypage = 'decaf_piwik_tracker';
  global $REX;
  if (file_exists($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php'))
  {
    $piwik_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php', true);
    $content = $params['subject'];

    // Frontend
    $debugMsg = $trackingSnippet = NULL;
    if ($piwik_config['piwik']['tracker_url'] && $piwik_config['piwik']['site_id'])
    {
      // JavaScript tracking
      if ($piwik_config['piwik']['tracking_method'] == 'JavaScript')
      {
        if(isset($_SESSION[$REX['INSTNAME']]['UID']) || isset($_COOKIE['redaxo_piwiktracker_ignore']))
        {
          $debugMsg = 'Piwik Tracker: JavaScript. Did not track REDAXO user.';
        } else {
          $debugMsg = 'Piwik Tracker: JavaScript.';
          $trackingSnippet = "
<!-- Piwik -->
<script type=\"text/javascript\">
    var _paq = _paq || [];
    (function(){ var u=\"".$piwik_config['piwik']['tracker_url']."/\";
    _paq.push(['setSiteId', ".$piwik_config['piwik']['site_id']."]);
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript'; g.defer=true; g.async=true; g.src=u+'piwik.js';
    s.parentNode.insertBefore(g,s); })();
</script>
<noscript>
  <img src=\"".$piwik_config['piwik']['tracker_url']."/piwik.php?idsite=".$piwik_config['piwik']['site_id']."&amp;rec=1\" style=\"border:0\" alt=\"\">
</noscript>
<!-- End Piwik -->
";
        }
      }

      // PHP tracking
      if ($piwik_config['piwik']['tracking_method'] == 'PHP' && ini_get('allow_url_fopen'))
      {
        if(isset($_SESSION[$REX['INSTNAME']]['UID']) || isset($_COOKIE['redaxo_piwiktracker_ignore']))
        {
          $debugMsg = 'Piwik Tracker: PHP. Did not track REDAXO user.';
        } else {
          $debugMsg = 'Piwik Tracker: PHP.';
          require_once($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/classes/PiwikTracker.php');
          PiwikTracker::$URL = $piwik_config['piwik']['tracker_url'];
          $piwikTracker = new PiwikTracker(  $idSite = $piwik_config['piwik']['site_id']);
          preg_match('/<title>(.*)<\/title>/U', $content, $hits);
          if ($piwikTracker->doTrackPageView($hits[1]) != true)
          {
            $debugMsg .= ' FAILURE.';
          } else {
            $debugMsg .= ' SUCCESS.';
          }
        }
      }

      $content = str_replace("</body>", $trackingSnippet.PHP_EOL."<!-- ".$debugMsg." -->".PHP_EOL."</body>", $content);
      return $content;
    }
  }
}
