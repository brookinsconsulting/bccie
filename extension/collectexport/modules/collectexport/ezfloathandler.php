<?php

include_once('extension/collectexport/modules/collectexport/basehandler.php');

class eZFloatHandler extends BaseHandler {

	function exportAttribute(&$attribute, $seperationChar) {
		return $this->escape($attribute->content(), $seperationChar);
	}

}

?>