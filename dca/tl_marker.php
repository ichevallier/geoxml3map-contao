<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright IRa coding 2015
 * @author	  IRa coding <ic.github@gmail.com>
 * @package	  marker 
 * @license	  LGPL
 * @filesource
 */

/**
 * Table tl_marker
 */
$GLOBALS['TL_DCA']['tl_marker'] = array
	(
	// Config
	'config' => array
		(
		'dataContainer'    => 'Table',
		'ptable'           => 'tl_marker_type',
		'sql' => array
			(
			'keys' => array
				(
				'id' => 'primary'
			)
		)
	),
	// List
	'list' => array
		(
		'sorting' => array
			(
			'mode'            => 1,
			'flag'            => 1,
			'fields'          => array('title'),
			'panelLayout'     => 'filter;sort,search,limit',
			'disableGrouping' => false
		),
		'label' => array
			(
			'fields' => array('title'),
			'format' => '%s',
			
		),
		'global_operations' => array
		(
			'import' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_newsletter_recipients']['import'],
				'href'       => 'key=import',
				'class'      => 'header_css_import',
				'attributes' => 'onclick="Backend.getScrollOffset()"'
			),
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_marker']['edit'],
				'href' 	=> 'act=edit',
				'icon' 	=> 'edit.gif'
			),
			'copy' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_marker']['copy'],
				'href' 	=> 'act=copy',
				'icon' 	=> 'copy.gif'
			),
			'delete' => array
			(
				'label' 		=> &$GLOBALS['TL_LANG']['tl_marker']['delete'],
				'href' 			=> 'act=delete',
				'icon' 			=> 'delete.gif',
				'attributes'	=> 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label' 			=> &$GLOBALS['TL_LANG']['tl_marker']['toggle'],
				'icon' 				=> 'visible.gif',
				'attributes' 		=> 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback' 	=> array('tl_marker', 'toggleIcon')
			),
			'show' => array
			(
				'label' => &$GLOBALS['TL_LANG']['tl_marker']['show'],
				'href' 	=> 'act=show',
				'icon' 	=> 'show.gif'
			)
		)
	),
	// Palettes
	'palettes' => array
	(
        'default' 		=> '{title_legend},title,alias;{address_legend},adresse1,adresse2,codepostal,ville,geocoderCountry,center,lat,lng;{publish_legend},published'
	),

	// Fields
	'fields' => array
	(
		'id' => array(
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
        'pid' => array
        (
            'label'				 	=> &$GLOBALS['TL_LANG']['tl_marker']['pid'],
            'exclude'               => true,
            'search'                => true,
            'inputType'             => 'select',
            'foreignKey'			=> 'tl_marker_type.title',
            'eval'					=> array('style' => 'height:50px','includeBlankOption'=>false, 'tl_class'=>'w50','multiple'=>false),
            'sql'                  	=> "int(10) unsigned NOT NULL default '0'"
        ),
		'title' => array
		(
			'label' 				=> &$GLOBALS['TL_LANG']['tl_marker']['title'],
			'exclude' 				=> true,
			'search' 				=> true,
			'inputType' 			=> 'text',
			'eval' 					=> array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'long'),
			'sql' 					=> "varchar(64) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'				 	=> &$GLOBALS['TL_LANG']['tl_news']['alias'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' 		=> array
											(
												array('tl_marker', 'generateAlias')
											),
			'sql'					=> "varbinary(128) NOT NULL default ''"
		),
		'adresse1' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_marker']['geocoderAddress'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255, 'tl_class'=>'long clr'),
			'sql' 					=> "varchar(255) NOT NULL default ''"
		),
		'adresse2' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_marker']['geocoderAddress'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255, 'tl_class'=>'long clr'),
			'sql' 					=> "varchar(255) NOT NULL default ''"
		),
		'codepostal' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_member']['postal'],
			'exclude'               => true,
			'search'                => true,
			'inputType'             => 'text',
			'eval'                  => array('maxlength'=>32, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
			'sql'                   => "varchar(32) NOT NULL default ''"
		),
		'ville' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_member']['city'],
			'exclude'               => true,
			'filter'                => true,
			'search'                => true,
			'sorting'               => true,
			'inputType'             => 'text',
			'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
			'sql'                   => "varchar(255) NOT NULL default ''"
		),
		'center' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_marker']['center'],
			'exclude'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>64),
			'save_callback' => array
			(
				array('tl_marker', 'generateCoords')
			),
			'sql' => "varchar(64) NOT NULL default ''"
		),
		'lat' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_marker']['latitude'],
			'exclude'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>64),
			'save_callback' => array
			(
				array('tl_marker', 'generateLatitude')
			),
			'sql' => "varchar(64) NOT NULL default ''"
		),
		'lng' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_marker']['longitude'],
			'exclude'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>64),
			'save_callback' => array
			(
				array('tl_marker', 'generateLongitude')
			),
			'sql' => "varchar(64) NOT NULL default ''"
		),
		'geocoderAddress' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_marker']['geocoderAddress'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255, 'tl_class'=>'long clr'),
			'sql' 					=> "varchar(255) NOT NULL default ''"
		),
		'geocoderCountry' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_marker']['geocoderCountry'],
			'exclude'				=> true,
			'filter'				=> true,
			'sorting'				=> true,
			'inputType'				=> 'select',
			'options'				=> $this->getCountries(),
			'eval'					=> array('includeBlankOption'=>true),
			'sql' 					=> "varchar(2) NOT NULL default '1'"
		),
		'published' => array
		(
			'label' 				=> &$GLOBALS['TL_LANG']['tl_marker']['published'],
			'exclude' 				=> true,
			'filter' 				=> true,
			'flag' 					=> 1,
			'inputType' 			=> 'checkbox',
			'eval' 					=> array('doNotCopy' => true),
			'sql' 					=> "char(1) NOT NULL default ''"
		)
	)
);

