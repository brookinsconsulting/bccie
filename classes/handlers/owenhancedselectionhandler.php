<?php
/**
 * File containing the owenhancedselectionhandler class.
 *
 * @copyright Copyright (C) 1999 - 2015 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */

include_once( 'extension/bccie/classes/basehandler.php' );

class OWEnhancedSelectionHandler extends BaseHandler
{

    function exportAttribute( &$attribute, $seperationChar )
    {
        $content = $attribute->attribute('content');
        return $this->escape( $content['to_string'], $seperationChar );
    }

}

?>