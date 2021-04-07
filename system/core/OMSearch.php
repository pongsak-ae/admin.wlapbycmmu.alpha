<?php
namespace OMCore;
use MongoRegex;
use MongoCode;
use OMCore;

class OMSearch {

	function __construct() {

	}

	public function getSearchText($q){

		$search = trim($q);

		$search_res = array();

		preg_match_all('/(?:([\'"]).*?\1|[^\s,\'"]+)(?=[\s,]|$)/', $search, $m);

		$search = array();

		if(!empty($m[0])){
			foreach ($m[0] as $key => $value) {
				$search[$key] = str_replace(array("'","\""),array("",""),trim($value));
			}
		}

		foreach ($search as $key => $value) {
			if(!in_array($value, $search_res) && $value != ""){
				array_push($search_res, $value);
			}
		}

		return $search_res;
	}

	public function search_hash($search){
		return md5($search);
	}

	public function getRatingScore(){
		$str = "pdf_txt|:|detail_plain|:|description|:|tag|:|title";
		$score_a = explode("|:|", $str);
		$score = array();

		foreach ($score_a as $key => $value) {
			$score[$value] = pow(2,$key);
		}

		return $score;
	}

	public function getDataFromSearchIndex($search_res){

		if(!empty($search_res)){
			$mongoTable = OMMongo::table("search_index");

			$where = array();

			$where["lang"] = strtoupper(LANG);
			$num_rows = 0;

			foreach ($search_res as $key => $value) {
				$rex = array('$regex' => new MongoRegex("/.{$value}./i"));

				$where['$or'][$num_rows] = array("pdf_txt"=>$rex);
				$where['$or'][$num_rows] = array("raw_data"=>$rex);
				$num_rows++;

			}

			$data = $mongoTable->find($where);

			$ds = array();
			$num_rows = 0;
			foreach ($data as $key => $value) {

				$ds[$num_rows]["ref_id"] = $value["ref_id"];
				$ds[$num_rows]["module"] = $value["module"];
				$ds[$num_rows]["lang"] = $value["lang"];
				$ds[$num_rows]["category_id"] = $value["category_id"];
				$ds[$num_rows]["obj_path_id"] = $value["obj_path_id"];
				$ds[$num_rows]["raw_data"] = $value["raw_data"];
				$ds[$num_rows]["title"] = $value["title"];
				$ds[$num_rows]["tag"] = $value["tag"];
				$ds[$num_rows]["description"] = $value["description"];
				$ds[$num_rows]["detail_plain"] = $value["detail_plain"];
				$ds[$num_rows]["published_item_date"] = $value["published_item_date"];
				$ds[$num_rows]["created_index_date"] = $value["created_index_date"];
				$ds[$num_rows]["pdf_txt"] = $value["pdf_txt"];
				$num_rows++;

			}

			if($num_rows > 0){

				return array("data"=>$ds,"row"=>$num_rows);
			}else{

				return array();
			}

		}else{
			return array();
		}

	}

	public function getScoreTitle($score,$search_res,$raw_data_value){
		$total = 0;

		if(!empty($search_res) && $raw_data_value != ""){
			foreach ($search_res as $key => $value) {
				$a = $value;
				$b = $raw_data_value;
				preg_match_all("'".$a."'", $b, $match);

				if(!empty($match[0])){
					foreach ($match[0] as $match_key => $match_value) {
						$total += $score;
					}
				}
			}
		}

		return $total;

	}

	public function getScoreTag($score,$search_res,$raw_data_value){

		$total = 0;

		if(!empty($search_res) && $raw_data_value != ""){
			foreach ($search_res as $key => $value) {
				$a = $value;
				$b = $raw_data_value;
				preg_match_all("'".$a."'", $b, $match);

				if(!empty($match[0])){
					foreach ($match[0] as $match_key => $match_value) {
						$total += $score;
					}
				}
			}
		}

		return $total;

	}

	public function getScoreDescription($score,$search_res,$raw_data_value){
		$total = 0;

		if(!empty($search_res) && $raw_data_value != ""){
			foreach ($search_res as $key => $value) {
				$a = $value;
				$b = $raw_data_value;
				preg_match_all("'".$a."'", $b, $match);

				if(!empty($match[0])){
					foreach ($match[0] as $match_key => $match_value) {
						$total += $score;
					}
				}
			}
		}

		return $total;
	}

