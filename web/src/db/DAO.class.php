<?php

require_once __DIR__ . "/../vendors/log4php/Logger.php";
require_once __DIR__ . "/dbconfig.php";

class DAO{
	// PDOs map indexed by dbhost+db+dbuser+dbpwd hash
	private static $staticpdos = array();

	public static $CACHE_ENABLED = true;
	/** @var Memcache $memcache */
	private static $memcache =null;

	const TTL_1_MINUTE = 60;
	const TTL_1_HOUR = 3600;
	const TTL_24_HOURS = 86400;
	const TTL_1_WEEK = 604800;

	const PDO_ERR_IN_DA_POCKET 		= 0;
	const PDO_ERR_THROW_EXCEPTION 	= 1;

	const ENABLE_RETRY_FOR_MAX_CONNECTIONS = true;
	const MAX_TRY_PERIOD = 5000;	// In milliseconds
	const TRY_WAIT = 500;	 // In milliseconds

	private $dbHost;
	private $dbName;
	private $dbUser;
	private $dbPasswd;
	private $errMode;
	private $encoding;

	private $logger;

	protected $cachePrefix = NULL;

	function __construct($host=null, $db=null, $user=null, $password=null, $lazyPDOInit = true, $errorMode = self::PDO_ERR_IN_DA_POCKET){

		if(defined("DB_ENABLE_CACHE")){
			self::$CACHE_ENABLED = DB_ENABLE_CACHE;
		}

		if(!$host && defined("DB_HOST")){
			$host = DB_HOST;
		}
		if(!$db && defined("DB_DATABASE")){
			$db = DB_DATABASE;
		}
		if(!$user && defined("DB_USER")){
			$user = DB_USER;
		}
		if(!$password && defined("DB_PASSWORD")){
			$password = DB_PASSWORD;
		}

		//Keep track of it in case of lazyPDOInit
		$this->dbHost = $host;
		$this->dbName = $db;
		$this->dbUser = $user;
		$this->dbPasswd = $password;
		$this->errMode = $errorMode;

		$this->logger = Logger::getLogger(get_class($this));

		if (!$lazyPDOInit){
			$this->createPDO($this->getMilliTimestamp(), $host, $db, $user, $password);
		}

		if(!self::$memcache && self::$CACHE_ENABLED && class_exists('Memcache')){
			set_error_handler(array($this, 'memcacheConnectErrorHandler'));
			try{
				self::$memcache = new Memcache;
				self::$memcache->connect('localhost', 11211);
			}catch (Exception $e){
				self::$memcache = null;
				self::$CACHE_ENABLED = false;
			}
			restore_error_handler();
		}
	}
	
	public function setCachePrefix($cachePrefix){
		$this->cachePrefix = $cachePrefix;
	}

	protected function setEncoding($charset){
		$this->encoding = $charset;
	}

	private static function debugLog($line){
	//	file_put_contents("/home/julien/dao.log", $line."\n", FILE_APPEND);
	}

	private function getDBHashKey($host, $db, $user, $password){
		return hash("md5", $host."-".$db."-".$user."-".$password);
	}
	
	public static function freePDOs(){
		self::$staticpdos = array();
	}

	protected function createPDO($firstTimestamp, $host, $db, $user, $password){
		$dbHash = $this->getDBHashKey($host, $db, $user, $password);
		if(!isset(self::$staticpdos[$dbHash])){
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			if ($this->encoding){
				$pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES ".$this->encoding;
			}
			else{
				$pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
			}
			//$pdo_options[PDO::ATTR_PERSISTENT] = true;
			try{
				//self::debugLog("creating PDO  $db $user $password".print_r(debug_backtrace(0), true));
				self::$staticpdos[$dbHash] = new PDO("mysql:host=" . $host . ";dbname=" . $db, $user, $password, $pdo_options);
				//self::debugLog("FK ".print_r(self::$staticpdo , true));
			} catch (PDOException $e) {
			//	self::debugLog("Caught exception ".print_r($e , true));
				// retry in case of max user connections reached
				if(self::ENABLE_RETRY_FOR_MAX_CONNECTIONS && $e->getCode() == 1226 && ($this->getMilliTimestamp() - $firstTimestamp < self::MAX_TRY_PERIOD)){
					$this->onMaxConnectionReached();
					usleep(self::TRY_WAIT * 1000);
					$this->createPDO($firstTimestamp, $host, $db, $user, $password);
				}else{
					if ($this->errMode == self::PDO_ERR_IN_DA_POCKET){
						print "Erreur de connexion<br/>";
						$this->logger->error(print_r($e, true));
						$this->logger->error("$host, $db, $user, $password");
					}else if ($this->errMode == self::PDO_ERR_THROW_EXCEPTION){
						throw $e;
					}
				}
			}
		}
	}
	
