<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI;

class Format
{
	/**
	 * @param	string	$string			String to colorize
	 * @param	string	$foreground		Name of foreground color
	 * @param	string	$background		Name of background color
	 * @return	string
	 */
	static public function colorize($string, $foreground=null, $background=null, $bold=false)
	{
		$prefix = $bold ? "1" : "0";
		$foregroundColors =
		[
			'black'		=> "{$prefix};30",
			'red'		=> "{$prefix};31",
			'green'		=> "{$prefix};32",
			'yellow'	=> "{$prefix};33",
			'blue'		=> "{$prefix};34",
			'purple'	=> "{$prefix};35",
			'cyan'		=> "{$prefix};36",
			'gray'		=> "{$prefix};37",
		];

		$backgroundColors =
		[
			'black'		=> '40',
			'red'		=> '41',
			'green'		=> '42',
			'yellow'	=> '43',
			'blue'		=> '44',
			'purple'	=> '45',
			'cyan'		=> '46',
			'gray'		=> '47',
		];

		$colorized = '';
		$colorized .= isset($foregroundColors[$foreground]) ? "\033[".$foregroundColors[$foreground]."m" : '';
		$colorized .= isset($backgroundColors[$background]) ? "\033[".$backgroundColors[$background]."m" : '';
		$colorized .= $string;
		$colorized .= "\033[0m";

		return $colorized;
	}

	/**
	 * @param	string	$timestamp
	 * @return	string	Date string formatted like `ls` dates
	 */
	static public function date($timestamp=null)
	{
		if(is_null($timestamp))
		{
			$timestamp = time();
		}

		$now  = getdate();
		$date = getdate($timestamp);

		$detail = ($now[0] - $date[0] <= 15778500) ? sprintf('%02s:%02s', $date['hours'], $date['minutes']) : $date['year'];

		return sprintf('%.3s %2s %5s', $date['month'], $date['mday'], $detail);
	}

	/**
	 * @return	string
	 */
	static public function underline( $string )
	{
		return sprintf( "\033[4m%s\033[0m", $string );
	}
}