<?php

class MongoDatabase {

    static $DEFAULT_SERVER = 'mongodb://localhost:27017';

    static $DEFAULT_DBNAME = 'local';

    static $LAST_ERROR_MESSAGE;

    public function getDb($data = null,$db = null) {

        $server = self::$DEFAULT_SERVER;

        
            $dbname = self::$DEFAULT_DBNAME;


        if (class_exists('WCMSetting')) {    

            if (isset(WCMSetting::$MONGODB_SERVER)) {

                $server = WCMSetting::$MONGODB_SERVER;    

            }

            if (isset(WCMSetting::$MONGODB_NAME)) {

                $dbname = WCMSetting::$MONGODB_NAME;    

            }

        }
        if(isset($data)){
             $server = $data;
        }

        if(isset($db)){
            $dbname = $db;
        }


        try {    

            $mongo = new Mongo($server);    

        } catch (MongoConnectionException $e) {

            self::$LAST_ERROR_MESSAGE = $e->getMessage();

            return null;

        }

        if ($mongo != null) {

            $db = $mongo->selectDB($dbname);

        }

        return $db;

        

    }

}



?>