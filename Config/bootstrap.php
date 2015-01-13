<?php
/*
 * AutoAppBuild Plugin for CakePHP2.x
 *
 * Author: Yasushi Ichikawa http://github.com/ichikaway/ (Twitter: @ichikaway)
 *
 * @copyright Copyright 2012, Yasushi Ichikawa http://github.com/ichikaway/
 * @package AutoAppBuild
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */



/* ------ Execute --------*/
//AutoAppBuild::build();
//AutoAppBuild::dump();



/**
 * AutoAppBuild class
 **/
class AutoAppBuild {

/**
 * path of App directory
 **/
	private static $appDir = APP;

/**
 * target directory name
 **/
	private static $list = array(
			'Controller',
			'Model',
			);

/**
 * ingore directory name in a target directory
 **/
	private static $ignoreDirName = array(
			'Controller' => array('Component'),
			'Model' => array('Behavior', 'Datasource'),
			);

/**
 * set App path to target directory
 **/
	private static function setAppPath() {
		if(!empty(self::$list)) {
			foreach(self::$list as $key => $val) {
				self::$list[$val] = self::$appDir . $val . DS;
				unset(self::$list[$key]);
			}
		}
	}

/**
 * execute App::build 
 **/
	public static function build($plugin = null) {
		if($plugin != null){
			self::$appDir = CakePlugin::path($plugin);
		}
		self::setAppPath();

		foreach(self::$list as $target => $path) {
			try{
				$buildPath = self::createBuildPath($target, $path);
				if(!empty($buildPath)) {
					App::build(array(
							$target => $buildPath,
					));
				}
			} catch (Exception $e){
				//nothing to do
			}
		}
	}

/**
 * createBuildPath
 * @param $target string
 * @param $path string
 * @return array : directory paths
 **/
	private static function createBuildPath($target = null, $path = null) {
			if(empty($target) || empty($path)) {
				throw new Exception('target or path is null');
			}

			$ignoreList = $buildPath = array();
			$dirs = glob($path."*", GLOB_ONLYDIR);
			if(empty($dirs)){
				throw new Exception('No directory');
			}

			//make Ingore directory list
			if(!empty(self::$ignoreDirName[$target])) {
				foreach(self::$ignoreDirName[$target] as $ignoreDir) {
					$ignoreList[] = $path.$ignoreDir;
				}
			}

			//make directory list without ignore directories
			foreach ($dirs as $fullDirPath) {
				if(!in_array($fullDirPath, $ignoreList)) {
					$buildPath[] = $fullDirPath . DS;
				}
			}
			return $buildPath;
	}

/**
 * dump App::build() with all paths for Copy&Paste
 **/
	public static function dump() {
		self::setAppPath();
		$dump = array();
		foreach(self::$list as $target => $path) {
			try{
				$buildPath = self::createBuildPath($target, $path);
				if(!empty($buildPath)) {
					pr(
						'App::build(array(' . PHP_EOL .
							'"' . $target .'" => array("' . join('", "', $buildPath) . '")'. PHP_EOL .
						');'. PHP_EOL 
					);
				}
			} catch (Exception $e){
				//nothing to do
			}
		}

	}



}


?>