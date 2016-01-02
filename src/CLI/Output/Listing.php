<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI\Output;

class Listing extends \Huxtable\CLI\Output
{
	/**
	 * @var	array
	 */
	protected $items=[];

	/**
	 * @param	string	$item
	 * @return	void
	 */
	public function item( $item )
	{
		$this->items[] = $item;
	}

	/**
	 * @return	string
	 */
	public function flush()
	{
		$output  = '';

		// Determine padding
		$maxLength = 0;
		foreach( $this->items as $item )
		{
			if( strlen( $item ) >  $maxLength )
			{
				$maxLength = strlen( $item );
			}
		}

		$padding = $maxLength + 4;
		$width = $this->getCols();

		$cols = floor( $width / $padding );
		$rows = ceil( count( $this->items ) / $cols );

		if( count( $this->items ) == 0 )
		{
			return;
		}

		if( $rows == 1 )
		{
			foreach( $this->items as $item )
			{
				$items[] = sprintf( "%-{$padding}s", $item );
			}

			return implode( '', $items );
		}

		for( $r = 0; $r < $rows; $r++ )
		{
			$line = '';

			for( $c = 0; $c < $cols; $c++ )
			{
				$index = $r + ($c * $rows);
				if( isset( $this->items[$index] ) )
				{
					$line .= sprintf( "%-{$padding}s", $this->items[$index] );
				}
			}

			$output .= $line . PHP_EOL;
		}

		return $output;
	}
}
