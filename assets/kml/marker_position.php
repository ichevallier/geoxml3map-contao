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

// function
function map_point($value){
	
	$geocoder = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";
	if(substr($value, -3, 4) == '000'){

	// paris,lyon,marseille
	$value =  substr($value, 0, 2).'001';
	}
	$value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE',$value);
	$adresse = $value.',france';
	// Requête envoyée à l'API Geocoding
    $query = sprintf($geocoder, urlencode(utf8_encode($adresse)));

    $result = json_decode(file_get_contents($query));
    $json = $result->results[0];
    $position = array
    (
	'lat' => (string) $json->geometry->location->lat,
	'lng' => (string) $json->geometry->location->lng
    );
	return $position;
}
if ($_GET['findpos']) {
	$point = map_point($_GET['findpos']);
	echo json_encode($point);
}
?>