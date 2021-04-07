<?php
class OMDataGrid {
	private $_Header;
	private $_Data;
	private $_Param;

	function __construct() {
		$this->_Data = array();
		$this->_Header = array();
		$this->_Param = array();
		$this->_Param["num_row"] = 0;
		$this->_Param["num_col"] = 0;
		$this->_Param["sort_mode"] = "none";
	}

	function addHeaderColumn($column_code, $column_title, $column_width, $id, $column_align, $display_option){
		$this->_Header[$column_code]["title"] = $column_title;
		$this->_Header[$column_code]["width"] = $column_width;
		$this->_Header[$column_code]["id"] = $id;
		$this->_Header[$column_code]["align"] = $column_align;
		$this->_Header[$column_code]["class"] = "";
		$this->_Header[$column_code]["sort"] = "";
		if($display_option != null){
			$this->_Header[$column_code]["display_option"] = $display_option;		
		} else {
			$this->_Header[$column_code]["display_option"] = null;
		}
		$this->_Param["column_code"][ $this->_Param["num_col"]] = $column_code;
		$this->_Param["num_col"] = $this->_Param["num_col"] + 1;
		return;
	}

	function setColumnSortMode($column_code, $sort_mode){
		$i=0;
		if($sort_mode == "asc" || $sort_mode == "desc"){
			$keylist = array_keys($this->_Header);
			for($i = 0; $i < $this->_Param["num_col"]; $i++){ 
				if (isset($this->_Header[$keylist[$i]]) && isset($this->_Header[$keylist[$i]]["id"])) {
					if($this->_Header[$keylist[$i]]["id"] != "") {
						$this->_Header[$keylist[$i]]["class"] = "click_" . $sort_mode;
					} else {
						$this->_Header[$keylist[$i]]["class"] = "NOID";
					}
				}
				
			}

			if($sort_mode == "asc") { 
				$this->_Header[$column_code]["class"] = "asc click_desc"; 
			} else if ($sort_mode == "desc") { 
				$this->_Header[$column_code]["class"] = "desc click_asc"; 
			}
			$this->_Param["sort_mode"] = $sort_mode;
		}
	}

	function addRow() {
		$p = func_get_args();
        if (count($p) % 2 != 0) {
            throw (new Exception(""));			
        }

		$i;
		for ($i = 0; $i < count($p); $i += 2){
			$this->_Data[$this->_Param["num_row"]][$p[$i]] = $p[$i + 1];			
		}
		$this->_Param["num_row"] = $this->_Param["num_row"] + 1;
	}


	
	function generateJson($p=null){
		if ($p==null) $p = array();
		$p["Header"] = $this->_Header;
		$p["Data"] = $this->_Data;
		$p["Param"] = $this->_Param;
		return OMJson::encode($p);
	}

	function Clear() {
		$this->_Param = array();
		$this->_Header = array();
		$this->_Data = array();
		$this->_Param["num_row"] = 0;
		$this->_Param["num_col"] = 0;
		$this->_Param["sort_mode"] = "none";
	}
}
?>