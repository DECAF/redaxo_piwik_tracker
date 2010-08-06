<?php
/**
 * piwikTracker Addon
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id$
 */

  if (!file_exists($REX['INCLUDE_PATH'] .'/addons/decaf_piwikTracker/config/config.ini.php'))
  {
    echo rex_warning($I18N->msg('piwik_config_missing'));
    exit;
  }


  $allow_url_fopen = ini_get('allow_url_fopen');

  if (!$allow_url_fopen) {
    $tracking_types = array('Javascript');
    echo rex_warning($I18N->msg('piwik_allow_url_fopen_off'));
  } else {
    $tracking_types = array('Javascript', 'PHP');
  }

  $message = FALSE;

  if (rex_post('btn_save', 'string') != '')
  {
    $file = $REX['INCLUDE_PATH'] .'/addons/decaf_piwikTracker/config/config.ini.php';
    $message = rex_is_writable($file);

    if($message === true)
    {
      $message  = $I18N->msg('piwik_config_saved_error');
      $tpl      = rex_get_file_contents($REX['INCLUDE_PATH'] .'/addons/decaf_piwikTracker/config/_config.ini.php.php');
      $search   = array();
      $replace  = array();

      foreach($_POST as $key => $val)
      {
        $search[]   = '{{'.$key.'}}';
        $replace[]  = $val;
      }
      $config_str = str_replace($search, $replace, $tpl);
      if (file_put_contents($REX['INCLUDE_PATH'] .'/addons/decaf_piwikTracker/config/config.ini.php', $config_str))
      {
        $message  = $I18N->msg('piwik_config_saved_successful');
      }
    }
  }

  $piwik_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/decaf_piwikTracker/config/config.ini.php', true);

  $sel_tracking_method = new rex_select();
  $sel_tracking_method->setId('piwik_tracking_method');
  $sel_tracking_method->setName('tracking_method');
  $sel_tracking_method->setSize(1);
  $sel_tracking_method->setSelected($piwik_config['piwik']['tracking_method']);
  foreach($tracking_types as $type)
  $sel_tracking_method->addOption($type,$type);

if($message) {
  echo rex_info($message);
}
?>

<div class="rex-addon-output">

  <div id="rex-addon-editmode" class="rex-form">
    <form action="" method="post">
      <fieldset class="rex-form-col-1">
        <div class="rex-form-wrapper">
          <h3 class="rex-hl2"><?php echo $I18N->msg('piwik_configuration'); ?></h3>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_tracker_url'); ?></label>
              <input type="text" name="tracker_url" id="piwik_tracker_url" value="<?php echo $piwik_config['piwik']['tracker_url'] ?>" placeholder="<?php echo $I18N->msg('piwik_placeholder_tracker_url') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_site_id'); ?></label>
              <input type="text" name="site_id" id="piwik_site_id" value="<?php echo $piwik_config['piwik']['site_id'] ?>"  placeholder="<?php echo $I18N->msg('piwik_placeholder_site_id') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_login'); ?></label>
              <input type="text" name="login" id="piwik_login" value="<?php echo $piwik_config['piwik']['login'] ?>"  placeholder="<?php echo $I18N->msg('piwik_placeholder_login') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_md5_pass'); ?></label>
              <input type="text" name="pass_md5" id="piwik_md5_pass" value="<?php echo $piwik_config['piwik']['pass_md5'] ?>" placeholder="<?php echo $I18N->msg('piwik_placeholder_md5_pass') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_token_auth'); ?></label>
              <input type="text" name="token_auth" id="piwik_token_auth" value="<?php echo $piwik_config['piwik']['token_auth'] ?>" placeholder="<?php echo $I18N->msg('piwik_placeholder_token_auth') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-select">
              <label for="encoding"><?php echo $I18N->msg('piwik_tracking_method'); ?></label>
              <?php echo $sel_tracking_method->show(); ?>
            </p>
          </div>
        </div>
      </fieldset>
      <fieldset class="rex-form-col-1">
        <div class="rex-form-wrapper">
          <h3 class="rex-hl2"><?php echo $I18N->msg('piwik_configuration_ministats'); ?></h3>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_api_period'); ?></label>
              <input type="text" name="api_period" id="piwik_api_period" value="<?php echo $piwik_config['ministats']['api_period'] ?>" placeholder="<?php echo $I18N->msg('piwik_placeholder_api_period') ?>" />
            </p>
          </div>
          <div class="rex-form-row">
            <p class="rex-form-col-a rex-form-text">
              <label for="fromname"><?php echo $I18N->msg('piwik_api_date'); ?></label>
              <input type="text" name="api_date" id="piwik_api_date" value="<?php echo $piwik_config['ministats']['api_date'] ?>" placeholder="<?php echo $I18N->msg('piwik_placeholder_api_date') ?>" />
            </p>
          </div>

        </div>
      </fieldset>
      <div class="rex-form-row">
        <p class="rex-form-col-a rex-form-submit">
          <input class="rex-form-submit" type="submit" name="btn_save" value="<?php echo $I18N->msg('piwik_save'); ?>" />
          <input class="rex-form-submit rex-form-submit-2" type="reset" name="btn_reset" value="<?php echo $I18N->msg('piwik_reset'); ?>" />
        </p>
      </div>
    </form>
  </div>
</div>
<p style="text-align: right">
  Addon by<br /> <a href="http://decaf.de/"><img width="100" height="23" alt="DECAF" align="right" style="margin: 5px 0 0;" src="data:image/png;base64,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" /></a>
</p>

