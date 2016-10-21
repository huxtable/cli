<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI;

use Huxtable\CLI\Command\InvalidClosureException;
use Huxtable\Core\File;

class Command
{
	/**
	 * @var array
	 */
	protected $aliases=[];

	/**
	 * @var string
	 */
	protected $closure;

	/**
	 * @var	Huxtable\CLI\Cookie
	 */
	protected $cookies;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var	Huxtable\Core\File\Directory
	 */
	public $dirApp;

	/**
	 * @var	Huxtable\Core\File\Directory
	 */
	public $dirData;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $options=[];

	/**
	 * Array of Command objects
	 *
	 * @var array
	 */
	protected $subcommands=[];

	/**
	 * @var string
	 */
	protected $usage='';

	/**
	 * @param	string	$name
	 * @param	string	$description
	 * @param	mixed	$closure 		Closure, name of static function or [object, function] array
	 */
	public function __construct($name, $description, $closure)
	{
		$this->name = $name;
		$this->description = $description;
		$this->setClosure($closure);
	}

	/**
	 * Alias for self::registerAlias
	 *
	 * @param	string	$alias
	 */
	public function addAlias($alias)
	{
		$this->registerAlias( $alias );
	}

	/**
	 * Alias for self::registerSubcommand
	 *
	 * @param	Command	$command
	 */
	public function addSubcommand(Command $command)
	{
		$this->registerSubcommand( $command );
	}

	/**
	 * @param	string	$section
	 * @param	string	$name
	 * @return	void
	 */
	public function deleteCookie( $section, $name )
	{
	   $this->cookies->delete( $section, $name );
	}

	/**
	 * Return array of aliases
	 *
	 * @return	array
	 */
	public function getAliases()
	{
		return $this->aliases;
	}

	/**
	 * @return	Closure
	 */
	public function getClosure()
	{
		if($this->closure instanceof \Closure)
		{
			return $this->closure;
		}

		$reflect = new \ReflectionMethod($this->closure);
		return $reflect->getClosure();
	}

	/**
	 * @param	string	$section
	 * @param	string	$name
	 * @return	mixed
	 */
	public function getCookie( $section, $name )
	{
		return $this->cookies->get( $section, $name );
	}

	/**
	 * @return	string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return	string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Return the command options whose values have been set
	 *
	 * @return	array
	 */
	public function getOptionsWithValues()
	{
		$options = [];

		foreach( $this->options as $option => $value )
		{
			if( !is_null($value) )
			{
				$options[$option] = $value;
			}
		}

		return $options;
	}

	/**
	 * @param	string	$key
	 * @return	mixed
	 */
	public function getOptionValue($key)
	{
		if(isset($this->options[$key]) && !is_null($this->options[$key]))
		{
			return $this->options[$key];
		}
	}

	/**
	 * @return	Command
	 */
	public function getSubcommand($name)
	{
		if(isset($this->subcommands[$name]))
		{
			return $this->subcommands[$name];
		}
	}

	/**
	 * Return array of Command objects registered as subcommands
	 *
	 * @return	array
	 */
	public function getSubcommands()
	{
		return $this->subcommands;
	}

	/**
	 * @return	string
	 */
	public function getUsage()
	{
		if(strlen($this->usage) > 0)
		{
			return $this->usage;
		}

		$parameters = [];

		// Inspect closure parameters to build usage string
		$rf = new \ReflectionFunction($this->getClosure());

		foreach($rf->getParameters() as $parameter)
		{
			$pattern = $parameter->isOptional() ? '[<%s>]' : '<%s>';
			$parameters[] = sprintf($pattern, $parameter->name);
		}

		return sprintf('%s %s', $this->name, implode(' ', $parameters));
	}

	/**
	 * @param	string	$alias
	 * @return	void
	 */
	public function registerAlias( $alias )
	{
		$this->aliases[] = $alias;
	}

	/**
	 * @param	Huxtable\CLI\Cookie		$cookie
	 * @return	void
	 */
	public function registerCookieController( Cookie $cookie )
	{
		$this->cookies = $cookie;
	}

	/**
	 * @param	string	$option
	 */
	public function registerOption($option)
	{
		if(is_string($option))
		{
			$this->options[$option] = null;
		}
	}

	/**
	 * @param	Command	$command
	 */
	public function registerSubcommand( Command $command )
	{
		$this->subcommands[$command->getName()] = $command;
	}

	/**
	 * @param	Huxtable\Core\File\Directory	$dirApp
	 */
	public function setAppDirectory( File\Directory $dirApp )
	{
		$this->dirApp = $dirApp;
	}

	/**
	 * @param	mixed	$closure
	 */
	protected function setClosure($closure)
	{
		if($closure instanceof \Closure)
		{
			$this->closure = $closure->bindTo($this);
			return;
		}

		if(is_string($closure))
		{
			// Verify that static method exists
			$pieces = explode('::', $closure);

			if(count($pieces) == 2 && method_exists($pieces[0], $pieces[1]))
			{
				$this->closure = $closure->bindTo($this);
				return;
			}
		}

		if(is_array($closure) && count($closure) == 2)
		{
			$object     = $closure[0];
			$methodName = $closure[1];

			if(is_object($object) && is_string($methodName) && method_exists($object, $methodName))
			{
				$reflect = new \ReflectionClass($object);
				$method  = $reflect->getMethod($methodName);

				$this->closure = $method->getClosure($object);
				return;
			}
		}

		throw new InvalidClosureException("Invalid closure passed for '{$this->name}'");
	}

	/**
	 * @param	string	$section
	 * @param	string	$name
	 * @param	mixed	$value
	 * @return	void
	 */
	public function setCookie( $section, $name, $value )
	{
		$this->cookies->set( $section, $name, $value );
	}

	/**
	 * @param	Huxtable\Core\File\Directory	$dirData
	 */
	public function setDataDirectory( File\Directory $dirData )
	{
		$this->dirData = $dirData;
	}

	/**
	 * @param	string	$key
	 * @param	string	$value
	 */
	public function setOptionValue($key, $value)
	{
		if(array_key_exists($key, $this->options))
		{
			$this->options[$key] = $value;
		}
	}

	/**
	 * @param	string	$usage
	 */
	public function setUsage($usage)
	{
		$this->usage = $usage;
	}
}
