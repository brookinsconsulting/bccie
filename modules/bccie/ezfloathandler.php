<?php
/**
 * File containing the ezfloathandler class.
 *
 * @copyright Copyright (C) 1999 - 2012 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */

include_once('extension/bccie/modules/bccie/basehandler.php');

class eZFloatHandler extends BaseHandler {

	function exportAttribute(&$attribute, $seperationChar) {
		return $this->escape($attribute->content(), $seperationChar);
	}

}

?>
