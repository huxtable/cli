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
	 * @var	number
	 */
	protected $cols;

	/**
	 * @var	number
	 */
	protected $rows;

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
		return is_null( $this->cols ) ? exec( 'tput cols' ) : $this->cols;
	}

	/**
	 * @return	number
	 */
	public function getRows()
	{
		return is_null( $this->rows ) ? exec( 'tput lines' ) : $this->rows;
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

		$buffer = substr( $string, 0, $width ) . PHP_EOL;
		$bufferRight = substr( $string, $width );

		$lines = str_split( $bufferRight, $width - $indent );

		foreach( $lines as $line )
		{
			for( $i=1; $i <= $indent; $i++ )
			{
				$buffer .= ' ';
			}

			$buffer .= ltrim( $line ) . PHP_EOL;
		}

		$this->buffer .= $buffer;
	}

	/**
	 * @param	string	$string
	 */
	public function line($string='')
	{
		$this->buffer = $this->buffer . $string . PHP_EOL;
	}

	/**
	 * @param	number	$cols
	 * @return	void
	 */
	public function setCols( $cols )
	{
		$this->cols = $cols;
	}

	/**
	 * @param	number	$rows
	 * @return	void
	 */
	public function setRows( $rows )
	{
		$this->rows = $rows;
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
