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
$password = "********";
$dbname = "Flickr";
$tableRead = "BLPhotos20200330";
$tableWrite = "BLPhotosNewData";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$iniRec = 1;
$numRec = 5;

$encoding="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$sql="SELECT * FROM ".$tableRead." LIMIT ".$iniRec.",".$numRec.";";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        		
		$desc = "";
		$newdesc = "";
		$photo_id = $row["photoid"];
		$title = $row["title"];
		
		echo "\n\nPhoto ID: " . $photo_id;
		echo "\n\n";
		
		echo "\n\nTitle: " . $title;
		echo "\n\n";
		
		// update 2020/04/06
		$NewTitle = "British Library digitised ".str_replace('taken from page','from scanned page',$title);
		
		echo "\n\nNew Title: " . $NewTitle;
		echo "\n\n";
				
		$desc = $row["description"];
		
		echo "\nCURRENT Desc:\n";
		
		echo $desc;
				
		$desc = str_replace("Download the OCR-derived text for this volume","\nDownload the OCR-derived text for this volume",$desc);
		$desc = str_replace("\n\nDownload the OCR-derived text for this volume","\nDownload the OCR-derived text for this volume",$desc);
		
		echo "\nNEW Desc:\n";

		
		
		$pubYear = '0000';
		
		//
		// delete the 404 links
		//
		$array = explode("\n",$desc);
		foreach($array as $arr) {
			if(!(preg_match('/^Download the OCR-derived text for this volume/',$arr))) {
				$output[] = $arr;
			}
		}

		$newdesc = implode("\n",$output);
		
	//
	// find the page number
	//	
		
		$pageNumber = '0';
		
		$matches=[];
		
		$re = '/\<strong\>Page\<\/strong\>: (.*.)\n/m';
		
		preg_match($re, $newdesc, $matches, PREG_OFFSET_CAPTURE, 0);
	
		if(isset($matches[1][0])){
			
			$pageNumber = $matches[1][0] - 1;	
		};	
		
	//
	// 20200406: add "Title: <<British Library digitised image from page>> 1032 << of >> 
	//
		$newdesc = str_replace("<strong>Title</strong>: ","<strong>Title</strong>: British Library digitised image from page ".$pageNumber." of ",$newdesc);
		
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
									
			$newdesc = str_replace($sysnumber,$NewSysnumber,$newdesc);
			$newLinks = '<a href="http://explore.bl.uk/primo_library/libweb/action/search.do?cs=frb&amp;doc=BLL01'.$sysnumber.'&amp;dscnt=1&amp;scp.scps=scope:(BLCONTENT)&amp;frbg=&amp;tab=local_tab&amp;srt=rank&amp;ct=search&amp;mode=Basic&amp;dum=true&amp;tb=t&amp;indx=1&amp;vl(freeText0)=014872662&amp;fn=search&amp;vid=BLVU1" rel="nofollow" target="_blank">'.$sysnumber.'</a> (physical book) and <a href="http://explore.bl.uk/primo_library/libweb/action/search.do?cs=frb&amp;doc=BLL01'.$NewSysnumber.'&amp;dscnt=1&amp;scp.scps=scope:(BLCONTENT)&amp;frbg=&amp;tab=local_tab&amp;srt=rank&amp;ct=search&amp;mode=Basic&amp;dum=true&amp;tb=t&amp;indx=1&amp;vl(freeText0)=014872662&amp;fn=search&amp;vid=BLVU1" rel="nofollow" target="_blank">'.$NewSysnumber.'</a> (digitised book) [links go to British Library record in ‘Explore’]';
			
			$newdesc = str_replace("sysnum".$NewSysnumber,"sysnum".$sysnumber,$newdesc);
			$newdesc = str_replace("</strong>: ".$NewSysnumber,"s</strong>: ".$newLinks,$newdesc);	
			
		};		
		
		 		 
	//
	// Generate Universal Viewer URL
	//
		$newUVurl = 'http://access.bl.uk/item/viewer/'.$booksMetadata[$key][2].'#?cv='.$pageNumber;	
		
	//
	// Remove old description after Explore (inc)
	// 
		$re = '/(.*)<strong>Explore:<\/strong>(.*)(Click <strong>.*)/s';
		preg_match_all($re,$newdesc, $matches, PREG_SET_ORDER, 0);
				
		$newdesc = str_replace('<strong>Page</strong>','<strong>Book scan page</strong> (may not match the book page number)',$matches[0][1]);
		
		$newdesc .= 'Open the book scan page in the British Library’s <a href="'.$newUVurl.'" alt="Open the book scan page in the British Library’s Universal Viewer">Universal Viewer</a> (to download further pages from the book, please follow instructions bellow*).
';
		$newdesc .= 'Download the OCR-derived text for this volume: <a href="https://data.bl.uk/19cbooks/json/'.substr($sysnumber,0,4).'/'.$sysnumber.'_01_text.json" target="_blank">json file</a>.
