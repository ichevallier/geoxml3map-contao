<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao;


/**
 * Import Markers
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Marker extends \Backend
{

	/**
	 * Return a form to choose a CSV file and import it
	 *
	 * @return string
	 */
	public function importMarkers()
	{
		if (\Input::get('key') != 'import') {
			echo 'nope';
		} //else {echo 'yess';}

		$this->import('BackendUser', 'User');
		$class = $this->User->uploader;

		// See #4086 and #7046
		if (!class_exists($class) || $class == 'DropZone') {
			$class = 'FileUpload';
		}

		/** @var \FileUpload $objUploader */
		$objUploader = new $class();

		// Import CSS
		if (\Input::post('FORM_SUBMIT') == 'tl_recipients_import') {
			set_time_limit(0);
			$arrUploaded = $objUploader->uploadTo('system/tmp');

			if (empty($arrUploaded)) {
				\Message::addError($GLOBALS['TL_LANG']['ERR']['all_fields']);
				$this->reload();
			}

			$time = time();
			$intTotal = 0;
			$intInvalid = 0;

			$strTable = 'tl_marker';
			$strFieldseparator = \Input::post('separator');
			$arrSelectedFields = \Input::post('selected_fields');
			$strPrimaryKey = 'id';
			$strImportMode = \Input::post('import_mode');

			// store the options in $this->arrData
	        $this->arrData = array(
				'tablename'         => $strTable, 
				'primaryKey'        => $strPrimaryKey, 
				'importMode'        => $strImportMode,
				'selectedFields'    => is_array($arrSelectedFields) ? $arrSelectedFields : array(),
				'fieldSeparator'    => $strFieldseparator, 
				// 'fieldEnclosure' => $strFieldenclosure,
	        );

	        // truncate table
	        if ($this->arrData['importMode'] == 'truncate_table') {

	            $this->Database->execute('TRUNCATE TABLE `' . $strTable . '`');
	        }

			foreach ($arrUploaded as $strCsvFile) {
				
				$objFile = new \File($strCsvFile, true);

				if ($objFile->extension != 'csv') {

					\Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension));
					continue;
				}

				// Get separator
				switch (\Input::post('separator')) {
					case 'semicolon':
						$strSeparator = ';';
						break;

					case 'tabulator':
						$strSeparator = "\t";
						break;

					case 'linebreak':
						$strSeparator = "\n";
						break;

					default:
						$strSeparator = ',';
						break;
				}

				$arrRecipients = array();
				$resFile = $objFile->handle;

				
				while (($arrRow = @fgetcsv($resFile, null, $strSeparator)) !== false) {
					//$arrRecipients = array_merge($arrRecipients, $arrRow);
				  	$arrRecipients[] = $arrRow;
				}
			
				// count rows
        		$rows  = 0;
        		$limit_geo = 0;
				//$arrRecipients = array_filter(array_unique($arrRecipients));
				unset ($arrRecipients[0]);
				
				/*echo '<pre>';
				print_r($arrRecipients);
				echo '</pre>';*/

				foreach ($arrRecipients as $strRecipient) {

					$countFields = count($strRecipient);
					

					// si les nombres de champs sont differents 
					if (count($this->arrData['selectedFields']) == $countFields) {

						$limit_geo++;
						if ($limit_geo < 2500) $lieu = $this->getLocation($strRecipient); // attention limite a 2500 requetes / jour
						//else $lieu = '';
						//echo $lieu;
						//$lieu = $this->getLocation($strRecipient);

						$alias = standardize($strRecipient[0]);

						// Creation de l insert 
						$query_fields = "tstamp, alias, ";
						
						$query_insert_fields2 = '"'.time().'", "'.$alias.'",';

						// si import mode = truncate 
						if ($this->arrData['importMode'] == 'truncate_table') {
							
							$row++;
							$query_fields .= "id, ";
							$query_insert_fields2 .= '"'.$row.'",';

						}
						// check si le distributeur existe 
						$objExist = $this->Database->prepare("SELECT COUNT(*) AS count FROM ".$strTable." WHERE alias=?")
												   ->execute($alias);
						if ($objExist->count < 1)
						{						   
							for ($i=0;$i<$countFields;$i++) {	

								$query_fields .= $this->arrData['selectedFields'][$i].", ";
								$query_insert_fields2 .= '"'.$this->myTrim($strRecipient[$i]).'", ';

							}
							$query_insert_fields2 .= '"'.$lieu.'", "1"';
							$query_fields.= 'center, published';
							
							$query = 'INSERT INTO '.$strTable.' ('.$query_fields.' ) values ('.$query_insert_fields2.')';
							
							try {

								$this->Database->execute($query);
							}
							catch (Exception $e) {

								++$intInvalid;
								continue;
							}
						} // end objExist
					} else {
						// verification du nombre de champs
						++$intInvalid;
						continue;
					} 
				}
			}

			//\Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['tl_distributeur']['confirm'], $intTotal));

			if ($intInvalid > 0)
			{
				\Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_marker']['invalid'], $intInvalid));
			}

			\System::setCookie('BE_PAGE_OFFSET', 0, 0);
			$this->reload();
		}

		// Ajout des champs 
		$leschamps = $this->optionsCbSelectedFields();
		$selectedFields ='<fieldset id="ctrl_selected_fields" class="tl_checkbox_container"> <legend><span class="invisible">Champ obligatoire</span>Selection des champs pour l\'import '.$GLOBALS['TL_LANG']['tl_import_from_csv']['selected_fields'].'<span class="mandatory">*</span></legend>';
		$i = 0;
		foreach ($leschamps as $key => $value) {
			$i++;
			# code...
			$selectedFields.='<input name="selected_fields[]" id="opt_selected_fields_'.$i.'" class="tl_checkbox" value="'.$key.'" onfocus="Backend.getScrollOffset()" type="checkbox"> <label for="opt_selected_fields_'.$i.'">'.$value.'</label><br>';
		}
		$selectedFields.='</fieldset>';
		// Return form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=import', '', \Environment::get('request'))).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>
