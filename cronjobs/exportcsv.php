<?php
//
// Definition of eZCollectExport ExportCSV Cronjob
//
// CollectExport ExportCSV Cronjob
//
// Created on: <01-Mar-2007 00:06:00 Graham Brookins>
// Last Updated: <19-Jul-2007 00:14:55 Graham Brookins>
// Version: 1.0.2
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

include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'extension/collectexport/classes/export.php' );

// Settings

$ini = eZINI::instance( "cie.ini" );

$debug = $ini->hasVariable( 'ExportCollectionCSVSettings', 'Debug' ) ? $ini->variable( 'ExportCollectionCSVSettings', 'Debug' ) == 'enabled' : false;

$collection = $ini->variable( "ExportCollectionCSVSettings", "Collection" );
$dir = $ini->variable( "ExportCollectionCSVSettings", "Directory" );
$format = $ini->variable( "ExportCollectionCSVSettings", "Format" );
$separator = $ini->variable( "ExportCollectionCSVSettings", "Separator" );


// Export collections

foreach ( $collection as $item )
{
  if ( $debug )
  {
    print_r("Object Collection ID For Export: $item" ."\n");
  }
  if ( is_numeric( $item ) )
  {
    $collection_id = $item;
    exportCollection( $collection_id, $dir, $format, $separator, $debug );
  }
}

?>