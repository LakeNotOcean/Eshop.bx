<?php

class Autoloader
{
	/**
	 * @var Autoloader
	 */
	private static $instance;

	/**
	 * @var array<string, string>
	 */
	private static $vendorNamespaceToBaseDir = [];

	protected function __construct()
	{
		spl_autoload_register([$this, 'load']);
	}

	public static function getInstance(): Autoloader
	{
		if (null === self::$instance)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function addVendorNamespacePath(string $vendorNamespace, string $root_path): void
	{
		self::$vendorNamespaceToBaseDir[$vendorNamespace] = $root_path;
	}

	private function load($classname): void
    {
		$path = $this->getClassPath($classname);
		if ($path)
		{
			require_once $path;
		}
	}

	private function getClassPath($classname): ?string
	{
		foreach (self::$vendorNamespaceToBaseDir as $vendorNamespace => $baseDir)
		{
			// does the class use namespace prefix
			$vendorNamespaceLen = strlen($vendorNamespace);
			if (strncmp($vendorNamespace, $classname, $vendorNamespaceLen) !== 0)
			{
				continue;
			}
			$relativeClass = substr($classname, $vendorNamespaceLen);

			$file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
			if (file_exists($file))
			{
				return $file;
			}
		}

		return null;
	}

}
