<?php

use WMDE\ConfigDiff\ConstantParser;

$autoloadFiles = [
	__DIR__ . '/../vendor/autoload.php',
	__DIR__ . '/../../../autoload.php'
];

foreach ( $autoloadFiles as $autoloadFile ) {
	if ( file_exists( $autoloadFile ) ) {
		require_once $autoloadFile;
	}
}

// @codingStandardsIgnoreStart
function usage() {
	// @codingStandardsIgnoreEnd
	global $argv;
	echo "Usage: {$argv[0]} TEMPLATE_FILE CONFIG_FILE\n";
	die( 1 );
}

if ( empty( $argv[1] ) || empty( $argv[2] ) ) {
	echo "Missing parameters.\n";
	usage();
}

$templateFile = $argv[1];
$configFile = $argv[2];

if ( !file_exists( $templateFile ) ) {
	echo "Template file does not exist.\n";
	usage();
}
if ( !file_exists( $configFile ) ) {
	echo "Config file does not exist.\n";
	usage();
}

$parser = new ConstantParser();

$templateConstants = $parser->getConstants( file_get_contents( $templateFile ) );
$configConstants = $parser->getConstants( file_get_contents( $configFile ) );

$templateKeys = array_keys( $templateConstants );
$configKeys = array_keys( $configConstants );

$extra = array_diff( $configKeys, $templateKeys );
$missing = array_diff( $templateKeys, $configKeys );

if ( empty( $missing ) && empty( $extra ) ) {
	exit;
}

if ( $missing ) {
	echo "The following configuration keys are missing:\n";
	echo implode( "\n", $missing ) . "\n";
}

if ( $extra ) {
	echo "The following configuration keys are set without equivalent in template file:\n";
	echo implode( "\n", $extra ) . "\n";
}
