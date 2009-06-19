<?php
//
//
//

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'kernel/common/template.php' );
//include_once( 'kernel/classes/ezpreferences.php' );

$http =& eZHTTPTool::instance();
$module =& $Params['Module'];
$objectID = $Params['ObjectID'];

$object = false;

if( !isset( $offset ) )
{
    $offset = false;
}

if( is_numeric( $objectID ) )
{
    $object =& eZContentObject::fetch( $objectID );
}

if( !$object )
{
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$collections = eZInformationCollection::fetchCollectionsList( $objectID, /* object id */
                                                              false, /* creator id */
                                                              false, /* user identifier */
                                                              array() /* limit array */ );

$numberOfCollections = eZInformationCollection::fetchCollectionsCount( $objectID );

$viewParameters = array( 'offset' => $offset );
$objectName = $object->attribute( 'name' );

$tpl =& templateInit();
$tpl->setVariable( 'module', $module );
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'collection_array', $collections );
$tpl->setVariable( 'collection_count', $numberOfCollections );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:collectexport/export.tpl' );
$Result['path'] = array( array( 'url' => '/collectexport/overview',
                                'text' => ezi18n( 'extension/collectexport', 'Collected information export' ) ),
                         array( 'url' => false,
                                'text' => $objectName ) );

?>
