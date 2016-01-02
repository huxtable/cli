<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI;

class Output
{
	/**
	 * @var string
	 */
	protected $buffer='';

	/**
	 * @return	string
	 */
	public function flush()
	{
		return $this->buffer;
	}

	/**
	 * @return	number
	 */
	public function getCols()
	{
		return exec( 'tput cols' );
	}

	/**
	 * @return	number
	 */
	public function getRows()
	{
		return exec( 'tput lines' );
	}

	/**
	 * Add line to buffer, automatically indenting based on width
	 *
	 * @param	string	$string
	 * @param	number	$indent
	 */
	public function indentedLine( $string, $indent )
	{
		$length = strlen( $string );
		$width = $this->getCols();

		if( $length <= $width )
		{
			$this->buffer .= $string . PHP_EOL;
			return;
		}

		$buffer = substr( $string, 0, $width );
		$bufferRight = substr( $string, $width );

		$lines = str_split( $bufferRight, $width - $indent );

		foreach( $lines as $line )
		{
			for( $i=1; $i <= $indent; $i++ )
			{
				$buffer .= ' ';
			}

			$buffer .= $line;
		}

		$this->buffer .= $buffer . PHP_EOL;
	}

	/**
	 * @param	string	$string
	 */
	public function line($string)
	{
		$this->buffer = $this->buffer . $string . PHP_EOL;
	}

	/**
	 * @param	string	$string
	 */
	public function string($string)
	{
		$this->buffer .= $string;
	}

	/**
	 * @param	string	$string
	 */
	public function unshiftLine($string)
	{
		$this->buffer = $string . PHP_EOL . $this->buffer;
	}
}
