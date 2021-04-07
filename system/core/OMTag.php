<?php
namespace OMCore;
/**
$tag = new OMTag("rent", 6, "keyword");
echo $tag->TagSubmit();
echo ($tag->errInfo );
*/
	class OMTag{

		var $class_db;
		var $module = '';
		var $module_table = 'wcm_content';
		var $errInfo = '';
		var $ref_id = 0;
		var $field_tag = "tag";
		var $tag_db = array();

		function OMTag($module_tag, $module_ref_id, $module_field_tag = "",$module_table = "market"){
			if ($module_field_tag != "") $this->field_tag = $module_field_tag;
			$this->module = $module_tag;
			$this->module_table = $module_table;
			$this->ref_id = $module_ref_id;
			$this->class_db = OMDb::singleton();
		}

		function TagSubmit(){
			if ($this->module == "") {
				$this->errInfo = "module is empty string";
				return false;
			}
			if (intval($this->ref_id) <= 0) {
				$this->errInfo = $this->module_table ."_id is invalid[".intval($this->ref_id)."]" ;
				return false;
			}

			$strSQL = "SELECT " . $this->field_tag . "
								FROM " . $this->module_table . "
								WHERE " . $this->module_table . "_id ='" . $this->ref_id . "' ";

			$rs = $this->class_db->query($dt, $strSQL);
			if ($rs > 0) {
				$tag_module = $dt[0][$this->field_tag];
				$tagmodule_array = explode(',', $tag_module);
				if (count($tagmodule_array) <=0) return false;
				$tags = array();
				foreach ($tagmodule_array as $val) {
					$val = trim($val);
					if(!empty($val) ){
						$tags[] = $val;
					}
				}

				$this->deleteTagModule();
				$this->getTag($tags);
				$this->insertTag($tags);
				$this->insertTaxonomy($tags);
				// $this->updateTagTotal();
				return true;
			}
			return false;
		}

		function getTag($tagmodule_array){
			$where = null;
			if (count($tagmodule_array) <=0) return false;
			foreach ($tagmodule_array as $tag) {
				$where[] = " tag = '" . $this->class_db->db_escape_string ( $tag ) . "' ";
 			}
			$strSQL = "SELECT tag_id, tag FROM tag where (" . implode(" or ", $where) .")";
			// echo $strSQL;
			$result = OMDb::fetch($strSQL);
			while ($value = mysql_fetch_assoc($result)) {
				$this->tag_db[$value["tag"]] = $value["tag_id"];
			}
			mysql_free_result($result);
		}

		function insertTag($tagmodule_array){
			$datenow = OMDateTime::Now();
			foreach ($tagmodule_array as $value) {
				$value =  trim($value);
				if ( $value != "" && !isset($this->tag_db[$value])) {
					$data = array(
						"tag"=>$value,
						"obj_lang"=>"MAS",
						"obj_published_date"=>$datenow
					);
					$last_id = 0;
					$this->class_db->executeInsert("tag", $data, $last_id);
					$this->tag_db[$value] = $last_id;
				}
			}
		}

		function insertTaxonomy($tagmodule_array){
			$datenow = OMDateTime::Now();
			foreach ($tagmodule_array as $tag) {
				$data = array(
					"tag_id"=>$this->tag_db[$tag],
					"ref_id"=>$this->ref_id,
					"module"=>$this->module,
					"created_date"=>$datenow
				);
				$this->class_db->executeInsert("taxonomy", $data);
			}
		}

		function deleteTagModule(){
			$strSQL = "DELETE FROM taxonomy WHERE
								ref_id=@ref_id
								AND module=@module ";
			$param = array(
				"@ref_id"=> $this->ref_id,
				"@module"=> $this->module
			);
			$rs = $this->class_db->execute($strSQL, $param);
			if ($rs > 0) return true;
		}

		function getModule(){
			$strSQL = "select t.tag
							,count(1) as word_count
					from taxonomy x,tag t
					where x.tag_id=t.tag_id ";
			$rs = $this->class_db->query($dt, $strSQL);
			if ($rs > 0) {
				foreach ($dt as $value) {
					$this->tag_db[$value["tag"]] = $value["tag_id"];
				}
			}
		}

		function updateTagTotal(){
			$strSQL = "truncate table tag_total;";
			$param = array();
			$rs = $this->class_db->execute($strSQL, $param);
			if ($rs < 0) return false;
			$strSQL = "INSERT INTO tag_total (tag,module,total)
					select t.tag,x.module, count(1) as word_count
					from taxonomy x,tag t
					where x.tag_id=t.tag_id group by t.tag ,x.module";
			$rs = $this->class_db->execute($strSQL, $param);
			$strSQL = "INSERT INTO tag_total (tag,module,total)
					select t.tag,'all', count(1) as word_count
					from taxonomy x,tag t
					where x.tag_id=t.tag_id group by t.tag ";
			$rs = $this->class_db->execute($strSQL, $param);
			if ($rs > 0) return true;

		}

	}
?>