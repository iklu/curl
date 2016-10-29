<?php
ini_set('memory_limit', '512M');
error_reporting( E_ALL | E_STRICT );
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV',
(getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') :
'development'));
spl_autoload_register('loadClass');
function loadClass($className)
{
	$base_dir = __DIR__ . '/';
    $file = str_replace('\\', '/', '../'.$className) . '.php';
	if(file_exists($file))
	{
		require_once $file;
	}
	else
	{
		 $pathDir = str_replace('\\', '/', $base_dir);
		 $fileDir = str_replace('\\', '/', $className).'.php';
		 $combined = $pathDir.$fileDir;
		if(file_exists($combined))
		{
			require_once $combined;
			return;
		}
	}
}