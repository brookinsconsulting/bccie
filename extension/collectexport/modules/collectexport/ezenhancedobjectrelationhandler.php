<?php

include_once('extension/collectexport/modules/collectexport/basehandler.php');

class ezenhancedobjectrelationHandler extends BaseHandler {

	function exportAttribute(&$attribute, $seperationChar) {
                $content=$attribute->content();
                $id_list=$content['id_list'];
                                               
                $ini = eZINI::instance( "csv.ini" );
                if ($ini->variable( "ezenhancedobjectrelation", "OutputRelatedObjectNames" )) {
                	$names=array();
                	foreach ($id_list as $id) {
                		$object=eZContentObject::fetch($id);
                		$names[]=$object->name();
                	} 
			return $this->escape( join(" ", $names) , $seperationChar);
                	                        
                }
                else {
			return $this->escape( join(" ", $id_list ) , $seperationChar);
		}
	}

}

?>