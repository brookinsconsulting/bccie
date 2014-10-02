<?php
/**
 * File containing the ezenhancedobjectrelationhandler class.
 *
 * @copyright Copyright (C) 1999 - 2014 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */


class eZObjectrelationHandler extends BaseHandler
{

    function exportAttribute( &$attribute, $seperationChar )
    {
        $content = $attribute->content();
        $ini = eZINI::instance( "export.ini" );
        if ( $ini->variable( "ezobjectrelation", "OutputRelatedObjectNames" ) !== 'false' )
        {
            return $this->escape( $content->name(), $seperationChar );
        }
        else
        {
            return $this->escape( $content->attribute('id' ), $seperationChar );
        }
    }
}

?>