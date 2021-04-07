<?php
class OMWCMUtil {
	
    
    static $ITEM_STATUS_ACTIVE = "active";
    static $ITEM_STATUS_DELETED = "deleted";
    static $ITEM_STATUS_ARCHIVED = "archived";
    static $ITEM_STATE_DRAFT = "draft";
    static $ITEM_STATE_PUBLISHED = "published";
    static $ITEM_STATE_PUBLISHED_WITH_CHANGE = "published_ch";
	static $ACTION_CMD_CREATE = "create";
	static $ACTION_CMD_MODIFY = "modify";
	static $ACTION_CMD_PUBLISH = "publish";
    static $ACTION_CMD_DELETE = "delete";
    static $ACTION_CMD_REVERT = "revert";
    static $ACTION_CMD_UNPUBLISH = "unpublish";
    static $ACTION_CMD_UNPUBLISH_WITH_DRAFT = "unpublish_with_draft";
    static $ACTION_CMD_UNPUBLISH_WITH_RECENT_DRAFT = "unpublish_with_recent_draft";
    static $ACTION_CMD_CHANGE_KEY = "change_key";
    static $ACTION_CMD_PURGE_LOG = "purge_log";
    static $ACTION_CMD_IMPORT_DATA = "import_data";
	

	static $ACTION_CHILD_CMD_CREATE = "create";
	static $ACTION_CHILD_CMD_SAVE = "save";
	static $ACTION_CHILD_CMD_PUBLISH = "publish";
	static $ACTION_CHILD_CMD_UNPUBLISH = "unpublish";
	static $ACTION_CHILD_CMD_DELETE = "delete";
	static $ACTION_CHILD_CMD_CHANGE_KEY = "change_key";        
		
	static function formatLogActionType($cmd) {
        switch ($cmd) {
            case self::$ACTION_CMD_CREATE: return "Create";
            case self::$ACTION_CMD_MODIFY: return "Modify";
            case self::$ACTION_CMD_PUBLISH: return "Publish";
            case self::$ACTION_CMD_REVERT: return "Revert";
            case self::$ACTION_CMD_UNPUBLISH: return "Unpublish";
            case self::$ACTION_CMD_UNPUBLISH_WITH_DRAFT: return "Unpublish";
            case self::$ACTION_CMD_DELETE: return "Delete";
            case self::$ACTION_CMD_CHANGE_KEY: return "Relocate";
            case self::$ACTION_CMD_PURGE_LOG: return "Purge Log";
            case self::$ACTION_CMD_IMPORT_DATA: return "Import Data";
            default: return "Unknow";
        }
    }
	static function formatLogActionChildType($cmd) {
        switch ($cmd) {
            case self::$ACTION_CHILD_CMD_CREATE: return "Create";
            case self::$ACTION_CHILD_CMD_SAVE: return "Modify";
            case self::$ACTION_CHILD_CMD_PUBLISH: return "Publish";
            case self::$ACTION_CHILD_CMD_UNPUBLISH: return "Unpublish";
            case self::$ACTION_CHILD_CMD_DELETE: return "Delete";
            case self::$ACTION_CHILD_CMD_CHANGE_KEY: return "Relocate";
            default: return "Unknow";
        }
    }
	static function formatLogMessage($isSuccess, $cmd, $info , $param = null) {
        $msg = "";
        $template = "";
        $module_name = $info["module_name"];
        $username = $info["username"];
		if ($username == "") {
			$username = "system";
		}
        if ($isSuccess) {
            switch ($cmd) {
                case self::$ACTION_CMD_CREATE:
                    $template = "\"%s\" item was created by \"%s\"";
                    break;
                case self::$ACTION_CMD_MODIFY:
                    $template = "\"%s\" item was modified by \"%s\"";
                    break;
                case self::$ACTION_CMD_PUBLISH:
                    $template = "\"%s\" item was published by \"%s\"";
                    break;
                case self::$ACTION_CMD_REVERT:
                    $template = "\"%s\" item was reverted by \"%s\"";
                    break;
                case self::$ACTION_CMD_UNPUBLISH:
                    $template = "\"%s\" item was unpublished by \"%s\"";
                    break;
                case self::$ACTION_CMD_UNPUBLISH_WITH_DRAFT:
                    $template = "\"%s\" item was unpublished by \"%s\"";
                    break;
                case self::$ACTION_CMD_UNPUBLISH_WITH_RECENT_DRAFT:
                    $template = "\"%s\" item was unpublished by \"%s\"";
                    break;
                case self::$ACTION_CMD_DELETE:
                    $template = "\"%s\" item was deleted by \"%s\"";
                    break;
                case self::$ACTION_CMD_CHANGE_KEY:
                    $template = "\"%s\" item was relocated by \"%s\"";
                    break;
                case self::$ACTION_CMD_PURGE_LOG:
                    $template = "\"%s\" item was purged log older " . $param["date_purge"] . " by \"%s\"";
                    break;
                case self::$ACTION_CMD_IMPORT_DATA:
                    $template = "\"%s\" item was imported at " . $param["date_import"] . " by \"%s\"";
                    break;
                default:
                    $template = "Unknow";
                    break;
            }
        } else {
            switch ($cmd) {
                case self::$ACTION_CMD_CREATE:
                    $template = "Creating the \"%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CMD_MODIFY:
                    $template = "modifying the \"%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CMD_PUBLISH:
                    $template = "Publishing the \"%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CMD_REVERT:
                    $template = "Reverting the \"%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CMD_UNPUBLISH:
                    $template = "Unpublishing the \"%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CMD_UNPUBLISH_WITH_DRAFT:
                    $template = "Unpublishing the \"%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CMD_UNPUBLISH_WITH_RECENT_DRAFT:
                    $template = "Unpublishing the \"%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CMD_DELETE:
                    $template = "Deleting the \"%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CMD_CHANGE_KEY:
                    $template = "Relocating the \"%s\" item by \"%s\"";
                    break;
                default:
                    $template = "Unknow error";
                    break;
            }
        }
        $msg = sprintf($template, $module_name, $username);
        return $msg;
    }
	static function formatLogMessageChild($isSuccess, $cmd, $info) {
        $msg = "";
        $template = "";
        $module_name = $info["module_name"];
        $username = $info["username"];
		if ($username == "") {
			$username = "system";
		}		
        $child_name = $info["child_name"];
        if ($isSuccess) {
            switch ($cmd) {
                case self::$ACTION_CHILD_CMD_CREATE:
                    $template = "\"%s:%s\" item was created by \"%s\"";
                    break;
                case self::$ACTION_CHILD_CMD_SAVE:
                    if ($info["item_id"] == 0) {
                        $template = "\"%s:%s\" item was reconciled by \"%s\"";
                    } else {
                        $template = "\"%s:%s\" item was modified by \"%s\"";
                    }
                    break;
                case self::$ACTION_CHILD_CMD_PUBLISH:
                    $template = "\"%s:%s\" item was published by \"%s\"";
                    break;
                case self::$ACTION_CHILD_CMD_UNPUBLISH:
                    $template = "\"%s:%s\" item was unpublished by \"%s\"";
                    break;
                case self::$ACTION_CHILD_CMD_DELETE:
                    $template = "\"%s:%s\" item was deleted by \"%s\"";
                    break;
                case self::$ACTION_CHILD_CMD_CHANGE_KEY:
                    $template = "\"%s:%s\" item was relocated by \"%s\"";
                    break;
                default:
                    $template = "Unknow";
                    break;
            }
        } else {
            switch ($cmd) {
                case self::$ACTION_CHILD_CMD_CREATE:
                    $template = "Creating the \"%s:%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CHILD_CMD_SAVE:
                    if ($info["item_id"] == 0) {
                        $template = "Reconciling the \"%s:%s\" item by \"%s\"";
                    } else {
                        $template = "modifying the \"%s:%s\" item by \"%s\"";
                    }
                    break;
                case self::$ACTION_CHILD_CMD_PUBLISH:
                    $template = "Publishing the \"%s:%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CHILD_CMD_UNPUBLISH:
                    $template = "Unpublishing the \"%s:%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CHILD_CMD_DELETE:
                    $template = "Deleting the \"%s:%s\" item by \"%s\"";
                    break;
                case self::$ACTION_CHILD_CMD_CHANGE_KEY:
                    $template = "Relocating the \"%s:%s\" item by \"%s\"";
                    break;
                default:
                    $template = "Unknow error";
                    break;
            }
        }
        $msg = sprintf($template, $module_name, $child_name, $username);
        return $msg;
    }

	static function action($cmd, $DB, $data, &$result) {
		$log = OMLog::Current();
	
		$item_id = $data["key"]["item_id"];
		$revision = $data["key"]["revision"];
		$language = $data["key"]["language"];
		
		$module_name = $data["module_name"];
		$username=OMSession::Current()->Username;
		
		if (strlen($module_name) > 0) {
			$module_name[0] = strtoupper($module_name[0]);           
        }
		
		$log_item_id = $item_id;
        $log_lang = $language;
        $log_rev = $revision;
		
		$r = OMWCMUtil::_action($cmd, $DB, $data, $result);
		if ($result != null) {
			if (isset($result["item_id"])) $log_item_id = $result["item_id"];
			if (isset($result["language"])) $log_lang = $result["language"];
			if (isset($result["revision"])) $log_rev = $result["revision"];
		}
		$loginfo = array();
        $loginfo["module_name"] = $module_name;
        $loginfo["username"] = $username;
		$ainfo = array("data"=>$data, "result"=>$result);
		if ($r) {
            if ($log != null) $log->writeActionLog(self::formatLogMessage($r, $cmd, $loginfo), "Information", $module_name, self::formatLogActionType($cmd), OMJson::encode($ainfo),null, null, null, $log_item_id, $log_lang, $log_rev);
        } else {
            if ($log != null) $log->writeActionLog(self::formatLogMessage($r, $cmd, $loginfo), "Error", $module_name, self::formatLogActionType($cmd), OMJson::encode($ainfo),null, null, null, $log_item_id, $log_lang, $log_rev);
        }
		return $r;
	}
	
