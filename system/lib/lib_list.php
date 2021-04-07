<?php

use OMCore\OMDb;
use OMCore\OMMongo;


require_once(ROOT_DIR.'system/lib/lib_user.php');


function checkStatusProcessList() {
    $res = array();
    $res["consolidate_queue"] = checkQueueProcessing("consolidate");
    $res["consolidate_result"] = getConsolidateResult("summary");
    $res["merge_queue"] = checkQueueProcessing("merge");

    if (count($res["consolidate_queue"]["current_queue_process"]) > 0) {
        $mongo = OMMongo::get();

        $res["consolidate_queue"]["progress"] = 0;

        if (isset($res["consolidate_queue"]["current_queue_process"][0]["current_progress"]) && isset($res["consolidate_queue"]["current_queue_process"][0]["total_progress"])) {
            if ($res["consolidate_queue"]["current_queue_process"][0]["total_progress"] > 0) {
                 $res["consolidate_queue"]["progress"] = round(($res["consolidate_queue"]["current_queue_process"][0]["current_progress"]/$res["consolidate_queue"]["current_queue_process"][0]["total_progress"])*100);
            }
        }
    }
    if (count($res["merge_queue"]["current_queue_process"]) > 0) {
        $mongo = OMMongo::get();

        $res["merge_queue"]["progress"] = 0;

        $total_merge = $res["merge_queue"]["current_queue_process"][0]["total_merge"];
        if ($total_merge > 0) {
            if ($res["merge_queue"]["current_queue_process"][0]["type"] == "auto") {
                $in_queue = $mongo->consolidate_result->find(array("value.status_auto_merge" => "possible"))->count();
                $done = $total_merge - $in_queue;
                $progress = round(($done/$total_merge)*100);
                $res["merge_queue"]["progress"] = $progress;
            } else {
                $res["merge_queue"]["progress"] = 100;
            }
        }
    }
    return $res;
}



function getConsolidateResult($mode) {
    if ($mode == "summary") {
        $mongo = OMMongo::get();

        $num_consolidate_result = $mongo->consolidate_result->find()->count();
        $num_can_autoMerge = $mongo->consolidate_result->find(array("value.status_auto_merge" => "possible"))->count();
        $num_cannot_autoMerge = $mongo->consolidate_result->find(array("value.status_auto_merge" => "not_possible"))->count();
            
        $res = array();
        $res["num_consolidate_result"] = $num_consolidate_result;
        $res["num_can_autoMerge"] = $num_can_autoMerge;
        $res["num_cannot_autoMerge"] = $num_cannot_autoMerge;

        return $res;
    } else if ($mode == "detail") {

    }
} 


function getDataManualMerge($page) {
    $DB = OMDb::singleton();
    $mongo = OMMongo::get();
    $now = new MongoDate();

    $NUM_PER_PAGE = 10;

    $result = $mongo->consolidate_result->find(array('value.status_auto_merge' => 'not_possible'))->sort(array('_id' => 1))->skip(($page-1)*$NUM_PER_PAGE)->limit($NUM_PER_PAGE);
    $total = $mongo->consolidate_result->find(array('value.status_auto_merge' => 'not_possible'))->count();

    $manual_data = array();
    foreach ($result as $key => $value) {
        $manual_data[] = $value;
    }

    $max_page = ceil($total/$NUM_PER_PAGE);

    $result = array();
    $result["manual_marge_data"] = $manual_data;
    $result["total"] = $total;
    $result["item_per_page"] = $NUM_PER_PAGE;
    $result["max_page"] =  $max_page;


    $id_email = getEmailCustomFieldID();
    $key_email = "cf_".$id_email;

    $custom_field_data = array();
    $custom_field_data["key_email"] = $key_email;
    $custom_field_data["all_list"] = array();
    $custom_field_data["all_key"] = array();
    $dt = null;
    $str_sql = "SELECT custom_field_id,custom_field_mode,custom_field_name,custom_field_type FROM custom_field_list ORDER BY custom_field_name ";
    $res = array();
    $dt = null;
    $r = $DB->query($dt,$str_sql,null,0,-1,"ASSOC");
    for ($i=0;$i<count($dt);$i++) {
        $custom_field_data["all_list"][] = $dt[$i];
        $custom_field_data["all_key"]["cf_".$dt[$i]["custom_field_id"]] = $dt[$i];
    }

    $result["custom_field_data"] = $custom_field_data;

    return $result;
}

