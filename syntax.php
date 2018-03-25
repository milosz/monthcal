<?php
/**
 * Plugin monthcal: Display monthly calendar
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Milosz Galazka <milosz@sleeplessbeastie.eu>
 */
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once (DOKU_INC . 'inc/html.php');
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_monthcal extends DokuWiki_Syntax_Plugin {
 
 
 
   /**
    * Get the type of syntax this plugin defines.
    *
    */
    function getType(){
        return 'substition';
    }
 
   /**
    * Define how this plugin is handled regarding paragraphs.
    *
    */
    function getPType(){
        return 'block';
    }
 
   /**
    * Where to sort in?
    *
    * Doku_Parser_Mode_html	190
    *  --> execute here <--
    * Doku_Parser_Mode_code	200
    */
    function getSort(){
        return 199;
    }
 
 
   /**
    * Connect lookup pattern to lexer.
    *
    */
    function connectTo($mode) {
      $this->Lexer->addSpecialPattern('{{monthcal.*?}}',$mode,'plugin_monthcal');
    }
 
 
   /**
    * Handler to prepare matched data for the rendering process.
    *
    */
    function handle($match, $state, $pos, &$handler){
	$data = array();

	$data['month'] = date('m');
	$data['year'] =  date('Y');

	$provided_data = substr($match, 11, -2);
	$arguments = explode (',', $provided_data);
	foreach ($arguments as $argument) {
		list($key, $value) = explode('=', $argument);
		switch($key) {
			case 'year':
				$data['year'] = $value;
				break;
			case 'month':
				$data['month'] = $value;
				break;
		}
	}
        return $data;
    }

 
   /**
    * Handle the actual output creation.
    *
    */
    function render($mode, &$renderer, $data) {
        if($mode == 'xhtml'){
            $renderer->doc .= $this->create_calendar($data);
            return true;
        }
        return false;
    }


   /**
    * Create calendar
    *
    */
    function create_calendar($data) {
	// date: from -> to
	$date_from = new DateTime($data['year'] . "-" . $data['month'] . "-01");
	$date_to   = (new DateTime($date_from->format('Y-m-d')))->modify('+1 month');

	$date_interval = new DateInterval('P1D');
	$date_range    = new DatePeriod($date_from, $date_interval, $date_to);

	// first day in on ...
	$date_from_on_weekday = $date_from->format('N');

	// language specific
	$weekdays = $this->getLang('monthcal_weekdays_short');
	$months   = $this->getLang('monthcal_months');

	// weekday variable which is used inside each loop
	$wday = 1;

	// html code
	$html = '<table class="monthcal">';

	// header
	$html .= '<tr class="description"><td class="month" colspan="4">' . $months[$date_from->format('m')-1] . '</td><td class="year" colspan="3">' . $date_from->format('Y') . '</td></tr>';

	// weekdays
	$html .= '<tr>';
	foreach($weekdays as $weekday) {
		$html .= '<th>' . $weekday . '</th>';
	}
	$html .= '</tr>';
	$html .= '<tr>';

	// first empty days
	if ($date_from_on_weekday > 1) {
		for($wday;$wday < (7-$date_from_on_weekday + 1);$wday++) {
			$html .= '<td></td>';
		}
	}

	// month days
	foreach($date_range as $date) {
		if ($wday > 7) {
			$wday = 1;
			$html .= "</tr>";
			$html .= "<tr>";
		}
		$html .= '<td>' . $date->format('d') . '</td>';
		$wday++;
	}

	// last empty days
	if ($wday < 8) {
		for($wday;$wday<8;$wday++) {
			$html .= '<td></td>';
		}
	}

	// close table
	$html .= '</table>';

	// return table
        return $html;
    }
}
 
//Setup VIM: ex: et ts=4 enc=utf-8 :
?>
