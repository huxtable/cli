<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI\Format;

class String
{
	/**
	 * @var	string
	 */
	protected $backgroundColor;

	/**
	 * @var	boolean
	 */
	protected $bold=false;

	/**
	 * @var	string
	 */
	protected $foregroundColor;

	/**
	 * @var	string
	 */
	protected $string;

	/**
	 * @var	boolean
	 */
	protected $underline=false;

	/**
	 * @param	string	$string
	 * @return	void
	 */
	public function __construct( $string )
	{
		$this->string = $string;
	}

	/**
	 * @return	string
	 */
	public function __toString()
	{
		$bold = $this->bold ? "1" : "0";
		if( $this->bold == true && !isset( $this->foregroundColor ) )
		{
			$this->foregroundColor = 'grey';
		}

		$foregroundColors =
		[
			'black'		=> "{$bold};30",
			'red'		=> "{$bold};31",
			'green'		=> "{$bold};32",
			'yellow'	=> "{$bold};33",
			'blue'		=> "{$bold};34",
			'purple'	=> "{$bold};35",
			'cyan'		=> "{$bold};36",
			'gray'		=> "{$bold};37",
			'grey'		=> "{$bold};37",
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
			'grey'		=> '47',
		];

		$foregroundColor = isset( $foregroundColors[ $this->foregroundColor ] ) ? $foregroundColors[ $this->foregroundColor ] : null;
		$backgroundColor = isset( $backgroundColors[ $this->backgroundColor ] ) ? $backgroundColors[ $this->backgroundColor ] : null;

		$string = '';
		$string .= isset( $foregroundColor) ? "\033[{$foregroundColor}m" : '';
		$string .= isset( $backgroundColor) ? "\033[{$backgroundColor}m" : '';
		$string .= $this->string;
		$string .= "\033[0m";

		return $string;
	}

	/**
	 * @param	boolean	$bold
	 * @return	self
	 */
	public function bold( $bold=true )
	{
		$this->bold = $bold;
		return $this;
	}

	/**
	 * @param	string	$color
	 * @return	self
	 */
	public function foregroundColor( $color )
	{
		$this->foregroundColor = $color;
		return $this;
	}

	/**
	 * Get length of unformatted string
	 *
	 * @return	number
	 */
	public function length()
	{
		return strlen( $this->string );
	}

	/**
	 * @param	boolean	$underline
	 * @return	self
	 */
	public function underline( $underline=true )
	{
		$this->underline = true;
		return $this;
	}
}
