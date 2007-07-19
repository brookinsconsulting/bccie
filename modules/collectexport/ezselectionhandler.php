<?php
include_once('extension/collectexport/modules/collectexport/parser.php');

class eZSelectionHandler extends BaseHandler{
       function exportAttribute(&$attribute, $seperationChar) {
	        $ret = false;
                
		$content=&$attribute->content();
		$selectionID=$content[0];

		// $selectionAttribute=&$attribute["ContentObjectAttributeID"];
		$contentObjectAttribute = $attribute->contentObjectAttribute();
		$contentClassAttribute = $attribute->contentClassAttribute();
		$contentClassAttributeContent = $contentClassAttribute->content();
		if( isset( $contentClassAttributeContent['options'] ) )
		{
		    $options = $contentClassAttributeContent['options'];
		    $selectionOptionName = $options[$selectionID]['name'];

		    // print("\n\nBegin Options \n\n");
		    // print("Selection: $selectionID\n");
		    // print("Selection Name: $selectionOptionName\n");
		    $ret = $selectionOptionName;
		}
		// print_r("$ret\n");
	       return $this->escape($ret, $seperationChar);
               // return $this->escape($content[0], $seperationChar);
       }
}

?>