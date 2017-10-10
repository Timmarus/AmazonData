<?php
		//echo "<pre>";
		include_once('./includes/functions.php');  
		$values = selectDBField("amazonKEWORDsearch");
		//print_r($values);
		function mssql_escape($data) {
        if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "''", $data );
        return $data;
    	}

		$server = '';
		$lnk = mssql_connect($server, '', '');
		mssql_select_db('AmazonCustomers', $lnk);
		foreach($values as $value) {
			 if($value["Placement For Keyword 1"]==0){
				 $value["Placement For Keyword 1"] =0;
			 }else{
				$value["Placement For Keyword 1"] = $value["Placement For Keyword 1"] + $value["totalItems"];
			 
			 }
			 if(empty($value["Placement For Keyword 1"])){
				 $value["Placement For Keyword 1"] =0;
			 }
			 mssql_query("UPDATE ScraperOutput SET isActive=0");
			 $query = "INSERT INTO ScraperOutput 
			 	(ASIN, Marketplace, Keyword1, Placement,
			 	ReviewRating, ReviewCount, Tag, page,
			 	Competitor1, brand1, salesrank1,
			 	Competitor2, brand2, salesrank2,
			 	Competitor3, brand3, salesrank3)
				VALUES(
				'".mssql_escape($value['asin'])."', 
				'".mssql_escape($value['Marketplace'])."', 
				'".mssql_escape($value['Keyword1'])."',
				'".mssql_escape($value['Placement For Keyword 1'])."',
				'".mssql_escape($value['Review Rating (Stars)'])."',
				'".mssql_escape($value['Review Count'])."',
				'".mssql_escape($value['Tag'])."',
				'".mssql_escape($value['page'])."',
				'".mssql_escape($value['Competitor1'])."',
				'".mssql_escape($value['brand1'])."',
				'".mssql_escape($value['salesrank1'])."',
				'".mssql_escape($value['Competitor2'])."',
				'".mssql_escape($value['brand2'])."',
				'".mssql_escape($value['salesrank2'])."',
				'".mssql_escape($value['Competitor3'])."',
				'".mssql_escape($value['brand3'])."',
				'".mssql_escape($value['salesrank3'])."')";
			mssql_query($query);
		}

		if($values){
			$path =  realpath(dirname(__FILE__)); 
			//print_r($path);
			$filefolder = "/".$_GET["folder"]."/";
			if (!file_exists($path.$filefolder)) { 
			 	 mkdir($path.$filefolder);
			 
			}
			$date =date('m-d-Y_hia').'.csv'; 
			$file = $path.$filefolder.$date;
			if($values){
				$fp = fopen($file,'w');
				$count= 0;
				foreach($values as $value){
					$newid =$value["id"];
					 unset($value["id"]); 
					 unset($value["status"]);
					 unset($value["status1"]);
					 unset($value["status2"]);
					 unset($value["status3"]);
					 unset($value["time"]);
					 if($value["Placement For Keyword 1"]==0){
						 $value["Placement For Keyword 1"] =0;
					 }else{
						$value["Placement For Keyword 1"] = $value["Placement For Keyword 1"] + $value["totalItems"];
					 
					 }
					 if(empty($value["Placement For Keyword 1"])){
						 $value["Placement For Keyword 1"] =0;
					 }
					unset($value["totalItems"]); 
					 //unset($no); 
					if($fp){
						if($count==0){ 
							$heading = array_keys($value);
							fputcsv($fp, $heading);
							fputcsv($fp, $value);
							$download["status"] = 2;
							//updateDBField("id",$newid,"testOrderAmazon",$download);
						}else{
							fputcsv($fp, $value);
							$download["status"] = 2;
							//updateDBField("id",$newid,"testOrderAmazon",$download);
					
						} 
					}
					unset($no); 
					unset($results); 
					unset($result); 
					$count++;
				}
				fclose($fp);
				mail("","Amazon Scraping Data File","".$date);
				mail("","Amazon Scraping Data File","".$date);
				downloadFile($file); 
				 
			}else{
				echo "process of downloading data to file is done";
				 
			} 
		}else{
			echo "No More Data please submit the csv Data file";
		}
		 
	?>