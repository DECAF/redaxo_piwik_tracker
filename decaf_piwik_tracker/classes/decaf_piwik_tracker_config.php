<?php
/*---------------------------------------------------------------------------+
|              ________ __________________________ __________                |
|              ___  __ \___  ____/__  ____/___    |___  ____/                |
|              __  / / /__  __/   _  /     __  /| |__  /_                    |
|              _  /_/ / _  /___   / /___   _  ___ |_  __/                    |
|              /_____/  /_____/   \____/   /_/  |_|/_/                       |
|                                                                            |
|              DECAF° - agentur für digitale kommunikation                   |
|              http://www.decaf.de         <info@decaf.de>                   |
+---------------------------------------------------------------------------*/
/**
  * @author    Sven Kesting <sk@decaf.de>
  * @version   $Id$
  * @copyright DECAF GmbH & Co. KG, 08 August, 2010
  * @package   Piwik Tracker
**/


class decaf_piwik_tracker_config
{
  public $config;
  public $widget_config;
  public $editable_widget_fields = array('widget_title','api_period','api_date','width');

  private $config_file;
  private $config_widgets_file;
  private $addon_path;


  /**
   * (void)__construct((str)addon_path,(object)I18N)
   * 
   * Constructor for decaf_piwik_tracker_config
   * 
   * @author Sven Kesting <sk@decaf.de>
   **/
  public function __construct($addon_path, $piwik_I18N)
  {
    $this->config_file          = $config_file;
    $this->I18N                 = $piwik_I18N;
    $this->addon_path           = $addon_path;
    $this->config_file          = $this->addon_path.'/config/config.ini.php';
    $this->config_widgets_file  = $this->addon_path.'/config/widgets.ini.php';
    if (!file_exists($this->config_file))
    {
      echo rex_warning($this->I18N->msg('piwik_config_missing'));
    }
    $this->loadConfig();
  }

  /**
   * (void)loadConfig()
   * 
   * sets config by parsing config_file
   * 
   * @author Sven Kesting <sk@decaf.de>
   **/
  public function loadConfig()
  {
    $this->config = parse_ini_file($this->config_file, true);
  }

  /**
   * (void)loadWidgetConfig()
   * 
   * sets widget_config by parsing config_widgets_file
   * 
   * @author Sven Kesting <sk@decaf.de>
   **/
  public function loadWidgetConfig()
  {
    $this->widget_config = parse_ini_file($this->config_widgets_file, true);
  }

  /**
   * (str)getI18nTitle((array($conf)))
   * 
   * gets the title from the passed config array.
   * if the config array has 'widget_title' set, this is returned
   * 
   * @return string
   * @author Sven Kesting <sk@decaf.de>
   **/
  public function getI18nTitle($conf)
  {
    if ($conf['widget_title'])
    {
      return $conf['widget_title'];
    }
    $title = '';
    $type_items = explode(',',$conf['columns']);
    foreach ($type_items as &$item) 
    {
      $item = $this->I18N->msg($item);
    }

    if (count($type_items) > 2) 
    {
      $tmp = $this->I18N->msg('and') .' ' . array_pop($type_items);
      $title .= implode(', ',$type_items) . ' ' . $tmp;
    } 
    else 
    {
      $title .= implode(' '.$this->I18N->msg('and').' ',$type_items);
    }
    $title .= ' ';

    if (substr($conf['api_date'], 0, 4) == 'last') 
    {
      $quantity = substr($conf['api_date'], 4, strlen($conf['api_date']));
      if ($quantity > 1) 
      {
        $title .= sprintf($this->I18N->msg('last_'.$conf['api_period'].'s'), $quantity);
      } 
      else 
      {
        $title .= $this->I18N->msg('last_'.$conf['api_period']);
      }
    }
    return $title;
  }

} // end class  