<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI\Command;

class InvalidCommandException extends \OutOfBoundsException
{
	const UNDEFINED		= 1;
	const UNSPECIFIED	= 2;
}

?>
