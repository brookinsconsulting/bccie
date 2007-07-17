<?php
//
// Definition of eZCollectExport ExportCSV Cronjob
//
// CollectExport ExportCSV Cronjob
//
// Created on: <01-Mar-2007 00:06:00 Graham Brookins>
// Last Updated: <01-Mar-2007 00:06:20 Graham Brookins>
// Version: 1.0.1
//
// Copyright (C) 2001-2007 Brookins Consulting. All rights reserved.
//
// This source file is part of an extension for the eZ publish (tm)
// Open Source Content Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 (or greater) as published by
// the Free Software Foundation and appearing in the file LICENSE
// included in the packaging of this file.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html
//
// Contact licence@brookinsconsulting.com if any conditions
// of this licencing isn't clear to you.
//

include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezinformationcollection.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'extension/collectexport/modules/collectexport/parser.php' );
include_once( 'lib/ezutils/classes/ezexecution.php' );

/*
  Exports Object Collection to File
*/
function exportCollection( $objectID=false, $dir='var/export', $format='csv', $separator=',', $debug=false )
{
  $ret = false;
  $object = false;


  // Settings
  $ini = eZINI::instance( "cronjob.ini" );
  $excludeAttributeID = $ini->variable( "ExportCollectionCSVSettings", "ExcludeAttributeID" );


  if( is_numeric( $objectID ) )
  {
    $object =& eZContentObject::fetch( $objectID );
    $classID = $object->attribute('contentclass_id');
  }

  // eZDebug::writeDebug( $object );

  if( is_numeric( $classID ) )
  {
    $class =& eZContentClass::fetch( $classID );
  }

  if ( $debug )
    echo "Object ClassID: $classID\n";

  if( is_object( $class ) )
  {
    $className = $class->attribute('identifier');
    $classDataMap = $class->attribute('data_map');
  }

  // Settings
  $ini = eZINI::instance( "cronjob.ini" );
  $excludeAttributeID = $ini->variable( "ExportCollectionCSVSettings", "ExcludeAttributeID" );

  if ( $debug )
  {
    echo "Exporting Collection: $objectID\n";
    echo "Output Directory: $dir\n";
    echo "Output Format: $format\n";
    echo "Output Separator: $separator\n";
    echo "Object Collection ID: $objectID\n";
  }

  if ( $debug )
    echo "Object Class Name: $className\n";


  // print_r( $classDataMap );
  // print_r( $class );
  // die( );


  if( !$object )
  {
    // return false;
    die('Encountered Non-Object, Unknown Error');
  }


  $collections = eZInformationCollection::fetchCollectionsList(
                 $objectID,
                 false,
                 false,
                 array() );

  if ( $debug )
  {  
     echo "Object Collection Contents: \n";
     print_r( $collections ); 
  }


  $collection_count = eZInformationCollection::fetchCollectionCountForObject( $objectID );

  if ( $debug )
    echo "Object Collection Count: $collection_count\n\n";


  $attributes_to_export = array();

  // fetch collection class attributes for export
  foreach ( $classDataMap as $attribute )
  {
    // print_r( $attribute );
    if( is_object( $attribute ) )
    { 
      $is_ic = $attribute->attribute('is_information_collector');
      if( is_numeric( $is_ic ) )
      {
        if ( $is_ic )
        {
         $id = $attribute->attribute('id');
         $name = $attribute->attribute('identifier');
         
         $attributes_to_export[]=$id;

         if( $debug )
         {
          print_r("Object Class Attribute Name: $name \n");
          // echo "Object Class Attribute is Information Collector: $is_ic\n";
          print_r("Object Class Attribute ID: $id \n\n");
         }
        }
      }
    }
  }

  // Set output file date
  $date_export = date("Y_m_d_H_i__s");

  // Set output format type name
  switch( $format )
  {
    case 'csv':
     $filename = $object->attribute( 'name' ) ."_export_". $date_export .".csv";
     break;
    case 'sylk':
     $filename = $object->attribute( 'name' ) ."_export_". $date_export .".slk";
     break;
    default :
     $filename = $object->attribute( 'name' ) ."_export_". $date_export .".csv";
     break;
  }

  $sdir = $dir.'/';
  $path = $sdir.$filename;

  if ( $debug )
  {
    echo "Collection Output Filename: $filename\n";
    echo "Collection Output Path: $path\n";
  }

  if ( $debug )
  {   echo "Class Attributes ot Export (Array): \n";
      print_r( $attributes_to_export ); echo "\n";
  }

  print_r("Object information collection record entries fetch in progress...\n");

  $parser = new Parser();
  $data = $parser->exportInformationCollection( $collections, 
          $attributes_to_export, $separator,
          $format, $objectID );


  if ( $debug )
  {
    echo "Collection Output Content:\n";
    echo( "$data\n" );
  }

  include_once( 'lib/ezfile/classes/ezfile.php' );

  $file = new eZFile();
  $file->create( $filename, $dir, $data );


  print_r("Object Collection Data Export File Path: $path\n");
  print_r("Object Collection Export Completed!\n\n");

  // flush();
  // eZExecution::cleanExit();
}

?>
