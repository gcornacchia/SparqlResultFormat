<?php


if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	die( 'Not an entry point' );
}

error_reporting( -1 );
ini_set( 'display_errors', '1' );

$autoloader = require __DIR__ . '/autoloader.php';

/*$autoloader->addPsr4( 'SMW\\Test\\', __DIR__ . '/phpunit' );
$autoloader->addPsr4( 'SMW\\Tests\\', __DIR__ . '/phpunit' );

$autoloader->addClassMap( [
	'SMW\Tests\DataItemTest'                     => __DIR__ . '/phpunit/includes/dataitems/DataItemTest.php',
	'SMW\Maintenance\RebuildConceptCache'        => __DIR__ . '/../maintenance/rebuildConceptCache.php',
	'SMW\Maintenance\RebuildData'                => __DIR__ . '/../maintenance/rebuildData.php',
	'SMW\Maintenance\RebuildPropertyStatistics'  => __DIR__ . '/../maintenance/rebuildPropertyStatistics.php',
	'SMW\Maintenance\RebuildFulltextSearchTable' => __DIR__ . '/../maintenance/rebuildFulltextSearchTable.php',
	'SMW\Maintenance\DumpRdf'                    => __DIR__ . '/../maintenance/dumpRDF.php',
	'SMW\Maintenance\SetupStore'                 => __DIR__ . '/../maintenance/setupStore.php',
	'SMW\Maintenance\UpdateEntityCollation'      => __DIR__ . '/../maintenance/updateEntityCollation.php',
	'SMW\Maintenance\RemoveDuplicateEntities'    => __DIR__ . '/../maintenance/removeDuplicateEntities.php'
] );*/

/**
 * Register a shutdown function the invoke a final clean-up
 */
register_shutdown_function( function() {

	if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
		return;
	}

} );
