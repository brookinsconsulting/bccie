<?php
/**
 * File containing the ezoptionhandler class.
 *
 * @copyright Copyright (C) 1999 - 2012 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */

include_once('extension/bccie/modules/bccie/parser.php');

class eZOptionHandler extends BaseHandler{
       function exportAttribute(&$attribute, $seperationChar) {
       	          	
       	   $content = $attribute->content();
		   
		   $contentObjectAttribute = $attribute->contentObjectAttribute();

		   $xml = simplexml_load_string($contentObjectAttribute->DataText);
		    
		   foreach ( $xml->options as $item )
		   {
		   		 
		   		 foreach ( $item->option as $opcja )
		   		 { 
		   		 		$ok = false;
		   		 		
			   		 	foreach ( (array)$opcja as $key => $value )
			   		 	{
			   		 			if( $ok == true )
			   		 			{
			   		 				$nazwa = $value;
			   		 				$ok = false;
			   		 			}
			   		 		
			   		 			if( is_array($value) )
			   		 			{
			   		 				if( $value['id'] == $attribute->DataInt )
			   		 					$ok = true;
			   		 			}
			   		 	}		   		 	
		   		 }
		   }
		   		 
	       return $this->escape($nazwa, $seperationChar);
       }
}

?>
