<?php
require_once('raphaelizer.class.php');


class raphaelizerPiwikStats extends raphaelizer
{
  private $data;
  private $stats;
  private $header_date;
  private $header_type;
  private $nb_columns;
  private $max;

  private $color_visits           = '#3c9ed0';
  private $color_uniq_visitors    = '#14568a';
  private $color_actions          = '#5ab8ef';
  
  public function __construct($id='stats_canvas')
  {
    parent::__construct(750, 260, $id);
    $this->stats = array();
  }

  public function setStats($stats = array())
  {
    $this->stats = 
      array (
        '2010-07-30' => array (
          'nb_actions' => 300,
          'nb_uniq_visitors' => 40,
          'nb_visits' => 50,
        ),
        '2010-07-31' => array (
          'nb_actions' => 200,
          'nb_uniq_visitors' => 8,
          'nb_visits' => 23,
        ),
        '2010-08-01' => array (
          'nb_actions' => 344,
          'nb_uniq_visitors' => 10,
          'nb_visits' => 42,
        ),
        '2010-08-02' => array (
          'nb_actions' => 892,
          'nb_uniq_visitors' => 52,
          'nb_visits' => 106,
        ),
        '2010-08-03' => array (
          'nb_actions' => 2040,
          'nb_uniq_visitors' => 133,
          'nb_visits' => 304,
        ),
        '2010-08-04' => array (
          'nb_actions' => 3600,
          'nb_uniq_visitors' => 240,
          'nb_visits' => 670,
        ),
        '2010-08-05' => array (
          'nb_actions' => 1201,
          'nb_uniq_visitors' => 87,
          'nb_visits' => 103,
        )
    );
    $this->stats = $stats;
    $this->setData();
  }

  public function canvas($bgcolor=FALSE)
  {
    parent::canvas($bgcolor);
    // draw bg stripes
    $stripe_height = 50;
    for ($i=0;$i<4;$i++)
    {
      $bgcolor = ($i%2) ? '#eff9f9' : '#dfe9e9';
      $this->rect(10,($i*$stripe_height + 10),($this->w - 20),$stripe_height,array('fill' => $bgcolor, 'stroke-width' => 0));
    }
    $this->path(
      array(
        0 => array('x' => 10, 'y' => 211),
        1 => array('x' => 745, 'y' => 211)
      ), 
      array(
        'stroke-width'  => '1', 
        'stroke'        => '#bbb'
      )
    );
    $this->drawScale();
    $this->drawCaptions();
    $this->drawStatBars();
  }

  public function drawCaptions()
  {
    $i=0;
    $offset_x         = 100;
    $segment_width    = floor(650 / $this->nb_columns)-2;
    foreach($this->header_date as $date)
    {
      $this->text($offset_x + round($segment_width/2) + ($segment_width * $i), 225, $this->convertPiwikDate($date),array(
        'font'        => 'Helvetica, Arial, sans-serif',
        'font-size'   => '12',
        'font-weight' => 'bold'
      ));
      $i++;
    }
  }

  public function drawScale()
  {
    $nb_actions_step = $this->max['nb_actions'] / 4;
    $nb_visits_step = $this->max['nb_visits'] / 4;
    
    for ($i = 4; $i > 0; $i--)
    {
      $this->text(80,228 - (50 * $i),$nb_actions_step * $i,
        array(
          'text-anchor' => 'end', 
          'fill'        => $this->color_actions, 
          'font-weight' => 'bold',
          'font-size'   => '12'
        ));
      $this->text(80,243 - (50 * $i),$nb_visits_step * $i,
        array(
          'text-anchor' => 'end', 
          'fill'        => $this->color_uniq_visitors, 
          'font-weight' => 'bold',
          'font-size'   => '12'
        ));
        
    }
  }

