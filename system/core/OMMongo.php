<?php
namespace OMCore;

use MongoClient;
use MongoCollection;

class OMMongo{
    private function __construct(){
    }
    public static function get()
    {
		static $_OMMongoDB = null;

		if ( $_OMMongoDB == null ){
			$connection_string = MONGO_HOST ;//. ();
			if(MONGO_PORT != ''){
				$connection_string = MONGO_HOST . ':' . MONGO_PORT;
			}
			$_OMMongo = new MongoClient($connection_string , array('connect' => false ));
			// MongoClient::connect();
			$_OMMongoDB = $_OMMongo->selectDB(MONGO_DB);
		}

		return $_OMMongoDB;
    }

    public static function table($tableName){
		$db = self::get();
		$collection = new MongoCollection($db, $tableName);
		return $collection;
	}

	public static function collection(){
		$db = self::get();
		return $db;
	}

}
?>