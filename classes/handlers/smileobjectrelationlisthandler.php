<?php
/**
 * File containing the smileobjectrelationlistHandler class.
 *
 */
class smileobjectrelationlistHandler extends BaseHandler
{
    function exportAttribute( &$attribute, $seperationChar )
    {
        eZDebug::writeDebug( $attribute, "SMILE" );
        $content = $attribute->content();

        $relation_list = $content['relation_list'];
        eZDebug::writeDebug( $content, "SMILE" );

        $names = array();
        foreach ( $relation_list as $relation )
        {
            $object = eZContentObject::fetch( $relation['contentobject_id'] );
            $names[] = $object->name();
        }
        return $this->escape( join( " ", $names ), $seperationChar );
    }
}
