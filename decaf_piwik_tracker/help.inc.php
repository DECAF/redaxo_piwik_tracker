<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting | sk@decaf.de
 * @author htttp://www.decaf.de
 * @package redaxo4
 * @version $Id$
 */
$mypage = 'decaf_piwik_tracker';
$base_path = $REX['INCLUDE_PATH'] .'/addons/'.$mypage;
require_once($base_path.'/classes/markdown.class.php');

if ($lang == 'default' || $lang == 'de_de' || $lang == 'de_de_utf8')
{
  $file = 'LIESMICH.markdown';
}
else {
  $file = 'README.markdown';  
}


$content = Markdown(file_get_contents(dirname( __FILE__)."/".$file));
 
?>
<style type="text/css" media="screen">
  div#decaf_piwik_tracker_help p { margin-bottom: 12px; margin-left: 10px;}
  div#decaf_piwik_tracker_help h1 { margin-bottom: 10px; font-size: 150%;}
  div#decaf_piwik_tracker_help h2 { margin-top: 20px; margin-bottom: 10px; font-size: 120%;}
  div#decaf_piwik_tracker_help ul { margin-bottom: 10px; padding-left: 40px; }
</style>

<div id="decaf_piwik_tracker_help">

<?php echo $content ?>

</div>