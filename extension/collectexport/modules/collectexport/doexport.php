<?php

include_once ('kernel/classes/ezcontentclass.php');
include_once ( 'kernel/classes/ezinformationcollection.php' );
include_once ('lib/ezutils/classes/ezhttptool.php');
include_once ('extension/collectexport/modules/collectexport/parser.php');
include_once ('lib/ezutils/classes/ezexecution.php');

header("Content-type:text/csv; charset=utf-8");

$http = eZHTTPTool::instance();
$module = $Params['Module'];
$objectID = $Params['ObjectID'];

$object = false;

if( is_numeric( $objectID ) )
{
    $object = eZContentObject::fetch( $objectID );
}

if( !$object )
{
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$conditions = array( 'contentobject_id' => $objectID  );

$start = false;
$end = false;
if ( eZHTTPTool::hasPostVariable( "start_year" ) )
{
    $start = mktime( 0,0,0, (int)eZHTTPTool::postVariable("start_month"), (int)eZHTTPTool::postVariable("start_day"), (int)eZHTTPTool::postVariable("start_year") );
}
if ( eZHTTPTool::hasPostVariable( "end_year" ) )
{
    $end = mktime( 0,0,0, (int)eZHTTPTool::postVariable("end_month"), (int)eZHTTPTool::postVariable("end_day"), (int)eZHTTPTool::postVariable("end_year") );
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
$collections = eZPersistentObject::fetchObjectList( eZInformationCollection::definition(),
                                                      null,
                                                      $conditions,
                                                      false,
                                                      false );

$counter=0;
$attributes_to_export=array();
while (true) {
	$currentattribute=eZHTTPTool::postVariable("field_$counter");
	if (!$currentattribute) {
		break;
	}
	$attributes_to_export[]=$currentattribute;
	$counter++;
}

$seperation_char = eZHTTPTool::postVariable("separation_char");
$export_type = eZHTTPTool::postVariable("export_type");
$parser=new Parser();

$date_export = date("d-m-Y");
$contentobject = & eZContentObject::fetch($objectID);

eZDebug::writeDebug($contentobject);

switch($export_type){
    case 'csv':
        $filename = "export_". $date_export .".csv";
        break;
    case 'sylk':
        $filename = "export_". $date_export .".slk";
        break;
    default :
        $filename = "export_". $date_export .".csv";
        break;
    }
header("Content-Disposition: attachment; filename=$filename");


$export_string=$parser->exportInformationCollection( $collections, $attributes_to_export, $seperation_char, $export_type, $objectID);

echo($export_string);
flush();
eZExecution::cleanExit();

?>
