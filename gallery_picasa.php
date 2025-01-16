<?php
include 'common.php';
$url = $_REQUEST['_url'];
$height =  $_REQUEST['_height'];
if ($height=="") {$height="230";}
$xml = simplexml_load_file($url);
?>
<script>
var img=new Array();
var img_title=new Array();
var img_url=new Array();
<?php

$i_tmp = -1;
foreach ($xml->channel->item as $item) {
  $i_tmp++;
  $url = $item->enclosure['url'];
  $img_title =  $item->title; 
  $img_url = $item->link; 
  $img_url = urldecode($img_url);  
  echo "img[".$i_tmp."]='".$url."';\r\n";
  echo "img_title[".$i_tmp."]='".$img_title."';\r\n";
  if (strpos($img_url,'googleusercontent.com'))
  {
  echo "img_url[".$i_tmp."]='".$url."';\r\n";  // xml from picasa	  
  }
  else
  {
  echo "img_url[".$i_tmp."]='".$img_url."';\r\n"; // xml generated from myquickappps album	  
  }


  
}
?>

function goto_url(i)
{
if (img_url[i]!='')
	{ window.location.href = img_url[i] ; }
}

function thumbnail_clic(i)
{
	// alert(img_url[i]);
	if (img_url[i]!="") 
	{ 
	// alert('open link');
	goto_url(i);
	// window.location.href = img_url[i] ;
	}
	else
	{
	// alert('enlarge image');
	enlarge_image(i);
	}
}

function enlarge_image(i)
{
	var url = img[i];
	var title = img_title[i];
	var i_next = i + 1;
	var i_previous = i - 1;
	if (i_previous == 0 ) { i_previous = img.length - 1 ;}
	if ( i_next == img.length ) { i_next = 0 ; }
	// var next = img[i_next];
	// document.getElementById("large_image").innerHTML = "<p align='center'><a href='"+url+"'><img width='250' src='"+url+"' /></a></p>";
	var htmlbloc = "";
	htmlbloc =  "<p align='right'><a href='javascript:close()'><img height='20' width='45' src='/images/window-close.png' /></a></p>";
	htmlbloc += "<p align='center'><table style='text-align:center;width:100%;' ><tr>";
	htmlbloc += "<td><a href='javascript:enlarge_image("+i_previous+")'>";
	htmlbloc += "<img height='70%' width='30' src='/images/image-previous2.png' /></a></td>";
	htmlbloc += "<td style='border: 4px ridge grey;background-color: white;'>";
	htmlbloc += "<a href='javascript:goto_url("+i+")'>";
	htmlbloc += "<img style='margin:auto;width:100%;max-height:300' src='"+url+"' />";
	htmlbloc += "</a>";
	htmlbloc += "</td>";
	htmlbloc += "<td><a href='javascript:enlarge_image("+i_next+")'><img height='70%'  width='30'  src='/images/image-next2.png' /></a></td>";
	htmlbloc += "</tr>";
	htmlbloc += "<tr><td></td><td style='background-color: white;'>"+title+"</td><td></td></tr>";
	htmlbloc += "</table></p>";

	document.getElementById("large_image").innerHTML = htmlbloc ;
	document.getElementById("thumbnails").style.display="none";
}

// ======================================================
//
// ======================================================
function close()
{
document.getElementById("large_image").innerHTML = "";
document.getElementById("thumbnails").style.display="";
}

</script>
<?php
echo "<div style='margin:auto;width:100%;' id='large_image'>";
$url = $xml->channel->item->enclosure['url'];
//echo "<p align='right'><a href='javascript:close()'><img height='20' width='45' src='/images/window-close.png' /></a></p>";
//echo "<p align='center'><a href='javascript:enlarge_image(1)'>";
//echo "<img height='250' src='".$url."' />";
//echo "<img style='margin:auto;width:100%' src='".$url."' />";
//echo "</a> </p>";
echo "</div>\r\n";

echo "<h2 style='text-align:center'>".$xml->channel->title . "</h2>\r\n";

$i_tmp = -1;


echo "<p id='thumbnails' align='center'>";
foreach ($xml->channel->item as $item) {
  $i_tmp++;
//  print_r($item);
  $prev_tmp = $xml->channel->item[$i_tmp - 1];
  $previous = $prev_tmp->enclosure['url'];
  $next_tmp = $xml->channel->item[$i_tmp + 1];
  $next = $next_tmp->enclosure['url'];  
  if ( $mobiledetect->isMobile() ) 
  {
    $url = $item->enclosure['url'];
    $link = $item->link;
//    $link_array = explode("/", $link);
    $link_array = explode("#", $link);
//    $photo_id_array = $link_array[4];
//    $photo_id = $photo_id_array[1];
//    $slideshow_link = "https://picasaweb.google.com/m/viewer#photo/".$link_array[3]."/".$photo_id;
//    $slideshow_link = $link_array[0]."#slideshow/".$link_array[1];
    $slideshow_link = $link_array[0]."#slideshow/".$link_array[1];

    echo "<a href='$url'>";
//    echo "<a href='$slideshow_link'>";
    echo "<span style='margin:10px'>";
    echo "<img style='border-color:white;' border='3' height='$height' ";
//    echo " width='$height' src='http://myquickapps.com/apps/image_square.php?img=$url&size=230' />";
    echo " width='$height' src='https://appli.pro/image_square.php?img=$url&size=230' />";
    echo " </span></a>\r\n";
    
  }
  else
  { // display image 
    $url = $item->enclosure['url'];
    $link = $item->link;
    $link_array = explode("#", $link);
    $slideshow_link = $link_array[0]."#slideshow/".$link_array[1];
//    echo "<a href='javascript:enlarge_image($i_tmp)'>";
    echo "<a href='javascript:thumbnail_clic($i_tmp)'>";
    // echo "<a href='$slideshow_link'>";
    echo "<span style='margin:10px'>";
    echo "<img style='border-color:white;' border='3' height='$height' ";
//    echo " width='$height' src='http://myquickapps.com/apps/image_square.php?img=$url&size=100'  />";
   echo " width='$height' src='https://appli.pro/image_square.php?img=$url&size=100'  />";
    echo "</span></a>\r\n";    
  }

//  $mediagroup = $item->media:group;
//  print_r($mediagroup);  
//  $thumbnail = $item->media:group->media:thumbnail['url'];
//  $tmp = image_square_crop($url, $url, $height);

//  echo "<a href='$url'><span style='margin:10px'><img style='border-color:white;' border='3' height='$height' src='$url' /></span></a>";
}
echo "</p>";
echo "<hr />";
?>
