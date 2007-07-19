<?php

include_once('extension/collectexport/modules/collectexport/basehandler.php');

class eZTextHandler extends BaseHandler{
    function exportAttribute(&$attribute, $seperationChar) {
        $content=&$attribute->content();

	return $this->escape($content, $seperationChar);
        //return $this->escape($content);
        //return utf8_decode($content);
    }
}
?>