function checkQueueProcessing($type) {
    $DB = OMDb::singleton();
    $mongo = OMMongo::get();
    $now = new MongoDate();

    $res = array();

    if ($type == "consolidate") {
        //TODO ลบถ้าเก่ากว่า
        //$mongo->consolidate_queue->remove(array(),array('safe' => true));
        $result = $mongo->consolidate_queue->find(array("status" => "processing"));
        $current_queue_process = array();
        foreach ($result as $key => $value) {
            $user_id = $value["user_id"];

            if ($user_id == -1) {
                $obj_user = array();
                $obj_user["user_id"] = -1;
                $obj_user["username"] = "System";
                $obj_user["user_role_id"] = null;
                $obj_user["user_role_name"] = null;
                $obj_user["create_date"] = null;
                
                $dt["permission_list"] = array();

                $dt = array();
                $dt[] = $obj_user;
            } else {
                $dt = null;
                $str_sql = "SELECT user.username FROM user WHERE user.user_id = @user_id  ";
                $DB->query($dt,$str_sql,array("user_id"=>$user_id),0,-1,"ASSOC");
            }

            $value["user_data"] = $dt;
            $value["create_date"] = date('Y-m-d H:i:s', $value["create_date"]->sec);
            $value["human_create_date"] = humanTiming(strtotime($value["create_date"]));

            $current_queue_process[] = $value;
        }

        $res["queue_summary"] = $result->count();

        $result = $mongo->consolidate_queue->find(array("status" => "complete"))->sort(array('_id' => -1))->limit(1);
        $last_queue_process = array();
        foreach ($result as $key => $value) {
            $user_id = $value["user_id"];

            if ($user_id == -1) {
                $obj_user = array();
                $obj_user["user_id"] = -1;
                $obj_user["username"] = "System";
                $obj_user["user_role_id"] = null;
                $obj_user["user_role_name"] = null;
                $obj_user["create_date"] = null;
                
                $dt["permission_list"] = array();

                $dt = array();
                $dt[] = $obj_user;
            } else {
                $dt = null;
                $str_sql = "SELECT user.username FROM user WHERE user.user_id = @user_id  ";
                $DB->query($dt,$str_sql,array("user_id"=>$user_id),0,-1,"ASSOC");
            }
            $value["user_data"] = $dt;
            $value["create_date"] = date('Y-m-d H:i:s', $value["create_date"]->sec);
            $value["human_create_date"] = humanTiming(strtotime($value["create_date"]));
            $value["modify_date"] = date('Y-m-d H:i:s', $value["modify_date"]->sec);
            $value["human_modify_date"] = humanTiming(strtotime($value["modify_date"]));

            $last_queue_process[] = $value;
        }

        $res["current_queue_process"] = $current_queue_process;
        $res["last_queue_process"] = $last_queue_process;
    } else {
        //TODO ลบถ้าเก่ากว่า
        //$mongo->merge_queue->remove(array(),array('safe' => true));
        $result = $mongo->merge_queue->find(array("status" => "processing"));
        $current_queue_process = array();
        foreach ($result as $key => $value) {
            $user_id = $value["user_id"];

            if ($user_id == -1) {
                $obj_user = array();
                $obj_user["user_id"] = -1;
                $obj_user["username"] = "System";
                $obj_user["user_role_id"] = null;
                $obj_user["user_role_name"] = null;
                $obj_user["create_date"] = null;
                
                $dt["permission_list"] = array();

                $dt = array();
                $dt[] = $obj_user;
            } else {
                $dt = null;
                $str_sql = "SELECT user.username FROM user WHERE user.user_id = @user_id  ";
                $DB->query($dt,$str_sql,array("user_id"=>$user_id),0,-1,"ASSOC");
            }

            $value["user_data"] = $dt;
            $value["create_date"] = date('Y-m-d H:i:s', $value["create_date"]->sec);
            $value["human_create_date"] = humanTiming(strtotime($value["create_date"]));

            $current_queue_process[] = $value;
        }

        $res["queue_summary"] = $result->count();

        $result = $mongo->merge_queue->find(array("status" => "complete"))->sort(array('_id' => -1))->limit(1);
        $last_queue_process = array();
        foreach ($result as $key => $value) {
            $user_id = $value["user_id"];

            if ($user_id == -1) {
                $obj_user = array();
                $obj_user["user_id"] = -1;
                $obj_user["username"] = "System";
                $obj_user["user_role_id"] = null;
                $obj_user["user_role_name"] = null;
                $obj_user["create_date"] = null;
                
                $dt["permission_list"] = array();

                $dt = array();
                $dt[] = $obj_user;
            } else {
                $dt = null;
                $str_sql = "SELECT user.username FROM user WHERE user.user_id = @user_id  ";
                $DB->query($dt,$str_sql,array("user_id"=>$user_id),0,-1,"ASSOC");
            }

            $value["user_data"] = $dt;
            $value["create_date"] = date('Y-m-d H:i:s', $value["create_date"]->sec);
            $value["modify_date"] = date('Y-m-d H:i:s', $value["modify_date"]->sec);

            $last_queue_process[] = $value;
        }

        $res["current_queue_process"] = $current_queue_process;
        $res["last_queue_process"] = $last_queue_process;
    }

    return $res;
}


