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
 * @author    IRa coding <ic.github@gmail.com>
 * @package   markers
 * @license   LGPL
 * @filesource
 */

/**
 * Table tl_marker_type
 */
$GLOBALS['TL_DCA']['tl_marker_type'] = array
    (
	// Config
    'config' => array
        (
        'dataContainer'    => 'Table',
        'ctable'           => array('tl_marker'),
        'switchToEdit'     => true,
        'enableVersioning' => true,
        'sql'              => array
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
			'mode'                    => 1,
			'flag'                    => 1,
			'fields'                  => array('title'),
			'panelLayout'             => 'filter;sort,search,limit',
			'disableGrouping'		  => false
        ),
        'label' => array
            (
            'fields' => array('title'),
            'format' => '%s'
        ),
        'global_operations' => array
            (
            'all' => array
                (
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
            (
            'edit' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_marker_type']['edit'],
                /*'href' => 'act=edit',*/
				'href' => 'table=tl_distributeur',
                'icon' => 'edit.gif'
            ),
            'editheader' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_slideshow2']['editheader'],
                'href'                => 'act=edit',
                'icon'                => 'header.gif',
                'attributes'          => 'class="edit-header"'
            ),      
            'copy' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_marker_type']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ),
            'delete' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_marker_type']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_marker_type']['toggle'],
                'icon' => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array('tl_marker_type', 'toggleIcon')
            ),
            'show' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_marker_type']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            )
        )
    ),
    // Palettes
    'palettes' => array
        (
		'default' => '{title_legend},title,alias;{publish_legend},published'
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
        'title' => array
            (
            'label'         => &$GLOBALS['TL_LANG']['tl_marker_type']['title'],
            'exclude'       => true,
            'search'        => true,
            'inputType'     => 'text',
            'eval'          => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'long'),
            'sql'           => "varchar(255) NOT NULL default ''"
        ),
		'alias' => array
		(
			'label'         => &$GLOBALS['TL_LANG']['tl_news']['alias'],
			'exclude'       => true,
			'search'        => true,
			'inputType'     => 'text',
			'eval'          => array('rgxp'=>'alias', 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' => array
			(
				array('tl_marker_type', 'generateAlias')
			),
			'sql'           => "varbinary(128) NOT NULL default ''"
		),
        'center' => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_distributeur']['center'],
            'exclude'       => true,
            'inputType'     => 'text',
            'eval'          => array('maxlength'=>64),
            'sql'           => "varchar(64) NOT NULL default ''"
        ),
        'published' => array
            (
            'label'         => &$GLOBALS['TL_LANG']['tl_marker_type']['published'],
            'exclude'       => true,
            'filter'        => true,
            'flag'          => 1,
            'inputType'     => 'checkbox',
            'eval'          => array('doNotCopy' => true),
            'sql'           => "char(1) NOT NULL default ''"
        )
    )
);

/**
 * Class tl_marker_type
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  IRa coding 2015
 * @author     IRa coding <ic.github@gmail.com>
 * @package    Controller
 */
class tl_marker_type extends Backend {

    /**
     * Import the back end user object
     */
    public function __construct() {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Compile Videos for the backend view
     * @param array
     * @return string
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

        $objAlias = $this->Database->prepare("SELECT id FROM tl_marker_type WHERE id=? OR alias=?")
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
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes) {
        if (strlen($this->Input->get('tid'))) {
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_marker_type::published', 'alexf')) {
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_marker_type::published', 'alexf')) {
            $this->log('Not enough permissions to publish/unpublish FAQ ID "' . $intId . '"', 'tl_marker_type toggleVisibility', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        $this->createInitialVersion('tl_marker_type', $intId);

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_marker_type']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_marker_type']['fields']['published']['save_callback'] as $callback) {
                $this->import($callback[0]);
                $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_marker_type SET tstamp=" . time() . ", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
                ->execute($intId);

        $this->createNewVersion('tl_marker_type', $intId);
    }
}

?>