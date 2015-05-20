<?php
/**
 * File containing the doexport module view.
 *
 * @copyright Copyright (C) 1999 - 2015 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'extension/bccie/classes/parser.php' );
include_once( 'lib/ezutils/classes/ezexecution.php' );

$http = eZHTTPTool::instance();
$module = $Params['Module'];
$objectID = $Params['ObjectID'];

$cieINI = eZINI::instance( 'cie.ini' );
$exportFileName = $cieINI->variable( 'CieSettings', 'ExportFileName' );
$exportFileNameDateFormat = $cieINI->variable( 'CieSettings', 'ExportFileNameDateFormat' );

$object = false;
$exportCreationDate = false;
$exportModificationDate = false;

if ( is_numeric( $objectID ) )
{
    $object = eZContentObject::fetch( $objectID );
}

if ( !$object )
{
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$exportContentObjectName = strtolower( str_replace( ' ', '_', $object->attribute( 'name' ) ) );

$conditions = array( 'contentobject_id' => $objectID );

$start = false;
$end = false;
$days = false;

if ( $http->hasPostVariable( "start_year" ) )
{
    $start = mktime(
        0,
        0,
        0,
        (int)$http->postVariable( "start_month" ),
        (int)$http->postVariable( "start_day" ),
        (int)$http->postVariable( "start_year" )
    );
}
if ( $http->hasPostVariable( "end_year" ) )
{
    $end = mktime(
        23,
        59,
        59,
        (int)$http->postVariable( "end_month" ),
        (int)$http->postVariable( "end_day" ),
        (int)$http->postVariable( "end_year" )
    );
}
if ( $start !== false and $end !== false )
{
    $days = round( abs( $start - $end ) / 86400 );
}
if ( $start !== false and $end !== false )
{
    $conditions['created'] = array( false, array( $start, $end ) );
}
elseif ( $start !== false and $end === false )
{
    $conditions['created'] = array( '>', $start );
}
elseif ( $start === false and $end !== false )
{
    $conditions['created'] = array( '<', $end );
}

if ( $http->hasPostVariable( "creation_date" ) )
{
   $exportCreationDate = true;
}

if ( $http->hasPostVariable( "modification_date" ) )
{
   $exportModificationDate = true;
}

set_time_limit( 180 );

$collections = eZPersistentObject::fetchObjectList(
                                 eZInformationCollection::definition(),
                                     null,
                                     $conditions,
                                     false,
                                     false
);

//TODO: change error handler
if ( !$collections )
{
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$counter = 0;
$attributes_to_export = array();

while ( true )
{
    $currentattribute = $http->postVariable( "field_$counter" );
    if ( !$currentattribute )
    {
        break;
    }
    $attributes_to_export[] = $currentattribute;
    $counter++;
}

$seperation_char = $http->postVariable( "separation_char" );
$export_type = $http->postVariable( "export_type" );
$parser = new Parser( $objectID );

$date_export = date( $exportFileNameDateFormat );

switch ( $export_type )
{
    case 'csv':
        $filename = $exportFileName . $exportContentObjectName . '-on-' . $date_export . ".csv";
        break;
    case 'sylk':
        $filename = $exportFileName . $exportContentObjectName . '-on-' . $date_export . ".slk";
        break;
    default :
        $filename = $exportFileName . $exportContentObjectName . '-on-' . $date_export . ".csv";
        break;
}

$export_string = $parser->exportInformationCollection(
                        $collections,
                            $attributes_to_export,
                            $seperation_char,
                            $export_type,
                            $days,
                            $exportCreationDate,
                            $exportModificationDate
);

$exportFormatOutputHandler = bccieExportFormatOutputHandler::instance();
$exportFormatOutputHandler->setOutputFileName( $filename );

$exportFormatOutputHandler = $exportFormatOutputHandler->output( $export_string );

flush();

eZExecution::cleanExit();

?>
