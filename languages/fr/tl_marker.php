<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * Legends
 */

//Back end modules
//$GLOBALS['TL_LANG']['MOD']['marker'] = array('markers', 'Gérez les markers.');

/**
 * Operations
 */
$GLOBALS['TL_LANG']['tl_marker']['new'][0]        = 'Nouveau marker';
$GLOBALS['TL_LANG']['tl_marker']['new'][1]        = 'Créer un nouveau marker';
$GLOBALS['TL_LANG']['tl_marker']['show'][0]       = 'Détails du marker';
$GLOBALS['TL_LANG']['tl_marker']['show'][1]       = 'Afficher les détails du marker ID %s';
$GLOBALS['TL_LANG']['tl_marker']['edit'][0]       = 'Editer le marker';
$GLOBALS['TL_LANG']['tl_marker']['edit'][1]       = 'Editer le marker ID %s';
$GLOBALS['TL_LANG']['tl_marker']['copy'][0]       = 'Dupliquer le marker';
$GLOBALS['TL_LANG']['tl_marker']['copy'][1]       = 'Dupliquer le marker ID %s';
$GLOBALS['TL_LANG']['tl_marker']['cut'][0]        = 'Déplacer le marker';
$GLOBALS['TL_LANG']['tl_marker']['cut'][1]        = 'Déplacer le marker ID %s';
$GLOBALS['TL_LANG']['tl_marker']['delete'][0]     = 'Supprimer le marker';
$GLOBALS['TL_LANG']['tl_marker']['delete'][1]     = 'Supprimer le marker ID %s';
$GLOBALS['TL_LANG']['tl_marker']['toggle'][0]     = 'Publier / dé-publier le marker';
$GLOBALS['TL_LANG']['tl_marker']['toggle'][1]     = 'Publier / dé-publier le marker ID %s';
$GLOBALS['TL_LANG']['tl_marker']['editheader'][0] = 'Editer l\'en-tête de l\'archive';
$GLOBALS['TL_LANG']['tl_marker']['editheader'][1] = 'Editer l\'en-tête de cette archive';
$GLOBALS['TL_LANG']['tl_marker']['editmeta'][0]   = 'Editer les paramètres du marker';
$GLOBALS['TL_LANG']['tl_marker']['editmeta'][1]   = 'Editer les paramètres du marker';
$GLOBALS['TL_LANG']['tl_marker']['pasteafter'][0] = 'Coller dans l\'archive';
$GLOBALS['TL_LANG']['tl_marker']['pasteafter'][1] = 'Coller après le marker ID %s';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_marker']['marker'][0]          = 'Description';
$GLOBALS['TL_LANG']['tl_marker']['marker'][1]          = 'champ libre à remplir';
$GLOBALS['TL_LANG']['tl_marker']['title'][0]           = 'Nom';
$GLOBALS['TL_LANG']['tl_marker']['alias'][0]           = 'Alias';
$GLOBALS['TL_LANG']['tl_marker']['geocoderAddress'][0] = 'Adresse';
$GLOBALS['TL_LANG']['tl_marker']['ville'][0]           = 'ville';
$GLOBALS['TL_LANG']['tl_marker']['geocoderCountry'][0] = 'Pays';
$GLOBALS['TL_LANG']['tl_marker']['center'][0]          = 'Latitude et Longitude';
$GLOBALS['TL_LANG']['tl_marker']['description'][0]     = 'Description'; 
$GLOBALS['TL_LANG']['tl_marker']['addImage'][0]        = 'Ajouter une image';
$GLOBALS['TL_LANG']['tl_marker']['communes'][0]        = 'Commune(s)/Département(s)';
$GLOBALS['TL_LANG']['tl_marker']['pid'][0]             = 'Type';
$GLOBALS['TL_LANG']['tl_marker']['multiSRC'][0]        = 'Galerie';
$GLOBALS['TL_LANG']['tl_marker']['etat'][0]            = 'Statut';
$GLOBALS['TL_LANG']['tl_marker']['published'][0]       = 'Publier';

$GLOBALS['TL_LANG']['tl_marker']['title_legend']       = 'Titre';
$GLOBALS['TL_LANG']['tl_marker']['date_legend']        = 'Date et heure';
$GLOBALS['TL_LANG']['tl_marker']['teaser_legend']      = 'Sous-titre et accroche';
$GLOBALS['TL_LANG']['tl_marker']['text_legend']        = 'Texte de l\'actualité';
$GLOBALS['TL_LANG']['tl_marker']['image_legend']       = 'Paramètres de l\'image';
$GLOBALS['TL_LANG']['tl_marker']['carte'][0]           = 'Son département';
$GLOBALS['TL_LANG']['tl_marker']['technique_legend']   = 'Détails techniques';
$GLOBALS['TL_LANG']['tl_marker']['address_legend']     = 'Coordonnées';
$GLOBALS['TL_LANG']['tl_marker']['docs_legend']        = 'Documents';
$GLOBALS['TL_LANG']['tl_marker']['publish_legend']     = 'Publication';

/* Import */
$GLOBALS['TL_LANG']['tl_marker']['import'][0]          = 'CSV import';
$GLOBALS['TL_LANG']['tl_marker']['import'][1]          = 'Importer les markers à partir d\'un fichier CSV';
$GLOBALS['TL_LANG']['tl_marker']['invalid']            = '%s entrées incorrectes ont été omises';
$GLOBALS['TL_LANG']['tl_marker']['confirm']            = '%s nouveaux distibuteurs ont été importés';

?>