  public function drawStatBars()
  {
    if (!$this->nb_columns) {
      return;
    }
    $segment_width    = floor(650 / $this->nb_columns)-2;
    $bar_width        = floor($segment_width / 3);
    $offset_x         = 100;
    $offset_y         = 210;

    $actions_ratio = 200 / $this->max['nb_actions'];
    $visits_ratio  = 200 / $this->max['nb_visits'];
    $uniq_visitors_ratio  = 200 / $this->max['nb_visits']; // visits & unique visitors share the same ratio

    for( $i=0; $i < $this->nb_columns; $i++ )
    {

      // segment divider
      $this->path(
        array(
          array('x' => ($offset_x + (($segment_width + 1) * $i)), 'y' => 10),
          array('x' => ($offset_x + (($segment_width + 1) * $i)), 'y' => 210),
        ),
        array('stroke' => '#eff9f9', 'stroke-width' => '2')
      );
      // nb_actions
      $h = round($this->data[$i]['nb_actions'] * $actions_ratio);
      $x = $offset_x + $segment_width * $i + $bar_width * 2 + $i;
      $y = $offset_y - $h;
      $this->rect($x-8,$y-1,$bar_width+5,$h,array('fill' => '#eff9f9', 'stroke-width' => '0'));
      $this->rect($x-7,$y,$bar_width+3,$h,array('fill' => $this->color_actions, 'stroke-width' => '0'));

      if ($h > 12) 
      {
        $this->text($x + $bar_width - 8 , $y+7, $this->data[$i]['nb_actions'],array(
          'fill'        => '#fff',
          'font'        => 'Helvetica, Arial, sans-serif',
          'font-size'   => '9',
          'text-anchor' => 'end'
        ));
      }
      else 
      {
        $this->text($x + $bar_width - 8, $y-5, $this->data[$i]['nb_actions'],array(
          'fill'        => $this->color_actions,
          'font'        => 'Helvetica, Arial, sans-serif',
          'font-size'   => '9',
          'text-anchor' => 'end'
        ));
      }

      // nb_visits
      $h = round($this->data[$i]['nb_visits'] * $visits_ratio);
      $x = $offset_x + $segment_width * $i + $bar_width + $i;
      $y = $offset_y - $h;
      $this->rect($x-6,$y-1,$bar_width+5,$h,array('fill' => '#eff9f9', 'stroke-width' => '0'));
      $this->rect($x-5,$y,$bar_width+3,$h,array('fill' => $this->color_visits, 'stroke-width' => '0'));
      if ($h > 12) 
      {
        $this->text($x + $bar_width - 6, $y+7, $this->data[$i]['nb_visits'],array(
          'fill'        => '#fff',
          'font'        => 'Helvetica, Arial, sans-serif',
          'font-size'   => '9',
          'text-anchor' => 'end'
        ));
      }
      else 
      {
        $this->text($x + $bar_width - 12, $y-5, $this->data[$i]['nb_visits'],array(
          'fill'        => $this->color_visits,
          'font'        => 'Helvetica, Arial, sans-serif',
          'font-size'   => '9',
          'text-anchor' => 'end'
        ));
      }
      // nb_uniq_visitors
      $h = round($this->data[$i]['nb_uniq_visitors'] * $uniq_visitors_ratio);
      $x = $offset_x + $segment_width * $i + $i;
      $y = $offset_y - $h;
      $this->rect($x+1,$y-1,$bar_width+5,$h,array('fill' => '#eff9f9', 'stroke-width' => '0'));
      $this->rect($x+2,$y,$bar_width+3,$h,array('fill' => $this->color_uniq_visitors, 'stroke-width' => '0'));
      if ($h > 12) 
      {
        $this->text($x + $bar_width - 2, $y+7, $this->data[$i]['nb_uniq_visitors'],array(
          'fill'        => '#fff',
          'font'        => 'Helvetica, Arial, sans-serif',
          'font-size'   => '9'  ,
          'text-anchor' => 'end'
        ));
      }
      else 
      {
        $this->text($x + $bar_width - 8, $y-5, $this->data[$i]['nb_uniq_visitors'],array(
          'fill'        => $this->color_uniq_visitors,
          'font'        => 'Helvetica, Arial, sans-serif',
          'font-size'   => '9',
          'text-anchor' => 'end'
        ));
      }
    }
  }

  public function setData()
  {
    $i=0;
    $max = array(
      'total'             => 1,     // we don't wanna divide by zero
      'nb_actions'        => 1,
      'nb_uniq_visitors'  => 1,
      'nb_visits'         => 1
    );
    $this->header_date  = array();
    $this->header_type  = array();
    $this->data         = array();

    foreach($this->stats as $date => $values)
    {
      $this->header_date[$i] = $date;
      foreach($values as $k => $v) {
        $this->header_type[$k] = $k;
        $this->data[$i][$k] = $v;
        if ($v > $max['total'])
        {
          $max['total'] = $v;
        }
        if ($v > $max[$k])
        {
          $max[$k] = $v;
        }
      }
      $i++;
    }
    $this->max        = $this->normalizeMax($max);
    $this->nb_columns = count($this->header_date);
  }

  private function normalizeMax($max)
  {
    foreach ($max as &$m)
    {
      $len = strlen($m)-1;
      $first = substr($m,0,1);
      $first += 1;
      while (floor(($first / 4) * 10) != ($first / 4) * 10)
      {
        $first += 1;
      }
      $m = $first;
      for($i=0;$i < $len;$i++)
      {
        $m .= '0';
      }
    }
    return $max;
  }

  public function getData()
  {
    return $this->data;
  }

  public function getMax()
  {
    return $this->max;
  }

  public function getNbColumns()
  {
    return $this->nb_columns;
  }

  public function getHeaderDate()
  {
    return $this->header_date;
  }

  public function getHeaderType()
  {
    return $this->header_type;
  }
  
  private function convertPiwikDate($str)
  {
    $retval = $str;
    $date = date_parse($str);
    if (strpos($str,'to')) // probably a week
    {
      $retval = 'KW '.date('W', mktime(0,0,0,$date['month'], $date['day'], $date['year'])).', '.$date['year'];
    } else {
      if (!$date['error_count'] && strlen($str) == 10) // this is a day
      {
        $retval = date('j.n.Y', mktime(0,0,0,$date['month'], $date['day'], $date['year']));
      }
      if (!$date['error_count'] && strlen($str) == 7) // this is a day
      {
        $retval = date('M Y', mktime(0,0,0,$date['month'], 1, $date['year']));
      }
      
    }
    return $retval;
  }

} // end class