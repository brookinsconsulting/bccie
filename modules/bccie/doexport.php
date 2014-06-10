<?php
/**
 * File containing the doexport module view.
 *
 * @copyright Copyright (C) 1999 - 2014 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'extension/bccie/classes/parser.php' );
include_once( 'lib/ezutils/classes/ezexecution.php' );

header( "Content-type:text/csv; charset=utf-8" );

$http = eZHTTPTool::instance();
$module = $Params['Module'];
$objectID = $Params['ObjectID'];
$object = false;

if ( is_numeric( $objectID ) )
{
    $object = eZContentObject::fetch( $objectID );
}

if ( !$object )
{
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

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

$date_export = date( "d-m-Y" );

switch ( $export_type )
{
    case 'csv':
        $filename = "export_" . $date_export . ".csv";
        break;
    case 'sylk':
        $filename = "export_" . $date_export . ".slk";
        break;
    default :
        $filename = "export_" . $date_export . ".csv";
        break;
}

header( "Content-Disposition: attachment; filename=$filename" );
echo "\xEF\xBB\xBF";

$export_string = $parser->exportInformationCollection(
                        $collections,
                            $attributes_to_export,
                            $seperation_char,
                            $export_type,
                            $days
);

echo( $export_string );

flush();

eZExecution::cleanExit();

?>
