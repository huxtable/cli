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
