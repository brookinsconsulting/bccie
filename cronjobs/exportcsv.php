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

$debug = $ini->hasVariable( 'CieSettings', 'Debug' ) ? $ini->variable( 'CieSettings', 'Debug' ) == 'enabled' : false;
$collection = $ini->variable( "CieSettings", "Collection" );
$dir = $ini->variable( "CieSettings", "Directory" );
$format = $ini->variable( "CieSettings", "CsvFormat" );
$separator = $ini->variable( "CieSettings", "CsvSeparator" );
$limitedRange = $ini->variable( "CieSettings", "ExportLimitedRange" ) == 'enabled' ? true : false;
$removeExported = $ini->variable( "CieSettings", "RemoveExported" ) == 'enabled' ? true : false;

if( $limitedRange == true ) {
    $days = $ini->variable( "CieSettings", "DateRangeToExport" );
} else {
    $days = false;
}


// Export collections

exportCollections( $collection, $dir, $format, $separator, $days, $removeExported, $debug );

?>