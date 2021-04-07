<?php

$row = 1;
$all_contry = array();
$arr_check = array("Asia");
if (($handle = fopen("country.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    	if($row == 1){
    		$row++;
    		continue;
    	}

        $country_key = "Inter";
        if(in_array($data[5],$arr_check)){
            $country_key = $data[5];
        }

        if($data[1] == "TH"){
            $country_key = "Thai";
        }

    	if(!isset($all_contry[$country_key])){
    		$all_contry[$country_key] = array();
    	}

        array_push($all_contry[$country_key], $data[1]);
    }
    fclose($handle);
}

file_put_contents("country_success.json", json_encode($all_contry,JSON_PRETTY_PRINT));
// json_encode($all_contry));
exit();


?>