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
	$html = "";
        return $html;
    }
}
 
//Setup VIM: ex: et ts=4 enc=utf-8 :
?>
