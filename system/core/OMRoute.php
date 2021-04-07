<?php
namespace OMCore;

class OMUri {
	public $path = "";
	public $filename = "";
	public $dir = "/";
	public $uri = "";
	public $matchs = "";
	public $args = array();
	public $lang = "/(th|en|cn|jp)\/(.*)$/i";
	public $langArr = array("th" => "tha", "en" => "eng", "cn" => "chn", "jp" => "jap");
	function __construct() {
		if (isset($_GET['REQUEST_URI'])) {
			$this->uri = $_GET['REQUEST_URI'];
		} else if (isset($_SERVER['REQUEST_URI'])) {
			$this->uri = $_SERVER['REQUEST_URI'];
		}
		$request_uri = explode('?', $this->uri);

		if (defined("WEB_REWRITE_BASE") && WEB_REWRITE_BASE != "") {
			$pattern = '/^\\' . WEB_REWRITE_BASE . '/i';
			$replacement = '';
			$request_uri[0] = preg_replace($pattern, $replacement, $request_uri[0]);
		}
		$rules = array(
			// "/user/$1" => array("^\/admin\/(.*)","^\/master\/(.*)"),
			"/user/" => array("\/(admin|user)\/"),
			"/template/" => array("\/(template)\/"),
		);
		if (!empty($rules)) {
			foreach ($rules as $path => $rule) {
				foreach ($rule as $patt) {
					$patt = "/" . $patt . "/i";
					// echo "<br/>" . $patt, $request_uri[0], $matchs;
					if (preg_match($patt, $request_uri[0], $matchs)) {
						// echo "<br/> matchs" ;
						$this->matchs = $matchs;
						$request_uri[0] = preg_replace($patt, $path, $request_uri[0]);
						// echo "RO:" . $request_uri[0];
						// echo "<br/> END" ;
					}
				}
			}
		}

		if (ENABLE_LANG) {
			preg_match($this->lang, $request_uri[0], $mathLang);
			if ($mathLang) {
				define("LANG", $this->langArr[$mathLang[1]]);
				define("WEB_META_BASE_LANG", WEB_META_BASE_URL . $mathLang[1] . "/");
			} else {
				define("WEB_META_BASE_LANG", WEB_META_BASE_URL . "th/");
				OMRoute::notFound();
			}
			$requestURI = explode('/', $mathLang[2]);
		} else {
			$requestURI = explode('/', $request_uri[0]);
		}
		//array_shift($requestURI);
		if (end($requestURI) == "") {
			array_pop($requestURI);
		}

		if (empty($requestURI)) {
			$this->path = WEB_INDEX_PAGE;
			$this->dir = "/";
			return;
		}

		do {
			$realpath = ROOT_DIR . "controllers/" . implode('/', $requestURI);
			// echo "<br/>" . $realpath;
			if (is_file($realpath . ".php")) {
				$this->path = implode('/', $requestURI);
				$this->filename = array_pop($requestURI);
				$this->dir = implode('/', $requestURI) . "/";
			} elseif (is_dir($realpath)) {
				if (empty($requestURI)) {
					$this->path = "index";
				} else {
					$this->path = implode('/', $requestURI) . "/index";
				}

				$this->filename = 'index';
				$this->dir = implode('/', $requestURI) . "/";
			} else {

				$argVal = array_pop($requestURI);
				if (count($requestURI) == 0) {
					$this->path = $argVal;
				} else {
					$this->args[] = $argVal;

				}
			}

		} while ($this->path == "" && count($requestURI) > 0);
		$this->args = array_reverse($this->args);
	}
}
class OMRoute {
	protected $pathArray = array();
	protected $path;
	public function __construct() {
		global $HTTP_SERVER_VARS;
		$this->pathArray = explode("/", $HTTP_SERVER_VARS["REQUEST_URI"]);
		// var_dump($this->pathArray);
		// exit();
	}
	private static $uri = null;
	public static function parse() {
		if (self::$uri == null) {
			self::$uri = new OMUri();
		}

		return self::$uri;
	}

	public static function path() {
		$uri = self::parse();
		return $uri->path;
	}
	public static function path_language() {
		$uri = self::parse();
  		$route = str_replace("/".self::$uri->filename,"",$uri->path);
		return $route;
	}
	public static function dir() {
		$uri = self::parse();
		return $uri->dir;
	}

	public static function uri() {
		$uri = self::parse();
		return $uri->uri;
	}

	public static function matchs($i = null) {
		$uri = self::parse();
		if ($i == null) {

			return $uri->matchs;
		} else {
			if (isset($uri->matchs[$i])) {
				return $uri->matchs[$i];
			}
		}
	}

	public static function current_url() {
		$uri = self::parse();
		return WEB_META_BASE_URL . substr($uri->uri, 1);
	}

	public static function args($i = '') {
		$uri = self::parse();
		if ($i === '') {
			return $uri->args;
		}

		return isset($uri->args[$i]) ? $uri->args[$i] : "";
	}

	public function getPathByNumber($pathNumber) {
		if (substr($this->pathArray[1], -4, 4) == '.php') {
			$this->path = mysql_escape_string($this->pathArray[$pathNumber + 1]);
		} else {
			$this->path = mysql_escape_string($this->pathArray[$pathNumber]);
		}
		return $this->path;
	}

	public static function notFound() {
		include TMPL_DIR . "404.tpl";
		exit();
	}
	public static function notPermission() {
		include TMPL_DIR . "403.tpl";
		exit();
	}

} //ends URI class

?>