function updateProgress($consolidate_token,$current_progress) {
    $mongo = OMMongo::get();

    $old_query = array("consolidate_token" => $consolidate_token );
    $new_data = array('$set' => array("current_progress" => $current_progress) );
    $mongo->consolidate_queue->update($old_query, $new_data);
}

function processConsolidate() {
    set_time_limit(0);
    ini_set('memory_limit', '1G');

    $DB = OMDb::singleton();
    $mongo = OMMongo::get();
    $now = new MongoDate();


    $user_id = -1;
    if (isset($_SESSION["OM_USER"])) {
        $user_id = $_SESSION["OM_USER"]["user_id"];
    }

    if (checkSystemPossibleProcess()) {
        $consolidate_token = uniqid();
        $data = array();
        $data["status"] = "processing";
        $data["create_date"] = $now;
        $data["user_id"] = $user_id;
        $data["consolidate_token"] = $consolidate_token;
        $mongo->consolidate_queue->insert($data);

        //===============================================================
        //CONVERT TYPE
        $options = array();
        $options['allowDiskUse'] = true;

        $id_email = getEmailCustomFieldID();
        $key_email = "cf_".$id_email;


        $match = array('$match' => 
                        array(
                            '$and' => array(
                                array( "data.".$key_email => array('$exists' => true ) ),
                                array( "data.".$key_email => array('$ne' => null ) ),
                                array( "insert_status" => "api" )
                            )
                        )
                    );

        
        $group =    array('$group' => array(
                        '_id' => '$data.'.$key_email
                    ));

        $pipeline = array($match,$group);  

        $res = $mongo->list_data->aggregate($pipeline , $options);

        if (isset($res["result"])) {
            for ($i=0;$i<count($res["result"]);$i++) {
                $email = $res["result"][$i]["_id"];

                $finish_time = new MongoDate();
                $old_query = array("data.".$key_email => $email , "insert_status" => "api" );
                $new_data = array('$unset' => array("insert_status" => 1) );

                $mongo->list_data->update($old_query, $new_data);
                afterInsert($key_email,$email);
            }
        }
        //===============================================================
        
        $MODE = "A";

        if ($MODE == "A") {
            session_write_close();

            $collection_out = "consolidate_result_".$consolidate_token;
            $mongo->createCollection($collection_out);
            $mongo->$collection_out->ensureIndex(array("value.status_auto_merge" => 1));

            $options = array();
            $options['allowDiskUse'] = true;
            //$options['socketTimeoutMS'] = -1;

            $id_email = getEmailCustomFieldID();
            $key_email = "cf_".$id_email;


            $match = array('$match' => 
                            array(
                                '$and' => array(
                                    array( 
                                        'count_num_row' => array('$gt' => 1)
                                    )
                                )
                            )
                        );

            
            $group =    array('$group' => array(
                            '_id' => '$data.'.$key_email 
                        ));
          
            $pipeline = array($match,$group);  

            $res = $mongo->list_data->aggregate($pipeline , $options);
            
            $now = new MongoDate();

            if (isset($res["result"])) {
                //UPDATE TOTAL ---------------------------------------------------------------------
                $MAX_LOOP_UPDATE = 1000;
                $CURRENT_LOOP_UPDATE = 0;

                $total = count($res["result"]);
                $old_query = array("consolidate_token" => $consolidate_token );
                $new_data = array('$set' => array("total_progress" => $total,"current_progress" => 0) );

                $mongo->consolidate_queue->update($old_query, $new_data);
                //----------------------------------------------------------------------------------

                for ($k=0;$k<count($res["result"]);$k++) {
                    $CURRENT_LOOP_UPDATE++;

                    $email = $res["result"][$k]["_id"];

                    $res2 = $mongo->list_data->find(array("data.".$key_email => $email),array('_id' => 1,  'data' => 1, 'create_date' => 1 ))->sort(array('create_date' => 1 ));
            
                    $obj = array();
                    $obj["bad_data"] = 'T'; 
                    $new_obj = array();
                    $create_date = '';
                    $count_consolidate_case = 0;
                    $all_case_consolidate = array();
                    $all_case_consolidate_id = array();
                
                    //for ($i=0; $i<count($res2["result"]); $i++) {
                        //$vals = $res2["result"][$i];
                    $i = 0;
                    foreach ($res2 as $_k => $vals) {

                        if (isset($vals["data"])) { 
                        
                            $all_key = array_keys($vals["data"]);
                            $sub_count_consolidate_case = 0;
                            for ($j=0;$j<count($all_key) && $sub_count_consolidate_case == 0;$j++) {
                                $_key = $all_key[$j]; 

                                if (isset($new_obj[$_key])) { 
                                    if ($_key == "cf_2") {
                                        $a = explode(",",str_replace(' ', '',$new_obj[$_key]));
                                        $b = explode(",",str_replace(' ', '',$vals["data"][$_key]));

                                        $new_array =  array_unique(array_merge($a,$b));

                                        $new_obj[$_key] = implode(", ",$new_array);
                                    } else {
                                        if ($new_obj[$_key] == $vals["data"][$_key]) { 

                                        } else if ($new_obj[$_key] == "" && $vals["data"][$_key] != "") { 
                                            $new_obj[$_key] = $vals["data"][$_key]; 
                                        } else { 
                                            $count_consolidate_case++; 
                                            $sub_count_consolidate_case++; 
                                        } 
                                    }
                                } else { 
                                    $new_obj[$_key] = $vals["data"][$_key]; 
                                } 
                            } 
                            $all_case_consolidate_id[] = $vals["_id"]; 
                            $all_case_consolidate[] = $vals["data"]; 
                    
                            $obj["bad_data"] = 'F'; 

                            if ($i == 0) {
                                $obj["create_date"] = $vals["create_date"]; 
                            }
                        }
                        $i++;
                    }

                    if ($count_consolidate_case > 0) {
                        $obj["status_auto_merge"] = 'not_possible';
                        $obj["all_case_consolidate"] = $all_case_consolidate;
                        $obj["count_consolidate_case"] = $count_consolidate_case;
                    } else {
                        $obj["new_obj"] = $new_obj; 
                        $obj["status_auto_merge"] = 'possible'; 
                    }
                    $obj["all_case_consolidate_id"] = $all_case_consolidate_id; 
            
                    if ($obj["bad_data"] == "F") {
                        $obj2 = array();
                        $obj2["_id"] = $email;
                        $obj2["value"] = $obj;
                        $mongo->$collection_out->insert($obj2);
                    }


                    if ($k%$MAX_LOOP_UPDATE == 0) {
                        updateProgress($consolidate_token,$CURRENT_LOOP_UPDATE);
                    }
                }
                updateProgress($consolidate_token,$CURRENT_LOOP_UPDATE);
            }


            $query = array("renameCollection" => MONGO_DB.".".$collection_out, "to" => MONGO_DB."."."consolidate_result", "dropTarget" => "true");
            $mongo_c = (new MongoClient());
            $mongo_admin = $mongo_c->admin;
            $s = $mongo_admin->command($query);
        }
        //===============================================================
        if ($MODE == "B") {
            $dt = null;
            $str_sql = "SELECT custom_field_id,custom_field_name,allow_consolidate FROM custom_field_list  ";
            $res = array();
            $dt = null;
            $r = $DB->query($dt,$str_sql,null,0,-1,"ASSOC");



            $uniqueKey = "";
            $listCustomfield = array();
            for ($i=0;$i<count($dt);$i++) {
                if (strtolower($dt[$i]["allow_consolidate"]) == "true") {
                    $uniqueKey = "cf_" . $dt[$i]["custom_field_id"];
                } else {
                    $listCustomfield[] = "cf_" . $dt[$i]["custom_field_id"];
                }
            }

            $id_email = getEmailCustomFieldID();
            $key_email = "cf_".$id_email;


            //$mongo->consolidate_result->remove(array(),array('safe' => true));

            $map_str = "function() { ";
            
            $map_str.= "var _obj = new Object(); ";
            $map_str.= "_obj.temp_id = this._id;";
            $map_str.= "_obj.data = this.data;";
            $map_str.= "_obj.create_date = this.create_date;";
            $map_str.= "_obj.status_auto_merge = 'possible'; ";
            //$map_str.= "obj.modify_date = this.modify_date;";
            //$map_str.= "_obj.count_num_row = this.count_num_row;";
            
            $map_str.= "emit(this.data.".$key_email.", _obj); }";

            $map = new MongoCode($map_str);

            $reduce_str = "function(key, vals) { ";
            
            $reduce_str.=   "var obj = new Object();";
            $reduce_str.=   "var new_obj = new Object();";
            $reduce_str.=   "var create_date = '';";
            $reduce_str.=   "var count_consolidate_case = 0;";
            $reduce_str.=   "var all_case_consolidate = new Array();";
            $reduce_str.=   "var all_case_consolidate_id = new Array();";
            
           

            $reduce_str.=   "for (var i=0; i<vals.length; i++) {";

            $reduce_str.=       "if (create_date == '' ) { ";
            $reduce_str.=       "   create_date = vals[i].create_date; ";
            $reduce_str.=       "} else { ";
            $reduce_str.=       "   var d1 = new Date(create_date).getTime(); ";
            $reduce_str.=       "   var d2 = new Date(vals[i].create_date).getTime(); ";
            $reduce_str.=       "   if (d2 < d1) { ";
            $reduce_str.=       "       create_date = vals[i].create_date; ";
            $reduce_str.=       "   }";
            $reduce_str.=       "} ";

            $reduce_str.=       "if (vals[i].data != null && vals[i].data != undefined ) { ";
            
            $reduce_str.=           "var all_key = Object.keys(vals[i].data);";
            $reduce_str.=           "var sub_count_consolidate_case = 0;";
            $reduce_str.=           "for (var j=0;j<all_key.length && sub_count_consolidate_case == 0;j++) {";
            $reduce_str.=               "var _key = all_key[j]; ";

            $reduce_str.=               "if (new_obj[_key] != null && new_obj[_key] != undefined) { ";
            
            $reduce_str.=                       "if (new_obj[_key] == vals[i].data[_key]) { ";

            $reduce_str.=                       "} else if (new_obj[_key] == '' && vals[i].data[_key] != '') { ";
            $reduce_str.=                           "new_obj[_key] = vals[i].data[_key]; ";
            $reduce_str.=                       "} else { ";
            $reduce_str.=                           "count_consolidate_case++; ";
            $reduce_str.=                           "sub_count_consolidate_case++; ";
            $reduce_str.=                       "} ";
            $reduce_str.=               "} else { ";
            $reduce_str.=                   "new_obj[_key] = vals[i].data[_key]; ";
            $reduce_str.=               "} ";
            $reduce_str.=           "} ";
            $reduce_str.=           "all_case_consolidate_id.push(vals[i].temp_id); ";
            $reduce_str.=           "all_case_consolidate.push( vals[i].data ); ";
            
            $reduce_str.=           "obj.bad_data = 'F'; ";
            $reduce_str.=       "} else {";
            $reduce_str.=           "obj.bad_data = 'T'; ";
            $reduce_str.=       "}";
            $reduce_str.=   "}";
            
            $reduce_str.=   "if (count_consolidate_case > 0) {";
            $reduce_str.=   " obj.status_auto_merge = 'not_possible'; ";
            $reduce_str.=   " obj.all_case_consolidate = all_case_consolidate; ";
            $reduce_str.=   " obj.count_consolidate_case = count_consolidate_case; ";
            $reduce_str.=   "} else {";
            $reduce_str.=   " obj.new_obj = new_obj; ";
            $reduce_str.=   " obj.status_auto_merge = 'possible'; ";
            $reduce_str.=   "}";
            $reduce_str.=   "obj.all_case_consolidate_id = all_case_consolidate_id; ";
            $reduce_str.=   "obj.create_date = create_date; ";
            
            //$reduce_str.=   "obj.data_vals = vals; ";
            //$reduce_str.=   "obj.key = key; ";
            //$reduce_str.=   "obj.count_num_row = vals[0].count_num_row; ";
            $reduce_str.= "return obj; }";

            $reduce = new MongoCode($reduce_str);

            $collection_out = "consolidate_result";

            session_write_close();

            $mongo->command(array(
                "mapreduce" => "list_data",
                "map" => $map,
                "reduce" => $reduce,
                "query" => array( 
                        'count_num_row' => array('$gt' => 1)
                    ),

                "out" => $collection_out ),
                array('socketTimeoutMS' => -1));
        }

        sleep(3);

        $finish_time = new MongoDate();
        $old_query = array('status'=> 'processing' );
        $new_data = array('$set'=>array('status'=>'complete','modify_date'=>$finish_time));
        $mongo->consolidate_queue->update($old_query, $new_data);
    }
}

