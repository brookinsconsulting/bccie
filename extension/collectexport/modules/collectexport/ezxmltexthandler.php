<?php

include_once('extension/collectexport/modules/collectexport/parser.php');

class eZXMLTextHandler extends BaseHandler{
	function exportAttribute(&$attribute, $seperationChar) {
		$content=&$attribute->content();
		return $this->escape($content->XMLData, $seperationChar);
	}
}
?>