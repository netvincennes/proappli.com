<?php
// session_start();
include 'common.php';
//include 'session_start.php';
$application = $_SESSION['application'];
// $dossier = "multimedia/icons/";
$dossier = "_resources/fonts_google/";
$files = scandir($dossier);
$field = $_REQUEST['field'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 

<!--
    <link rel="stylesheet" type="text/css" href="common.css.php?_application=$application" />
-->

<title>Google Fonts</title>
<style type="text/css">
div.img
{
margin:0px;
border:1px solid #0000ff;
height:auto;
width:auto;
float:left;
text-align:center;
}
div.img img
{
display:inline;
margin:1px;
border:1px solid #ffffff;
}
div.img a:hover img
{
border:1px solid #0000ff;
}
div.desc
{
text-align:center;
font-weight:normal;
width:120px;
margin:2px;
}
</style>
<script language="javascript" src="common.js"></script> 
<script type="text/javascript" >
function select(url)
{
//alert(url);
// window.opener.galleryreturn('<?PHP echo $field;?>',url);
// window.opener.copy_value_to_elementid(url,'<?PHP echo $field;?>');
//var win = window ;
// win.parent.font_return(url,"<?php echo $field;?>"); 
window.opener.font_return(url,"<?php echo $field;?>");
//window.opener.test();
open(location, '_self').close(); 
}
</script>
</head>
<body>

<?php



// uniquement si 
?>

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="fileForm" enctype="multipart/form-data">


<?php    
//    print_r($files);

    foreach ($files as $file)
    {
      if (($file !='.') and ($file!='..')) {
      $dir_and_file = $dossier.$file ;
//      $imagesize = getimagesize($file);
      $height = '21' ;
      $width = '180' ;
//      $height = $imagesize[1] * 100 / $imagesize[0] ;
      //$len = strlen($file);
      $alt = substr($file,0,- 4);
      echo "<div class='img'>";
//      echo "<a href='javascript:window.opener.galleryreturn(\"".$file."\")'>";
      echo "<a href='javascript:select(\"".$dir_and_file."\")'>";

//      echo "<img src='$dir_and_file' width='$width' height='$height' /></a>";
      echo "<img title='$alt' alt='$alt' src='$dir_and_file' height='$height' width='$width' /></a>";

      echo "</div>";
      }
    }


?>

      </form>
</body>  
</html>
