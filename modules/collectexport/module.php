<?php
//
// Creation Date : 12 October 2006
// Author : Mathias VITALIS
// www.silverhand.fr
//
// This module allows for easy export collected informations 
// from contentobjects to csv or sylk files
//
// Inspired from the csv export module from Gabriel Ambuehl.

$Module = array( 'name' => 'collectedInformationExport' );

$ViewList = array();
$ViewList['overview'] = array(
    'script' => 'overview.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'collectexport',
    'ui_context' => 'view',
    'unordered_params' => array( 'offset' => 'Offset' ),
    'single_post_actions' => array( 'RemoveObjectCollectionButton' => 'RemoveObjectCollection',
                                    'ConfirmRemoveButton' => 'ConfirmRemoval',
                                    'CancelRemoveButton' => 'CancelRemoval' ) );

$ViewList['export'] = array(
    'script' => 'export.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'collectexport',
    'ui_context' => 'view',
    'params' => array( 'ObjectID' ), );

$ViewList['doexport'] = array(
    'script' => 'doexport.php',
    'functions' => array( 'read' ),
    'default_navigation_part' => 'collectexport',
    'ui_context' => 'view',
    'params' => array( 'ObjectID' ) );

$FunctionList['read'] = array();

?>
