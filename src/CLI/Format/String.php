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
	 * @var	string
	 */
	protected $foregroundColor;

	/**
	 * Foreground color prefix, which affects properties like bold, underline, etc.
	 *
	 * @var	number
	 */
	protected $prefix=0;

	/**
	 * @var	string
	 */
	protected $string;

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
		if( $this->prefix != 0 && !isset( $this->foregroundColor ) )
		{
			$this->foregroundColor = 'none';
		}

		$foregroundColors =
		[
			'none'		=> "{$this->prefix};29",
			'black'		=> "{$this->prefix};30",
			'red'		=> "{$this->prefix};31",
			'green'		=> "{$this->prefix};32",
			'yellow'	=> "{$this->prefix};33",
			'blue'		=> "{$this->prefix};34",
			'purple'	=> "{$this->prefix};35",
			'cyan'		=> "{$this->prefix};36",
			'gray'		=> "{$this->prefix};37",
			'grey'		=> "{$this->prefix};37",
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
	 * @param	string	$color
	 * @return	self
	 */
	public function backgroundColor( $color )
	{
		$this->backgroundColor = $color;
		return $this;
	}

	/**
	 * @return	self
	 */
	public function blink()
	{
		$this->prefix = 5;
		return $this;
	}

	/**
	 * @return	self
	 */
	public function bold()
	{
		$this->prefix = 1;
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
	 * @return	self
	 */
	public function invert()
	{
		$this->prefix = 7;
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
	 * @return	self
	 */
	public function normal()
	{
		$this->prefix = 0;
		return $this;
	}

	/**
	 * @return	self
	 */
	public function underline()
	{
		$this->prefix = 4;
		return $this;
	}
}
