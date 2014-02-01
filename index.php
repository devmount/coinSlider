<?php

/**
 * Plugin:   coinSlider
 * @author:  HPdesigner (hpdesigner[at]web[dot]de)
 * @version: v1.0.2013-09-19
 * @license: GPL
 * @see:     The fear of the LORD is the beginning of knowledge: but fools despise wisdom and instruction.
 *           - The Bible
 *
 * Plugin created by DEVMOUNT
 * www.devmount.de
 *
**/

if(!defined('IS_CMS')) die();

class coinSlider extends Plugin {
	
	public $admin_lang;
	private $cms_lang;
	var $GalleryClass;
	
	function getContent($value) {

		global $CMS_CONF;
		global $syntax;

		$this->cms_lang = new Language(PLUGIN_DIR_REL.'coinSlider/sprachen/cms_language_'.$CMS_CONF->get('cmslanguage').'.txt');
		
		// get params
		$values = explode('|', $value);
		// id for current coinslider = existing gallery name
		$param_id = rawurlencode(trim($values[0]));
		// build specific coinslider configuration
		$params = array(
			// width for current coinslider
			'width' => trim($values[1]),
			// height for current coinslider
			'height' => trim($values[2]),
			// squares per width
			'spw' => trim($values[3]),
			// squares per height
			'sph' => trim($values[4]),
			// delay between images in ms
			'delay' => trim($values[5]),
			// delay beetwen squares in ms
			'sDelay' => trim($values[6]),
			// opacity of title and navigation
			'opacity' => trim($values[7]),
			// speed of title appereance in ms
			'titleSpeed' => trim($values[8]),
			// effect
			'effect' => '"' . trim($values[9]) . '"',
			// show navigation
			'navigation' => trim($values[10]),
			// show links
			'links' => trim($values[11]),
			// hoverpause
			'hoverPause' => trim($values[12])
		);

		$conf_prev = trim($this->settings->get('prev'));
		$conf_next = trim($this->settings->get('next'));

		// initialize gallery
		include_once(BASE_DIR.'cms/GalleryClass.php');
		$this->GalleryClass = new GalleryClass();
		$this->GalleryClass->initial_Galleries(false, false, false, true);

		// if gallery exists read pictures, otherwise warn
		if(in_array($param_id,$this->GalleryClass->get_GalleriesArray())) 
			$pictures = $this->GalleryClass->get_GalleryImagesArray($param_id);
		else 
			return $this->cms_lang->getLanguageValue("error_nogallery", $param_id);
		if(empty($pictures)) 
			return $this->cms_lang->getLanguageValue("error_nopictures", $param_id);
		
		// initialize return content
		$content = '';
		
		// include jquery and coinslider javascript
		$syntax->insert_jquery_in_head('jquery');
		$content .= '<script language="JavaScript" src="'.URL_BASE.PLUGIN_DIR_NAME.'/coinSlider/js/coin-slider.js"></script>';
		// html for container
		$content .= '<div  style="width:'.$param['width'].'px;"><div id="'.str_replace(' ','_',rawurldecode($param_id)).'">'; 
		foreach($pictures as $picture) {
			$content .= '<a href="'.$this->GalleryClass->get_ImageSrc($param_id, $picture, false).'" target="_blank">
							<img src="'.$this->GalleryClass->get_ImageSrc($param_id, $picture, false).'" />
							<span>'.$this->GalleryClass->get_ImageDescription($param_id, $picture, false).'</span>
						</a>';
		}
		$content .= '</div></div>';

		// call coinslider
		$content .= '<script type="text/javascript">$(document).ready(function() {
						$("#' . str_replace(' ','_',rawurldecode($param_id)) . '").coinslider({';
		foreach($params as $label => $param) {
			if($param != '') $content .= $label . ': ' . $param . ',';
		}

		if (isset($conf_prev) and $conf_prev != '') $content .= 'prevText: "' . $conf_prev . '",';
		if (isset($conf_next) and $conf_next != '') $content .= 'nextText: "' . $conf_next . '",';

		// remove last commata
		$content = substr($content, 0, -1);
		$content .= '});});</script>';
		
		// return coinSlider
		return $content;

	} // function getContent
	
	
	function getConfig() {

		$config = array();

		// prev button
		$config['prev']  = array(
			'type' => 'text',
			'description' => $this->admin_lang->getLanguageValue('config_prev'),
			'maxlength' => '100',
			'size' => '4'
		);

		// next button
		$config['next']  = array(
			'type' => 'text',
			'description' => $this->admin_lang->getLanguageValue('config_next'),
			'maxlength' => '100',
			'size' => '4'
		);

		return $config;
		
	} // function getConfig    
	
	
	function getInfo() {
		global $ADMIN_CONF;

		$this->admin_lang = new Language(PLUGIN_DIR_REL."coinSlider/sprachen/admin_language_".$ADMIN_CONF->get("language").".txt");
				
		$info = array(
			// Plugin-Name + Version
			'<b>coinSlider</b> v1.0.2013-09-19',
			// moziloCMS-Version
			'2.0',
			// Kurzbeschreibung nur <span> und <br /> sind erlaubt
			$this->admin_lang->getLanguageValue('description'), 
			// Name des Autors
			'HPdesigner',
			// Docu-URL
			'http://www.devmount.de/Develop/Mozilo%20Plugins/coinSlider.html',
			// Platzhalter für die Selectbox in der Editieransicht 
			array(
				'{coinSlider|name|width|height|spw|sph|delay|sDelay|opacity|titleSpeed|effect|navigation|links|hoverPause}' => $this->admin_lang->getLanguageValue('placeholder'),
				'{coinSlider|name|width|height||||||||||}' => $this->admin_lang->getLanguageValue('placeholder')
			)
		);
		// return plugin information
		return $info;
		
	} // function getInfo

}

?>