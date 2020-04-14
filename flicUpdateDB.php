<?php

#
# Open and convert to a multi-dimensional array the mappings of MS Book (PDF) id and the $ARK for BL's Universal Viewer
#

// $filename = 'MSBooks_SysNumberPhy_SysNumberDigi_ARK_mapping.csv';
$filename = 'Microsoft-Books-with-mapping_tab_flickrUpdate.txt';

// The nested array to hold all the arrays
$booksMetadata = []; 

// Open the file for reading
if (($h = fopen("{$filename}", "r")) !== FALSE) 
{
  // Each line in the file is converted into an individual array that we call $data
  // The items of the array are comma separated -- 1000 = max longest line -- if bigger it will will be split into a new field
  // while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
  while (($data = fgetcsv($h, 1000, "\t")) !== FALSE) 
  {
    // Each individual array is being pushed into the nested array
    $booksMetadata[] = $data;		
  }

  // Close the file
  fclose($h);
}

date_default_timezone_set('UTC');
setlocale(LC_ALL, 'pt_PT.utf8');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

$servername = "database";
$username = "*******";
$password = "************";
$dbname = "Flickr";
$tableRead = "BLPhotos20200330";
$tableWrite = "BLPhotosNewData";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$iniRec = 132421;
$numRec = 100;

$encoding="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$sql="SELECT * FROM ".$tableRead." LIMIT ".$iniRec.",".$numRec.";";
// $sql="SELECT * FROM ".$tableRead." WHERE photoid = '11114116566';"; // test record with georeferencer
// $sql="SELECT * FROM ".$tableRead." WHERE photoid = '11015984325';"; // test record of Russian lang

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        		
		$desc = "";
		$newdesc = "";
		$photo_id = $row["photoid"];
		$title = $row["title"];
		
		echo "\n\nPhoto ID: " . $photo_id;
			
		echo "\n\nTitle: " . $title;
	
		
		// update 2020/04/06
		$NewTitle = "British Library digitised ".str_replace('taken from page','from scanned page',$title);
		
		echo "\n\nNew Title: " . $NewTitle."'
		
	    ";
				
		$newdesc = $row["description"];	
		
		$pubYear = '0000';
	
	//
	// find the page number
	//	
		
		$pageNumber = '';
		$pageNumberViewer = '0';
		
		$matches=[];
		
		$re = '/\<strong\>Page\<\/strong\>: (.*.)\n/m';
		
		preg_match($re, $newdesc, $matches, PREG_OFFSET_CAPTURE, 0);
	
		if(isset($matches[1][0])){
			
			$pageNumber = $matches[1][0];
			$pageNumberViewer -= 1;	
		};	

	//
	// Check if this description has a link to the georeferencer page
	//	
		$geoRefId = '';
		
		$matches=[];
		
		$re = '/.georeferencer.com\/id\/(.*.)\"/m';
				
		preg_match($re, $newdesc, $matches, PREG_OFFSET_CAPTURE, 0);
	
		if(isset($matches[1][0])){
			
			$geoRefId = $matches[1][0];	
		};		

	
		
	//
	// replace ALEPH's Sys Number from Physical to the Online record
	//
	
		$re = '/amp;doc=BLL01(.*.)&amp;dscnt=1/m';
		
		preg_match($re, $newdesc, $matches, PREG_OFFSET_CAPTURE, 0);

		$sysnumber = "";
		
		if(isset($matches[1][0])){
			
			$sysnumber = $matches[1][0];
		
			$key = array_search($sysnumber, array_column($booksMetadata,0));
			
			$NewSysnumber = $booksMetadata[$key][1];
			
		};		
		
		 		 
	//
	// Generate Universal Viewer URL
	//
		$newUVurl = 'http://access.bl.uk/item/viewer/'.$booksMetadata[$key][2].'#?cv='.$pageNumberViewer;		
		
		$newdesc = 'Image taken from:
		
<strong>Title</strong>: &quot;'.$booksMetadata[$key][6].'&quot;
<strong>Author(s)</strong>: '.$booksMetadata[$key][5].'
<strong>British Library shelfmark</strong>: &quot;'.$booksMetadata[$key][13].'&quot;
<strong>Page</strong>: '.$pageNumber.' (scanned page number - not necessarily the actual page number in the publication)
<strong>Place of Publication</strong>: '.$booksMetadata[$key][8];
		if($booksMetadata[$key][7] !==""){
			$newdesc .= ' ('.$booksMetadata[$key][7].')';
		};
		$pubYear = $booksMetadata[$key][10];
		$newdesc .= '
