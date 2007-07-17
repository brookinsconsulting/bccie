<?php
include_once('extension/collectexport/modules/collectexport/parser.php');

class eZSelectionHandler extends BaseHandler{
       function exportAttribute(&$attribute, $seperationChar) {
                $content=&$attribute->content();
                return $this->escape($content[0], $seperationChar);
       }
}

?>