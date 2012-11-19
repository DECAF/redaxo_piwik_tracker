<?php
/**
 * Piwik Tracker Addon
 *
 * @author DECAF
 * @version $Id$
 */

$content = '';


// init tracking methods
$tracking_types = NULL;
$allow_url_fopen = ini_get('allow_url_fopen');
if (!$allow_url_fopen) {
  $tracking_types = array('Javascript');
  echo rex_view::warning($this->i18n('piwik_allow_url_fopen_off'));
} 
else {
  $tracking_types = array('PHP', 'Javascript');
}

// submit action
if (rex_post('config-submit', 'boolean')) {
  $this->setConfig(rex_post('config', array(
    array('tracker_url', 'string'),
    array('site_id', 'string'),
    array('token_auth', 'string'),
    array('tracking_method', 'string')
  )));

  // update config
  /*
  $file = $REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/config.ini.php';
  $message = rex_is_writable($file);

  if($message === true)
  {
    $message  = rex_i18n::msg('piwik_config_saved_error');
    $tpl      = rex_get_file_contents($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/_config.ini.php');
    $search   = array();
    $replace  = array();

    foreach($_POST as $key => $val)
    {
      $search[]   = '@@'.$key.'@@';
      if ($key == 'pass_md5' && strlen($val) != 32) {
        $val = md5($val);
      }
      $replace[]  = $val;
    }
    $config_str = str_replace($search, $replace, $tpl);
    if (file_put_contents($REX['INCLUDE_PATH'] .'/addons/'.$mypage.'/config/config.ini.php', $config_str))
    {
      $message  = rex_i18n::msg('piwik_config_saved_successful');
    }
  }
  */

  echo rex_view::success($this->i18n('piwik_config_saved_successful'));
}

// generate form elements
$formElements = array(); // init

$n = array();
$n['label'] = '<label for="piwik_tracker_url">' . $this->i18n('piwik_tracker_url') . '</label>';
$n['field'] = '<input type="text" id="piwik_tracker_url" name="config[tracker_url]" value="' . $this->getConfig('tracker_url') . '" placeholder="' . $this->i18n('piwik_tracker_url_placeholder') . '" />';
$n['after'] = '<div class="rex-form-notice">' . $this->i18n('piwik_tracker_url_notice') . '</div>';
$formElements[] = $n;

$n = array();
$n['label'] = '<label for="piwik_site_id">' . $this->i18n('piwik_site_id') . '</label>';
$n['field'] = '<input type="text" id="piwik_site_id" name="config[site_id]" value="' . $this->getConfig('site_id') . '" placeholder="' . $this->i18n('piwik_site_id_placeholder') . '" />';
$n['after'] = '<div class="rex-form-notice">' . $this->i18n('piwik_site_id_notice') . '</div>';
$formElements[] = $n;

$n = array();
$n['label'] = '<label for="piwik_token_auth">' . $this->i18n('piwik_token_auth') . '</label>';
$n['field'] = '<input type="text" id="piwik_token_auth" name="config[token_auth]" value="' . $this->getConfig('token_auth') . '" placeholder="' . $this->i18n('piwik_token_auth_placeholder') . '" />';
$n['after'] = '<div class="rex-form-notice">' . $this->i18n('piwik_token_auth_notice') . '</div>';
$formElements[] = $n;

$n = array();
$n['label'] = '<label for="piwik_tracking_method">' . $this->i18n('piwik_tracking_method') . '</label>';
$piwik_config = parse_ini_file($this->getDataPath('.config.ini'), true);
$select = new rex_select();
$select->setId('piwik_tracking_method');
$select->setName('config[tracking_method]');
$select->setSize(1);
$select->setSelected($piwik_config['piwik']['tracking_method']);
foreach($tracking_types as $type) {
  $select->addOption($type,$type);
}
$select->setSelected($this->getConfig('config[tracking_method]'));
$n['field'] = $select->get();
$n['after'] = '<div class="rex-form-notice">' . $this->i18n('piwik_tracking_method_notice') . '</div>';
$formElements[] = $n;

$n = array();
$n['field'] = '<input type="submit" name="config-submit" value="' . $this->i18n('piwik_save') . '" ' . rex::getAccesskey($this->i18n('piwik_save'), 'save') . ' />';
$formElements[] = $n;

// build form content
$content .= '

    <h2>' . rex_i18n::msg('piwik_configuration') . '</h2>

    <form action="' . rex_url::currentBackendPage() . '" method="post">
      <fieldset>'.PHP_EOL;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= '
        '.$fragment->parse('form.tpl').PHP_EOL;

$content .= '
    </fieldset>
  </form>';

echo rex_view::contentBlock($content);