<strong>Date of Publication</strong>: '.$pubYear;
		if($booksMetadata[$key][9] !==""){
			$newdesc .= '
<strong>Publisher</strong>: '.$booksMetadata[$key][9];
		};
		if($booksMetadata[$key][11] !==""){
			$newdesc .= '
<strong>Edition</strong>: '.$booksMetadata[$key][11];
		};
	$newdesc .= '
<strong>Type of resource</strong>: '.$booksMetadata[$key][3].'
<strong>Language(s)</strong>: '.$booksMetadata[$key][17];
		if($booksMetadata[$key][12] !==""){
			$newdesc .= '
<strong>Physical description</strong>: '.$booksMetadata[$key][12];
		};
		if($booksMetadata[$key][15] !==""){
			$newdesc .= '
<strong>Genre</strong>: '.$booksMetadata[$key][15];
		};
		if($booksMetadata[$key][16] !==""){
			$newdesc .= '
<strong>Literary form</strong>: '.$booksMetadata[$key][16];
		};
		if($booksMetadata[$key][14] !==""){
			$newdesc .= '
<strong>Topics</strong>: '.$booksMetadata[$key][14];
		};		
		$newdesc .= '		
		
<strong>Explore this item</strong> in the British Library’s catalogue (numbers are British Library identifiers):  
<a href="http://explore.bl.uk/primo_library/libweb/action/search.do?cs=frb&amp;doc=BLL01'.$sysnumber.'&amp;dscnt=1&amp;scp.scps=scope:(BLCONTENT)&amp;frbg=&amp;tab=local_tab&amp;srt=rank&amp;ct=search&amp;mode=Basic&amp;dum=true&amp;tb=t&amp;indx=1&amp;vl(freeText0)='.$sysnumber.'&amp;fn=search&amp;vid=BLVU1" rel="nofollow">'.$sysnumber.'</a> (physical copy) and <a href="http://explore.bl.uk/primo_library/libweb/action/search.do?cs=frb&amp;doc=BLL01'.$NewSysnumber.'&amp;dscnt=1&amp;scp.scps=scope:(BLCONTENT)&amp;frbg=&amp;tab=local_tab&amp;srt=rank&amp;ct=search&amp;mode=Basic&amp;dum=true&amp;tb=t&amp;indx=1&amp;vl(freeText0)='.$NewSysnumber.'&amp;fn=search&amp;vid=BLVU1" rel="nofollow">'.$NewSysnumber.'</a> (digitised copy)

<strong>Other links to explore related to this image:</strong>
- <strong>View</strong> this image as a scanned publication on the <a href="'.$newUVurl.'" alt="Open the book scan page in the British Library’s Universal Viewer">British Library’s online viewer</a> (you can download the image, whole book or selected pages)
- <strong>Download</strong> the Optical Character Recognised (OCR) <a href="https://data.bl.uk/19cbooks/json/'.substr($sysnumber,0,4).'/'.$sysnumber.'_01_text.json">derived text</a> for this publication as JavaScript Object Notation (JSON)';
		if($geoRefId !==""){
			$newdesc .= '
- <strong>View</strong> the <a href="http://britishlibrary.georeferencer.com/id/'.$geoRefId.'" rel="noreferrer nofollow">digitised map overlaid on a modern map</a> on the <strong>British Library’s Georeferencer service</strong>';
		};		
		$newdesc .= '
- <strong>View</strong> all the <a href="http://www.flickr.com/photos/britishlibrary/tags/sysnum'.$sysnumber.'">illustrations found in this publication</a>
- <strong>Order</strong> a <a href="http://bit.ly/1b3VS7i" alt="The British Library: Digitisation Services">higher quality scanned version of this image</a> from the British Library
- <strong>View</strong> all other <a href="http://www.flickr.com/photos/britishlibrary/tags/date'.$pubYear.'">illustrations in publications from the same year</a> ('.$pubYear.')
- <strong>Explore</strong> and experiment</strong> with the British Library’s other <a href="https://data.bl.uk/" alt="British Library’s digital collections">digital collections</a>
- <strong>Learn more</strong> about the <a href="https://www.bl.uk" alt="The British Library">British Library</a>
- <a href="https://support.bl.uk/Donate" alt="Donate to The British Library"><strong>Donate</strong> to the British Library</a>';
		
		
		echo $newdesc;
		echo "
		------------------------------------------------
		------------------------------------------------
		";
		unset($output);
	
    }
} else {
    echo "0 results";
}	
?>