<?php
// classes/Environment.php

class Environment {
		
	private static $loaded = false;
	private static $env = [];
	
	/**
	 * Cargar variables de entorno desde archivo .env
	 */
	public static function load($path = null) {
		if (self::$loaded) {
			return;
		}
		
		$envFile = $path ?: PATH . '.env';
		
		if (!file_exists($envFile)) {
			return;
		}
		
		$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
		foreach ($lines as $line) {
				// Ignorar comentarios
			if (strpos(trim($line), '#') === 0) {
				continue;
			}
			
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
	
	/**
	 * Obtener variable de entorno
	 */
	public static function get($key, $default = null) {
		if (!self::$loaded) {
			self::load();
		}
		
		return self::$env[$key] ?? getenv($key) ?: $default;
	}
	
	/**
	 * Verificar si existe una variable de entorno
	 */
	public static function has($key) {
		if (!self::$loaded) {
			self::load();
		}
		
		return isset(self::$env[$key]) || getenv($key) !== false;
	}
}