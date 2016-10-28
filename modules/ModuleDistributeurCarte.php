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
 * @package	  Frontend
 * @license	  LGPL
 * @filesource
 */

/**
 * Class ModuleMarkerMap
 *
 * Front end module "Marker map".
 * @copyright IRa coding 2015
 * @author	  IRa coding <ic.github@gmail.com>
 * @package	  Controller
 */
class ModuleMarkerMap extends Module {

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_marker_map';

	/**
	 * generate the module
	 * @return string
	 */
	public function generate() {
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### MARKER - MAP ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile() {
		$time = time();

		/* -- Calcul distance -- */
		$objCenter = Database::getInstance()
				->prepare(
						"SELECT center
						FROM tl_distributeur_type
						WHERE published = ?
						"
				)
				-> execute(1,$this->liste_distributeur);

		$split_center =  explode(',',$objCenter->center);

		$latitude  = $split_center[0];
		$longitude = $split_center[1];

		$formule = "(6366*acos(cos(radians($latitude))*cos(radians(`lat`))*cos(radians(`lng`) -radians($longitude))+sin(radians($latitude))*sin(radians(`lat`))))";
		
		/*---- Markers ----*/

		$objmarker = Database::getInstance()
				->prepare(
						"SELECT *
						FROM tl_marker
						WHERE published = ? AND pid = ? AND center!=''
						AND $formule<='50'
							ORDER BY dist ASC"
				)
				-> execute(1,$this->liste_distributeur);
		
		
		if ($objmarker->numRows < 1) {
			$this->Template->noarticle = '<p class="error">marker not found</p>';
			//header('HTTP/1.1 404 Not Found');
			//return;
		} else {
			# echo $this->jumpTo;
			$i=0;
			while ($objmarker->next()) {
				$i++;

				/*---- template ----*/
				$address = '';
				if($objmarker->adresse1!='') $address .= $objmarker->adresse1.' ';
				if($objmarker->adresse2!='') $address .= $objmarker->adresse2.' ';

				$arrMarker[] = array
				(
					'id'				=> $objmarker->id,
					'title'				=> $objmarker->title.''.$objmarker->lon,
					'address'			=> strtolower($address),
					'geocoderCountry'	=> $objmarker->geocoderCountry,
					'center' 			=> $objmarker->center,
                    'type'              => $objmarker->pid,
					'codePostal'		=> $objmarker->codepostal,
					'city'				=> ucfirst(strtolower($objmarker->ville)),
					'tel'				=> $objmarker->telephone,
					'number'			=> $i,
					'distance'			=> number_format($objmarker->dist,2)
				);
			}
			
			$this->Template->Marker_data = $arrMarker;
			$this->Template->Marker_type_center = $objCenter->center;

            // CARTE 
			$GLOBALS['TL_JAVASCRIPT'][] = 'http://maps.google.com/maps/api/js?language=fr&amp;sensor=false';
			$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/geoxml3map-contao/assets/js/map_control.js';
            
            // MIXITUP 
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/geoxml3map-contao/assets/mixitup/jquery.mixitup.min.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/geoxml3map-contao/assets/mixitup/jquery.mixitup-pagination.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/geoxml3map-contao/assets/mixitup/liste.js';
            
		}
	}

}

?>