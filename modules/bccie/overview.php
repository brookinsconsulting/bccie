<?php
/**
 * File containing the overview module view.
 *
 * @copyright Copyright (C) 1999 - 2014 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2 (or any later version)
 * @version //autogentag//
 * @package bccie
 */

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezpreferences.php' );
include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'kernel/common/i18n.php' );

$http = eZHTTPTool::instance();
$module = $Params['Module'];
$offset = $Params['Offset'];

if( !is_numeric( $offset ) )
{
    $offset = 0;
}


if( $module->isCurrentAction( 'RemoveObjectCollection' ) && $http->hasPostVariable( 'ObjectIDArray' ) )
{
    $objectIDArray = $http->postVariable( 'ObjectIDArray' );
    $http->setSessionVariable( 'ObjectIDArray', $objectIDArray );

    $collections = 0;

    foreach( $objectIDArray as $objectID )
    {
        $collections += eZInformationCollection::fetchCollectionCountForObject( $objectID );
    }

    $tpl = eZTemplate::factory();
    $tpl->setVariable( 'module', $module );
    $tpl->setVariable( 'collections', $collections );
    $tpl->setVariable( 'remove_type', 'objects' );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:infocollector/confirmremoval.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezpI18n::tr( 'kernel/infocollector', 'Collected information' ) ) );
    return;
}


if( $module->isCurrentAction( 'ConfirmRemoval' ) )
{

    $objectIDArray = $http->sessionVariable( 'ObjectIDArray' );

    if( is_array( $objectIDArray) )
    {
        foreach( $objectIDArray as $objectID )
        {
            eZInformationCollection::removeContentObject( $objectID );
        }
    }
}


if( eZPreferences::value( 'admin_infocollector_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_infocollector_list_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}


$db = eZDB::instance();
$objects = $db->arrayQuery( 'SELECT DISTINCT ezinfocollection.contentobject_id,
                                    ezcontentobject.name,
                                    ezcontentobject_tree.main_node_id,
                                    ezcontentclass.*,
                                    ezcontentclass.identifier AS class_identifier
                             FROM   ezinfocollection,
                                    ezcontentobject,
                                    ezcontentobject_tree,
                                    ezcontentclass
                             WHERE  ezinfocollection.contentobject_id=ezcontentobject.id
                                    AND ezcontentobject.contentclass_id=ezcontentclass.id
                                    AND ezinfocollection.contentobject_id=ezcontentobject_tree.contentobject_id',
                             array( 'limit'  => (int)$limit,
                                    'offset' => (int)$offset ) );

$infoCollectorObjectsQuery = $db->arrayQuery( 'SELECT COUNT( DISTINCT ezinfocollection.contentobject_id ) as count
                                               FROM ezinfocollection,
                                                    ezcontentobject,
                                                    ezcontentobject_tree
                                               WHERE
                                                    ezinfocollection.contentobject_id=ezcontentobject.id
                                                    AND ezinfocollection.contentobject_id=ezcontentobject_tree.contentobject_id' );
$numberOfInfoCollectorObjects = 0;

if( $infoCollectorObjectsQuery )
{
    $numberOfInfoCollectorObjects = $infoCollectorObjectsQuery[0]['count'];
}

foreach ( array_keys( $objects ) as $i )
{
    $collections = eZInformationCollection::fetchCollectionsList( (int)$objects[$i]['contentobject_id'], /* object id */
                                                                  false, /* creator id */
                                                                  false, /* user identifier */
                                                                  false, /* limitArray */
                                                                  false, /* sortArray */
                                                                  false  /* asObject */
                                                                 );
    $class = new eZContentClass( $objects[$i] );
    $objects[$i]['class_name'] = $class->attribute( 'name' );
    $first = $collections[0]['created'];
    $last  = $first;

    for($j=0; $j<count( $collections ); $j++ )
    {
        $current = $collections[$j]['created'];

        if( $current < $first )
            $first = $current;

        if( $current > $last )
            $last = $current;
    }

    $objects[$i]['first_collection'] = $first;
    $objects[$i]['last_collection'] = $last;
    $objects[$i]['collections']= count( $collections );
}

$viewParameters = array( 'offset' => $offset );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'module', $module );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'object_array', $objects );
$tpl->setVariable( 'object_count', $numberOfInfoCollectorObjects );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:bccie/overview.tpl' );
$Result['navigation_part'] = 'ezbccienavigationpart';
$Result['left_menu'] = 'design:bccie/export_menu.tpl';
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'extension/bccie', 'Collected information export' ) ) );

?>