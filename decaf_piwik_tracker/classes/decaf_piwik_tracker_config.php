<?php
class decaf_piwik_tracker_config
{
  public $config;
  public $widget_config;
  public $editable_widget_fields = array('widget_title','api_period','api_date','width');

  private $config_file;
  private $config_widgets_file;
  private $addon_path;


  public function __construct($addon_path, $I18N)
  {
    $this->config_file          = $config_file;
    $this->I18N                 = $I18N;
    $this->addon_path           = $addon_path;
    $this->config_file          = $this->addon_path.'/config/config.ini.php';
    $this->config_widgets_file  = $this->addon_path.'/config/widgets.ini.php';
    if (!file_exists($this->config_file))
    {
      echo rex_warning($this->I18N->msg('piwik_config_missing'));
    }
    $this->loadConfig();
  }


  public function loadConfig()
  {
    $this->config = parse_ini_file($this->config_file, true);
  }


  public function loadWidgetConfig()
  {
    $this->widget_config = parse_ini_file($this->config_widgets_file, true);
  }

/*
  public function saveWidgetConfig($widgets)
  {
    $config_str = '';
    $tpl = $this->getWidgetConfigTemplate();

    foreach($widgets as $key => $widget)
    {
      $search   = array();
      $replace  = array();
      $search[]   = '{{key}}';
      $replace[]  = $key;

      foreach ($widget as $k => $v)
      {
        $search[]   = '{{'.$k.'}}';
        $replace[]  = $v;
      }
      
      $config_str .= str_replace($search, $replace, $tpl);
      
    }
    $config_str = "; <?php die('No Access');\n" . $config_str;

    $message = rex_is_writable($this->config_widgets_file);

    if($message === true)
    {
      $message  = $this->I18N->msg('piwik_config_saved_error');
      if (file_put_contents($this->config_widgets_file, $config_str))
      {
        $message  = $this->I18N->msg('piwik_config_saved_successful');
        $this->loadWidgetConfig();
      }
    }
    return $message;
  }


  private function getWidgetConfigTemplate()
  {
    $tpl = '[{{key}}]
widget_title      = {{widget_title}}
api_period        = {{api_period}}
api_date          = {{api_date}}
width             = {{width}}

';
    return $tpl;
  }


  private function getConfigTemplate()
  {
    $tpl = '; <?php die(\'No access\');
[piwik]
tracker_url       = {{tracker_url}}
site_id           = {{site_id}}
login             = {{login}}
pass_md5          = {{pass_md5}}
token_auth        = {{token_auth}}
tracking_method   = {{tracking_method}}

[ministats]
api_period        = {{api_period}}
api_date          = {{api_date}}
';
    return $tpl;
  }
*/

} // end class  