/**
 * Class tl_marker
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  IRa coding 2015
 * @author     IRa coding <http://www.poisson-soluble.com>
 * @package	Controller
 */
class tl_marker extends Backend {

	/**
	 * Import the back end user object
	 */
	public function __construct() {
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	/**
	 * Marker list for the backend view
	 * @param array
	 * @return string
	 */
	/* public function listmarkerArticles($arrRow)
	{
		return '<div class="tl_content_left">' . $arrRow['title'] . ' <span style="color:#b3b3b3;padding-left:3px">[' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']) . ']</span></div>';
	}
	*/
	 public function generateAlias($varValue, DataContainer $dc) {
		$autoAlias = false;

		// Generate alias if there is none
		if (!strlen($varValue)) {
			$autoAlias = true;
			$key = $dc->activeRecord->title;
			if(strlen($dc->activeRecord->title) > 0) {
				$keyAlias = $key;
			}
			$varValue = standardize($keyAlias);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_marker WHERE id=? OR alias=?")
				->execute($dc->id, $varValue);

		// Check whether the page alias exists
		if ($objAlias->numRows > 1) {
			if (!$autoAlias) {
				throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
			}

			$varValue .= '-' . $dc->id;
		}

		return $varValue;
	}
	/**
	 * Return the "toggle visibility" button
	 * @param array
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes) {
		if (strlen($this->Input->get('tid'))) {
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_marker::published', 'alexf')) {
			return '';
		}

		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

		if (!$row['published']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}

	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible) {
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_marker::published', 'alexf')) {
			$this->log('Not enough permissions to publish/unpublish FAQ ID "' . $intId . '"', 'tl_marker toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_marker', $intId);

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_marker']['fields']['published']['save_callback'])) {
			foreach ($GLOBALS['TL_DCA']['tl_marker']['fields']['published']['save_callback'] as $callback) {
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_marker SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
				->execute($intId);

		$this->createNewVersion('tl_marker', $intId);
	}
	
	public function pagePicker(DataContainer $dc)
	{
		return ' <a href="contao/page.php?do='.Input::get('do').'&amp;table='.$dc->table.'&amp;field='.$dc->field.'&amp;value='.str_replace(array('{{link_url::', '}}'), '', $dc->value).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\'ctrl_'.$dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '').'\',\'self\':this});return false">' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}
	
	/**
	 * Dynamically set the "isGallery" or "isDownloads" flag depending on the type
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 */
	public function setFileTreeFlags($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord)
		{
			if ($dc->activeRecord->type == 'gallery')
			{
				$GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['isGallery'] = true;
			}
			elseif ($dc->activeRecord->type == 'downloads')
			{
				$GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['isDownloads'] = true;
			}
		}

		return $varValue;
	}
	/**
	 * GenerateCoords
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 */
	function generateCoords($varValue, DataContainer $dc) 
	{
		if (!$varValue)
		{
			$objGeo = $this->Database->prepare("SELECT adresse1,adresse2,codepostal,ville,geocoderCountry FROM tl_marker WHERE id=?")
									   ->limit(1)
									   ->execute($dc->id);
			
			if ($objGeo->adresse1)
			{
				
				$coords = $this->geocode($objGeo->adresse1." ".$objGeo->adresse2." ".$objGeo->codepostal." ".$objGeo->ville, null, $strLang = 'fr', $objGeo->geocoderCountry);
				
				if($coords)
				{
					$varValue = $coords['lat'] . ',' . $coords['lng'];
					
				}
				elseif(function_exists("curl_init"))
				{
					$strGeoURL = 'http://maps.google.com/maps/api/geocode/xml?address='.str_replace(' ', '+', $objGeo->adresse1." ".$objGeo->adresse2." ".$objGeo->codepostal." ".$objGeo->ville).'&sensor=false'.($objGeo->geocoderCountry ? '&region='.$objGeo->geocoderCountry : '');

					$curl = curl_init();
					if($curl)
					{
						if(curl_setopt($curl, CURLOPT_URL, $strGeoURL) && curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1) && curl_setopt($curl, CURLOPT_HEADER, 0))
						{
							$curlVal = curl_exec($curl);
							curl_close($curl);
							$xml = new SimpleXMLElement($curlVal);
							if($xml)
							{
								$varValue = $xml->result->geometry->location->lat . ',' . $xml->result->geometry->location->lng;
							}
						}
					} else {
						$varValue = $GLOBALS['TL_LANG']['tl_dlh_googlemaps']['references']['noCurl'];
					}
				}/**/
			} else {
				$varValue = $GLOBALS['TL_LANG']['tl_dlh_googlemaps']['references']['noCoords'];
			}
			if (!$varValue)
			{
				$varValue = $GLOBALS['TL_LANG']['tl_dlh_googlemaps']['references']['noCoords'];
			}
		}
		return $varValue;
	}

