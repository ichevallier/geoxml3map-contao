<?php
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
 * @copyright  IRa coding 2015
 * @author     IRa coding <ic.github@gmail.com>
 * @package    Markers
 * @license    LGPL
 * @filesource
 */

/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once('../../../../initialize.php');

// TOUS
if ($_GET['action']=='all') {

	$objmarker = Database::getInstance()
					->prepare(
							"SELECT *
							FROM tl_marker
							WHERE published = ? AND center!=''
							ORDER BY id DESC"
					)
					-> execute(1);
}else{	
	if ($_GET['action']=='pos') {

	/* -- Calcul distance -- */
	
	$latitude  = $_GET['lat'];
	$longitude = $_GET['lng'];
	

	$formule = "(6366*acos(cos(radians($latitude))*cos(radians(`lat`))*cos(radians(`lng`) -radians($longitude))+sin(radians($latitude))*sin(radians(`lat`))))";

	$objmarker = Database::getInstance()
				->prepare(
						"SELECT id,title,center,adresse1,adresse2,codepostal,ville,geocoderCountry,$formule AS dist 
						FROM tl_marker
						WHERE published = ? AND pid = ? AND center!=''
						AND $formule<='50'
						ORDER BY dist ASC"
				)
				-> execute(1,$_GET['d']);

	} // $get pos
}	
		if ($objmarker->numRows < 1) {
			//$this->Template->noarticle = '<p class="error">Marker not found.</p>';
			//header('HTTP/1.1 404 Not Found');
			//return;

		} else {
			
			$i=-1;
			while ($objmarker->next()) { 
				$i++;
				$address = '';
				if($objmarker->adresse1!='') $address .= $objmarker->adresse1.' ';
				if($objmarker->adresse2!='') $address .= $objmarker->adresse2.' ';
				$split_center = explode(',',$objmarker->center);
				$center = $split_center[1].','.$split_center[0];
				
				$arrMarker[] = array
				(
					'id'				=> $objmarker->id,
					'title'				=> $objmarker->title,
					'address'			=> strtolower($address),
					'geocoderCountry'	=> $objmarker->geocoderCountry,
					'center' 			=> $objmarker->center,
                    'type'              => $objmarker->pid, // 1
					'codePostal'		=> $objmarker->codepostal,
					'city'				=> ucfirst(strtolower($objmarker->ville)),
					'number'			=> $i,
					'distance'			=> number_format($objmarker->dist,2)
				);
			}
			$content = $arrMarker;
			echo json_encode($content);
		}
?>