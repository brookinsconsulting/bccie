<?php
/**
 * File containing the ezenhancedobjectrelationhandler class.
 *
 * @copyright Copyright (C) 1999 - 2014 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */

include_once('extension/bccie/classes/basehandler.php');

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