'.\Message::generate().'
<form action="'.ampersand(\Environment::get('request'), true).'" id="tl_recipients_import" class="tl_form" method="post" enctype="multipart/form-data">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_recipients_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
<input type="hidden" name="MAX_FILE_SIZE" value="'.\Config::get('maxFileSize').'">

<div class="tl_tbox">
  <div>'.$selectedFields.'</div>
  <div>
  <h3><label for="separator">'.$GLOBALS['TL_LANG']['MSC']['separator'][0].'</label></h3>
  <select name="separator" id="separator" class="tl_select" onfocus="Backend.getScrollOffset()">
    <option value="comma">'.$GLOBALS['TL_LANG']['MSC']['comma'].'</option>
    <option value="semicolon">'.$GLOBALS['TL_LANG']['MSC']['semicolon'].'</option>
    <option value="tabulator">'.$GLOBALS['TL_LANG']['MSC']['tabulator'].'</option>
    <option value="linebreak">'.$GLOBALS['TL_LANG']['MSC']['linebreak'].'</option>
  </select>'.(($GLOBALS['TL_LANG']['MSC']['separator'][1] != '') ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['separator'][1].'</p>' : '').'
  </div>
  <div>
	  <h3><label><span class="invisible">Champ obligatoire</span>Mode importation<span class="mandatory">*</span></label></h3>
	  <div class="styled_select tl_select"><span>only append data into the target table</span><b><i></i></b></div>
	  <select style="opacity: 0;" name="import_mode" id="ctrl_import_mode" class="tl_select" onfocus="Backend.getScrollOffset()">
		  <option value="append_entries" selected="">Ajout des données dans la table</option>
		  <option value="truncate_table">Supprimer les données dans la table avant import</option>
	  </select>
	  <p title="" class="tl_help tl_tip">Supprimer ou non les données avant l\'import du fichier csv.</p>
  </div>
  <h3>'.$GLOBALS['TL_LANG']['MSC']['source'][0].'</h3>'.$objUploader->generateMarkup().(isset($GLOBALS['TL_LANG']['MSC']['source'][1]) ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['MSC']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_distributeur']['import'][0]).'">