	static function actionChild($cmd, $DB, $data, &$result) {
		$log = OMLog::Current();
	
		$parent_id = $data["key"]["parent_id"];		
		$revision = $data["key"]["revision"];
		$language = $data["key"]["language"];
		
		$module_name = $data["module_name"];
		$child_name = $data["tbl"]["online"];
		$username=OMSession::Current()->Username;
		
		if (strlen($module_name) > 0) {
			$module_name[0] = strtoupper($module_name[0]);           
        }
		
		$log_parent_id = $parent_id;
        $log_lang = $language;
        $log_rev = $revision;
		
		$r = OMWCMUtil::_actionChild($cmd, $DB, $data, $result);
		
		$loginfo = array();
        $loginfo["module_name"] = $module_name;
        $loginfo["username"] = $username;
        $loginfo["child_name"] = $child_name;
		if(isset($data["key"]["item_id"])) {
			$loginfo["item_id"] = $data["key"]["item_id"];
		} else {
			$loginfo["item_id"] = 0;
		}
		if ($result != null) {
			if (isset($result["parent_id"])) $log_parent_id = $result["parent_id"];
			if (isset($result["language"])) $log_lang = $result["language"];
			if (isset($result["revision"])) $log_rev = $result["revision"];
		}
		$loginfo["parent_id"] = $log_parent_id;
		$loginfo["language"] = $log_lang;
		$loginfo["revision"] = $log_rev;
		
		$ainfo = array("data"=>$data, "result"=>$result);
		
		if ($r) {
            if ($log != null) $log->writeActionLog(self::formatLogMessageChild($r, $cmd, $loginfo), "Information", $module_name, self::formatLogActionChildType($cmd), OMJson::encode($ainfo),null, null, null, $log_parent_id, $log_lang, $log_rev);
        } else {
            if ($log != null) $log->writeActionLog(self::formatLogMessageChild($r, $cmd, $loginfo), "Error", $module_name, self::formatLogActionChildType($cmd), OMJson::encode($ainfo),null, null, null, $log_parent_id, $log_lang, $log_rev);
        }

		return $r;
	}
	
