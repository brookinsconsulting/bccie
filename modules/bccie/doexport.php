<?php
/**
 * File containing the doexport module view.
 *
 * @copyright Copyright (C) 1999 - 2014 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */

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

$conditions['contentobject_id'] = $objectID;
$dateConditions = BCCIEUtils::getDateConditions( $http );
$conditions['created'] = $dateConditions['conditions'];

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
$filename = BCCIEUtils::getFileName( $export_type );
$parser = new Parser( $objectID );

header( "Content-Disposition: attachment; filename=$filename" );

echo "\xEF\xBB\xBF";

$export_string = $parser->exportInformationCollection(
                            $collections,
                            $attributes_to_export,
                            $seperation_char,
                            $export_type,
                            $dateConditions['days']
);

echo( $export_string );

flush();

eZExecution::cleanExit();

?>