	public function getScoreDetailPlain($score,$search_res,$raw_data_value){
		$total = 0;

		if(!empty($search_res) && $raw_data_value != ""){
			foreach ($search_res as $key => $value) {
				$a = $value;
				$b = $raw_data_value;
				preg_match_all("'".$a."'", $b, $match);

				if(!empty($match[0])){
					foreach ($match[0] as $match_key => $match_value) {
						$total += $score;
					}
				}
			}
		}

		return $total;
	}

	public function getScorePdfTxt($score,$search_res,$raw_data_value){
		$total = 0;

		if(!empty($search_res) && $raw_data_value != ""){
			foreach ($search_res as $key => $value) {
				$a = $value;
				$b = $raw_data_value;
				preg_match_all("'".$a."'", $b, $match);

				if(!empty($match[0])){
					foreach ($match[0] as $match_key => $match_value) {
						$total += $score;
					}
				}
			}
		}

		return $total;
	}

	public function setHighlightHtml($data,$handle){

		$pos = 0;

		foreach ($data as $key => $value) {

			$pos = @mb_strpos($handle, $value, 1);

			if($pos > 0){
				break;
			}

		}

		$str = "";
		$res = "";
		$highlight_a = array();

		$len = 200;
		if($pos < $len){
			$start = 0;
			$end = $pos;
		} else {
			$start = $pos - $len;
			$end = $len;
		}

		$detail_res = mb_substr($handle, $start , $end).mb_substr($handle, $pos,$len);
		// $detail_res = htmlentities($detail_res,ENT_SUBSTITUTE);

		if(!empty($data)){
			$res = $this->str_highlight($detail_res, $data);
		}

		return $res;
	}

	public function str_highlight($text, $needle, $options = null, $highlight = null)
	{
	    // Default highlighting
	    if ($highlight === null) {
	        $highlight = '<span class="hilight">\1</span>';
	    }

	    // Select pattern to use
	    if ($options & @STR_HIGHLIGHT_SIMPLE) {
	        $pattern = '#(%s)#';
	        $sl_pattern = '#(%s)#';
	    } else {
	        $pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
	        $sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';
	    }

	    // Case sensitivity
	    if (!($options & @STR_HIGHLIGHT_CASESENS)) {
	        $pattern .= 'i';
	        $sl_pattern .= 'i';
	    }

	    $needle = (array) $needle;
	    foreach ($needle as $needle_s) {
	        $needle_s = preg_quote($needle_s);

	        // Escape needle with optional whole word check
	        if ($options & @STR_HIGHLIGHT_WHOLEWD) {
	            $needle_s = '\b' . $needle_s . '\b';
	        }

	        // Strip links
	        if ($options & @STR_HIGHLIGHT_STRIPLINKS) {
	            $sl_regex = sprintf($sl_pattern, $needle_s);
	            $text = preg_replace($sl_regex, '\1', $text);
	        }

	        $regex = sprintf($pattern, $needle_s);
	        $text = preg_replace($regex, $highlight, $text);
	    }

	    return $text;
	}

	public function chkCountRowMongo($search_hash=""){
		$now = strtotime('now');
		$mongoTable = OMMongo::table("search_result");

		$rs = $mongoTable
			->find(
			       array(
			            'value.lang' => strtoupper(LANG),
			            'value.search_hash' => $search_hash,
			            'value.datetime' =>
			            array(
			                  '$gt' => intval($now)-3600
			                )
			            )
			       )
			->sort(
			       array(
			             'score' => -1
			             )
			       )
			->count();

		return $rs;

	}

	public function chkCountRowMongoByCategory($search_hash="",$params){

		$now = strtotime('now');
		$mongoTable = OMMongo::table("search_result");

		$acl = new OMACL();

		$acl_id = $acl->getAclID();

		if($acl_id != null){

			$parmission_webdirectory = $this->getPermissionWebdirectory();

			// $param = array();

			// $param["value.acl_id.acl_id"] = $acl_id;
			// $param["value.lang"] = strtoupper(LANG);
			// $param["value.search_hash"] = $search_hash;
			// // $param["value.datetime"] = array('$gt' => intval($now)-3600);
			// if($params["group"] == "eicanalysis"){
			// 	$param['value.obj_path_id'] = new MongoRegex("/".EIC_ANALYSIS_ID.".*/i");
			// }

			$param = array();

			$param_document = array();
			$param_document["value.acl_id.acl_id"] = $acl_id;
			$param_document["value.lang"] = strtoupper(LANG);
			$param_document["value.module"] = "document";
			$param_document["value.search_hash"] = $search_hash;
			// $param["value.datetime"] = array('$gt' => intval($now)-3600);

			if($params["group"] == "eicanalysis"){
				$param_document['value.obj_path_id'] = new MongoRegex("/".EIC_ANALYSIS_ID.".*/i");
			}

			$param['$or'][] = $param_document;

			$param_webdirectory = array();
			$param_webdirectory["value.acl_id"] = array('$lte' => $parmission_webdirectory);
			$param_webdirectory["value.lang"] = strtoupper(LANG);
			$param_webdirectory["value.module"] = "webdirectory";
			$param_webdirectory["value.search_hash"] = $search_hash;

			$param['$or'][] = $param_webdirectory;

			// var_dump($param);

			$rs = $mongoTable
				->find($param)
				->sort(
				       array(
				             'score' => -1
				             )
				       )
				->count();

			return $rs;
		}else{
			return 0;
		}

	}