	/**
	 * Get geo coordinates from address, thanks to Oliver Hoff <oliver@hofff.com>
	 * @param array
	 * @return string
	 */
	private $arrGeocodeCache = array();
	public function geocode($varAddress, $blnReturnAll = false, $strLang = 'en', $strRegion = null, array $arrBounds = null) {
		
		if(is_array($varAddress))
			$varAddress = implode(' ', $varAddress);
		
		$varAddress = trim($varAddress);
		
		if(!strlen($varAddress) || !strlen($strLang))
			return;
		
		if($strRegion !== null && !strlen($strRegion))
			return;
			
		if($arrBounds !== null) {
			if(!is_array($arrBounds) || !is_array($arrBounds['tl']) || !is_array($arrBounds['br'])
			|| !is_numeric($arrBounds['tl']['lat']) || !is_numeric($arrBounds['tl']['lng'])
			|| !is_numeric($arrBounds['br']['lat']) || !is_numeric($arrBounds['br']['lng']))
				return;
		}
		
		$strURL = sprintf(
			'http://maps.google.com/maps/api/geocode/json?address=%s&sensor=false&language=%s&region=%s&bounds=%s',
			urlencode($varAddress),
			urlencode($strLang),
			strlen($strRegion) ? urlencode($strRegion) : '',
			$arrBounds ? implode(',', $arrBounds['tl']) . '|' . implode(',', $arrBounds['br']) : ''
		);
		
		if(!isset($this->arrGeocodeCache[$strURL])) {
			$arrGeo = json_decode(file_get_contents($strURL), true);
			$this->arrGeocodeCache[$strURL] = $arrGeo['status'] == 'OK' ? $arrGeo['results'] : false;
		}
		
		if(!$this->arrGeocodeCache[$strURL])
			return;
		
		return $blnReturnAll ? $this->arrGeocodeCache[$strURL] : array(
			'lat' => $this->arrGeocodeCache[$strURL][0]['geometry']['location']['lat'],
			'lng' => $this->arrGeocodeCache[$strURL][0]['geometry']['location']['lng']
		);
	}
	/**
	 * GenerateLatitude
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 */
	function generateLatitude($varValue, DataContainer $dc) 
	{
		if (!$varValue)
		{
			$objGeolat = $this->Database->prepare("SELECT center FROM tl_marker WHERE id=?")
									   ->limit(1)
									   ->execute($dc->id);
			$split_center = explode(',',$objGeolat->center);
			return $split_center[0];
		}
	}
	/**
	 * GenerateLongitude
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 */
	function generateLongitude($varValue, DataContainer $dc) 
	{
		if (!$varValue)
		{
			$objGeolng = $this->Database->prepare("SELECT center FROM tl_marker WHERE id=?")
									   ->limit(1)
									   ->execute($dc->id);
			$split_center = explode(',',$objGeolng->center);
			return $split_center[1];
		}
	}
/*	public function pagePicker(DataContainer $dc)
	{
		return ' <a href="contao/page.php?do='.Input::get('do').'&amp;table='.$dc->table.'&amp;field='.$dc->field.'&amp;value='.str_replace(array('{{link_url::', '}}'), '', $dc->value).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\'ctrl_'.$dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '').'\',\'self\':this});return false">' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}*/

}

?>