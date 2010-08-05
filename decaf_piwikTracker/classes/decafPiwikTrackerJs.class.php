<?php 
/**
 * Houses the core API for manipulating the Piwik tracking code.
 *
 * @package     sfPiwikPlugin
 * @subpackage  tracker
 * @author      Anush Ramani
 */

class sfPiwikTracker
{

  const POSITION_TOP    = 'top';
  const POSITION_BOTTOM  = 'bottom';
  const TRACKER_VAR    = 'piwikTracker';
  const NAMESPACE      = 'sf_piwik_plugin';
  
  
  //
  // --- Required parameters ---
  //
  
  /**
   * Whether or not to insert the piwik tracker code.
   * @var bool
   */
  protected $enabled = false;
  
  /**
   * The piwik server URL.
   * @var string
   */
  protected $trackerUrl = null;
  
  /**
   * The Site ID of the website being tracked.
   * @var int
   */
  protected $siteId = null;
  
  //
  // --- Optional parameters ---
  //
  
  /**
   * Where the tracker code is inserted (default = POSITION_BOTTOM).
   * @var string
   */
  protected $insertion = self::POSITION_BOTTOM;
  
  /**
   * The custom data that is sent as part of the tracker call. This is in the form of an
   * associative array.
   * 
   * NOTE: Custom data does not automatically get saved to the piwik DB. You'll need a custom
   * plugin that handles the data once it arrives at the piwik server. You should be able to
   * extract this data using the Piwik_Common::getRequestVar('data') function. The data will
   * be in JSON format and should be decoded using the json_decode() function.
   * 
   * @var array
   */
  protected $customData = null;
  
  /**
   * Set a custom title to the document.
   * @var string
   */
  protected $documentTitle = null;
  
  /**
   * Array of domains to be treated as local.
   * @var array
   */
  protected $domains = null;
  
  /**
   * Classes to be treated as downloads.
   * @var array
   */
  protected $downloadClasses = null;
  
  /**
   * File extensions to be recognized as downloads. This overrides the list of default
   * file extensions recognized as downloads.
   * @var array
   */
  protected $downloadExtensions = null;
  
  /**
   * Additional file extensions to be recognized as downloads. This does not override
   * the list of default file extensions recognized as downloads.
   * @var array
   */
  protected $moreDownloadExtensions = null;
  
  /**
   * Classes to be ignored if present in link.
   * @var
   */
  protected $ignoreClasses = null;
  
  /**
   * Classes to be treated as outlinks
   * @var array
   */
  protected $linkClasses = null;
  
  /**
   * Delay for link tracking (in milliseconds)
   * @var int
   */
  protected $linkTrackingTimer = null;
  
  /**
   * Whether or not link-tracking is enabled. (default = true)
   * @var bool
   */
  protected $enableLinkTracking = true;
  
  /**
   * A campaign name assigned to this tracker call.
   * @var string
   */
  protected $campaignName = null;
  
  /**
   * A campaign keyword assigned to this tracker call.
   * @var string
   */
  protected $campaignKeyword = null;

  /**
   * JS code to be executed immediately before the tracker AJAX call is made.
   * @var string
   */
  protected $beforeTrackerJs = null;
  
  /**
   * JS code to be executed immediately after the tracker makes the AJAX call.
   * @var string
   */
  protected $afterTrackerJs = null;
  
  /**
   * If true, tracker is inserted even thought the request type restricts it.
   * @var bool
   */
  protected $force = false;
  
  /**
   * If true, JS tracker initialization code is not inserted in the response. You would typically 
   * use this for AJAX requests in conjunction with the "force" parameter set to true.
   * @var bool
   */
  protected $noInit = false;
  
  //
  // --- Methods ---
  //
  
  public function __construct($parameters = array())
  {
      $this->initialize($parameters);
  }
  
  public function initialize($parameters = array())
  {
      // apply configuration from app.yml
      $prefix = 'app_'.self::NAMESPACE.'_';
  
      $params = sfConfig::get($prefix.'params', array());
      $params['enabled']    = sfConfig::get($prefix.'enabled');
      $params['site_id']    = sfConfig::get($prefix.'site_id');
      $params['tracker_url']  = sfConfig::get($prefix.'tracker_url');
  
      $this->configure($params);
      
      return true;
  }
  
