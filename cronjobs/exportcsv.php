<?php
/**
 * File containing the eZCollectExport ExportCSV Cronjob.
 *
 * @copyright Copyright (C) 1999 - 2012 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */


include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'extension/bccie/classes/export.php' );


// Settings
$ini = eZINI::instance( "cie.ini" );

$debug = $ini->hasVariable( 'CieSettings', 'Debug' ) ? $ini->variable( 'CieSettings', 'Debug' ) == 'enabled' : false;
$collection = $ini->variable( "CieSettings", "Collection" );
$dir = $ini->variable( "CieSettings", "Directory" );
$format = $ini->variable( "CieSettings", "CsvFormat" );
$separator = $ini->variable( "CieSettings", "CsvSeparator" );
$limitedRange = $ini->variable( "CieSettings", "ExportLimitedRange" ) == 'enabled' ? true : false;
$removeExported = $ini->variable( "CieSettings", "RemoveExported" ) == 'enabled' ? true : false;

// Test range
if( $limitedRange == true ) {
    $days = $ini->variable( "CieSettings", "DateRangeToExport" );
} else {
    $days = false;
}


// Export collections
exportCollections( $collection, $dir, $format, $separator, $days, $removeExported, $debug );

?>