';
		
		$newdesc .= str_replace('quality version','quality version of the image',$matches[0][3]);
		
		$newdesc .= 'Explore and experiment with the British Library’s <a href="https://data.bl.uk/" alt="British Library’s digital collections" target="_blank">digital collections</a>.';
		
		// $sql="INSERT INTO ".$tableWrite." (".$fields.") VALUES (".$values.")";
		// mysqli_query($conn, $sql);
		
		
		
		$newdesc .= 'Image taken from:
		
<strong>Title</strong>: British Library digitised image from page '.$pageNumber.' of &quot;'.$booksMetadata[$key][6].'&quot;
<strong>Author(s)</strong>: '.$booksMetadata[$key][5].'
<strong>British Library shelfmark</strong>: &quot;'.$booksMetadata[$key][13].'&quot;
<strong>Page</strong>: '.$pageNumber.' (scanned page number - not necessarily the actual page number in the publication)
<strong>Place of Publishing</strong>: '.$booksMetadata[$key][8];
		if($booksMetadata[$key][7] !==""){
			$newdesc .= ' ('.$booksMetadata[$key][7].')';
		};
		$newdesc .= '
<strong>Date of Publishing</strong>: '.$booksMetadata[$key][10];
		if($booksMetadata[$key][9] !==""){
			$newdesc .= '
<strong>Publisher</strong>: '.$booksMetadata[$key][9];
		};
		if($booksMetadata[$key][11] !==""){
			$newdesc .= '
<strong>Edition</strong>: '.$booksMetadata[$key][11];
		};
	$newdesc .= '
<strong>Type of resource</strong>: '.$booksMetadata[$key][3];
		
		$newdesc .= '		
		';
		
		$newdesc .= '
<strong>Explore this item</strong> in British Library’s catalogue (numbers are British Library identifiers):  
<a href="http://explore.bl.uk/primo_library/libweb/action/search.do?cs=frb&amp;doc=BLL01'.$sysnumber.'&amp;dscnt=1&amp;scp.scps=scope:(BLCONTENT)&amp;frbg=&amp;tab=local_tab&amp;srt=rank&amp;ct=search&amp;mode=Basic&amp;dum=true&amp;tb=t&amp;indx=1&amp;vl(freeText0)='.$sysnumber.'&amp;fn=search&amp;vid=BLVU1" rel="nofollow">'.$sysnumber.'</a> (physical copy) and <a href="http://explore.bl.uk/primo_library/libweb/action/search.do?cs=frb&amp;doc=BLL01'.$NewSysnumber.'&amp;dscnt=1&amp;scp.scps=scope:(BLCONTENT)&amp;frbg=&amp;tab=local_tab&amp;srt=rank&amp;ct=search&amp;mode=Basic&amp;dum=true&amp;tb=t&amp;indx=1&amp;vl(freeText0)='.$NewSysnumber.'&amp;fn=search&amp;vid=BLVU1" rel="nofollow">'.$NewSysnumber.'</a> (digitised copy)

<strong>Other links to explore related to this image:</strong>
- <strong><a href="'.$newUVurl.'" alt="Open the book scan page in the British Library’s Universal Viewer">View this image</a></strong> as a scanned publication on the <a href="'.$newUVurl.'" alt="Open the book scan page in the British Library’s Universal Viewer">British Library’s online viewer</a>. You can download the image, whole book or selected pages from the book in this online viewer.
- <strong>Download</strong> the Optical Character Recognised (OCR) <a href="https://data.bl.uk/19cbooks/json/'.substr($sysnumber,0,4).'/'.$sysnumber.'_01_text.json">derived text</a> for this publication as JavaScript Object Notation (JSON)
- <strong>View</strong> the digitised map overlaid on a modern map on the <strong>British Library’s Georeferencer service</strong>
- <strong>View</strong> all the <a href="http://www.flickr.com/photos/britishlibrary/tags/sysnum'.$sysnumber.'">illustrations found in this publication</a>
- <strong>Order</strong> a <a href="http://bit.ly/1b3VS7i" alt="The British Library: Digitisation Services">higher quality scanned version of this image</a> from the British Library
- <strong>View</strong> all other <a href="http://www.flickr.com/photos/britishlibrary/tags/date'.$pubYear.'">illustrations in publications from the same year</a> ('.$pubYear.')
- <strong>Explore</strong> and experiment</strong> with the British Library’s other <a href="https://data.bl.uk/" alt="British Library’s digital collections">digital collections</a>
- <strong>Learn more</strong> about the <a href="https://www.bl.uk" alt="The British Library">British Library</a>
- <a href="https://support.bl.uk/Donate" alt="Donate to The British Library"><strong>Donate</strong> to the British Library</a>';
		
		
		echo $newdesc;
		echo "------------------------------------------------\n\n------------------------------------------------\n\n";
		unset($output);
	
    }
} else {
    echo "0 results";
}	
?>