</div>

</div>
</form>';
	}




	/**
	 * Add newsletters to the indexer
	 *
	 * @param array   $arrPages
	 * @param integer $intRoot
	 * @param boolean $blnIsSitemap
	 *
	 * @return array
	 */
	public function getSearchablePages($arrPages, $intRoot=0, $blnIsSitemap=false)
	{
		$arrRoot = array();

		if ($intRoot > 0)
		{
			$arrRoot = $this->Database->getChildRecords($intRoot, 'tl_page');
		}

		$arrProcessed = array();
		$time = \Date::floorToMinute();

		# A completer

		return $arrPages;
	}

	/**
     * option_callback
     * @return array
     */
    public function optionsCbSelectedFields()
    {

        $objFields = $this->Database->listFields('tl_distributeur', 1);
        $arrOptions = array();
        foreach ($objFields as $field)
        {
            if ($field['name'] == 'PRIMARY')
            {
                continue;
            }
            if (in_array($field['name'], $arrOptions))
            {
                continue;
            }
            $arrOptions[$field['name']] = $field['name'] . ' [' . $field['type'] . ']';
        }
        return $arrOptions;
    }

    /**
	 * GetLocation
	 * @param mixed
	 * @param \DataContainer
	 * @return mixed
	 */
    public function getLocation($geoArray)
	{
		$strStreet = $geoArray[1];
        if($geoArray[2]!='') $strStreet.= ' '.$geoArray[2];
        if($geoArray[2]!='') $strStreet.= ' '.$geoArray[3];
        $strCity =  $geoArray[4];
        //$strCountry = $arrCustomValidation['arrayLine']['country'];

        $strStreet = str_replace(' ', '+', $strStreet);
        $strCity = str_replace(' ', '+', $strCity);
        $strAddress = $strStreet . '+' . $strCity;
       
        // Get Position from GoogleMaps
        $arrPos = $this->curlGetCoordinates(sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false', $strAddress));

        if (is_array($arrPos['results'][0]['geometry']))
        {
            $latPos = $arrPos['results'][0]['geometry']['location']['lat'];
            $lngPos = $arrPos['results'][0]['geometry']['location']['lng'];

            // Save geolocation in $arrCustomValidation['value']
            $value = $latPos . ',' . $lngPos;
        }
        else
        {
            // Error handling
            $value = " ";
           /* if ($this->curlErrorMsg != '')
            {
                //$arrCustomValidation['errorMsg'] = $this->curlErrorMsg; 
            }
            else
            {
                $arrCustomValidation['errorMsg'] = sprintf("Setting geolocation for (%s) failed!", $strAddress);
            }
            $arrCustomValidation['hasErrors'] = true;
            $arrCustomValidation['doNotSave'] = true; */
        }
		return $value;
	}

	/**
     * Curl helper method
     * @param $url
     * @return bool|mixed
     */
    public function curlGetCoordinates($url)
    {
        // is cURL installed on the webserver?
        if (!function_exists('curl_init'))
        {
            $this->curlErrorMsg = 'Sorry cURL is not installed on your webserver!';
            return false;
        }

        // Set a timout to avoid the OVER_QUERY_LIMIT
        usleep(25000);

        // Create a new cURL resource handle
        $ch = curl_init();

        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $url);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // Download the given URL, and return output
        $arrOutput = json_decode(curl_exec($ch), true);

        // Close the cURL resource, and free system resources
        curl_close($ch);

        return $arrOutput;
    }

    /**
     * @param string
     * @return string
     */
    private function myTrim($strFieldname)
    {
        return trim($strFieldname, $this->arrData['fieldEnclosure']);
    }
   
}