  /**
   * Apply configuration values.
   *
   * @param array $params
   */
  public function configure($params)
  {   
  // Call the setter for each passed parameter
      foreach($params as $key => $value)
  {
    $method = 'set'.ucfirst(sfInflector::camelize($key));
    if(method_exists($this, $method))
    {
      call_user_func(array($this, $method), $value);
    }
  }
  }
  
  /**
   * Insert tracking code into a response.
   *
   * @param sfResponse $response
   */
  public function insert(sfResponse $response)
  {
    $tracker = self::TRACKER_VAR;
    $js = '';
    $jsInclude = '';
    $jsTracker = '';
    
    if(!$this->noInit)
    {
      // Insert tracker initialization JS
      $jsInclude .= 'var pkBaseURL=(("https:" == document.location.protocol) ? "https://'.$this->trackerUrl.'" : "http://'.$this->trackerUrl.'");'
        .'document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));';
        
      $jsTracker .= sprintf('var %s = Piwik.getTracker(pkBaseURL+"piwik.php",%s);', $tracker, $this->siteId);
    }
    
    if($this->customData)
    {
      $jsTracker .= sprintf('%s.setCustomData(%s);', $tracker, json_encode($this->customData));
    }
    
    if($this->documentTitle)
    {
      $jsTracker .= sprintf('%s.setDocumentTitle(%s);', $tracker, $this->documentTitle);
    }
    
    if(!empty($this->downloadExtensions))
    {
      $jsTracker .= sprintf('%s.setDownloadExtensions(%s);', $tracker, join('|', $this->downloadExtensions));
    }
    
    if(!empty($this->moreDownloadExtensions))
    {
      $jsTracker .= sprintf('%s.addDownloadExtensions(%s);', $tracker, join('|', $this->moreDownloadExtensions));
    }
    
    if(!empty($this->domains))
    {
      $jsTracker .= sprintf('%s.setDomains(%s);', $tracker, json_encode($this->domains));
    }
    
    if(!empty($this->ignoreClasses))
    {
      $jsTracker .= sprintf('%s.setIgnoreClasses(%s);', $tracker, json_encode($this->ignoreClasses));
    }
    
    if(!empty($this->downloadClasses))
    {
      $jsTracker .= sprintf('%s.setDownloadClasses(%s);', $tracker, json_encode($this->downloadClasses));
    }
    
    if(!empty($this->linkClasses))
    {
      $jsTracker .= sprintf('%s.setLinkClasses(%s);', $tracker, json_encode($this->linkClasses));
    }
    
    if($this->linkTrackingTimer)
    {
      $jsTracker .= sprintf('%s.setLinkTrackingTimer(%n);', $tracker, $this->linkTrackingTimer);
    }
    
    if($this->enableLinkTracking)
    {
      $jsTracker .= sprintf('%s.enableLinkTracking();', $tracker);
    }
    
    // Pre-tracker custom JS
    $jsTracker .= $this->beforeTrackerJs;

    // Tracker AJAX call
    $jsTracker .= sprintf('%s.trackPageView();', $tracker);
    
    // Post-tracker custom JS
    $jsTracker .= $this->afterTrackerJs;
    
    // Encapsulate JS code within <script> tags
    sfLoader::loadHelpers(array('Javascript'));
    if(!$this->noInit)
      $js .= javascript_tag($jsInclude);
    
    $js .= javascript_tag('try{'.$jsTracker.'} catch(e){}');
    
    // Generate a non-JS version of the tracker
    $noScript = '<noscript><p><img src="http://'.$this->trackerUrl.'piwik.php?idsite='.$this->siteId.'" style="border:0" alt=""/></p></noscript>';
    
    // Insert code into the response body
    $content = $js.$noScript;
    $this->doInsert($response, $content, $this->insertion);
  }
    
