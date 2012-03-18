<?php

include_once('extension/bccie/modules/bccie/basehandler.php');

class eZCountryHandler extends BaseHandler {
	function exportAttribute(&$attribute, $seperationChar) {
		$ret = false;
		$objectAttribute = $attribute->contentObjectAttribute();
		$objectAttributeContent = $attribute->content();
		if($objectAttributeContent['value'])
			$ret = $objectAttributeContent['value'][$attribute->DataText]['Name'];
		return $this->escape($ret, $seperationChar);
	}
}
?>