function checkSystemPossibleProcess() {
    $DB = OMDb::singleton();
    $mongo = OMMongo::get();
    $now = new MongoDate();

    $queue_processing = checkQueueProcessing("consolidate");
    $merge_processing = checkQueueProcessing("merge");

    if ($queue_processing["queue_summary"] > 0 || $merge_processing["queue_summary"] > 0) {
        $res = array();
        $res["status"] = "error";
        if ($queue_processing["queue_summary"] > 0) {
            $res["data"] =  $queue_processing;
        } else {
            $res["data"] =  $merge_processing;
        }
        $res["msg"] = "Server busy some user processing Consolidate/Merge list data, please try again later.";
        echo json_encode($res);
        exit();
    } else {
        return true;
    }
}

function processMerge($mode=null,$option=null) {
    $MAX_PER_LOOP = 10000;

    $DB = OMDb::singleton();
    $mongo = OMMongo::get();
    $now = new MongoDate();

    $id_email = getEmailCustomFieldID();
    $key_email = "cf_".$id_email;

    if ($mode == "auto" || $mode == "manual") {
        $cf_type_date = getCustomFieldTypeDate();

        $user_id = -1;
        if (isset($_SESSION["OM_USER"])) {
            $user_id = $_SESSION["OM_USER"]["user_id"];
        }

        if (checkSystemPossibleProcess()) {
            $total_merge = 0;

            if ($mode == "auto") {
                $total_merge = $mongo->consolidate_result->find(array('value.status_auto_merge' => 'possible'))->count();
            } else {
                $email = $option["email"];
                $new_obj = $option["new_obj"];
                $total_merge = $mongo->list_data->find(array('data.'.$key_email => $email , "insert_status"=>null ))->count();
            }

            $data = array();
            $data["status"] = "processing";
            $data["type"] = $mode;
            $data["total_merge"] = $total_merge;
            $data["current_merge"] = 0;
            $data["create_date"] = $now;
            $data["user_id"] = $user_id;
            $mongo->merge_queue->insert($data);

            session_write_close();

            $summary_insert = 0;
            $summary_not_insert = 0;

            $do_insert = 1;

            while ($do_insert > 0) {
                if ($mode == "auto") {
                    $mongo = OMMongo::get();
                    $result = $mongo->consolidate_result->find(array('value.status_auto_merge' => 'possible'))->limit($MAX_PER_LOOP);
                } else {
                    $result = array();
                    
                    $mongo = OMMongo::get();
                    $_result = $mongo->consolidate_result->find(array('_id' => $email));
                    $result = array();
                    foreach ($_result as $key => $value) {
                        $result[] = $value;
                    }
                    if (count($result) == 1) {
                        $result[0]["value"]["new_obj"] = $option["new_obj"];
                    }
                }
                $do_insert = 0;
                foreach ($result as $key => $value) {
                    $mongo_id = $value['_id'];

                    if (isset($value['value']["bad_data"])) {
                        if ($value['value']["bad_data"] == "F") {
                            if (isset($value['value']["new_obj"])) {
                                $data = array();

                                $data["data"] = $value['value']["new_obj"];
                                $data["ref_str"] = array();
                                $all_new_key = array_keys($value['value']["new_obj"]);
                                for ($j=0;$j<count($all_new_key);$j++) {
                                    $_key = $all_new_key[$j]; 
                                    if (isset($cf_type_date[$_key])) { 
                                        if (isset($value['value']["new_obj"][$_key]->sec)) {
                                            $data["ref_str"][$_key] = date('Y-m-d H:i:s', $value['value']["new_obj"][$_key]->sec); 
                                        } else if (isset($value['value']["new_obj"][$_key]["sec"])) {
                                            $data["ref_str"][$_key] = date('Y-m-d H:i:s', $value['value']["new_obj"][$_key]["sec"]); 

                                            $data["data"][$_key] = new MongoDate(strtotime($data["ref_str"][$_key]));
                                        }
                                    } 
                                } 
                                $data["create_date"] = $value['value']["create_date"];
                                $data["ref_str"]["create_date"] = date('Y-m-d H:i:s', $value['value']["create_date"]->sec); 
                                $data["modify_date"] = $now;

                                if ($mode == "auto") {
                                    $do_insert++;
                                }

                                $char_explode = "||";
                                $temp_key_list = array();
                                $temp_in_list = array();
                                $temp_event_data = array();
                                //TODO CASE NOT AUTO ADD
                                if (true) {
                                    if (isset($value['value']["all_case_consolidate_id"])) {
                                        $all_case_consolidate_id = $value['value']["all_case_consolidate_id"];
                                        for ($i=0;$i<count($all_case_consolidate_id);$i++) {
                                            $case_consolidate_id = $all_case_consolidate_id[$i];

                                            $obj_data = $mongo->list_data->find(array('_id' => $case_consolidate_id ));

                                            foreach ($obj_data as $_k => $obj) {
                                                if (isset($obj["event_data"])) {
                                                    //$obj["event_data"]
                                                    for ($j=0;$j<count($obj["event_data"]);$j++) {
                                                        $temp_event_data[] = $obj["event_data"][$j];
                                                    }
                                                }
                                                if (isset($obj["in_list"])) {
                                                    $_in_list = explode($char_explode, $obj["in_list"]);
                                                    for ($j=0;$j<count($_in_list);$j++) {
                                                        if ($_in_list[$j] != "") {
                                                            if (isset($temp_key_list["k_".$_in_list[$j]])) {

                                                            } else {
                                                                $temp_key_list["k_".$_in_list[$j]] = true;
                                                                $temp_in_list[] = $_in_list[$j];
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            $mongo->list_data->remove(array('_id' => $case_consolidate_id ));
                                        }
                                    }

                                    if (count($temp_event_data) > 0) {
                                        $data["event_data"] = $temp_event_data;
                                    }
                                    if (count($temp_in_list) > 0) {
                                        $string_list = join($char_explode, $temp_in_list);

                                        $data["in_list"] = $char_explode . $string_list . $char_explode;
                                    }

                                    $mongo->list_data->insert($data);
                                    $summary_insert++;

                                    afterInsert($key_email,$data["data"][$key_email]);
                                } else {
                                    $summary_not_insert++;
                                }
                            }
                        }
                    } else {
                        afterInsert($key_email,$value['value']["data"][$key_email]);
                    }
                    $mongo->consolidate_result->remove(array('_id' => $mongo_id ));
                }

                usleep(200);
            }

            if ($mode == "auto") {
                sleep(3);
            } else {
                sleep(2);
            }

            $finish_time = new MongoDate();
            $old_query = array('status'=> 'processing' );
            $new_data = array('$set'=>array('status'=>'complete','modify_date'=>$finish_time));
            $mongo->merge_queue->update($old_query, $new_data);
        }
    }
}

function afterInsert($key_email,$value_email) {
    $mongo = OMMongo::get();
    $r1 = $mongo->list_data->find(array("data.".$key_email=>$value_email , "insert_status"=>null ), array('_id' => 1) )->count();

    $old_data = array("data.".$key_email=>$value_email, "insert_status"=>null);
    $new_data = array('$set'=>array('count_num_row'=>$r1));
    $r2 = $mongo->list_data->update($old_data, $new_data, array('multiple' => true));
}

function getEmailCustomFieldID() {
    $DB = OMDb::singleton();

    $str_sql = "SELECT custom_field_id FROM custom_field_list WHERE allow_consolidate = 'true' ";
    $dt = null;
    $r = $DB->query($dt,$str_sql,null,0,-1,"ASSOC");
    if ($r == 1) {
        return $dt[0]["custom_field_id"];
    } else {
        return -1;
    }
}

function getCustomFieldTypeDate() {
    $DB = OMDb::singleton();

    $obj = array();
    $str_sql = "SELECT custom_field_id FROM custom_field_list WHERE custom_field_type = 'datetime' ";
    $dt = null;
    $r = $DB->query($dt,$str_sql,null,0,-1,"ASSOC");
    for ($i=0;$i<$r;$i++) {
        $obj["cf_".$dt[$i]["custom_field_id"]] = "T";
    }

    return $obj;
}

?>