    /**
     * Insert content into a response.
     *
     * @param   sfResponse $response
     * @param   string $content
     * @param   string $position
     */
    protected function doInsert(sfResponse $response, $content, $position = self::POSITION_BOTTOM)
    {
    $old = $response->getContent();
    
    switch($position)
    {
        case self::POSITION_TOP:
            $new = preg_replace('/<body[^>]*>/i', "$0\n".$content."\n", $old, 1);
            break;
            
        case self::POSITION_BOTTOM:
            $new = str_ireplace('</body>', "\n".$content."\n</body>", $old);
            break;
    }
    
    if($old == $new)
    {
        $new .= $content;
    }
    
    $response->setContent($new);
    }
    
    /**
     * Escape the provided value for Javascript evaluation.
     *
     * @param   string $value
     *
     * @return  string
     */
    protected function escape($value)
    {
        if(function_exists('json_encode'))
        {
            $escaped = json_encode($value);
        }
        else
        {
            sfLoader::loadHelpers(array('Escaping'));
            $escaped = '"'.esc_js($value).'"';
        }
        
        return $escaped;
    }
  
  
  //
  // --- Getters and setters ---
  //
  
  public function setCustomData($customData)
  {
    $this->customData = $customData;
  }
  
  public function addCustomData($name, $value)
  {
    if(!$this->customData)
      $this->customData = array();
      
    $this->customData[$name] = $value;
  }
  
  public function getCustomData()
  {
    return $this->customData;
  }

    public function setbeforeTrackerJs($js)
    {
    $this->beforeTrackerJs = $js;
    }
    
    public function getbeforeTrackerJs()
    {
        return $this->beforeTrackerJs;
    }

    public function setafterTrackerJs($js)
    {
    $this->afterTrackerJs = $js;
    }
    
    public function getafterTrackerJs()
    {
        return $this->afterTrackerJs;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = (bool)$enabled;
    }
    
    public function isEnabled()
    {
        return $this->enabled;
    }

    public function setInsertion($insertion)
    {
    $this->insertion = $insertion;
    }
    
    public function getInsertion()
    {
        return $this->insertion;
    }
    
  public function setDocumentTitle($documentTitle)
  {
    $this->documentTitle = $documentTitle;
  }
  
  public function getDocumentTitle()
  {
    return $this->documentTitle;
  }
  
  public function setTrackerUrl($trackerUrl)
  {
    $this->trackerUrl = $trackerUrl;
    
    // Append trailing '/' if not present
    if(strrpos($trackerUrl, '/') != (strlen($trackerUrl)-1))
    {
      $this->trackerUrl .= '/';
    }
  }
  
  public function getTrackerUrl()
  {
    return $this->trackerUrl;
  }
  
  public function setSiteId($siteId)
  {
    $this->siteId = $siteId;
  }
  
  public function getSiteId()
  {
    return $this->siteId;
  }
  
  public function setLinkTrackingTimer($linkTrackingTimer)
  {
    $this->linkTrackingTimer = $linkTrackingTimer;
  }
  
  public function getLinkTrackingTimer()
  {
    return $this->linkTrackingTimer;
  }
  
  /**
   * Set array of hostnames or domains to be treated as local.
   * @param array $domains e.g: array('.example1.com', '.example2.com')
   */
  public function setDomains($domains)
  {
    $this->domains = $domains;
  }
  
  public function getDomains()
  {
    return $this->domains;
  }
    
  public function setDownloadClasses($downloadClasses)
  {
    $this->downloadClasses = $downloadClasses;
  }
  
  public function getDownloadClasses()
  {
    return $this->downloadClasses;
  }
    
  public function setDownloadExtensions($downloadExtensions)
  {
    $this->downloadExtensions = $downloadExtensions;
  }
  
  public function getDownloadExtensions()
  {
    return $this->downloadExtensions;
  }
    
  public function setIgnoreClasses($ignoreClasses)
  {
    $this->ignoreClasses = $ignoreClasses;
  }
  
  public function getIgnoreClasses()
  {
    return $this->ignoreClasses;
  }
    
  public function setLinkClasses($linkClasses)
  {
    $this->linkClasses = $linkClasses;
  }
  
  public function getLinkClasses()
  {
    return $this->linkClasses;
  }
  
  public function setForce($force)
  {
    $this->force = $force;
  }
  
  public function isForce()
  {
    return $this->force;
  }
  
  public function setNoInit($noInit)
  {
    $this->noInit = $noInit;
  }
  
  public function isNoInit()
  {
    return $this->noInit;
  }
}