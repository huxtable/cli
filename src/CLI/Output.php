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

		// Save some cycles for strings that already don't wrap
		if( $length <= $width )
		{
			$this->buffer .= $string . PHP_EOL;
			return;
		}

		$buffer = '';
		$isFirstLine = true;

		while( strlen( $string ) > 0 )
		{
			$currentIndent = $isFirstLine ? 0 : $indent;

			// Add indentation now to save a lot of offset acrobatics :)
			for( $i=1; $i <= $currentIndent; $i++ )
			{
				$string = ' ' . $string;
			}

			// If neither character is a space, slide back until one of them is
			$offset = 0;
			$lineBreak = substr( $string, $width - 1 + $offset, 2 );

			while( strlen( trim( $lineBreak ) ) == 2 )
			{
				$offset--;
				$lineBreak = substr( $string, $width - 1 + $offset, 2 );
			};

			// This line is done, add it to the buffer
			$buffer .= substr( $string, 0, $width + $offset ) . PHP_EOL;

			// Trim off used portion of string
			$string = substr( $string, $width + $offset );

			$isFirstLine = false;
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
	 * @param	array	$data
	 * @return	void
	 */
	public function table( $data )
	{
		$fields = array_keys( $data[0] );

		$maxLengths = [];
		foreach( $fields as $field )
		{
			$maxLengths[$field] = strlen( $field );
		}

		// Find max lengths for padding
		foreach( $data as $record )
		{
			foreach( $record as $key => $value )
			{
				$length = is_array( $value ) ? 2 * count( $value ) - 1 : strlen( $value );

				if( $length > $maxLengths[$key] )
				{
					$maxLengths[$key] = strlen( $value );
				}
			}
		}

		$rowLen = array_sum( $maxLengths ) + (3 * count( $fields ) + 1);
		$divider = '';
		for( $i = 0; $i < $rowLen; $i++ )
		{
			switch( $i )
			{
				case 0:
				case $rowLen - 1:
					$char = '+';
					break;

				default:
					$char = '-';
					break;
			}

			$divider .= $char;
		}

		echo $divider . PHP_EOL;

		// Header row
		echo '|';
		foreach( $fields as $field )
		{
			echo sprintf( " %-{$maxLengths[$field]}s |", $field );
		}
		echo PHP_EOL;

		echo $divider . PHP_EOL;

		// Data rows
		foreach( $data as $result )
		{
			echo '|';
			foreach( $result as $field => $value )
			{
				if( is_array( $value ) )
				{
					$value = implode( ',', $value );
				}
				echo sprintf( " %-{$maxLengths[$field]}s |", $value );
			}

			echo PHP_EOL;
		}

		echo $divider . PHP_EOL;
	}

	/**
	 * @param	string	$string
	 */
	public function unshiftLine($string)
	{
		$this->buffer = $string . PHP_EOL . $this->buffer;
	}
}
