<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting | sk@decaf.de
 * @author htttp://www.decaf.de
 * @package redaxo4
 * @version $Id$
 */
?>
<style type="text/css" media="screen">
  div#decaf_piwik_tracker_help p { margin-bottom: 12px; margin-left: 10px;}
  div#decaf_piwik_tracker_help h1 { margin-bottom: 10px; font-size: 150%;}
  div#decaf_piwik_tracker_help h2 { margin-bottom: 10px; font-size: 120%;}
  div#decaf_piwik_tracker_help ul { margin-bottom: 10px; padding-left: 40px; }
</style>

<div id="decaf_piwik_tracker_help">

  <h1 id="readme">README</h1>

  <h2 id="aboutdecaf_piwik_tracker">About decaf_piwik_tracker</h2>

  <p>This REDAXO addon adds the necessary Javescript- or PHP-Code to track your visitors with Piwik. Some statistics can be displayed in the REDAXO backend.</p>

  <h2 id="changelog">Changelog</h2>

  <p><strong>1.0.2:</strong> Initial release</p>

  <h2 id="requirements">Requirements</h2>

  <ul>
  <li>Piwik Server</li>
  <li>PHP 5.2+</li>
  <li>REDAXO 4.2+</li>
  </ul>

  <p>If you want to track your visitors using PHP-Code and display the statistics in REDAXO &#8216;allow_url_fopen&#8217; needs to be turned on.</p>

  <p>For more information on Piwik visit http://piwik.org. They have good documentation how to setup the statistics server.</p>

  <h2 id="installation">Installation</h2>

  <ul>
  <li>Unzip the addon</li>
  <li>Place the decaf_piwik_tracker folder into redaxo/include/addons</li>
  <li>make sure the folder decaf_piwik_tracker and decaf_piwik_tracker/config is writable by the webserver</li>
  <li>Use the Addon-Panel in the REDAXO-backend to install and activate the addon</li>
  </ul>

  <p><strong>Note for REDAXO 4.1 users:</strong></p>

  <p>If you want to use this addon with <strong>REDAXO 4.1</strong> you will need to manually create the folder /files/addons/decaf_piwik_tracker/ and copy everything from /redaxo/include/addons/decaf_piwik_tracker/files/ to the newly created folder.</p>

  <h2 id="configuration">Configuration</h2>

  <p>Once the addon is installed you need to configure some parameters on the configuration page.</p>

  <p><strong>Tracker URL:</strong>
  The URL to your Piwik-Server, no trailing slash please. E.g.: http://stats.your-server.tpl</p>

  <p><strong>Site Id:</strong>
  The ID as shown in Piwik under Settings » Websites.</p>

  <p><strong>Tracking Method:</strong>
  Choose between Javascript (default) and PHP. The PHP Method is only available if allow_url_fopen is turned on. </p>

  <p>Javascript has the ability to track more information (e.g. screen sizes), while PHP is less obvious.</p>

  <p><strong>Auth Token:</strong>
  The auth token is required if you want to include stats in the REDAXO backend. It&#8217;s shown in Piwik under Settings » Users.</p>

  <p><strong>Username:</strong>
  Optional parameter. If you want the link to the statistic server with automatic login you need to set this and the Password (MD5).</p>

  <p><strong>Password (MD5):</strong>
  Optional parameter. You can find the md5 encrypted password in the piwik_user MySQL-table.</p>

  <h2 id="widgetconfiguration">Widget Configuration</h2>

  <p>To configure what statistics are displayed in the REDAXO backend you need to edit the widgets.ini.php in the config/ folder.</p>

  <p>You can show multiple widgets by adding entries to the widget.ini.php.</p>

  <p><strong>api_period:</strong> The period to display. Can be either &#8216;day&#8217;, &#8216;week&#8217;, &#8216;month&#8217; or &#8216;year&#8217;</p>

  <p><strong>api_date:</strong> The date range to fetch. Right now only lastX ist supportet. To fetch the last 6 weeks use api_date= last6 and api_period = week.</p>

  <p><strong>columns:</strong> What columns to display. You can use nb_visits, nb_uniq_visitors and nb_actions. Separate multiple values with commas (,) and <strong>no spaces</strong>. </p>

  <p><strong>width:</strong> The width of the widget. Usually it&#8217;s 745. If you use smaller values the widgets will be displayed on the same row.</p>

  <p><strong>widget_title</strong> If you want to override the automatic title generation you can set your custom title here.</p>
</div>