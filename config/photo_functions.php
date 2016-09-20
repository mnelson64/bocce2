<?php 
///////////////////////////////////////////////////////////////////
// Upload Image
///////////////////////////////////////////////////////////////////

function upload_image($dst_height_max,$dst_width_max,$filename,$id,$checkSize,$top)
{
if ($top) $path = 'west_config/';
// image upload handling
if ($filename == 'staffPhoto') {
	$upload_dir_path = $path."staff_photos/".$id."/";
} else if ($filename == 'cncsstaffPhoto') {
	$upload_dir_path = $path."cncs_staff_photos/".$id."/";
} else if ($filename == 'photoName') {
	$upload_dir_path = $path."photos/".$id."/";
} else if ($filename == 'progImage') {
	$upload_dir_path = $path."prog_photos/".$id."/";
} else if ($filename == 'bannerName') {
	$upload_dir_path = $path."banners/".$id."/";
}
if (!is_dir($upload_dir_path)) {
	mkdir ($upload_dir_path);
}
$image_name = $_FILES[$filename]['name'];
$upload_file = $upload_dir_path.$image_name;
$file_uploaded_ok =(move_uploaded_file($_FILES[$filename]['tmp_name'], $upload_file));

// determine if image size meets specs, set warning flag if needed
$imageSizeWarning = false;
list($width, $height, $type, $attr) = getimagesize($upload_file);
if ($checkSize) {
	$imageSizeWarning = ($dst_height_max != $height or $dst_width_max != $width) ? true : false; 
}

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
if (strpos($image_name,".jpg") != FALSE or strpos($image_name,".jpeg") != FALSE
	or strpos($image_name,".JPG") != FALSE or strpos($image_name,".JPEG") != FALSE) {
	// Resample & resize
	if ($width > $dst_width_max or $height > $dst_height_max) {
		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromjpeg($upload_file);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($image_p, $upload_file, 100);
	}
} else if (stripos($image_name,".gif") != FALSE or stripos($image_name,".GIF") != FALSE) {
	// Resample & resize
	if ($width > $dst_width_max or $height > $dst_height_max) {
		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromgif($upload_file);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagegif($image_p, $upload_file, 100);
	}
} else if (stripos($image_name,".png") != FALSE) {
	// Resample & resize
	if ($width > $dst_width_max or $height > $dst_height_max) {
		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefrompng($upload_file);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagepng($image_p, $upload_file);
	}
}

$ret_array = array($image_name,$imageSizeWarning);

return $ret_array;

} //function


?>