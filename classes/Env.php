<?php

class Env {
		
	private static $loaded = false;
	private static $env = [];
	
	public static function load($path = null) {
		
    if (self::$loaded)  return false;
		
		$envFile = $path ?: PATH . '.env';
		
		if (!file_exists($envFile)) return false; 

		$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
		foreach ($lines as $line) {
			// Ignorar comentarios
			if (strpos(trim($line), '#') === 0) continue;
			
			// Parsear línea KEY=VALUE
			if (strpos($line, '=') !== false) {
				list($key, $value) = explode('=', $line, 2);
				$key = trim($key);
				$value = trim($value);
				
				// Remover comillas si existen
				if (preg_match('/^(["\'])(.*)\1$/', $value, $matches)) {
					$value = $matches[2];
				}
					
				self::$env[$key] = $value;
				putenv("$key=$value");
				$_ENV[$key] = $value;
				$_SERVER[$key] = $value;
			}
		}
		
		self::$loaded = true;
	}
	
	public static function get($key, $default = null) {
		if (!self::$loaded) {
			self::load();
		}

    if(!self::has($key)) return false;
		
		return self::$env[$key] ?? getenv($key) ?: $default;
	}
	
	public static function has($key) {
		if (!self::$loaded) {
			self::load();
		}
		
		return isset(self::$env[$key]) || getenv($key) !== false;
	}
}