<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * @package Markers
 * @link    http://www.contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['markerMap'] = '{title_legend},name,type,headline;{select_legend},marker_list;{config_legend},align,space,cssID';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['marker_list'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['list'],
	'exclude'                 => true,
	'inputType'               => 'radio',
	'foreignKey' 			  => 'tl_marker_type.title',
	'eval'                    => array('includeBlankOption'=>true,'multiple'=>false, 'mandatory'=>false),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

?>