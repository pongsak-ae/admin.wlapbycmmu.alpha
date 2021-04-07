<?php
namespace OMCore;

use OMWCMUtil;

abstract class WcmModel {
	var $_DB = null;
	var $_DEFAULT_LANG = 'MAS';

	function __construct($DB=null) {
		if ($DB == null) {
			if (isset($GLOBALS["DB"])) {
				$this->_DB = $GLOBALS["DB"];
			}
		} else {
			$this->_DB = $DB;
		}
	}

	abstract function getModel();
	/*
	function getModel() {
		return new OMModel_subscribe_enews();
	}
	*/

	function getActiveItem($item_id, $options = null) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;
		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}

		$sql = "select * from " . $data["tbl"]["draft"] . " where " . $data["field_id"]["draft"] . " = @item_id and obj_status='active' and obj_lang = @lang ";
		$sql_param = array(
							"item_id"=>$item_id,
							"lang"=>$lang

						);
		$r = $DB->query($ds, $sql, $sql_param, 0, -1, 'ASSOC');
		if ($r>0) {
			return $ds[0];
		} else {
			return null;
		}
	}

	function create($fielddata, $options = null) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;

		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}

		$data['key']['language']=$lang;
		$data['key']['revision']=1;
		$data['key']['item_id']=0;

		$data['fielddata'] = $fielddata;

		$resultAction = OMWCMUtil::actionCreate($DB, $data, $result);
		if ($resultAction) {
			if (isset($model->data_child)) {
				$tmp_session = 0;
				foreach($model->data_child as $kv_key=>$kv_value) {
					$kv_value["key"]["user_id"] = $data["key"]["user_id"];
					$kv_value["key"]["parent_id"] = $result["item_id"];
					$kv_value["key"]["language"] = $data["key"]["language"];
					$kv_value["key"]["old_revision"] = 0;
					$kv_value["key"]["revision"] = $result["revision"];
					$kv_value["key"]["tmp_session"] = $tmp_session;
					$kv_value["key"]["item_id"] = 0;
					if (OMWCMUtil::actionChildSave($DB, $kv_value, $result_child)) {

					}
				}
			}

			$data["key"]["revision"] = $result["revision"];
			$data["key"]["item_id"] = $result["item_id"];

			$data["field"][$data["module_name"] . "_id"] = $data["module_name"] . "_id";
			$data["field"]["obj_lang"] = "obj_lang";

			$resultAction = OMWCMUtil::actionPublish($DB, $data, $result);
			if (isset($model->data_child) && $resultAction) {
				foreach($model->data_child as $kv_key=>$kv_value) {
					$kv_value["key"]["user_id"] = $data["key"]["user_id"];
					$kv_value["key"]["parent_id"] = $data["key"]["item_id"];
					$kv_value["key"]["language"] = $data["key"]["language"];
					$kv_value["key"]["revision"] = $data["key"]["revision"];
					if (OMWCMUtil::actionChildPublish($DB, $kv_value, $result_child)) {

					}
				}
			}
			return $result["item_id"];
		} else {
			return false;
		}
	}



	function update($item_id, $fielddata, $options = null) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;

		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}

		$item_info = $this->getActiveItem($item_id, $options);

		if ($item_info == false) {
			return false;
		}

		$data['key']['language']=$lang;
		$data['key']['revision']=$item_info["obj_rev"];
		$data['key']['item_id']=$item_info[$data["module_name"] . "_id"];

		$data['fielddata'] = $fielddata;

		$resultAction = OMWCMUtil::actionModify($DB, $data, $result);
		// var_dump($resultAction);
		if ($resultAction) {
			if (isset($model->data_child)) {
				$tmp_session = 0;
				foreach($model->data_child as $kv_key=>$kv_value) {
					$kv_value["key"]["user_id"] = $data["key"]["user_id"];
					$kv_value["key"]["parent_id"] = $result["item_id"];
					$kv_value["key"]["language"] = $data["key"]["language"];
					$kv_value["key"]["old_revision"] = $data['key']['revision'];
					$kv_value["key"]["revision"] = $result["revision"];
					$kv_value["key"]["tmp_session"] = $tmp_session;
					$kv_value["key"]["item_id"] = 0;
					if (OMWCMUtil::actionChildSave($DB, $kv_value, $result_child)) {

					}
				}
			}
			$data["key"]["revision"] = $result["revision"];
			$data["key"]["item_id"] = $result["item_id"];

			$data["field"][$data["module_name"] . "_id"] = $data["module_name"] . "_id";
			$data["field"]["obj_lang"] = "obj_lang";

			$resultAction = OMWCMUtil::actionPublish($DB, $data, $result);
			if (isset($model->data_child) && $resultAction) {
				foreach($model->data_child as $kv_key=>$kv_value) {
					$kv_value["key"]["user_id"] = $data["key"]["user_id"];
					$kv_value["key"]["parent_id"] = $data["key"]["item_id"];
					$kv_value["key"]["language"] = $data["key"]["language"];
					$kv_value["key"]["revision"] = $data["key"]["revision"];
					if (OMWCMUtil::actionChildPublish($DB, $kv_value, $result_child)) {

					}
				}
			}
			return true;
		} else {
			return false;
		}
	}
	function unpublish($item_id, $options = null) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;

		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}
		$item_info = $this->getActiveItem($item_id, $options);
		if ($item_info == false) {
			return false;
		}

		$data['key']['language']=$lang;
		$data['key']['revision']=$item_info["obj_rev"];
		$data['key']['item_id']=$item_info[$data["module_name"] . "_id"];


		if (OMWCMUtil::actionUnpublish($DB, $data, $result)) {
			if (isset($model->data_child)) {
				foreach($model->data_child as $kv_key => $kv_value) {
					$kv_value["key"]["user_id"] = $data["key"]["user_id"];
					$kv_value["key"]["parent_id"] = $data["key"]["item_id"];
					$kv_value["key"]["language"] = $data["key"]["language"];
					$kv_value["key"]["old_revision"] = $data["key"]["revision"];
					$kv_value["key"]["revision"] = $result["revision"];
					$kv_value["key"]["tmp_session"] = 0;
					$kv_value["key"]["item_id"] = 0;
					if (OMWCMUtil::actionChildUnpublish($DB, $kv_value, $result_child)) {
						return true;
					}
				}
			}
		}
		return true;
	}

	function createChild($child_name, $parent_id, $fielddata, $options = null) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;

		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}
		$parent_info = $this->getActiveItem($parent_id, $options);

		if ($parent_info == null) return false;

		$data = $model->data_child[$child_name];
		$tmp_session = 0;
		$revision = $parent_info["obj_rev"];
		$parent_id = $parent_id;
		$child_item_id = 0;
		$data["key"]["language"] = $lang;
		$data["key"]["user_id"] = 1;
		$data["key"]["item_id"] = 0;
		$data["key"]["parent_id"] = $parent_id;
		$data["key"]["tmp_session"] = $tmp_session;
		$data["key"]["old_revision"] = $revision; // dont change
		$data["key"]["revision"] = $revision; // from data out
		$data["fielddata"] = $fielddata;
		$resultAction = OMWCMUtil::actionChildCreate($DB, $data, $result);
		if ($resultAction) {
			$child_item_id = $result["item_id"];
			if (isset($data["key"]["revision"]) && isset($result["revision"]) && $data["key"]["revision"] != $result["revision"]){
				foreach($model->data_child as $kv_key=>$kv_value) {
					if ($kv_key != $child_name){
						$kv_value["key"]["user_id"] = $data["key"]["user_id"];
						$kv_value["key"]["parent_id"] = $parent_id;
						$kv_value["key"]["language"] = $data["key"]["language"];
						$kv_value["key"]["revision"] = $result["revision"];
						$kv_value["key"]["old_revision"] = $revision;
						$kv_value["key"]["tmp_session"] = 0;
						$kv_value["key"]["item_id"] = 0;

						if (OMWCMUtil::actionChildSave($DB, $kv_value, $result_child)) {

						}
					}
				}
			}

			$parent_data = $model->data;

			$parent_data["key"]["language"] = $result["language"];
			$parent_data["key"]["revision"] = $result["revision"];
			$parent_data["key"]["item_id"] = $parent_id;
			$parent_data["key"]["user_id"] = 1;

			$parent_data["field"][$parent_data["module_name"] . "_id"] = $parent_data["module_name"] . "_id";
			$parent_data["field"]["obj_lang"] = "obj_lang";
			$parent_data['fielddata'] = array();

			$resultParentAction = OMWCMUtil::actionPublish($DB, $parent_data, $result);
			if (isset($model->data_child) && $resultParentAction) {
				foreach($model->data_child as $kv_key=>$kv_value) {
					$kv_value["key"]["user_id"] = $parent_data["key"]["user_id"];
					$kv_value["key"]["parent_id"] = $parent_data["key"]["item_id"];
					$kv_value["key"]["language"] = $parent_data["key"]["language"];
					$kv_value["key"]["revision"] = $parent_data["key"]["revision"];
					if (OMWCMUtil::actionChildPublish($DB, $kv_value, $result_child)) {

					}  else {

					}


				}
			}
			return $child_item_id;
		} else {
			return false;
		}
	}
	function updateChildren($child_name, $parent_id, $member_children_id, $fielddata, $options=null) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;

		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}
		$parent_info = $this->getActiveItem($parent_id,$options);
		if ($parent_info == null) return false;
		$data = $model->data_child[$child_name];
		$tmp_session = 0;
		$revision = $parent_info["obj_rev"];

		$data["key"]["language"] = $lang;
		$data["key"]["user_id"] = 1;
		$data["key"]["item_id"] = $member_children_id;
		$data["key"]["parent_id"] = $parent_id;
		$data["key"]["tmp_session"] = $tmp_session;
		$data["key"]["old_revision"] = $revision; // dont change
		$data["key"]["revision"] = $revision; // from data out
		$data["fielddata"] = $fielddata;
		$resultAction = OMWCMUtil::actionChildSave($DB, $data, $result);

		if ($resultAction) {

			if (isset($data["key"]["revision"]) && isset($result["revision"]) && $data["key"]["revision"] != $result["revision"]){
				$i = 0;
				$k = 0;
				$j = 0;
				foreach($model->data_child as $kv_key=>$kv_value) {

					if ($kv_key != $child_name){
						$kv_value["key"]["user_id"] = 0;
						$kv_value["key"]["parent_id"] = $parent_id;
						$kv_value["key"]["language"] = $data["key"]["language"];
						$kv_value["key"]["revision"] = $result["revision"];
						$kv_value["key"]["old_revision"] = $revision;
						$kv_value["key"]["tmp_session"] = 0;
						$kv_value["key"]["item_id"] = 0;
						$k++;
						if (OMWCMUtil::actionChildSave($DB, $kv_value, $result_child)) {
							$j++;
						}
					}
					$i++;
				}
			}



			$parent_data = $model->data;

			$parent_data["key"]["language"] = $result["language"];
			$parent_data["key"]["revision"] = $result["revision"];
			$parent_data["key"]["item_id"] = $parent_id;

			$parent_data["field"][$parent_data["module_name"] . "_id"] = $parent_data["module_name"] . "_id";
			$parent_data["field"]["obj_lang"] = "obj_lang";
			$parent_data['fielddata'] = array();

			$resultParentAction = OMWCMUtil::actionPublish($DB, $parent_data, $result);
			if (isset($model->data_child) && $resultParentAction) {
				foreach($model->data_child as $kv_key=>$kv_value) {
					$kv_value["key"]["user_id"] = $parent_data["key"]["user_id"];
					$kv_value["key"]["parent_id"] = $parent_data["key"]["item_id"];
					$kv_value["key"]["language"] = $parent_data["key"]["language"];
					$kv_value["key"]["revision"] = $parent_data["key"]["revision"];
					if (OMWCMUtil::actionChildPublish($DB, $kv_value, $result_child)) {

					}
				}
			}
			return true;
		} else {
			return false;
		}
	}

	function deleteChildren($child_name, $parent_id, $member_children_id, $fielddata, $options) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;

		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}

		$parent_info = $this->getActiveItem($parent_id,$options);

		if ($parent_info == null) return false;
		$data = $model->data_child[$child_name];
		$tmp_session = 0;
		$revision = $parent_info["obj_rev"];
		$data["key"]["language"] = $lang;
		$data["key"]["user_id"] = 1;
		$data["key"]["item_id"] = $member_children_id;
		$data["key"]["parent_id"] = $parent_id;
		$data["key"]["tmp_session"] = $tmp_session;
		$data["key"]["old_revision"] = $revision; // dont change
		$data["key"]["revision"] = $revision; // from data out
		$data["fielddata"] = $fielddata;
		$resultAction = OMWCMUtil::actionChildDelete($DB, $data, $result);
		if ($resultAction) {

			if (isset($data["key"]["revision"]) && isset($result["revision"]) && $data["key"]["revision"] != $result["revision"]){

				foreach($model->data_child as $kv_key=>$kv_value) {

					if ($kv_key != $child_name){
						$kv_value["key"]["user_id"] = 0;
						$kv_value["key"]["parent_id"] = $parent_id;
						$kv_value["key"]["language"] = $data["key"]["language"];
						$kv_value["key"]["revision"] = $result["revision"];
						$kv_value["key"]["old_revision"] = $revision;
						$kv_value["key"]["tmp_session"] = 0;
						$kv_value["key"]["item_id"] = 0;

						if (OMWCMUtil::actionChildSave($DB, $kv_value, $result_child)) {

						}
					}

				}
			}

			$parent_data = $model->data;

			$parent_data["key"]["language"] = $result["language"];
			$parent_data["key"]["revision"] = $result["revision"];
			$parent_data["key"]["item_id"] = $parent_id;

			$parent_data["field"][$parent_data["module_name"] . "_id"] = $parent_data["module_name"] . "_id";
			$parent_data["field"]["obj_lang"] = "obj_lang";
			$parent_data['fielddata'] = array();

			$resultParentAction = OMWCMUtil::actionPublish($DB, $parent_data, $result);
			if (isset($model->data_child) && $resultParentAction) {
				foreach($model->data_child as $kv_key=>$kv_value) {
					$kv_value["key"]["user_id"] = $parent_data["key"]["user_id"];
					$kv_value["key"]["parent_id"] = $parent_data["key"]["item_id"];
					$kv_value["key"]["language"] = $parent_data["key"]["language"];
					$kv_value["key"]["revision"] = $parent_data["key"]["revision"];
					if (OMWCMUtil::actionChildPublish($DB, $kv_value, $result_child)) {

					}
				}
			}

			return true;
		} else {
			return false;
		}
	}

	function createChildCustom($child_name, $parent_id, $fielddata, $options = null) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;

		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}
		$parent_info = $this->getActiveItem($parent_id, $options);

		if ($parent_info == null) return false;

		$data = $model->data_child[$child_name];
		$tmp_session = 0;
		$revision = $parent_info["obj_rev"];
		$parent_id = $parent_id;
		$child_item_id = 0;
		$data["key"]["language"] = $lang;
		$data["key"]["user_id"] = 1;
		$data["key"]["item_id"] = 0;
		$data["key"]["parent_id"] = $parent_id;
		$data["key"]["tmp_session"] = $tmp_session;
		$data["key"]["old_revision"] = $revision; // dont change
		$data["key"]["revision"] = $revision; // from data out
		$data["fielddata"] = $fielddata;
		$resultAction = OMWCMUtil::actionChildCreate($DB, $data, $result);
		if ($resultAction) {
			$child_item_id = $result["item_id"];
			if (isset($data["key"]["revision"]) && isset($result["revision"]) && $data["key"]["revision"] != $result["revision"]){
				foreach($model->data_child as $kv_key=>$kv_value) {
					if ($kv_key != $child_name){
						$kv_value["key"]["user_id"] = $data["key"]["user_id"];
						$kv_value["key"]["parent_id"] = $parent_id;
						$kv_value["key"]["language"] = $data["key"]["language"];
						$kv_value["key"]["revision"] = $result["revision"];
						$kv_value["key"]["old_revision"] = $revision;
						$kv_value["key"]["tmp_session"] = 0;
						$kv_value["key"]["item_id"] = 0;

						if (OMWCMUtil::actionChildSave($DB, $kv_value, $result_child)) {

						}
					}
				}
			}

			$parent_data = $model->data;

			$parent_data["key"]["language"] = $result["language"];
			$parent_data["key"]["revision"] = $result["revision"];
			$parent_data["key"]["item_id"] = $parent_id;
			$parent_data["key"]["user_id"] = 1;

			$parent_data["field"][$parent_data["module_name"] . "_id"] = $parent_data["module_name"] . "_id";
			$parent_data["field"]["obj_lang"] = "obj_lang";
			$parent_data['fielddata'] = array();

			// $resultParentAction = OMWCMUtil::actionPublish($DB, $parent_data, $result);
			// if (isset($model->data_child) && $resultParentAction) {
			// 	foreach($model->data_child as $kv_key=>$kv_value) {
			// 		$kv_value["key"]["user_id"] = $parent_data["key"]["user_id"];
			// 		$kv_value["key"]["parent_id"] = $parent_data["key"]["item_id"];
			// 		$kv_value["key"]["language"] = $parent_data["key"]["language"];
			// 		$kv_value["key"]["revision"] = $parent_data["key"]["revision"];
			// 		if (OMWCMUtil::actionChildPublish($DB, $kv_value, $result_child)) {

			// 		}  else {

			// 		}


			// 	}
			// }
			return $child_item_id;
		} else {
			return false;
		}
	}
	function updateChildCustom($child_name, $parent_id, $member_children_id, $fielddata, $options=null) {
		$DB = $this->_DB;
		$model = $this->getModel();
		$data = $model->data;

		$lang = $this->_DEFAULT_LANG;
		if ($options != null) {
			if (isset($options['lang'])) {
				$lang = $options['lang'];
			}
		}
		$parent_info = $this->getActiveItem($parent_id,$options);
		if ($parent_info == null) return false;
		$data = $model->data_child[$child_name];
		$tmp_session = 0;
		$revision = $parent_info["obj_rev"];

		$data["key"]["language"] = $lang;
		$data["key"]["user_id"] = 1;
		$data["key"]["item_id"] = $member_children_id;
		$data["key"]["parent_id"] = $parent_id;
		$data["key"]["tmp_session"] = $tmp_session;
		$data["key"]["old_revision"] = $revision; // dont change
		$data["key"]["revision"] = $revision; // from data out
		$data["fielddata"] = $fielddata;
		$resultAction = OMWCMUtil::actionChildSave($DB, $data, $result);
		if ($resultAction) {

			if (isset($data["key"]["revision"]) && isset($result["revision"]) && $data["key"]["revision"] != $result["revision"]){
				$i = 0;
				$k = 0;
				$j = 0;
				foreach($model->data_child as $kv_key=>$kv_value) {

					if ($kv_key != $child_name){
						$kv_value["key"]["user_id"] = 0;
						$kv_value["key"]["parent_id"] = $parent_id;
						$kv_value["key"]["language"] = $data["key"]["language"];
						$kv_value["key"]["revision"] = $result["revision"];
						$kv_value["key"]["old_revision"] = $revision;
						$kv_value["key"]["tmp_session"] = 0;
						$kv_value["key"]["item_id"] = 0;
						$k++;
						if (OMWCMUtil::actionChildSave($DB, $kv_value, $result_child)) {
							$j++;
						}
					}
					$i++;
				}
			}



			$parent_data = $model->data;

			$parent_data["key"]["language"] = $result["language"];
			$parent_data["key"]["revision"] = $result["revision"];
			$parent_data["key"]["item_id"] = $parent_id;

			$parent_data["field"][$parent_data["module_name"] . "_id"] = $parent_data["module_name"] . "_id";
			$parent_data["field"]["obj_lang"] = "obj_lang";
			$parent_data['fielddata'] = array();

			// $resultParentAction = OMWCMUtil::actionPublish($DB, $parent_data, $result);
			// if (isset($model->data_child) && $resultParentAction) {
			// 	foreach($model->data_child as $kv_key=>$kv_value) {
			// 		$kv_value["key"]["user_id"] = $parent_data["key"]["user_id"];
			// 		$kv_value["key"]["parent_id"] = $parent_data["key"]["item_id"];
			// 		$kv_value["key"]["language"] = $parent_data["key"]["language"];
			// 		$kv_value["key"]["revision"] = $parent_data["key"]["revision"];
			// 		if (OMWCMUtil::actionChildPublish($DB, $kv_value, $result_child)) {

			// 		}
			// 	}
			// }
			return true;
		} else {
			return false;
		}
	}

}

?>
