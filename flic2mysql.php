<?php

ini_set('error_reporting', 0); // Show no errors
ini_set('display_errors', 0); // disable error display
ini_set('log_errors', 0); // disable error logging

date_default_timezone_set('UTC');
setlocale(LC_ALL, 'pt_PT.utf8');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

$servername = "database";
$username = "*******";
$password = "*****************";
$dbname = "Flickr";
$table = "BLPhotos20200330";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

#
# build the API URL to call
#

$params = array(
	'api_key'	=> '**************************',
	'method'	=> 'flickr.photos.search',
	'user_id'	=> '********************',
	'extras'	=> 'description, license, date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_q, url_m, url_n, url_z, url_c, url_l, url_o',
	'per_page'	=> '250',
	'format'	=> 'php_serial',
);

$startPage = 1; // change this page number in case for some reason the API returns a page of empty records, and you need to grab it again -- change the $endPage page too, if you have run it until the end.
$endPage = 4095; // total pages = 4095 (250 records each)

$numRecords = 250;

for($page=$startPage;$page<$endPage+1;$page++){
	
	echo "--------------- Page: ".$page."\n\n";

	$encoded_params = array();

	foreach ($params as $k => $v){

		$encoded_params[] = urlencode($k).'='.urlencode($v);
	}

	#
	# call the API and decode the response
	#

	$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params)."&page=".$page;

	$rsp = file_get_contents($url);

	// echo $url;

	$rsp_obj = unserialize($rsp);

	$arr_length = count($rsp_obj);
		
	for($i=0;$i<$numRecords;$i++)
		{

			$photoid = '';
			$secret = '';
			$server = '';
			$farm = '';
			$title = '';
			$ispublic = '';
			$license = '';
			$o_width = '';
			$o_height = '';
			$dateupload = '';
			$lastupdate = '';
			$datetaken = '';
			$datetakengranularity = '';
			$datetakenunknown = '';
			$ownername = '';
			$iconserver = '';
			$iconfarm = '';
			$views = '';
			$tags = '';
			$machine_tags = '';
			$originalsecret = '';
			$originalformat = '';
			$latitude = '';
			$longitude = '';
			$accuracy = '';
			$context = '';
			$media = '';
			$media_status = '';
			$url_sq = '';
			$height_sq = '';
			$width_sq = '';
			$url_t = '';
			$height_t = '';
			$width_t = '';
			$url_s = '';
			$height_s = '';
			$width_s = '';
			$url_q = '';
			$height_q = '';
			$width_q = '';
			$url_m = '';
			$height_m = '';
			$width_m = '';
			$url_n = '';
			$height_n = '';
			$width_n = '';
			$url_z = '';
			$height_z = '';
			$width_z = '';
			$url_c = '';
			$height_c = '';
			$width_c = '';
			$url_l = '';
			$height_l = '';
			$width_l = '';
			$url_o = '';
			$height_o = '';
			$width_o = '';
			$pathalias = '';
			$description = '';

			$photoid = $rsp_obj['photos']['photo'][$i]['id'];
			$secret = $rsp_obj['photos']['photo'][$i]['secret'];
			$server = $rsp_obj['photos']['photo'][$i]['server'];
			$farm = $rsp_obj['photos']['photo'][$i]['farm'];
			$title = $rsp_obj['photos']['photo'][$i]['title'];
			$ispublic = $rsp_obj['photos']['photo'][$i]['ispublic'];
			$license = $rsp_obj['photos']['photo'][$i]['license'];
			$o_width = $rsp_obj['photos']['photo'][$i]['o_width'];
			$o_height = $rsp_obj['photos']['photo'][$i]['o_height'];
			$dateupload = $rsp_obj['photos']['photo'][$i]['dateupload'];
			$lastupdate = $rsp_obj['photos']['photo'][$i]['lastupdate'];
			$datetaken = $rsp_obj['photos']['photo'][$i]['datetaken'];
			$datetakengranularity = $rsp_obj['photos']['photo'][$i]['datetakengranularity'];
			$datetakenunknown = $rsp_obj['photos']['photo'][$i]['datetakenunknown'];
			$ownername = $rsp_obj['photos']['photo'][$i]['ownername'];
			$iconserver = $rsp_obj['photos']['photo'][$i]['iconserver'];
			$iconfarm = $rsp_obj['photos']['photo'][$i]['iconfarm'];
			$views = $rsp_obj['photos']['photo'][$i]['views'];
			$tags = $rsp_obj['photos']['photo'][$i]['tags'];
			$machine_tags = $rsp_obj['photos']['photo'][$i]['machine_tags'];
			$originalsecret = $rsp_obj['photos']['photo'][$i]['originalsecret'];
			$originalformat = $rsp_obj['photos']['photo'][$i]['originalformat'];
			$latitude = $rsp_obj['photos']['photo'][$i]['latitude'];
			$longitude = $rsp_obj['photos']['photo'][$i]['longitude'];
			$accuracy = $rsp_obj['photos']['photo'][$i]['accuracy'];
			$context = $rsp_obj['photos']['photo'][$i]['context'];
			$media = $rsp_obj['photos']['photo'][$i]['media'];
			$media_status = $rsp_obj['photos']['photo'][$i]['media_status'];
			$url_sq = $rsp_obj['photos']['photo'][$i]['url_sq'];
			$height_sq = $rsp_obj['photos']['photo'][$i]['height_sq'];
			$width_sq = $rsp_obj['photos']['photo'][$i]['width_sq'];
			$url_t = $rsp_obj['photos']['photo'][$i]['url_t'];
			$height_t = $rsp_obj['photos']['photo'][$i]['height_t'];
			$width_t = $rsp_obj['photos']['photo'][$i]['width_t'];
			$url_s = $rsp_obj['photos']['photo'][$i]['url_s'];
			$height_s = $rsp_obj['photos']['photo'][$i]['height_s'];
			$width_s = $rsp_obj['photos']['photo'][$i]['width_s'];
			$url_q = $rsp_obj['photos']['photo'][$i]['url_q'];
			$height_q = $rsp_obj['photos']['photo'][$i]['height_q'];
			$width_q = $rsp_obj['photos']['photo'][$i]['width_q'];
			$url_m = $rsp_obj['photos']['photo'][$i]['url_m'];
			$height_m = $rsp_obj['photos']['photo'][$i]['height_m'];
			$width_m = $rsp_obj['photos']['photo'][$i]['width_m'];
			$url_n = $rsp_obj['photos']['photo'][$i]['url_n'];
			$height_n = $rsp_obj['photos']['photo'][$i]['height_n'];
			$width_n = $rsp_obj['photos']['photo'][$i]['width_n'];
			$url_z = $rsp_obj['photos']['photo'][$i]['url_z'];
			$height_z = $rsp_obj['photos']['photo'][$i]['height_z'];
			$width_z = $rsp_obj['photos']['photo'][$i]['width_z'];
			$url_c = $rsp_obj['photos']['photo'][$i]['url_c'];
			$height_c = $rsp_obj['photos']['photo'][$i]['height_c'];
			$width_c = $rsp_obj['photos']['photo'][$i]['width_c'];
			$url_l = $rsp_obj['photos']['photo'][$i]['url_l'];
			$height_l = $rsp_obj['photos']['photo'][$i]['height_l'];
			$width_l = $rsp_obj['photos']['photo'][$i]['width_l'];
			$url_o = $rsp_obj['photos']['photo'][$i]['url_o'];
			$height_o = $rsp_obj['photos']['photo'][$i]['height_o'];
			$width_o = $rsp_obj['photos']['photo'][$i]['width_o'];
			$pathalias = $rsp_obj['photos']['photo'][$i]['pathalias'];
			$description = $rsp_obj['photos']['photo'][$i]['description']['_content'];
					
			$for_insert=array(
				'photoid'=>$photoid,
				'secret'=>$secret,
				'server'=>$server,
				'farm'=>$farm,
				'title'=>$title,
				'ispublic'=>$ispublic,
				'license'=>$license,
				'o_width'=>$o_width,
				'o_height'=>$o_height,
				'dateupload'=>$dateupload,
				'lastupdate'=>$lastupdate,
				'datetaken'=>$datetaken,
				'datetakengranularity'=>$datetakengranularity,
				'datetakenunknown'=>$datetakenunknown,
				'ownername'=>$ownername,
				'iconserver'=>$iconserver,
				'iconfarm'=>$iconfarm,
				'views'=>$views,
				'tags'=>$tags,
				'machine_tags'=>$machine_tags,
				'originalsecret'=>$originalsecret,
				'originalformat'=>$originalformat,
				'latitude'=>$latitude,
				'longitude'=>$longitude,
				'accuracy'=>$accuracy,
				'context'=>$context,
				'media'=>$media,
				'media_status'=>$media_status,
				'url_sq'=>$url_sq,
				'height_sq'=>$height_sq,
				'width_sq'=>$width_sq,
				'url_t'=>$url_t,
				'height_t'=>$height_t,
				'width_t'=>$width_t,
				'url_s'=>$url_s,
				'height_s'=>$height_s,
				'width_s'=>$width_s,
				'url_q'=>$url_q,
				'height_q'=>$height_q,
				'width_q'=>$width_q,
				'url_m'=>$url_m,
				'height_m'=>$height_m,
				'width_m'=>$width_m,
				'url_n'=>$url_n,
				'height_n'=>$height_n,
				'width_n'=>$width_n,
				'url_z'=>$url_z,
				'height_z'=>$height_z,
				'width_z'=>$width_z,
				'url_c'=>$url_c,
				'height_c'=>$height_c,
				'width_c'=>$width_c,
				'url_l'=>$url_l,
				'height_l'=>$height_l,
				'width_l'=>$width_l,
				'url_o'=>$url_o,
				'height_o'=>$height_o,
				'width_o'=>$width_o,
				'pathalias'=>$pathalias,
				'description'=>$description
			);
			
			
			   $fields='';
			   $values='';
			   foreach($for_insert as $key => $value){
					if(!empty(trim($value))){
						$fields.=$key.", ";
						$values.="'".mysqli_real_escape_string($conn, $value)."', ";
					}          
			   }
			   $fields = substr($fields,0,strlen($fields)-2);
			   $values = substr($values,0,strlen($values)-2);
			   $encoding="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
			   $sql="INSERT INTO ".$table." (".$fields.") VALUES (".$values.")";
			   
			   if(mysqli_query($conn, $encoding) && mysqli_query($conn, $sql)){
				   // echo "Ok";
			   } else {
				   echo "Error inserting: ".$photoid."\n\n";
				   printf("Error message: %s\n", $conn->error);
				   print_r($for_insert);
			   }
			
		}; // for each record
		// usleep(2000000); // 2 seconds sleep
	};  // for each page	
?>