	static function _action($cmd, $DB, $data, &$result) {
		$result = array();
		$sql_params = array();
		$pdate = OMDateTime::Now();
		
		if (isset($data["module_name"])) {
            $module_name = $data["module_name"];
        } else {
            $module_name = "WCMUtil";
        }

		$table_draft = (isset($data["tbl"]["draft"]))?$data["tbl"]["draft"]:null;
		$table_online = (isset($data["tbl"]["online"]))?$data["tbl"]["online"]:null;
		$field_id_draft = (isset($data["field_id"]["draft"]))?$data["field_id"]["draft"]:null;
		$field_id_online = (isset($data["field_id"]["online"]))?$data["field_id"]["online"]:null;
		$language = (isset($data["key"]["language"]))?$data["key"]["language"]:null;
		$wcm_user_id = (isset($data["key"]["user_id"]))?$data["key"]["user_id"]:0;

        if ($language == null || $language == "" || $table_draft == null || $table_draft == "" || $field_id_draft == null || $field_id_draft == "") {
            $result["detail"] = "Unexpected parameters";
            $result["sql"] = "";
            $result["param"] = "";
            $result["error"] = "";
            $DB->rollBack();
            return false;
        }
        if ($module_name == "WCMUtil") {
            $module_name = "WCMUtil." + $table_draft;
        }

		if ($cmd == self::$ACTION_CMD_CREATE) {
            $item_id = 0;
            if (isset($data["key"]["item_id"]) && $data["key"]["item_id"] != null) {
                $item_id = $data["key"]["item_id"];
            }
            if ($item_id == 0) {
                $item_id = $DB->getRunningNumber($table_draft);
            }
            $revision = 1;
			//
			$sql_string = "select max(obj_rev) as  max_obj_rev from " . $table_draft . " where `" . $field_id_draft . "` = @" . $field_id_draft . " and obj_lang = @obj_lang ";
			$sql_param["@" . $field_id_draft] = $item_id;
			$sql_param["@obj_lang"] = $language;
			$r = $DB->query($dt, $sql_string, $sql_param);
			if ($r > 0) {
				$revision = $DB->getLong($dt,0,"max_obj_rev", null);
				if ($revision != null) {
					$revision ++;
				} else {
					$revision = 1;
				}
			}
			//
            $sql_params2 = array();
            foreach ($data["fielddata"] as $field_key => $field_value) {
                $sql_params2[$field_key] = $field_value;
            }
            $sql_params2[$field_id_draft] = $item_id;
            $sql_params2["obj_lang"] = $language;
            $sql_params2["obj_rev"] = $revision;
            $sql_params2["obj_created_date"] = $pdate;
            $sql_params2["obj_created_user_id"] = $wcm_user_id;
            $sql_params2["obj_modified_date"] = $pdate;
            $sql_params2["obj_modified_user_id"] = $wcm_user_id;
            $sql_params2["obj_status"] = self::$ITEM_STATUS_ACTIVE;
            $sql_params2["obj_state"] = self::$ITEM_STATE_DRAFT;

            $r2 = $DB->executeInsert($table_draft, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to publish item to destination.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }

            self::removeOldRevision($DB, $module_name, $table_draft, $item_id, $language, $revision);

            $result["item_id"] = $item_id;
            $result["revision"] = $revision;
            $result["language"] = $language;
            $result["modified_date"] = $pdate;
            $DB->commit();
            return true;
        }
		
		$item_id = (isset($data["key"]["item_id"]))?$data["key"]["item_id"]:null;
        $revision = (isset($data["key"]["revision"]))?$data["key"]["revision"]:null;
        
		if ($item_id == null || $revision == null) {		
            $result["detail"] = "Unexpected parameters";
            $result["sql"] = "";
            $result["param"] = "";
            $result["error"] = "";
            $DB->rollBack();
            return false;
        }

        $sql_string = "select * from " . $table_draft . " where `" . $field_id_draft . "` = @" . $field_id_draft . " and obj_lang = @obj_lang and obj_rev = @obj_rev ";
        $sql_param["@" . $field_id_draft] = $item_id;
        $sql_param["@obj_lang"] = $language;
        $sql_param["@obj_rev"] = $revision;
        $r = $DB->query($dt, $sql_string, $sql_param);
        if ($r != 1 || count($dt) != 1) {
            $result["detail"] = "Unable to retieve data.";
            $result["sql"] = $DB->LastSQLQueryString;
            $result["param"] = OMJson::encode($sql_param);
            $result["error"] = $DB->LastErrorMessage;
            $DB->rollBack();
            return false;
        }

        $item_status = $DB->getString($dt, 0, "obj_status", "");
        $item_state = $DB->getString($dt, 0, "obj_state", "");

		if ($cmd == self::$ACTION_CMD_MODIFY && ($item_state == self::$ITEM_STATE_DRAFT || $item_state == self::$ITEM_STATE_PUBLISHED_WITH_CHANGE) && $item_status == self::$ITEM_STATUS_ACTIVE) {
            $sql_params2=array();
            $sql_params2[$field_id_draft] = $item_id;
            $sql_params2["obj_lang"] = $language;
            $sql_params2["obj_rev"] = $revision;
            foreach ($data["fielddata"] as $field_key=>$field_value) {
                $sql_params2[$field_key] = $field_value;
            }

            $sql_params2[$field_id_draft] = $item_id;
            $sql_params2["obj_lang"] = $language;
            $sql_params2["obj_rev"] = $revision;
            $sql_params2["obj_created_date"] = $pdate;
            $sql_params2["obj_created_user_id"] = $wcm_user_id;
            $sql_params2["obj_modified_date"] = $pdate;
            $sql_params2["obj_modified_user_id"] = $wcm_user_id;
            $r2 = $DB->executeUpdate($table_draft, 3, $sql_params2);

            if ($r2 < 0) {
                $result["detail"] = "Unable to save.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }

            $result["item_id"] = $item_id;
            $result["revision"] = $revision;
            $result["language"] = $language;
            $result["modified_date"] = $pdate;

            $DB->commit();
            return true;
        } else if ($cmd == self::$ACTION_CMD_MODIFY && $item_state == self::$ITEM_STATE_PUBLISHED && $item_status == self::$ITEM_STATUS_ACTIVE) {
            $sql_params2 = array();
            $sql_params2["@" . $field_id_draft] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_params2["@obj_rev"] = $revision;
            $sql_string2 = "update " . $table_draft . " set obj_status = '" . self::$ITEM_STATUS_ARCHIVED . "' where `" . $field_id_draft . "` = @" . $field_id_draft . " and obj_lang = @obj_lang and obj_rev = @obj_rev ";
            $r2 = $DB->execute($sql_string2, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to unpublish recent item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $next_rev=1;
            $sql_params2 = array();
            $sql_params2["@" . $field_id_draft] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_string2 = "select max(obj_rev) as max_obj_rev from " . $table_draft . " where obj_lang = @obj_lang and `" . $field_id_draft . "` = @" . $field_id_draft . "  ";
            $r2 = $DB->query($dt2, $sql_string2, $sql_params2, 0, 1);
            if ($r2 <= 0) {
                $result["detail"] = "Unable to get next revision.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
			$next_rev = $DB->getLong($dt2,0,"max_obj_rev", null);
			if ($next_rev == null) {
				$result["detail"] = "Unable to get next revision.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
			}
			$next_rev++;
            
            $sql_params2 = array();
            foreach ($dt[0] as $row_key => $row_value) {
				if (!is_numeric($row_key)) {
					$sql_params2[$row_key] = $row_value;
				}
			}

            foreach ($data["fielddata"] as $field_key => $field_value) {
                $sql_params2[$field_key] = $field_value;
            }
            $sql_params2[$field_id_draft] = $item_id;
            $sql_params2["obj_lang"] = $language;
            $sql_params2["obj_rev"] = $next_rev;
            $sql_params2["obj_state"] = self::$ITEM_STATE_PUBLISHED_WITH_CHANGE;
            $sql_params2["obj_status"] = self::$ITEM_STATUS_ACTIVE;
            $sql_params2["obj_modified_date"] = $pdate;
            $sql_params2["obj_modified_user_id"] = $wcm_user_id;
            $sql_params2["obj_created_date"] = $DB->getDateTime($dt, 0, "obj_created_date");
            $sql_params2["obj_created_user_id"] = $DB->getLong($dt, 0, "obj_created_user_id");

            $r2 = $DB->executeInsert($table_draft, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to create new item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $next_rev = $revision + 1;

            self::removeOldRevision($DB, $module_name, $table_draft, $item_id, $language, $next_rev);

            $result["item_id"] = $item_id;
            $result["revision"] = $next_rev;
            $result["language"] = $language;
            $result["modified_date"] = $pdate;
            $DB->commit();
            return true;
        } else if ($cmd == self::$ACTION_CMD_PUBLISH && $item_status == self::$ITEM_STATUS_ACTIVE) {
            $sql_params2 = array();
            $sql_params2["@" . $field_id_draft] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_string2 = "update " . $table_draft . " set obj_status = '" . self::$ITEM_STATUS_ARCHIVED . "' where obj_status = '" . self::$ITEM_STATUS_ACTIVE . "' and (obj_state = '" . self::$ITEM_STATE_PUBLISHED . "' or obj_state = '" . self::$ITEM_STATE_PUBLISHED_WITH_CHANGE . "') and obj_lang = @obj_lang and `" . $field_id_draft . "` = @" . $field_id_draft . " ";
            $r2 = $DB->execute($sql_string2, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to unpublish recent item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $sql_params2 = array();
            $sql_params2["@" . $field_id_online] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_string2 = "delete from " . $table_online . " where obj_lang = @obj_lang and " . $field_id_online . " = @" . $field_id_online . " ";
            $r2 = $DB->execute($sql_string2, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to delete destination item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $sql_params2 = array();
            foreach ($data["field"] as $field_key => $field_value) {
                $sql_params2[$field_key] = $dt[0][$field_key];
            }
            $sql_params2["obj_published_date"] = $pdate;
            $sql_params2["obj_published_user_id"] = $wcm_user_id;

            $r2 = $DB->executeInsert($table_online, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to publish item to destination.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $sql_params2 = array();
            $sql_params2["@" . $field_id_draft] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_params2["@obj_rev"] = $revision;
            $sql_params2["@obj_today_date"] = $pdate;
            $sql_params2["@obj_active_user_id"] = $wcm_user_id;
            $sql_string2 = "update " . $table_draft . " set obj_state = '" . self::$ITEM_STATE_PUBLISHED . "', obj_status = '" . self::$ITEM_STATUS_ACTIVE . "', obj_published_date = @obj_today_date, obj_published_user_id = @obj_active_user_id   where  obj_lang = @obj_lang and obj_rev = @obj_rev and `" . $field_id_draft . "` = @" . $field_id_draft . " ";
            $r2 = $DB->execute($sql_string2, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to update item status/state.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $result["item_id"] = $item_id;
            $result["revision"] = $revision;
            $result["language"] = $language;
            $result["modified_date"] = $pdate;
            $DB->commit();
            return true;
        } else if ($cmd == self::$ACTION_CMD_DELETE && $item_state == self::$ITEM_STATE_DRAFT && $item_status == self::$ITEM_STATUS_ACTIVE) {
            $sql_params2 = array();
            $sql_params2["@" . $field_id_draft] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_params2["@obj_rev"] = $revision;
            $sql_params2["@obj_today_date"] = $pdate;
            $sql_params2["@obj_active_user_id"] = $wcm_user_id;
            $sql_string2 = "update " . $table_draft . " set obj_status = '" . self::$ITEM_STATUS_DELETED . "', obj_modified_date = @obj_today_date, obj_modified_user_id = @obj_active_user_id  where obj_lang = @obj_lang and obj_rev = @obj_rev and " . $field_id_draft . " = @" . $field_id_draft . " ";
            $r2 = $DB->execute($sql_string2, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to update item status/state.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $result["item_id"] = $item_id;
            $result["revision"] = $revision;
            $result["language"] = $language;
            $DB->commit();
            return true;
        } else if (($cmd == self::$ACTION_CMD_UNPUBLISH || $cmd == self::$ACTION_CMD_UNPUBLISH_WITH_DRAFT || $cmd == self::$ACTION_CMD_UNPUBLISH_WITH_RECENT_DRAFT) && $item_state == self::$ITEM_STATE_PUBLISHED && $item_status == self::$ITEM_STATUS_ACTIVE) {
            $sql_params2 = array();
            $sql_params2["@" . $field_id_online] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_string2 = "delete from " . $table_online . " where obj_lang = @obj_lang and " . $field_id_online . " = @" . $field_id_online . " ";
            $r2 = $DB->execute($sql_string2, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to delete destination item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }

            $sql_params2 = array();
            $sql_params2["@" . $field_id_draft] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_params2["@obj_rev"] = $revision;
            $sql_params2["@obj_today_date"] = $pdate;
            $sql_params2["@obj_active_user_id"] = $wcm_user_id;
            $sql_string2 = "update " . $table_draft . " set obj_status = '" . self::$ITEM_STATUS_ARCHIVED . "', obj_modified_date = @obj_today_date, obj_modified_user_id = @obj_active_user_id  where  obj_lang = @obj_lang and obj_rev = @obj_rev and " . $field_id_draft . " = @" . $field_id_draft . " ";
            $r2 = $DB->execute($sql_string2, $sql_params2);

            if ($r2 < 0) {
                $result["detail"] = "Unable to update item status/state.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode(sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }

            if ($cmd == self::$ACTION_CMD_UNPUBLISH_WITH_DRAFT || $cmd == self::$ACTION_CMD_UNPUBLISH_WITH_RECENT_DRAFT) {

                $next_rev = 1;
                $sql_params2 = array();
                $sql_params2["@" . $field_id_draft] = $item_id;
                $sql_params2["@obj_lang"] = $language;
                $sql_string2 = "select max(obj_rev) as max_obj_rev from " . $table_draft . " where obj_lang = @obj_lang and `" . $field_id_draft . "` = @" . $field_id_draft . "  ";
                $r2 = $DB->query($dt2, $sql_string2, $sql_params2, 0, 1);
                if ($r2 <= 0) {
                    $result["detail"] = "Unable to retieve recent published item.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }
				
				if (!isset($dt2[0][0])) {
                    $result["detail"] = "Unable to get next revision.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }
				$next_rev = $dt2[0][0];
				$next_rev++;
                $sql_params2 = array();
                foreach ($dt[0] as $dt_key => $dt_value) {
					if (!is_numeric($dt_key)) {
						$sql_params2[$dt_key] = $dt_value;
					}
                }
                $sql_params2["obj_rev"] = $next_rev;
                $sql_params2["obj_state"] = self::$ITEM_STATE_DRAFT;
                $sql_params2["obj_status"] = self::$ITEM_STATUS_ACTIVE;
                $sql_params2["obj_modified_date"] = $pdate;
                $sql_params2["obj_modified_user_id"] = $wcm_user_id;

                $r2 = $DB->executeInsert($table_draft, $sql_params2);
                if ($r2 < 0) {
                    $result["detail"] = "Unable to create new item.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }

                self::removeOldRevision($DB, $module_name, $table_draft, $item_id, $language, $next_rev);

                $result["item_id"] = $item_id;
                $result["revision"] = $next_rev;
                $result["language"] = $language;
                $result["modified_date"] = $pdate;
                $DB->commit();
                return true;
            }
            $result["item_id"] = $item_id;
            $result["revision"] = $revision;
            $result["language"] = $language;
            $result["modified_date"] = $pdate;
            $DB->commit();
            return true;
        } if (($cmd == self::$ACTION_CMD_UNPUBLISH || $cmd == self::$ACTION_CMD_UNPUBLISH_WITH_DRAFT || $cmd == self::$ACTION_CMD_UNPUBLISH_WITH_RECENT_DRAFT) && $item_state == self::$ITEM_STATE_PUBLISHED_WITH_CHANGE && $item_status == self::$ITEM_STATUS_ACTIVE) {
            $sql_params2 = array();
            $sql_params2["@" . $field_id_online] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_string2 = "delete from " . $table_online . " where obj_lang = @obj_lang and " . $field_id_online . " = @" . $field_id_online . " ";
            $r2 = $DB->execute($sql_string2, $sql_params2);
            if ($r2 < 0) {
                $result["detail"] = "Unable to delete destination item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $sql_params2 = array();
            $sql_params2["@" . $field_id_draft] = $item_id;
            $sql_params2["@obj_lang"] = $language;
            $sql_params2["@obj_rev"] = $revision;
            $sql_params2["@obj_today_date"] = $pdate;
            $sql_params2["@obj_active_user_id"] = $wcm_user_id;
            if ($cmd == self::$ACTION_CMD_UNPUBLISH_WITH_RECENT_DRAFT) {
                $sql_string2 = "update " . $table_draft . " set obj_state = '" . self::$ITEM_STATE_DRAFT . "', obj_modified_date = @obj_today_date, obj_modified_user_id = @obj_active_user_id  where  obj_lang = @obj_lang and obj_rev = @obj_rev and " . $field_id_draft . " = @" . $field_id_draft . " ";
            } else {
                $sql_string2 = "update " . $table_draft . " set obj_status = '" . self::$ITEM_STATUS_DELETED . "', obj_modified_date = @obj_today_date, obj_modified_user_id = @obj_active_user_id  where  obj_lang = @obj_lang and obj_rev = @obj_rev and " . $field_id_draft . " = @" . $field_id_draft . " ";
            }
            $r2 = $DB->execute($sql_string2, $sql_params2);

            if ($r2 < 0) {
                $result["detail"] = "Unable to update item status/state.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params2);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            if ($cmd == self::$ACTION_CMD_UNPUBLISH_WITH_DRAFT) {
                $next_rev = 1;
                $recent_published_rev = 1;
                $sql_params2 = array();
                $sql_params2["@" . $field_id_draft] = $item_id;
                $sql_params2["@obj_lang"] = $language;
                $sql_string2 = "select max(obj_rev) as max_obj_rev from " . $table_draft . " where obj_lang = @obj_lang and `" . $field_id_draft . "` = @" . $field_id_draft . "  ";
                $r2 = $DB->query($dt2, $sql_string2, $sql_params2, 0, 1);
                if ($r2 <= 0) {
                    $result["detail"] = "Unable to retieve recent item revision.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }
				if (!isset($dt2[0][0])) {				
                    $result["detail"] = "Unable to get next revision.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }
				$next_rev = $dt2[0][0];
				$next_rev++;
                $sql_params2 = array();
                $sql_params2["@" . $field_id_draft] = $item_id;
                $sql_params2["@obj_lang"] = $language;
                $sql_string2 = "select max(obj_rev) as max_obj_rev from " . $table_draft . " where obj_lang = @obj_lang and " . $field_id_draft . " = @" . $field_id_draft . " and obj_state = '" . self::$ITEM_STATE_PUBLISHED . "' and obj_status = '" . self::$ITEM_STATUS_ARCHIVED . "' ";
                $r2 = $DB->query($dt2, $sql_string2, $sql_params2, 0, 1);

                if ($r2 <= 0) {
                    $result["detail"] = "Unable to find recent published revision.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }
                if (!isset($dt2[0][0])) {
                    $result["detail"] = "Unable to retieve recent published revision.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }
				$recent_published_rev = $dt2[0][0];
				
                $sql_string2 = "select * from " . $table_draft . " where `" . $field_id_draft . "` = @" . $field_id_draft . " and obj_lang = @obj_lang and obj_rev = @obj_rev ";
                $sql_params2["@" . $field_id_draft] = $item_id;
                $sql_params2["@obj_lang"] = $language;
                $sql_params2["@obj_rev"] = $recent_published_rev;
                $r2 = $DB->query($dt2, $sql_string2, $sql_params2);
                if ($r2 <= 0) {
                    $result["detail"] = "Unable to retieve recent published item.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode(sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }
                $sql_params2 = array();
                foreach ($dt2[0] as $dt2_key => $dt2_value) {
					if (!is_numeric($dt2_key)) {
						$sql_params2[$dt2_key] = $dt2_value;
					}
                }
                $sql_params2["obj_rev"] = $next_rev;
                $sql_params2["obj_state"] = self::$ITEM_STATE_DRAFT;
                $sql_params2["obj_status"] = self::$ITEM_STATUS_ACTIVE;
                $sql_params2["obj_modified_date"] = $pdate;
                $sql_params2["obj_modified_user_id"] = $wcm_user_id;
                
                $r2 = $DB->executeInsert($table_draft, $sql_params2);
                if ($r2 < 0) {
                    $result["detail"] = "Unable to create new item.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }

                self::removeOldRevision($DB, $module_name, $table_draft, $item_id, $language, $next_rev);

                $result["item_id"] = $item_id;
                $result["revision"] = $next_rev;
                $result["language"] = $language;
                $result["modified_date"] = $pdate;
                $DB->commit();
                return true;
            }
            $result["item_id"] = $item_id;
            $result["revision"] = $revision;
            $result["language"] = $language;
            $result["modified_date"] = $pdate;
            $DB->commit();
            return true;
        } else if ($cmd==self::$ACTION_CMD_CHANGE_KEY) {
            $new_item_id = 0;
            $new_language = "";
            $bForceReplace = false;
            $base_revision = 0;
			if (!isset($data["key"]["new_item_id"]) || !isset($data["key"]["new_language"])) {
                $result["detail"] = "Unexpected parameters";
                $result["sql"] = "";
                $result["param"] = "";
                $result["error"] = "";
                $DB->rollBack();
                return false;
			}
            
			$new_item_id = $data["key"]["new_item_id"];
			$new_language = $data["key"]["new_language"];
			if (isset($data["option"]["force"]) && strtolower($data["option"]["force"]) == "yes") {
				$bForceReplace = true;
			}
            
            if ($new_item_id == 0 || $new_language == null || $new_language == "") {
                $result["detail"] = "Unexpected parameters";
                $result["sql"] = "";
                $result["param"] = "";
                $result["error"] = "";
                $DB->rollBack();
                return false;
            }
            $sql_param = array();
            $sql_param["@item_id"] = $new_item_id;
            $sql_param["@obj_lang"] = $new_language;
            $r = $DB->query($dt2, "select *  from " . $table_draft . " where " . $field_id_draft . " = @item_id and obj_lang=@obj_lang ", $sql_param);
            if ($r < 0) {
                $result["detail"] = "Unable to get target key";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = $sql_param;
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;            
            }
            if ($r > 0 && !$bForceReplace) {
                $result["code"] = "DUP";
                $result["detail"] = "";
                $result["sql"] = "";
                $result["param"] = "";
                $result["error"] = "";
                $DB->rollBack();
                return false;
            }
            $sql_param = array();
            $sql_param["@item_id"] = $new_item_id;
            $sql_param["@obj_lang"] = $new_language;
            $r = $DB->query($dt2, "select max(obj_rev) as max_obj_rev from " . $table_draft . " where " . $field_id_draft . " = @item_id and obj_lang=@obj_lang ", $sql_param);
            if ($r > 0) {
                $base_revision = $DB->getLong($dt2, 0, "max_obj_rev");
            }
            $sql_param = array();
            $sql_param["item_id"]= $item_id;            
            $sql_param["obj_lang"] = $language;
            $sql_param["new_item_id"] = $new_item_id;
            $sql_param["new_obj_lang"] = $new_language;
            $r = $DB->execute("delete from " . $table_online . " where (" . $field_id_online . " = @item_id and obj_lang = @obj_lang) or  (" . $field_id_online . " = @new_item_id and obj_lang = @new_obj_lang)", $sql_param);
            if ($r < 0) {
                $result["detail"] = "Unable to clean up online table";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = $sql_param;
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $sql_param = array();
            $sql_param["item_id"] = $item_id;
            $sql_param["obj_lang"] = $language;
            $sql_param["new_item_id"] = $new_item_id;
            $sql_param["new_obj_lang"] = $new_language;
            $r = $DB->execute("update " . $table_draft . " set obj_status = '" . self::$ITEM_STATUS_ARCHIVED . "' where " . $field_id_draft . " = @new_item_id  and obj_lang=@new_obj_lang and obj_status = '" . self::$ITEM_STATUS_ACTIVE . "'  ", $sql_param);
            if ($r < 0) {
                $result["detail"] = "Unable to get target item";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = $sql_param;
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $sql_param = array();
            $sql_param["item_id"] = $item_id;
            $sql_param["obj_lang"] = $language;
            $sql_param["new_item_id"] = $new_item_id;
            $sql_param["new_obj_lang"] = $new_language;
            $sql_param["base_revision"] = $base_revision;
            $r = $DB->execute("update " . $table_draft . " set obj_rev = obj_rev + @base_revision, " . $field_id_draft . " = @new_item_id, obj_lang=@new_obj_lang where " . $field_id_draft . " = @item_id  and obj_lang=@obj_lang ", $sql_param);
            if ($r < 0) {
                $result["detail"] = "Unable to change key";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = $sql_param;
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            if ($item_state == self::$ITEM_STATE_PUBLISHED || $item_state == self::$ITEM_STATE_PUBLISHED_WITH_CHANGE) {
                $publish_revision = 0;
                if ($item_state == self::$ITEM_STATE_PUBLISHED) {
                    $publish_revision = $base_revision + $revision;
                }
                if ($item_state == self::$ITEM_STATE_PUBLISHED_WITH_CHANGE) {
                    $publish_revision = $base_revision + $revision - 1;
                }
                $sql_string2 = "select * from " . $table_draft . " where " . $field_id_draft . " = @" . $field_id_draft . " and obj_lang = @obj_lang and obj_rev = @obj_rev ";
                $sql_params2["@" . $field_id_draft] = $new_item_id;
                $sql_params2["@obj_lang"] = $new_language;
                $sql_params2["@obj_rev"] = $publish_revision;
                $r = $DB->query($dt2, $sql_string, $sql_params2);
                $sql_params2 = array();
                foreach ($data["field"] as $field_key => $field_value) {
                    $sql_params2[$field_key] =$dt2[0][$field_value];
                }
                $sql_params2["obj_published_date"] = $pdate;
                $sql_params2["obj_published_user_id"] = $wcm_user_id;

                $r2 = $DB->executeInsert($table_online, $sql_params2);
                if ($r2 < 0) {
                    $result["detail"] = "Unable to publish item to destination.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }
            } 
            $result["item_id"] = $new_item_id;
            $result["revision"] = $revision;
            $result["base_revision"] = $base_revision;
            $result["language"] = $new_language;
            $result["modified_date"] = $pdate;
            $DB->commit();
            return true;
        } else {
            $result["detail"] = "Unsupported item state and/or status.";
            $result["sql"] = $DB->LastSQLQueryString;
            $result["param"] = OMJson::encode($sql_param);
            $result["error"] = $DB->LastErrorMessage;
            $DB->rollBack();
            return false;
        }
			
		// UNHANDLE
		$result["detail"] = "Unimplement";
		$result["sql"] = $DB->LastSQLQueryString;
		$result["param"] = OMJson::encode($sql_params);
		$result["error"] = $DB->LastErrorMessage;
		return false;
	}
	static function _actionChild($cmd, $DB, $data, &$result) {
		//OMLog log = OMLog.Current;
        $dt;
        $sql_params = array();
        $sql_params2 = array();
        $result = array();
        $parent_id = 0;
        $item_id = 0;
        $revision = 0;
        $old_revision = 0;
        $tmp_session = 0;
        $language = "";
        $wcm_user_id = 0;
        $r = 0; 
		$r2 = 0;
        $i = 0;
        $sql_string = "";
        $table_draft = "";
        $table_online = "";
        $table_parent_draft = "";
        $table_parent_online = "";
        $field_id_draft = "";
        $field_id_online = "";
        $field_id_parent_draft = "";
        $field_id_parent_online = "";
        $module_name = "";
        $pdate = OMDateTime::Now();
        $parent_pdate = $pdate;
        $DB->begin();
        $fast_child_operation_enabled = false;
        
		if(isset($data["module_name"])) $module_name = $data["module_name"];
		if ($module_name == "") {
            $module_name = "WCMUtil";
		}
        		
		if (
			!isset($data["tbl"]["draft"]) ||
			!isset($data["tbl"]["online"]) ||
			!isset($data["tbl"]["parent_draft"]) ||
			!isset($data["tbl"]["parent_online"]) ||
			!isset($data["field_id"]["draft"]) ||
			!isset($data["field_id"]["online"]) ||
			!isset($data["field_id"]["parent_draft"]) ||
			!isset($data["field_id"]["parent_online"]) ||
			!isset($data["key"]["language"]) ||
			!isset($data["key"]["user_id"])
		) {
            $result["detail"] = "Unexpected parameters";
            $result["sql"] = "";
            $result["param"] = "";
            $result["error"] = "key";
            $DB->rollBack();
            return false;
        }
		        
		$table_draft = $data["tbl"]["draft"];
		$table_online = $data["tbl"]["online"];
		$table_parent_draft = $data["tbl"]["parent_draft"];
		$table_parent_online = $data["tbl"]["parent_online"];
		$field_id_draft = $data["field_id"]["draft"];
		$field_id_online = $data["field_id"]["online"];
		$field_id_parent_draft = $data["field_id"]["parent_draft"];
		$field_id_parent_online = $data["field_id"]["parent_online"];
		$language = $data["key"]["language"];
		$wcm_user_id = $data["key"]["user_id"];
        
        $result = array();
        $DB->begin();

		if ($cmd == self::$ACTION_CHILD_CMD_CREATE || $cmd ==  self::$ACTION_CHILD_CMD_SAVE || $cmd ==  self::$ACTION_CHILD_CMD_DELETE) {
			if (!isset($data["key"]["parent_id"]) || 
				!isset($data["key"]["item_id"]) || 
				!isset($data["key"]["revision"]) || 
				!isset($data["key"]["old_revision"]) || 
				!isset($data["key"]["tmp_session"])) {
				$result["detail"] = "Unexpected parameters";
                $result["sql"] = "";
                $result["param"] = "";
                $result["error"] = "key parent_id, item_id, revision, old_revision, tmp_session";
                $DB->rollBack();
                return false;
			}
			$parent_id = $data["key"]["parent_id"];
			$item_id = $data["key"]["item_id"];
			$revision = $data["key"]["revision"];
			$old_revision = $data["key"]["old_revision"];
			$tmp_session = $data["key"]["tmp_session"];

            if ($parent_id != 0 && $revision != 0) {
                if ($old_revision == $revision) {
                    $sql_params=array();
                    $sql_string = "select * from " . $table_parent_draft . " where `" . $field_id_parent_draft . "` = @" . $field_id_parent_draft . " and obj_lang=@obj_lang and obj_rev = @obj_rev ";
                    $sql_params["@" . $field_id_parent_draft] = $parent_id;
                    $sql_params["@obj_lang"] = $language;
                    $sql_params["@obj_rev"] = $revision;
                    $r = $DB->query($dt, $sql_string, $sql_params);

                    if ($r > 0) {
                        if ($DB->getString($dt, 0, "obj_state") != self::$ITEM_STATE_DRAFT || $DB->getString($dt, 0, "obj_state") != self::$ITEM_STATE_PUBLISHED_WITH_CHANGE) {
                            $pd = array();
                            $result2 = array();
                            $pd["module_name"] = $module_name;
                            $pd["tbl"][ "draft"] = $table_parent_draft;
                            $pd["tbl"]["online"] = $table_parent_online;
                            $pd["field_id"]["draft"] = $field_id_parent_draft;
                            $pd["field_id"]["online"] = $field_id_parent_draft;
                            $pd["key"]["item_id"] = $parent_id;
                            $pd["key"]["language"] = $language;
                            $pd["key"]["revision"] = $revision;
                            $pd["key"]["user_id"] = $wcm_user_id;

							foreach ($dt[0] as $kv_key => $kv_value) {
							    if (!is_numeric($kv_key)) $pd["fielddata"][$kv_key] = $kv_value;
							}
                            if (OMWCMUtil::actionModify($DB, $pd, $result2)) {
                                $revision = $result2["revision"];
                                $parent_pdate = $result2["modified_date"];
                            } else {
                            }
                        } else {
                        }
                    } else {
                    }
                }

                if ($old_revision != $revision) {
                    $sql_params = array();
                    $sql_string = "delete from " . $table_draft . " where obj_parent_id = @obj_parent_id and obj_lang = @obj_lang and obj_rev = @obj_rev ";
                    $sql_params["@obj_parent_id"] = $parent_id;
                    $sql_params["@obj_lang"] = $language;
                    $sql_params["@obj_rev"] = $revision;
                    $r = $DB->execute($sql_string, $sql_params);
                    if ($r < 0) {
                        $result["detail"] = "Unable to clear new revision.";
                        $result["sql"] = $DB->LastSQLQueryString;
                        $result["param"] = OMJson::encode($sql_params);
                        $result["error"] = $DB->LastErrorMessage;
                        $DB->rollBack();
                        return false;
                    }

                    if ($old_revision != 0) { 
                        $sql_params = array();
                        $sql_string = "select * from " . $table_draft . " where obj_lang = @obj_lang and obj_rev = @obj_rev and obj_parent_id = @obj_parent_id and obj_status <> '" . self::$ITEM_STATUS_DELETED . "' ";
                        $sql_params["@obj_lang"] = $language;
                        $sql_params["@obj_rev"] = $old_revision;
                        $sql_params["@obj_parent_id"] = $parent_id;
                        $r = $DB->query($dt, $sql_string, $sql_params);

                        if ($r >= 0) {
                            for ($i = 0; $i < count($dt); $i++) {
                                
                                foreach ($dt[$i] as $kv_key => $kv_value) {
                                    if (!is_numeric($kv_key)) $sql_params2[$kv_key] = $kv_value;
                                }
                                $sql_params2["obj_rev"] = $revision;
                                $r2 = $DB->executeInsert($table_draft, $sql_params2);
                                if ($r2 < 0) {
                                    $result["detail"] = "unable to clone old child item";
                                    $result["sql"] = $DB->LastSQLQueryString;
                                    $result["param"] = $sql_params2;
                                    $result["error"] = $DB->LastErrorMessage;
                                    $DB->rollBack();
                                    return false;
                                }

                                if ($i == 0) {
                                    self::removeOldRevision($DB, $module_name, $table_draft, $parent_id, $language, $revision , "child");
                                }
                            }
                        } else {
                            $result["detail"] = "unable to retrieve child item";
                            $result["sql"] = $DB->LastSQLQueryString;
                            $result["param"] = $sql_params;
                            $result["error"] = $DB->LastErrorMessage;
                            $DB->rollBack();
                            return false;
                        }
                    }
                }
                if ($tmp_session != 0) {
                    $sql_params = array();
                    $sql_string = "update " . $table_draft . " set obj_parent_id = @obj_parent_id, obj_rev = @obj_rev, obj_tmp_session_id = 0 where obj_tmp_session_id = @obj_tmp_session_id ";
                    $sql_params["@obj_parent_id"] = $parent_id;
                    $sql_params["@obj_rev"] = $revision;
                    $sql_params["@obj_tmp_session_id"] = $tmp_session;
                    $r = $DB->execute($sql_string, $sql_params);
                    if ($r < 0) {
                        $result["detail"] = "unable to clone old child item";
                        $result["sql"] = $DB->LastSQLQueryString;
                        $result["param"] = $sql_params;
                        $result["error"] = $DB->LastErrorMessage;
                        $DB->rollBack();
                        return false;
                    }
                }
            }
            if ($cmd == self::$ACTION_CHILD_CMD_CREATE) {
                $sql_params2 = array();
                foreach ($data["fielddata"] as $field_key => $field_value) {
                    $sql_params2[$field_key] = $field_value;
                }
                $item_id = $DB->getRunningNumber($table_draft);
                if ($parent_id != 0 && $revision != 0) {
                    $sql_params2["obj_tmp_session_id"] = 0;
                } else {
                    $sql_params2["obj_tmp_session_id"] = $tmp_session;
                }
                $sql_params2["obj_parent_id"] = $parent_id;
                $sql_params2[$field_id_draft] = $item_id;
                $sql_params2["obj_lang"] = $language;
                $sql_params2["obj_rev"] = $revision;
                $sql_params2["obj_created_date"] = $pdate;
                $sql_params2["obj_created_user_id"] = $wcm_user_id;
                $sql_params2["obj_modified_date"] = $pdate;
                $sql_params2["obj_modified_user_id"] = $wcm_user_id;
                $sql_params2["obj_status"] = self::$ITEM_STATUS_ACTIVE;
                $sql_params2["obj_state"] = self::$ITEM_STATE_DRAFT;

                $r2 = $DB->executeInsert($table_draft, $sql_params2);
                if ($r2 < 0) {
                    $result["detail"] = "Unable to create child item.";
                    $result["sql"] = $DB->LastSQLQueryString;
                    $result["param"] = OMJson::encode($sql_params2);
                    $result["error"] = $DB->LastErrorMessage;
                    $DB->rollBack();
                    return false;
                }

                self::removeOldRevision($DB, $module_name, $table_draft, $parent_id, $language, $revision , "child");
            } else if ($cmd == self::$ACTION_CHILD_CMD_SAVE) {
                if ($item_id != 0) {
                    $sql_params2 = array();
                    $sql_params2[$field_id_draft] = $item_id;
                    $sql_params2["obj_lang"] = $language;
                    $sql_params2["obj_rev"] = $revision;
                    foreach ($data["fielddata"] as $field_key => $field_value) {
                        $sql_params2[$field_key] = $field_value;
                    }
                    $sql_params2[$field_id_draft] = $item_id;
                    $sql_params2["obj_lang"] = $language;
                    $sql_params2["obj_rev"] = $revision;
                    $sql_params2["obj_modified_date"] = $pdate;
                    $sql_params2["obj_modified_user_id"] = $wcm_user_id;
                    $r2 = $DB->executeUpdate($table_draft, 3, $sql_params2);
                    if ($r2 <= 0) {
                        $result["detail"] = "unable to update child item";
                        $result["sql"] = $DB->LastSQLQueryString;
                        $result["param"] = $sql_params2;
                        $result["error"] = $DB->LastErrorMessage;
                        $DB->rollBack();
                        return false;
                    }
                }
            } else if ($cmd == self::$ACTION_CHILD_CMD_DELETE) {
                if ($item_id != 0) {
                    $sql_params2 = array();
                    $sql_params2[$field_id_draft] = $item_id;
                    $sql_params2["obj_lang"] = $language;
                    if (isset($data["key"]) && isset($data["key"]["next_rev"])) {
                        $revision = $data["key"]["next_rev"];
                    }
                    $sql_params2["obj_rev"] = $revision;
                    $sql_params2["obj_modified_date"] = $pdate;
                    $sql_params2["obj_modified_user_id"] = $wcm_user_id;
                    $sql_params2["obj_status"] = self::$ITEM_STATUS_DELETED;
                    $r2 = $DB->executeUpdate($table_draft, 3, $sql_params2);
                    if ($r2 <= 0) {
                        $result["detail"] = "unable to delete child item";
                        $result["sql"] = $DB->LastSQLQueryString;
                        $result["param"] = $sql_params2;
                        $result["error"] = $DB->LastErrorMessage;
                        $DB->rollBack();
                        return false;
                    }
                }
            }
            $result["parent_id"] = $parent_id;
            $result["item_id"] = $item_id;
            $result["language"] = $language;
            $result["revision"] = $revision;
            $result["modified_date"] = $pdate;
            $result["parent_modified_date"] = $parent_pdate;
            $result["detail"] = "";

            $DB->commit();
            return true;
			
        } else if ($cmd == self::$ACTION_CHILD_CMD_PUBLISH) {
            
			if (!isset($data["key"]["parent_id"]) || !isset($data["key"]["revision"])) {
                $result["detail"] = "Unexpected parameters";
                $result["sql"] = "";
                $result["param"] = "";
                $result["error"] = "Publishing child item";
                $DB->rollBack();
                return false;
            }
			
			$parent_id = $data["key"]["parent_id"];
			$revision = $data["key"]["revision"];
		
            $sql_params = array();
            $sql_string = "delete from " . $table_online . " where obj_parent_id = @obj_parent_id and obj_lang = @obj_lang ";
            $sql_params["@obj_parent_id"] = $parent_id;
            $sql_params["@obj_lang"] = $language;
            $r = $DB->execute($sql_string, $sql_params);
            if ($r < 0) {
                $result["detail"] = "Unable to delete destination item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }

            $sql_params = array();
            $sql_string = "select * from " . $table_draft . " where obj_lang = @obj_lang and obj_rev = @obj_rev and obj_parent_id = @obj_parent_id and obj_status <> '" . self::$ITEM_STATUS_DELETED . "' ";
            $sql_params["@obj_lang"] = $language;
            $sql_params["@obj_rev"] = $revision;
            $sql_params["@obj_parent_id"] = $parent_id;
            $r = $DB->query($dt, $sql_string, $sql_params);
            if ($r >= 0) {
                for ($i = 0; $i < count($dt); $i++) {
                    $sql_params2 = array();
                    foreach ($data["field"] as $field_key => $field_value) {
                        $sql_params2[$field_key] = $dt[$i][$field_value];
                    }

                    $r2 = $DB->executeInsert($table_online, $sql_params2);
                    if ($r2 < 0) {
                        $result["detail"] = "unable to publish child item";
                        $result["sql"] = $DB->LastSQLQueryString;
                        $result["param"] = $sql_params2;
                        $result["error"] = $DB->LastErrorMessage;
                        $DB.rollBack();
                        return false;
                    }
                }
            } else {
                $result["detail"] = "unable to retrieve child item";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = $sql_params;
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }

            $result["parent_id"] = $parent_id;
            $result["language"] = $language;
            $result["modified_date"] = $pdate;
            $result["parent_modified_date"] = $parent_pdate;
            $DB->commit();
            return true;
        } else if ($cmd == self::$ACTION_CHILD_CMD_UNPUBLISH) {
			if (!isset($data["key"]["parent_id"]) || !isset($data["key"]["revision"])) {
                $result["detail"] = "Unexpected parameters";
                $result["sql"] = "";
                $result["param"] = "";
                $result["error"] = "";
                $DB->rollBack();
                return false;
            }
           
			$parent_id = $data["key"]["parent_id"];
			$revision = $data["key"]["revision"];
	   
            $sql_params = array();
            $sql_string = "delete from " . $table_online . " where obj_parent_id = @obj_parent_id and obj_lang = @obj_lang ";
            $sql_params["@obj_parent_id"] = $parent_id;
            $sql_params["@obj_lang"] = $language;
            $r = $DB->execute($sql_string, $sql_params);
            if ($r < 0) {
                $result["detail"] = "Unable to create new item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $result["parent_id"] = $parent_id;
            $result["language"] = $language;
            $DB->commit();
            return true;
        } else if ($cmd == self::$ACTION_CHILD_CMD_CHANGE_KEY) {
            $new_parent_id = 0;
            $base_revision = 0;
            $new_language = "";
            if (!isset($data["key"]["parent_id"]) || !isset($data["key"]["revision"]) ) {
                $result["detail"] = "Unexpected parameters";
                $result["sql"] = "";
                $result["param"] = "";
                $result["error"] = "Publishing child item";
                $DB->rollBack();
                return false;
            }
			$parent_id = $data["key"]["parent_id"];
			$revision = $data["key"]["revision"];
			$base_revision = $data["key"]["base_revision"];
			$new_parent_id = $data["key"]["new_parent_id"];
			$new_language = $data["key"]["new_language"];
            if ($new_parent_id == 0 || $new_language == null || $new_language== "") {
                $result["detail"] = "Unexpected parameters";
                $result["sql"] = "";
                $result["param"] = "";
                $result["error"] = "Publishing child item";
                $DB->rollBack();
                return false;
            }
            $sql_params = array();
            $sql_params["obj_parent_id"] = $parent_id;
            $sql_params["new_obj_parent_id"] = $new_parent_id;           
            $sql_params["obj_lang"] = $language;
            $sql_params["new_obj_lang"] = $new_language;
            $sql_params["base_revision"] = $base_revision;
            $sql_string = "update " . $table_online . " set obj_parent_id = @new_obj_parent_id, obj_lang = @new_obj_lang  where obj_parent_id = @obj_parent_id and obj_lang = @obj_lang";
            $r = $DB->execute($sql_string, $sql_params);
            $sql_string = "update " . $table_draft . " set obj_parent_id = @new_obj_parent_id, obj_lang = @new_obj_lang, obj_rev = obj_rev + @base_revision where obj_parent_id = @obj_parent_id and obj_lang = @obj_lang";
            $r = $DB->execute($sql_string, $sql_params);
            if ($r < 0) {
                $result["detail"] = "Unable to update item.";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = OMJson::encode($sql_params);
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            $result["parent_id"] = $new_parent_id;
            $result["language"] = $new_language;
            $result["modified_date"] = $pdate;
            $result["parent_modified_date"] = $parent_pdate;
            $DB->commit();
            return true;
        } else {
            $result["detail"] = "Unsupported item state and/or status.";
            $result["sql"] = "";
            $result["param"] = "";
            $result["error"] = "";
            $DB->rollBack();
            return false;
        }		
		
		// UNHANDLE
		$result["detail"] = "Unimplement";
		$result["sql"] = $DB->LastSQLQueryString;
		$result["param"] = OMJson::encode($sql_params);
		$result["error"] = $DB->LastErrorMessage;
		return false;
	}

    static function removeOldRevision($DB, $module_name, $table_name, $item_id, $language, $revision, $mode="") {
        $field_id = $module_name . "_id";

        $sql_params = array();
        $sql_params["@" . $field_id] = $item_id;
        $sql_params["@obj_lang"] = $language;
        if (isset(WCMSetting::$MAX_SAVE_REVISION)) {
            $sql_params["@obj_rev"] = $revision-WCMSetting::$MAX_SAVE_REVISION;
        } else {
            $sql_params["@obj_rev"] = $revision-10;
        }

        $sql_string = "";
        if ($mode == "child") {
            $sql_string = "delete from " . $table_name . " where obj_lang = @obj_lang and obj_parent_id = @" . $field_id . " and obj_rev < @obj_rev ";
        } else {
            $sql_string = "delete from " . $table_name . " where obj_lang = @obj_lang and " . $field_id . " = @" . $field_id . " and obj_rev < @obj_rev ";
        }
        $r2 = $DB->execute($sql_string, $sql_params);
    }

	//TODO: Recheck
	static function updateChildPath($DB, $data) {
        $dt = null;
        $r = 0;
		$r2 = 0;
        $sql_string = "";
        $sql_param = array();
        $sql_param2 = array();
        $data2 = array();
        $separator = $data["key"]["separator"];
        $path_id = $data["key"]["path_id"];
        $path_label = $data["key"]["path_label"];
        $table_name = $data["tbl"]["draft"];
        $i = 0;
        $sql_string = "select * from " . $table_name . " where obj_referer_id = @obj_referer_id and obj_status = 'active' and obj_lang = @obj_lang ";
        $sql_param = array();
        $sql_param["obj_referer_id"] = $data["key"]["item_id"];
        $sql_param["obj_lang"] = $data["key"]["language"];
        $r = $DB->query($dt, $sql_string, $sql_param);
        if ($r > 0) {
            for ($i = 0; $i < count($dt); $i++) {
                $sql_param2 = array();
                $sql_param2[$data["field_id"]["draft"]] = $dt[$i][$data["field_id"]["draft"]];
                $sql_param2["obj_lang"] = $dt[$i]["obj_lang"];
                $sql_param2["obj_status"] = self::$ITEM_STATUS_ACTIVE;
                $sql_param2["obj_path_id"] = ($path_id != "" ? $path_id . $separator : "") . $DB->getString($dt, $i, $data["field_id"]["draft"]);
                $sql_param2["obj_path_label"] = ($path_label != "" ? $path_label . $separator : "") . $DB->getString($dt, $i, $data["field_label"]["draft"]);
                $r2 = $DB->executeUpdate($table_name, 3, $sql_param2);
				//TODO: online bug
				if ($dt[$i]['obj_state'] ==self::$ITEM_STATE_PUBLISHED || $dt[$i]['obj_state'] == self::$ITEM_STATE_PUBLISHED_WITH_CHANGE) {
					/*
                    $sql2_1 = "update `" . $data["tbl"]["online"] . "` o \r\n"; 
					$sql2_1 .= "	inner join `" . $data["tbl"]["draft"] . "` d on  o." . $data["field_id"]["online"] . " = d." . $data["field_id"]["draft"] . " and d.obj_status = 'active' and d.obj_state='published'\r\n"; 
					$sql2_1 .= "set o.obj_path_label = d.obj_path_label \r\n"; 
					$sql2_1 .= "WHERE \r\n"; 
					$sql2_1 .= "	o.obj_path_label <> d.obj_path_label \r\n"; 
					$sql2_1 .= "	and d.mini_page_id = @item_id";
					$r2_1 = $DB->execute($sql2_1, array("item_id" => $dt[$i][$data["field_id"]["draft"]]));
                    */
				}
				
                $data2 = $data;
                $data2["key"]["item_id"] = $DB->getLong($dt, $i, $data["field_id"]["draft"]);
                $data2["key"]["path_id"] = $sql_param2["obj_path_id"];
                $data2["key"]["path_label"] = $sql_param2["obj_path_label"];
                OMWCMUtil::updateChildPath($DB, $data2);
            }
        }
        return true;
    }
	//TODO: Recheck
	static function updateFinderPath($DB, $data, &$result) {
		$dt = null;
		$dt2 = null;
        $sql_string = "";
        $sql_string2 = "";
        $sql_string3 = "";
        $sql_param =  array();
        $sql_param2 = array();
        $sql_param3 = array();
        $sql_param4 = array();
        $result = array();
        $table_name = "";
        $field_id = "";
        $field_label = "";
        $language = "";
        $ref_path_label = "";
        $ref_path_id = "";
        $old_path_label = "";
        $old_path_id = "";
        $new_path_label = "";
        $new_path_id = "";
        $separator = "";
        $item_id = 0;
        $referer_id = 0;
        $r = 0;
		$r2 = 0;
		$r3 = 0;
        
		if (
			!isset($data["tbl"]["draft"]) || 
			!isset($data["field_id"]["draft"]) ||
			!isset($data["field_label"]["draft"]) ||
			!isset($data["key"]["separator"]) ||
			!isset($data["key"]["language"]) ||
			!isset($data["key"]["item_id"])
			) {
            $result["detail"] = "Unexpected parameters";
            $result["sql"] = "";
            $result["param"] = "";
            $result["error"] = "key";
            $DB->rollBack();
            return false;
        }
		
		$table_name = $data["tbl"]["draft"];
		$field_id = $data["field_id"]["draft"];
		$field_label = $data["field_label"]["draft"];
		$separator = $data["key"]["separator"];
		$language = $data["key"]["language"];
		$item_id = $data["key"]["item_id"];
        
        $sql_string = "select * from " . $table_name . " where " . $field_id . " = @" . $field_id . " and obj_lang = @obj_lang and obj_status = '" . self::$ITEM_STATUS_ACTIVE . "'";
        $sql_param["@" . $field_id] = $item_id;
        $sql_param["obj_lang"] = $language;
        $r = $DB->query($dt, $sql_string, $sql_param);
        if ($r > 0) {
            $referer_id = $DB->getLong($dt, 0, "obj_referer_id");
            $old_path_id = $DB->getString($dt, 0, "obj_path_id");
            $old_path_label = $DB->getString($dt, 0, "obj_path_label");
            $sql_param = array();
            $sql_string2 = "select * from " . $table_name . " where " . $field_id . " = @" . $field_id . " and obj_lang = @obj_lang and obj_status = '" . self::$ITEM_STATUS_ACTIVE . "'";
            $sql_param2["@" . $field_id] = $referer_id;
            $sql_param2["@obj_lang"] = $language;
            $r2 = $DB->query($dt2, $sql_string2, $sql_param2);
            if ($r2 > 0) {
                $ref_path_id = $DB->getString($dt2, 0, "obj_path_id");
                $ref_path_label = $DB->getString($dt2, 0, "obj_path_label");
            } else {
                $ref_path_id = "";
                $ref_path_label = "";
            }
            $new_path_label = $ref_path_label . ($ref_path_label != "" ? $separator : "") . $DB->getString($dt, 0, $field_label);
            $new_path_id = $ref_path_id . ($ref_path_id != "" ? $separator : "") . $DB->getString($dt, 0, $field_id);
            $sql_param3= array();
            $sql_param3[$field_id] = $item_id;
            $sql_param3["obj_lang"] = $language;
            $sql_param3["obj_status"] = self::$ITEM_STATUS_ACTIVE;
            $sql_param3["obj_path_label"] = $new_path_label;
            $sql_param3["obj_path_id"] = $new_path_id;
            $r3 = $DB->executeUpdate($table_name, 3, $sql_param3);
            if ($r3 <= 0) {
                $result["detail"] = "Unable to update item information";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = $sql_param3;
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
            if ($old_path_label != $new_path_label || $old_path_id != $new_path_id || true) {
                $data["key"]["path_id"] = $new_path_id;
                $data["key"]["path_label"] = $new_path_label;
                OMWCMUtil::updateChildPath($DB, $data);
            }
            /*$sql_string3 = "update a set a.obj_haschild = if(isnull(b.total_child ), 0, b.total_child) \r\n";
            $sql_string3 .= "from " . $table_name . " a \r\n";
            $sql_string3 .= "	left join \r\n";
            $sql_string3 .= "	(select obj_referer_id, obj_lang, count(*) as total_child \r\n";
            $sql_string3 .= "		from " . $table_name . " \r\n";
            $sql_string3 .= "		where obj_status = 'active' \r\n";
            $sql_string3 .= "		group by obj_referer_id, obj_lang \r\n";
            $sql_string3 .= "	) as b on a." . $field_id . " = b.obj_referer_id  and a.obj_lang = b.obj_lang \r\n";
            $sql_string3 .= "where \r\n";
            $sql_string3 .= "	a.obj_lang = @obj_lang and a.obj_status = 'active' and (a." . $field_id . " = @" . $field_id . " or a." . $field_id . " = @referer_id) ;";
            */
			$sql_string3 = "update " . $table_name . " as a,  \r\n"; 
			$sql_string3 .= "(select obj_referer_id, obj_lang, count(*) as total_child \r\n"; 
			$sql_string3 .= "	from " . $table_name . " \r\n"; 
			$sql_string3 .= "	where obj_status = 'active' \r\n"; 
			$sql_string3 .= "	group by obj_referer_id, obj_lang \r\n"; 
			$sql_string3 .= ") as b\r\n"; 
			$sql_string3 .= "set a.obj_haschild = if(isnull(b.total_child ), 0, b.total_child) \r\n"; 
			$sql_string3 .= "where \r\n"; 
			$sql_string3 .= "	a." . $field_id . " = b.obj_referer_id and \r\n"; 
			$sql_string3 .= "	a.obj_lang = b.obj_lang and \r\n"; 
			$sql_string3 .= "	a.obj_status = 'active' \r\n"; 
			//$sql_string3 .= "	and a.obj_lang = 'MAS' \r\n"; 
			//$sql_string3 .= "	and (a." . $field_id . " = '1' or a." . $field_id . " = '0') ;";
			$sql_param3 =  array();
            $sql_param3[$field_id] = $item_id;
            $sql_param3["referer_id"] = $referer_id;
            $sql_param3["obj_lang"] = $language;
            $sql_param3["obj_status"] = self::$ITEM_STATUS_ACTIVE;
            $sql_param3["obj_path_label"] = $new_path_label;
            $sql_param3["obj_path_id"] = $new_path_id;
            $r3 = $DB->execute($sql_string3, $sql_param3);
            if ($r3 < 0) {
                $result["detail"] = "Unable to update item information";
                $result["sql"] = $DB->LastSQLQueryString;
                $result["param"] = $sql_param3;
                $result["error"] = $DB->LastErrorMessage;
                $DB->rollBack();
                return false;
            }
        } else {
            $result["detail"] = "Unable to retieve item information";
            $result["sql"] = $DB->LastSQLQueryString;
            $result["param"] = $sql_param;
            $result["error"] = $DB->LastErrorMessage;
            $DB->rollBack();
            return false;
        }
        return true;
	}
    static function checkReferenceMapping($DB, $module_name , $reference_mapping , $inputfield , $lang , $item_id) {
        $res = array();
        if ($inputfield == null) {
            $sql_string = "select * from " . $module_name . "_draft where obj_lang = @obj_lang and obj_status = 'active' and " . $module_name . "_id = @item_id ";
            $sql_params = array();
            $sql_param["@item_id"] = $item_id;
            $sql_param["@obj_lang"] = $lang;
            $inputfield = null;
            $r = $DB->query($inputfield, $sql_string, $sql_param ,0,-1, "ASSOC");
            if ($r > 0) {
                $inputfield = $inputfield[0];
            }
        }

        $sql_string = "select * from " . $module_name . " where obj_lang = @obj_lang and " . $module_name . "_id = @item_id ";
        $dt = null;
        $sql_params = array();
        $sql_param["@item_id"] = $item_id;
        $sql_param["@obj_lang"] = $lang;
        $r = $DB->query($dt, $sql_string, $sql_param ,0,-1, "ASSOC");
        if ($r > 0) {
            $res["before_data_mapping"] = $dt[0][$reference_mapping];
        } else {
            $res["before_data_mapping"] = $inputfield[$reference_mapping];
        }
        $res["after_data_mapping"] = $inputfield[$reference_mapping];

        $sql_params = array();
        $sql_param["@reference_mapping"] = $inputfield[$reference_mapping];
        $sql_param["@obj_lang"] = $lang;

        $sql_string = "select * from " . $module_name . " where " . $reference_mapping .  " = @reference_mapping and obj_lang = @obj_lang ";
        if ($item_id != 0) {
            $sql_param["@item_id"] = $item_id;
            $sql_string .= " and " . $module_name . "_id != @item_id ";
        }
        $dt = null;
        $r = $DB->query($dt, $sql_string, $sql_param);

        if ($r > 0) {
            $res["status"] = "REFERENCE_MAPPING_DUPLICATE_ACTIVE";
        } else {
            $res["status"] = "OK";
        }
        return $res;
    }
    static function changeReferenceMapping($DB, $module_name , $reference_mapping , $list_relation_mapping , $status_check) {
        if ($status_check["before_data_mapping"] != $status_check["after_data_mapping"]) {
            for ($i=0;$i<count($list_relation_mapping);$i++) {
                $current_change = $list_relation_mapping[$i];

                $sql_params = array();
                $sql_params["before"] = $status_check["before_data_mapping"];
                $sql_params["after"] = $status_check["after_data_mapping"];

                $sql_string = "UPDATE " . $current_change["tblname"] . " SET " . $current_change["field_name"] . " = @after WHERE " . $current_change["field_name"] . " = @before ";
                $r = $DB->execute($sql_string, $sql_params);

                $sql_string = "UPDATE " . $current_change["tblname"] . "_draft SET " . $current_change["field_name"] . " = @after WHERE " . $current_change["field_name"] . " = @before ";
                $r = $DB->execute($sql_string, $sql_params);
            }
        }
    }
    static function updateReferenceMapping($DB, $data) {
        return false;
    }
	static function actionCreate($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_CREATE, $DB, $data, $result);
	}
	static function actionModify($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_MODIFY, $DB, $data, $result);
	}	
	static function actionPublish($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_PUBLISH, $DB, $data, $result);
	}
	static function actionUnpublish($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_UNPUBLISH, $DB, $data, $result);
	}
	static function actionUnpublishWithDraft($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_UNPUBLISH_WITH_DRAFT, $DB, $data, $result);
	}
	static function actionUnpublishWithRecentDraft($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_UNPUBLISH_WITH_RECENT_DRAFT, $DB, $data, $result);
	}
	static function actionDelete($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_DELETE, $DB, $data, $result);
	}
	static function actionRevert($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_REVERT, $DB, $data, $result);
	}
	static function actionChangeKey($DB, $data, &$result) {
		return self::action(self::$ACTION_CMD_CHANGE_KEY, $DB, $data, $result);
	}
	
	static function actionChildCreate($DB, $data, &$result) {
		return self::actionChild(self::$ACTION_CHILD_CMD_CREATE, $DB, $data, $result);		
	}
	static function actionChildSave($DB, $data, &$result) {
		return self::actionChild(self::$ACTION_CHILD_CMD_SAVE, $DB, $data, $result);
	}	
	static function actionChildPublish($DB, $data, &$result) {
		return self::actionChild(self::$ACTION_CHILD_CMD_PUBLISH, $DB, $data, $result);
	}
	static function actionChildUnpublish($DB, $data, &$result) {
		return self::actionChild(self::$ACTION_CHILD_CMD_UNPUBLISH, $DB, $data, $result);
	}
	static function actionChildDelete($DB, $data, &$result) {
		return self::actionChild(self::$ACTION_CHILD_CMD_DELETE, $DB, $data, $result);
	}
	static function actionChildChangeKey($DB, $data, &$result) {
		return self::actionChild(self::$ACTION_CHILD_CMD_CHANGE_KEY, $DB, $data, $result);
	}
	
	static function setMessageAsRead($msg_id_list, &$result) {
		return self::setMessageReadFlag($msg_id_list, "T", $result);
	}

	static function setMessageAsUnread($msg_id_list, &$result) {
		return self::setMessageReadFlag($msg_id_list, "F", $result);
	}

	static function setMessageReadFlag($msg_id_list, $read_flag, &$result) {
		$db = new OMDatabase();
        $sql_params = array();
        $result = array();
        $sql = "";
        $num_marked = 0;
        $effected_list = "";
        if ($read_flag != "T") { $read_flag = "F"; }
        $sql = "update wcm_message set is_read = @is_read where (msg_id = @msg_id) and (owner_user_id = @owner_user_id) ";
        $sql_params["@is_read"] = $read_flag;
        $sql_params["@owner_user_id"] = OMSession::Current()->UserId;
        foreach ($msg_id_list as $msg_id) {
            $sql_params["@msg_id"] = $msg_id;
            if ($db->execute($sql, $sql_params) > 0) {
                if ($effected_list != "") {
                    $effected_list .= ",";
                }
                $effected_list .= $msg_id;
                $num_marked++;
            }
        }
        $result["effected"] = $effected_list;
        return ($num_marked);
	}

	static function setMessageToTrash($msg_id_list, &$result) {
		return self::setMessageFolder("t", $msg_id_list, $result);
	}

	static function setMessageToDeleted($msg_id_list, &$result) {
		return self::setMessageFolder("deleted", $msg_id_list, $result);
	}

	static function setMessageFolder($folder, $msg_id_list, &$result) {
		$db = new OMDatabase();
        $sql_params = array();
        $result = array();
        $sql = "";
        $num_marked = 0;
        $effected_list = "";
        $folder_update_str = "";
        $status_update_str = "";
        if ($folder == "t") {
            $folder_update_str = "folder = concat('t', folder)";
            $status_update_str = "obj_status = 'active'";
        } else if ($folder == "i") {
            $folder_update_str = "folder = 'i'";
            $status_update_str = "obj_status = 'active'";
        } else if ($folder == "s") {
            $folder_update_str = "folder = 's'";
            $status_update_str = "obj_status = 'active'";
        } else if ($folder == "deleted") {
            $folder_update_str = "folder = folder";
            $status_update_str = "obj_status = 'deleted'";
        }
        $sql = "update wcm_message set " . $folder_update_str . ", " . $status_update_str . " where (msg_id = @msg_id) and (owner_user_id = @owner_user_id) ";
        $sql_params["@owner_user_id"] = OMSession::Current()->UserId;
        foreach ($msg_id_list as $msg_id) {
            $sql_params["@msg_id"] = $msg_id;
            if ($db->execute($sql, $sql_params) > 0) {
                if ($effected_list != "") {
                    $effected_list .= ",";
                }
                $effected_list .= $msg_id;
                $num_marked++;
            }
        }
        $result["effected"] = $effected_list;
        return ($num_marked);
	}

	static function setMessagePutBack($msg_id_list, &$result) {
		$db = new OMDatabase();
        $sql_params = array();
        $result = array();
        $sql = "";
        $num_marked = 0;
        $effected_list = "";
        $sql = "update wcm_message set folder = CASE WHEN folder = 'ts' THEN 's' ELSE 'i' END where (msg_id = @msg_id) and (owner_user_id = @owner_user_id) and folder <> 'deleted'";
        $sql_params["@owner_user_id"] = OMSession::Current()->UserId;
        foreach ($msg_id_list as $msg_id) {
            $sql_params["@msg_id"] = $msg_id;
            if ($db->execute($sql, $sql_params) > 0) {
                if ($effected_list != "") {
                    $effected_list .= ",";
                }
                $effected_list .= $msg_id;
                $num_marked++;
            }
        }
        $result["effected"] = $effected_list;
        return ($num_marked);
	}
	
	static function printMessage($msg_header, $body) {
        $form = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">";
        foreach ($msg_header as $kv_key => $kv_value) {            
            $form .= "<tr>";
            $form .= "	<td width=\"60\" align=\"right\" style=\"color:#666666; font-weight: bold;\" >" . $kv_value["label"] . ":</td>";
            $form .= "	<td>" . $kv_value["data"] . "</td>";
            $form .= "</tr>";
        }
        $form .= "<tr style=\"height:5px;\"><td colspan=\"2\"></td></tr>";
        $form .= "<tr>";
        $form .= "	<td style=\"padding-top:10px; border-top:1px solid #cccccc; width:626px; height:250px; overflow: auto;\" colspan=\"2\" valign=\"top\">" . $body . "</td>";
        $form .= "</tr>";
        $form .= "</table>";
        return ($form);
    }
}
?>