	public function getPermissionWebdirectory(){

		$acl = new OMACL();
		$omcookie = new OMCookie();

		$parmission_webdirectory = 1;

		if($acl->checkIntranet()){
			$parmission_webdirectory = 3;
		}else{
			if($omcookie->get(COOKIE_USERNAME) == USER_GUEST){
				$parmission_webdirectory = 1;
			}else{
				$parmission_webdirectory = 2;
			}
		}

		return strval($parmission_webdirectory);
	}

	public function getDataMongo($search_hash="",$search_res="",$start=0,$end=20,$search_most,$params){
		$now = strtotime('now');
		$arr = array();
		$mongoTable = OMMongo::table("search_result");
		$num_rows = 0;
		$html = "";
		$acl = new OMACL();
		$omdtct = new OMDateTimeConverter();


		$acl_id = $acl->getAclID();

		$sort = array('value.score' => -1);

		if($search_most == "most_relevant"){
			$sort = array('value.score' => -1);
		}else{
			$sort = array('value.datetime' => -1);
		}

		if($acl_id != null){

			$parmission_webdirectory = $this->getPermissionWebdirectory();

			// $param = array();

			// $param["value.acl_id.acl_id"] = $acl_id;
			// $param["value.lang"] = strtoupper(LANG);
			// $param["value.search_hash"] = $search_hash;
			// // $param["value.datetime"] = array('$gt' => intval($now)-3600);

			// if($params["group"] == "eicanalysis"){
			// 	$param['value.obj_path_id'] = new MongoRegex("/".EIC_ANALYSIS_ID.".*/i");
			// }

			$param = array();

			$param_document = array();
			$param_document["value.acl_id.acl_id"] = $acl_id;
			$param_document["value.lang"] = strtoupper(LANG);
			$param_document["value.module"] = "document";
			$param_document["value.search_hash"] = $search_hash;
			// $param["value.datetime"] = array('$gt' => intval($now)-3600);

			if($params["group"] == "eicanalysis"){
				$param_document['value.obj_path_id'] = new MongoRegex("/".EIC_ANALYSIS_ID.".*/i");
			}

			$param['$or'][] = $param_document;

			$param_webdirectory = array();
			$param_webdirectory["value.acl_id"] = array('$lte' => $parmission_webdirectory);
			$param_webdirectory["value.lang"] = strtoupper(LANG);
			$param_webdirectory["value.module"] = "webdirectory";
			$param_webdirectory["value.search_hash"] = $search_hash;

			$param['$or'][] = $param_webdirectory;

			// var_dump($param);

			$data = $mongoTable
				->find($param)
				->sort($sort)
				->skip($start)
				->limit($end);

				$data = iterator_to_array($data);

				$num_rows = 0;
				foreach ($data as $key1 => $value1) {
					$ref_id = $value1["value"]["ref_id"]!=null&&$value1["value"]["ref_id"]!=""?$value1["value"]["ref_id"]:"";
					$image = $value1["value"]["image"]!=null&&$value1["value"]["image"]!=""?$value1["value"]["image"]:"";
					$module = $value1["value"]["module"]!=null&&$value1["value"]["module"]!=""?$value1["value"]["module"]:"";
					$title = $value1["value"]["title"]!=null&&$value1["value"]["title"]!=""?$value1["value"]["title"]:"";
					$detail = $value1["value"]["detail"]!=null&&$value1["value"]["detail"]!=""?$value1["value"]["detail"]:"";
					$link_web = $value1["value"]["link"]!=null&&$value1["value"]["link"]!=""?$value1["value"]["link"]:"";
					$slug_name = $value1["value"]["slug_name"]!=null&&$value1["value"]["slug_name"]!=""?$value1["value"]["slug_name"]:"";
					$last_update = $value1["value"]["last_update"]!=null&&$value1["value"]["last_update"]!=""?$value1["value"]["last_update"]:"";
					$tag = $value1["value"]["tag"]!=null&&$value1["value"]["tag"]!=""?$value1["value"]["tag"]:"";

					$link = "";
					$link_mode = "";
					if($link_web != ""){
						$link = $link_web;
						$link_mode = "_blank";
					}else{
						$link = WEB_META_BASE_LANG."detail/".$ref_id."/".$slug_name;
						$link_mode = "_self";
					}

					$pattern = "d M Y";
					$date_lang = "en";

					if(strtoupper(LANG) == "THA"){
						$pattern = "วัน %A %B ปี %Y %H:%m";
						$date_lang = "th";
					}


					$html .= '<div class="search_list row">';

						if($image != ""){
							$html .= '<div class="col-xs-12 col-sm-1 col-no-padding">';
							$html .= '<img width="70" src="http://scbeic.ifirstfix.alpha/stocks/media/'.$image.'" class="list_images">';
							$html .= "</div>";

							$html .= '<div class="col-xs-12 col-xs-11 col-no-padding">';
						}else{

							$html .= '<div class="col-xs-12 col-no-padding">';
						}


						if(($this->setHighlightHtml($search_res,$title)) != ""){
							$title_r = $title;
							if($params["where_keyword_title"] == 1){
								$title_r = $this->setHighlightHtml($search_res,$title);
							}
							if($module == "webdirectory"){
								$html .= '<div class="list_title"><a href="'.WEB_META_BASE_LANG.'webdirectory/redirec/'.$ref_id.'" target="_blank">'.$title_r.'</a></div>';
							}else{
								$html .= '<div class="list_title"><a href="'.$link.'" target="'.$link_mode.'">'.$title_r.'</a></div>';
							}
						}

						if($module == "webdirectory"){
							$html .= '<div class="list_link"><a href="'.WEB_META_BASE_LANG.'webdirectory/redirec/'.$ref_id.'" target="_blank">'.$link.'</a></div>';
						}

						if(($this->setHighlightHtml($search_res,$detail)) != ""){
							$detail_r = $detail;
							if($params["where_keyword_text"] == 1){
								$detail_r = $this->setHighlightHtml($search_res,$detail);
							}
							$html .= '<div class="list_description">'.$detail_r.'</div>';
						}
						if($omdtct->gen_date($last_update,$pattern,$date_lang) != ""){
							$html .= '<div class="list_last_update">'.$omdtct->gen_date($last_update,$pattern,$date_lang).'</div>';
						}
						if(substr($tag,1,-1) != ""){

							$tag_r = substr($tag,1,-1);
							if($params["where_keyword_tag"] == 1){
								$tag_r = $this->setHighlightHtml($search_res,substr($tag,1,-1));
							}

							$html .= '<div class="list_tag"><img src="'.WEB_META_BASE_URL.'images/search/icon_tag.png"/> <strong>Tag:</strong> '.$tag_r.'</div>';
						}

							$html .= '<div class="clearfix"></div>';
						$html .= '</div>';
						$html .= '<div class="clearfix"></div>';
					$html .= '</div>';

					$num_rows++;

				}


			return array(
			            "row"=>$num_rows,
			            "html"=>strval($html)
						);

		}else{
			return null;
		}

	}

