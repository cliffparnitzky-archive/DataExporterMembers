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
 * @copyright  Cliff Parnitzky 2012
 * @author     Cliff Parnitzky/Julian von Bülow
 * @package    DataExporterMembers
 * @license    LGPL
 */

/**
 * Class DataExporterMembers
 *
 * The exporter of member data.
 * @copyright  Cliff Parnitzky 2012
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class DataExporterMembers extends AbstractDataExporter 
{	
	/* Create the export file*/	 
	public function createExportFile($objConfig) 
	{		
		//Datenbankabfrage initialisieren
		$this->import("Database");
		
		//Datei erstellen
		$objFile = $this->createFile($objConfig, "Mitgliederexport " . date("d.m.Y"), 'csv');
		
		//gecheckte Spalten auslesen
		$arrFields = deserialize($objConfig->dataExporterMembersFields);
		$arrGroupsB = deserialize($objConfig->dataExporterMembersGroups);
		$strCsvLine = "";
		
		$gender = $objConfig->gender;
		
		echo "<script>alert('".$gender."');</script>";
		
		//Gruppennamen auslesen
		foreach($arrGroupsB as $i)
		{
			$dbResult = $this->Database->prepare("SELECT name FROM tl_member_group WHERE id=?")->execute($i);			
			
			if($dbResult->next())
				$strCsvLine .= $dbResult->name.";";			
		}
		
		$objFile->append($strCsvLine);	
		$strCsvLine = "";
		
		//Spaltennamen
		foreach($arrFields as $field)		
			$strCsvLine = $strCsvLine.$field.";";					
		
		$objFile->append($strCsvLine);
		$objFile->append("");
		
		//Aus einem Array in dem die Felder gespeichert sind einem String erstellen, indem die Felder durch ',' getrennt sind
		$strSelectedFields = implode(",", $arrFields);	

		//Datenbankabfrage für alle ausgewählten Felder und die Gruppe des Mitglieds
		$dbResult = $this->Database->prepare("SELECT ".$strSelectedFields. ", groups FROM tl_member")->execute();						
		
		//wenn ein Mitglied in mehreren Gruppen ist, soll es trotzdem nur 1x hinzugefügt werden
		$boolAdded = false;
		
		//solange die Datenbank noch Zeilen bereit hat
		while ($dbResult->next()) 
		{		
			$strValue = "";						
			
			//die Gruppen eines einzelnen Mitglieds
			$arrGroupsA = deserialize($dbResult->groups);					
			
			//Prüfe alle Gruppen eines Mitglieds...
			foreach($arrGroupsA as $groupA)			
			{				
				foreach($arrGroupsB as $groupB)
				{
					//...ob es zu einer Gruppe gehört, die der Benutzer im Backend ausgewählt hat
					// außerdem darf der User nicht zweimal in der Tabelle vorkommen. $boolAdded checkt das
					if($groupA == $groupB && $boolAdded == false) 
					{
						foreach($arrFields as $field) //Alle Felder, die der Benutzer im Backend ausgewählt hat...						
							$strValue .= $dbResult->$field.";";		//...sollen in der Tabelle vorkommen							
						
						$boolAdded = true; //das Mitglied ist nun in der Spalte vertreten und wird nicht noch einmal durch einen anderen Gruppenmatch hinzugefügt
					}
				}
			}
			$objFile->append($strValue); //Das Mitglied bekommt nun eine eigene Zeile in der Tabelle
			$boolAdded=false; //Für das nächste Mitglied wird die Variable wieder auf false gesetzt			
		}
							
		
		$objFile->close();
		
		return $objFile->value;
	}
}

?>