	protected function onMaxConnectionReached(){
		//Give me a chance to log this out
	}
	
	protected function onPDOWillInit(){
		//Give me a chance to log this out
	}

	protected function onPDODidInit(){
		//Give me a chance to log this out
	}
	
	private function getMilliTimestamp() {
		list($usec, $sec) = explode(" ", microtime());
		$usec = substr($usec, 0, 5);
		return intval(((float)$usec + (float)$sec) * 1000);
	}

	/**
	 * @return PDO
	 */
	protected function getPDO(){
		$dbHash = $this->getDBHashKey($this->dbHost, $this->dbName, $this->dbUser, $this->dbPasswd);
		if(!isset(self::$staticpdos[$dbHash])){
			$this->onPDOWillInit();
			$this->createPDO($this->getMilliTimestamp(), $this->dbHost, $this->dbName, $this->dbUser, $this->dbPasswd);
			$this->onPDODidInit();
		}
		// 		self::debugLog("FU ".print_r(self::$staticpdo , true));
		if (isset( self::$staticpdos[$dbHash])){
			return self::$staticpdos[$dbHash];
		}
		return NULL;
	}

	function memcacheConnectErrorHandler($errno, $errstr, $errfile, $errline)
	{
		self::$memcache = null;
		self::$CACHE_ENABLED = false;
		return true;
	}

	protected function getFromCache($cacheKey, $useCachePrefix = TRUE){
		if(self::$memcache){
			if ($this->cachePrefix && $useCachePrefix){
				$cacheKey = $this->cachePrefix."-".$cacheKey;
			}
			try {
				$result = self::$memcache->get($cacheKey);
				return $result;
			} catch (Exception $e) {
			}
		}
		return FALSE;
	}

	protected function putInCache($cacheKey, $ttl, $data){
		if(self::$memcache){
			if ($this->cachePrefix){
				$cacheKey = $this->cachePrefix."-".$cacheKey;
			}

			try {
				self::$memcache->set($cacheKey,$data,0, $ttl);
			} catch (Exception $e) {
			}
		}
	}

	public function deleteFromCache($cacheKey){
		if(self::$memcache){
			if ($this->cachePrefix){
				$cacheKey = $this->cachePrefix."-".$cacheKey;
			}
			self::$memcache->delete($cacheKey);
		}
	}

	protected function incrementCachedVal($cacheKey){
		if(self::$memcache){
			if ($this->cachePrefix){
				$cacheKey = $this->cachePrefix."-".$cacheKey;
			}		return self::$memcache->increment($cacheKey);
		}
		return False;
	}

	public function invalidateCache($ns){
		$ns = $this->getCacheNamespace($ns);
		$this->cachePrefix = null;
		echo "Before updating namespace ".$this->getFromCache($ns).PHP_EOL;
		if (!$this->incrementCachedVal($ns)){
			echo "Unable to increment $ns (".$this->getFromCache($ns).")".PHP_EOL;
			//remove namespace from cache and prey that rand will give us a new int
			$this->deleteFromCache($ns);
		}
		echo "After updating namespace ".$this->getFromCache($ns).PHP_EOL;
		$this->computeCachePrefix($ns);
	}

	protected function computeCachePrefix($ns) {
		$ns = $this->getCacheNamespace($ns);
		$rand = $this->getFromCache($ns);
		if ($rand === FALSE){
			$rand =  rand(1, 10000);
			$this->putInCache($ns,  self::TTL_1_WEEK, $rand);
		}
		$this->cachePrefix = $ns."-$rand";
	}

	private function getCacheNamespace($ns){
		if (defined("CACHE_PREFIX")){
			$ns = CACHE_PREFIX."-$ns";
		}
		return $ns;
	}

	/**
	 * @param Logger $logger
	 * @param $startQuery
	 * @param PDOStatement $stmt
	 * @param array $parameters
	 */
	protected function traceQuery($logger, $startQuery, PDOStatement $stmt, array $parameters) {
		if ($logger->isTraceEnabled ()) {
			$logger->trace ( "Query :\n" . self::parms ( $stmt->queryString, $parameters ) . "\ntook: " . (self::getTimestamp () - $startQuery) );
		}
	}

	private static function parms($string, $data) {
		$indexed = $data == array_values ( $data );
		foreach ( $data as $k => $v ) {
			if (is_string ( $v ))
				$v = "'$v'";
			if ($indexed)
				$string = preg_replace ( '/\?/', $v, $string, 1 );
			else
				$string = str_replace ( ":$k", $v, $string );
		}
		return $string;
	}

	public static function getTimestamp() {
		$seconds = microtime ( true ); // true = float, false = weirdo "0.2342 123456" format
		return round ( ($seconds * 1000) );
	}
}
?>
