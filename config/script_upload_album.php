<?php
set_time_limit (300);
include "admin_app_top.php";
include "checkUser.php"; 
include "defsPhoto.php"; 
$dst_width_max = 600;
$dst_height_max = 600;
$albumID = $_GET['dir'];
$images = scandir('photos/'.$_GET['dir']);


foreach ($images as $image) {
	
	
	if ($image != '.' and $image != '..' and $image != 'Thumbs.db') {
	
		$list_query=sprintf("SELECT * FROM `%s` WHERE `albumID` = '%s'",ITEM_TABLE,mysql_real_escape_string($albumID));
		$list_set = mysql_query($list_query) or die(mysql_error());
		$listOrder = mysql_num_rows($list_set) + 1;
	
		$query = sprintf("INSERT INTO %s (albumID,photoName,photoDescription,listOrder) VALUES('%s','%s','%s','%s')",ITEM_TABLE,mysql_real_escape_string($albumID),mysql_real_escape_string($image),mysql_real_escape_string($_POST['photoDescription']),$listOrder);	
		mysql_query($query);
		echo mysql_error();
		$myID = mysql_insert_id();
		
		$upload_file = 'photos/'.$_GET['dir'].'/'.$image;
		list($width, $height, $type, $attr) = getimagesize('photos/'.$_GET['dir'].'/'.$image);
		//resize image if needed
		if ($dst_width_max == 0) {
			if ($dst_height_max == 0) {
				$div_factor = 1;
			} else {
				$div_factor = $height/$dst_height_max;
			}
		} elseif ($dst_height_max == 0) {
			$div_factor = $width/$dst_width_max;
		} else {
			if (($width/$dst_width_max) > ($height/$dst_height_max)) {
				$div_factor = $width/$dst_width_max;
			} else {
				$div_factor = $height/$dst_height_max;		
			};
		}
		if ($width > $dst_width_max or $height > $dst_height_max) {
			$new_width = $width/$div_factor;
			$new_height = $height/$div_factor;
		} else {
			$new_width = $width;
			$new_height = $height;
		}
			
		// Detrmine File type
		if (strpos($image,".jpg") != FALSE or strpos($image,".jpeg") != FALSE
			or strpos($image,".JPG") != FALSE or strpos($image,".JPEG") != FALSE) {
			// Resample & resize
			if ($width > $dst_width_max or $height > $dst_height_max) {
				$image_p = imagecreatetruecolor($new_width, $new_height);
				$image = imagecreatefromjpeg($upload_file);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				imagejpeg($image_p, $upload_file, 100);
				
			}
		} else if (stripos($image,".gif") != FALSE or stripos($image,".GIF") != FALSE) {
			// Resample & resize
			if ($width > $dst_width_max or $height > $dst_height_max) {
				$image_p = imagecreatetruecolor($new_width, $new_height);
				$image = imagecreatefromgif($upload_file);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				imagegif($image_p, $upload_file, 100);
			}
		} else if (stripos($image,".png") != FALSE) {
			// Resample & resize
			if ($width > $dst_width_max or $height > $dst_height_max) {
				$image_p = imagecreatetruecolor($new_width, $new_height);
				$image = imagecreatefrompng($upload_file);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				imagepng($image_p, $upload_file);
			}
		}
	}
	
	echo 'image= '.$image.'width ='.$width.' new_width='.$new_width.' upload_file='.$upload_file.'<br />';
}
echo "done";
?>