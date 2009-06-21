<?php
include_once('extension/collectexport/modules/collectexport/parser.php');

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