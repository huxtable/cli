<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI\Output;

class Listing
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
	 * @param
	 * @return	string
	 */
	public function flush()
	{
		$columns = 2;
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

		// Render each line
		$indexMid   = floor( count( $this->items ) / 2 );
		$indexLeft  = 0;
		$indexRight = $indexMid;

		while( isset( $this->items[$indexLeft] ) && $indexLeft < $indexMid )
		{
			$itemLeft  = sprintf( "%-{$padding}s", $this->items[$indexLeft] );
			$itemRight = isset( $this->items[$indexRight] ) ? sprintf( "%-{$padding}s", $this->items[$indexRight] ) : '';

			$output .= "{$itemLeft}{$itemRight}\n";

			$indexLeft++;
			$indexRight++;
		}

		return $output;
	}
}
