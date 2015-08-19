<?php

/*
 * This file is part of Huxtable\CLI
 */
namespace Huxtable\CLI\Git;

use Huxtable\CLI\Shell;
use Huxtable\Core\FileInfo;

class Repository
{
	/**
	 * @
	 */
	public function __construct( FileInfo $path )
	{
		if( !$path->isDir() )
		{
			throw new \Exception( "Repository path must be a valid directory, '{$path}' given" );
		}

		if( !$path->child( '.git' )->isDir() )
		{
			throw new \Exception( "Not a git repository" );
		}

		$this->path = $path;
	}

	/**
	 * @param	string	$subcommand
	 */
	protected function exec( $subcommand, $arguments=[], $options=[] )
	{
		chdir( $this->path );

		// Build command
		$command = "git {$subcommand}";

		foreach( $options as $option => $value )
		{
			if( $value === true )
			{
				if( strlen( $option ) == 1 )
				{
					$command .= " -{$option}";
				}
				else
				{
					$command .= " --{$option}";
				}
			}
			else
			{
				$command .= " --{$option}={$value}";
			}
		}

		return Shell::exec( $command );
	}

	/**
	 * Run 'git pull [options]'
	 *
	 * @param	array	$options
	 * @return	array
	 */
	public function pull( $options=[] )
	{
		return $this->exec( 'pull', [], $options );
	}

	/**
	 * Run 'git stash {$message}'
	 *
	 * @param	string	$message
	 * @return	array
	 */
	public function stashSave( $message )
	{
		return $this->exec( "stash save '{$message}'" );
	}

	/**
	 * Run 'git submodule update [options]'
	 * 
	 * @param	array	$options
	 * @return	array
	 */
	public function submoduleUpdate( $options=[] )
	{
		return $this->exec( 'submodule update', [], $options );
	}
}

?>
