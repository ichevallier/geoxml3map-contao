<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Contao\Marker'   => 'system/modules/geoxml3map-contao/classes/Marker.php',
	
	// Modules
	'ModuleMarkerMap' => 'system/modules/geoxml3map-contao/modules/ModuleDistributeurCarte.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_marker_map'  => 'system/modules/geoxml3map-contao/templates'
));
