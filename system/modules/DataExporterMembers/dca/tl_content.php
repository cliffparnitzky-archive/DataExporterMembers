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
 * @copyright  Cliff Parnitzky 2012
 * @author     Cliff Parnitzky
 * @package    DataExporterMembers
 * @license    LGPL
 */

/**
 * Add palettes to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['exporter_DataExporterMembers'] = 'dataExporterMembersFields, dataExporterMembersGroups, isActive, gender';

/**
 * Add fields to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['dataExporterMembersFields'] = array
(
	'label'              => &$GLOBALS['TL_LANG']['tl_content']['dataExporterMembersFields'],
	'exclude'            => true,
	'inputType'          => 'checkboxWizard',
	'options_callback'   => array('DataExporterMembersContentHelper', 'getMemberFields'), 
	'eval'               => array('mandatory'=>true, 'multiple'=>true) 
);

$GLOBALS['TL_DCA']['tl_content']['fields']['dataExporterMembersGroups'] = array
(
	'label'              => &$GLOBALS['TL_LANG']['tl_content']['dataExporterMembersGroups'],
	'exclude'            => true,
	'inputType'          => 'checkboxWizard',
	'foreignKey'         => 'tl_member_group.name',
	'eval'               => array('mandatory'=>true, 'multiple'=>true) 
);
/*
$GLOBALS['TL_DCA']['tl_content']['fields']['isActive'] = array
(
	'label'              => &$GLOBALS['TL_LANG']['tl_content']['isActive'],
	'exclude'            => true,
	'inputType'          => 'radio',
	'options'			 => array( "Ja", "Nein"),
	'eval'               => array('mandatory'=>true, 'multiple'=>true, 'tl_class' => 'w50') 
);
*/

$GLOBALS['TL_DCA']['tl_content']['fields']['gender'] = array
(
	'label'              => &$GLOBALS['TL_LANG']['tl_content']['gender'],	
	'inputType'          => 'radio',
	'options'			 => array("Nein", "Nur Männer", "Nur Frauen"),
	'eval'               => array('mandatory'=>true, 'multiple'=>true, 'tl_class' => 'w50') 
);

/**
 * Class DataExporterMembersContentHelper
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Cliff Parnitzky 2012
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class DataExporterMembersContentHelper extends Backend {

	/**
	 * Return all member fields as HTML drop down menu
	 * @return array
	 */
	public function getMemberFields() {
		$this->loadLanguageFile('tl_member');
		$this->loadDataContainer('tl_member');

		$arrReturn = array();

		// Get all excluded fields
		foreach ($GLOBALS['TL_DCA']['tl_member']['fields'] as $k=>$v) {
			$arrReturn[$k] = strlen($v['label'][0]) ? $v['label'][0] : $k;
		}
		asort($arrReturn);
		return $arrReturn;
	}
}

?>