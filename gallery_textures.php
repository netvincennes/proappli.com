<?php
session_start();
if (isset($_REQUEST['field'])) {$field = $_REQUEST['field'];} else {$field = "";}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/> 

    <link rel="stylesheet" type="text/css" href="common.css" />
<title>Select background</title>
<style type="text/css">
div.img
{
margin:2px;
border:1px solid #0000ff;
height:auto;
width:auto;
float:left;
text-align:center;
}
div.img img
{
display:inline;
margin:3px;
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
// alert(url);
// window.opener.galleryreturn('<?PHP echo $field;?>',url);
// window.opener.copy_value_to_elementid(url,'<?PHP echo $field;?>');
//window.parent.backgroundselect(url);
//window.opener.setbackground(url);
window.opener.set_body_bg('<?=$field?>',url);
self.close(); 
}

</script>
</head>
<body>
<center><h1>Select texture</h1></center>
<!--
<p><a href='javascript:show_textures()'>Textures</a> | <a href='javascript:show_images()'>Images</a></p>
-->
<!--
        <div id='menu'>
        <ul style="border:1px solid #ccc !important; list-style-type:none;overflow:hidden;padding:0;margin:0;background-color:#FFFFFF !important">
			<li id='textures_button' style="border-top-left-radius: 6px; border-top-right-radius: 6px; background: rgb(204, 204, 204) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204) ! important; float: left; padding: 3px; margin: 2px;"><a href='javascript:show_textures()'>Textures</a></li>  
			<li id='images_button' style="border-top-left-radius: 6px; border-top-right-radius: 6px; background: rgb(204, 204, 204) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204) ! important; float: left; padding: 3px; margin: 2px;"><a href='javascript:show_images()'>Images</a></li>   
			<li id='gradients_button' style="border-top-left-radius: 6px; border-top-right-radius: 6px; background: rgb(204, 204, 204) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204) ! important; float: left; padding: 3px; margin: 2px;"><a href=''>Gradients</a></li>   
        </ul>
-->
<?php
include 'common.php';
include 'session_start.php';
$application = $_SESSION['application'];


// uniquement si 
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="fileForm" enctype="multipart/form-data">

<div style='display: block' id='textures'>

<?php    
$dossier = "_resources/backgrounds/";
$files = scandir($dossier);

//    print_r($files);

    foreach ($files as $file)
    {
      if (($file !='.') and ($file!='..')) {
      $dir_and_file = $dossier.$file ;
//      $imagesize = getimagesize($file);
      $height = 70 ;
//      $height = $imagesize[1] * 100 / $imagesize[0] ;
      
      echo "<div class='img'>";
//      echo "<a href='javascript:window.opener.galleryreturn(\"".$file."\")'>";
      echo "<a href='javascript:select(\"".$dir_and_file."\")'>";

//      echo "<img src='$dir_and_file' width='$width' height='$height' /></a>";
      echo "<img src='$dir_and_file' height='$height' /></a>";

      echo "</div>";
      }
    }


?>
</div>


      </form>
</body>  
</html>
