<?php 
function wordLimit($inputText,$maxWords)
{
	$words = explode(" ",$inputText);
	if (count($words) > $maxWords) {
		$outputText = implode(" ",array_slice($words,0,$maxWords))."...";
	} else {
		$outputText = $inputText;
	}
	return $outputText;
}

function catInfo($catID,$catTable) {
	
$cat_name_query=sprintf("SELECT * FROM %s  WHERE `catID` = '%s'",$catTable, $catID);
//echo $cat_name_query;
$cat_name_set = mysql_query($cat_name_query) or die(mysql_error());
$row_cat_name_set = mysql_fetch_assoc($cat_name_set);

if ($row_cat_name_set['parentID'] != 0) {
	$parent_name_query=sprintf("SELECT * FROM %s  WHERE `catID` = '%s'",$catTable,$row_cat_name_set['parentID']);
	//echo $parent_name_query;
	$parent_name_set = mysql_query($parent_name_query) or die(mysql_error());
	$row_parent_name_set = mysql_fetch_assoc($parent_name_set);	
	
	$ret_array = array($row_cat_name_set['catName'],$row_parent_name_set['catName'],$row_cat_name_set['catID'],$row_parent_name_set['catID']);
} else {
	$ret_array = array('',$row_cat_name_set['catName'],'',$row_cat_name_set['catID']);
}

return $ret_array;
}

function parse_citystatezip($address){
    /***
    2008-05-08 used first for quickbooks import => database table
    
    johnson city, tex|tx|texas  78691[-1234]
    vancouver, bc A0A 0A0
    charlotte, n.c. 02899

    ***/
    $address=trim($address);
    $zip='([0-9]{5,5})';
	if(preg_match("/$zip/i",$address,$a)){
		
		$zipcode = $a[1];
		
		$zip_query=sprintf("SELECT * FROM zip_code  WHERE `zip_code` = '%s'",$zipcode);
		$zip_set = mysql_query($zip_query) or die(mysql_error());
		$row_zip_set = mysql_fetch_assoc($zip_set);
		if (mysql_num_rows($zip_set) > 0) {
			return $zipcode;
		} else {
			return "error";
		}
		
	} else {
		
		$addressPieces = explode(',',$address);
		if (count($addressPieces) == 2) {
			//print_r($addressPieces);
			$city = trim($addressPieces[0]);
			$state = trim($addressPieces[1]);
			$zip_query=sprintf("SELECT * FROM zip_code  WHERE CONVERT(`city` USING latin1) LIKE '%s' AND (CONVERT(`state_name` USING latin1) LIKE '%s' OR CONVERT(`state_prefix` USING latin1) LIKE '%s') LIMIT 1",'%'.$city.'%','%'.$state.'%','%'.$state.'%');
			//echo $zip_query;
			$zip_set = mysql_query($zip_query) or die(mysql_error());
			$row_zip_set = mysql_fetch_assoc($zip_set);
			if (mysql_num_rows($zip_set) > 0) {
				return  $row_zip_set['zip_code'];
			} else {
				return "error";
			}
		} else {
			$addressPieces = explode(' ',$address);
			if (count($addressPieces) > 1) {		
				$state = trim($addressPieces[count($addressPieces) - 1]);
				array_pop($addressPieces);
				$city = trim(implode(' ',$addressPieces));
				$zip_query=sprintf("SELECT * FROM zip_code  WHERE CONVERT(`city` USING latin1) LIKE '%s' AND (CONVERT(`state_name` USING latin1) LIKE '%s' OR CONVERT(`state_prefix` USING latin1) LIKE '%s') LIMIT 1",'%'.$city.'%','%'.$state.'%','%'.$state.'%');
				//echo $zip_query;
				$zip_set = mysql_query($zip_query) or die(mysql_error());
				$row_zip_set = mysql_fetch_assoc($zip_set);
				if (mysql_num_rows($zip_set) > 0) {
					return  $row_zip_set['zip_code'];
				} else {
					return "error";
				}
			} else {
				return "error";
			}
		}
	}
	
}
//echo '<pre>';
//print_r(parse_citystatezip($addr)); 
?>