	public function saveSearchResult($hash,$keyword,$during,$search_most,$params){

		$field_find = array();

		if($params["where_keyword_title"] == 1){
			array_push($field_find, "title");
		}

		if($params["where_keyword_tag"] == 1){
			array_push($field_find, "tag");
		}

		if($params["where_keyword_text"] == 1){
			array_push($field_find, "description");
			array_push($field_find, "raw_data");
		}

		$now = strtotime('now');
		$arrWord = $this->getSearchText($keyword);
		$strKeyword = "";
		$field_find_s = "";
		$strDefaultScore = "";
		for($i=0; $i < count($arrWord); $i++) {
			if($i != 0){
				$strKeyword .= ",";
				$strDefaultScore .= ",";
			}
			$strDefaultScore .= "0";
			$strKeyword .= '"' . str_replace(".","\.", addslashes($arrWord[$i])) . '"';
		}
		for($i=0; $i < count($field_find); $i++) {
			if($i != 0){
				$field_find_s .= ",";
			}
			$field_find_s .= '"' . str_replace(".","\.", addslashes($field_find[$i])) . '"';

		}

		$map = "
			function() {
				var score = [" . $strDefaultScore. "];
				var arrWord = [" .$strKeyword. "];
				var field_find = [".$field_find_s."];
				var field_weight = [40, 20, 10, 1];
				var field_data = [this.title, this.tag, this.description, this.raw_data];
				var zero = false;
				var sum = 0;

				var detail = '';

				for(var j = 0; j < ".count($field_find)."; j++){
					if(typeof(field_data[j]) == 'string' && field_data[j] != '') {
						var fi_data = field_data[j].toLowerCase();
						for(var i = 0;i < " .  count($arrWord) . "; i++){
							if(fi_data.indexOf(arrWord[i]) != -1){
								score[i] += field_weight[j];
							}
						}
					}
				}

				for(var i = 0;i < arrWord.length; i++){
					var str_search = arrWord[i].replace(' ', '\\s');

					for(var j = 0;j < field_find.length ; j++){
						var str = this[field_find[j]];
						var rex = new RegExp(str_search,'g');

						try{
						  sum += parseInt(str.match(rex).length)*parseInt(field_weight[j]);
						}catch(e){
						  sum += 0;
						}
					}
				}

				if(this.raw_data !=''){
					var start = this.raw_data.search(/".@$arrWord[0]."/i)-520;
					var end = start + 1040;
					detail = this.raw_data.substring(start, end);
				}else{
					detail = this.description;
				}

				if(!zero){

					emit(
						'".$hash."_' + this._id.toString() ,
						{
							search_hash : '" . $hash . "',
							ref_id: this.ref_id,
							title: this.title,
							image: this.image,
							module:this.module,
							lang:this.lang,
							obj_path_id:this.obj_path_id,
							acl_id:this.acl_id,
							tag:this.tag,
							detail:detail,
							link:this.link,
							slug_name:this.slug_name,
							datetime:".$now.",
							last_update:this.published_item_date,
							score: sum
						}
					);
				}
			}";

		$reduce = "
				function(key, values) {
					return values;
				}";

		$arrKeyword = $arrWord;

		$arrFindKeyword = array();
		$num_and = 1;
		foreach($field_find as $key => $item){
			foreach ($arrKeyword as $k => $v) {
				$str = preg_quote($v);
				foreach($field_find as $key1 => $item1){
					$regex = array();
					if($item1 == "tag"){
						$regex = array($item1 => new MongoRegex("/,{$str},/i"));
					}else{
						$regex = array($item1 => new MongoRegex("/.*{$str}.*/i"));
					}
					$arrFindKeyword[$k]['$or'][$key1] = $regex;
				}

			}
			$num_and++;
		}
		$arrFind['lang'] = strtoupper(LANG);
		$arrFind['$and'] = $arrFindKeyword;

		if($params["group"] == "eicanalysis"){
			$arrFind['obj_path_id'] = new MongoRegex("/".EIC_ANALYSIS_ID.".*/i");
		}

		if($during != "all"){
			$arrFind['datetime'] = array( '$gt' => $now-($during*2592000));
		}

		// echo "<pre>";
		// var_dump($arrFind);
		// echo "<pre>";
		// die;
		$out_table = array("merge" => "search_result");
		$MongoDB = OMMongo::collection();

		$result = $MongoDB->command(array(
			"mapreduce" => 'search_index',
			"map" => new MongoCode($map),
			"reduce" => new MongoCode($reduce),
			"query" => $arrFind,
			"out" => $out_table
		));

	}

	public function chkDeleteDataMongo(){
		$now = strtotime('now');
		$mongoTable = OMMongo::table("search_result");
		$rw = $mongoTable->remove();

		return $rw["n"];

	}

	public function getCookieCategoryToRex($category_id){

		$omcookie = new OMCookie();
		$category_id = explode(",", $category_id);
		$category_id_a = array();

		if($category_id){
			foreach ($category_id as $key => $value) {
				$str = str_replace("|", "\|", $value);
				$rex = array('$regex' => new MongoRegex("/.*{$str}.*/i"));
				array_push($category_id_a, array('value.obj_path_id'=>$rex));
			}
		}

		return $category_id_a;

	}



}
?>