<?php

/* ------ Execute --------*/
AutoAppBuild::build();



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
	public function build() {
		self::setAppPath();

		foreach(self::$list as $target => $path) {
			$ignoreList = $buildPath = array();

			$dirs = glob($path."*", GLOB_ONLYDIR);
			if(empty($dirs)){
				continue;
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

			if(!empty($buildPath)) {
				App::build(array(
							$target => $buildPath,
							));
			}
		}
	}

}


?>