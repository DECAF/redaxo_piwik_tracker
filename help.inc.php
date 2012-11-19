<?php
/**
 * Piwik Tracker Addon
 *
 * @author DECAF
 * @version $Id$
 */
?>
<hr>

<h3>Features:</h3>

<ul>
  <li><strong>Adds the tracking code to the website, either as JavaScript snippet or PHP include.</strong></li>
  <li><strong>Shows graphical statistics on the REDAXO backend being focused on essential output per default (last 14 days’ visits) but also being quite configurable.</strong></li>
  <li><strong>Adds a direct link on your Piwik installation with an auto login feature (optional).</strong></li>
</ul>

<hr>

<h3>Requirements:</h3>

<ul>
  <li>Piwik Server</li>
  <li>PHP 5.2+</li>
  <li>REDAXO 5+</li>
  <li>If you want to track your visitors using PHP-Code and display the statistics in REDAXO 'allow_url_fopen' needs to be turned on.</li>
</ul>
<p>For more information on Piwik visit <a href="http://piwik.org">http://piwik.org</a>. They have good documentation how to setup the statistics server.</p>

<hr>

<h3>Configuration</h3>

<p>Once the addon is installed you need to configure some parameters on the configuration page.</p>
<ul>
  <li><strong>Tracker URL:</strong> The URL to your Piwik-Server, no trailing slash please. E.g.: http://stats.your-server.tpl</li>
  <li><strong>Site Id:</strong> The ID as shown in Piwik under Settings » Websites.</li>
  <li><strong>Tracking Method:</strong> Choose between Javascript (default) and PHP. The PHP Method is only available if allow_url_fopen is turned on. Javascript has the ability to track more information (e.g. screen sizes), while PHP is more dependable.</li>
  <li><strong>Auth Token:</strong> The auth token is required if you want to include stats in the REDAXO backend. It's shown in Piwik under Settings » Users.</li>
  <li><strong>Username:</strong> Optional parameter. Enables auto login at the Piwik stat server (requires username and password).</li>
  <li><strong>Password:</strong> Optional parameter. Enables auto login at the Piwik stat server (requires username and password).</li>
</ul>

<hr>

<h3>Widget Configuration</h3>

<p>To configure what statistics are displayed in the REDAXO backend you need to edit the widgets.ini in the config/ folder and reinstall the addon in order to activate the new config. You can show multiple widgets by adding entries to the widget.ini.</p>
<ul>
  <li><strong>api_period:</strong> The period to display. Can be either 'day', 'week', 'month' or 'year'</li>
  <li><strong>api_date:</strong> The date range to fetch. Right now only lastX ist supportet. To fetch the last 6 weeks use api_date= last6 and api_period = week.</li>
  <li><strong>columns:</strong> What columns to display. You can use nb_visits, nb_uniq_visitors and nb_actions. Separate multiple values with commas (,) and <strong>no spaces</strong>.</li>
  <li><strong>width:</strong> The width of the widget. Usually it's 745. If you use smaller values the widgets will be displayed on the same row.</li>
  <li><strong>widget_title:</strong> If you want to override the automatic title generation you can set your custom title here.</li>
</ul>

<hr>
