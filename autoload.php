<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI;

$pathBaseCLI	= __DIR__;
$pathSrcCLI		= $pathBaseCLI . '/src/CLI';
$pathVendorCLI	= $pathBaseCLI . '/vendor';

/*
 * Initialize autoloading
 */
include_once( $pathSrcCLI . '/Autoloader.php' );
Autoloader::register();
