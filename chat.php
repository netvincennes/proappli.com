<?php
// print_r( $_FILES );
//print_r($_FILES['upfiles']);
//print_r($_REQUEST);

$notification = "";
$chatroom = "";
$login = "";
$email = "";
$action = "";

if ( isset($_REQUEST['chatroom']) ) 
	{ $chatroom = strtolower( $_REQUEST['chatroom'] ); }
$chatroom = ucfirst( $chatroom ) ; 


if ( isset($_REQUEST['login']) ) 
	{ $login = strtolower( $_REQUEST['login'] ); }



if ( isset($_REQUEST['email']) ) 
	{ $email = strtolower( $_REQUEST['email'] ); }


if ( isset($_REQUEST['action']) ) 
	{ $action = strtolower( $_REQUEST['action'] ); }

if ( ($chatroom == "") OR ( $login=="" ) )
{
echo "<html> \r\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/> \r\n"; 
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'/> \r\n";
echo "<body><form>";
echo "<p>chatroom : <input value='$chatroom' name='chatroom' /></p>";
echo "<p>login : <input name='login' /></p>";
// echo "<li>email : <input name='email' /></li>";
echo "<p><input type='submit' name='action' /></p>";

echo "</form></body></html>";
exit ;
}


// echo $action ; 

$smileys = array (	"😀", "😆",  "😎", "😉" , "😊", "😍", "😇",  "😘", "😋", "😛", "🤔", "🤨", 
					"🙄", "🤥",  "😴", "😷" , "🤒", "🤮", "😲",  "😳", "😧", "😢",  
					"😱", "😠",  "🤡", "👿" , "☠", "💩", "👽",  "🖖", "👌",  
					"👍", "👎",  "👏", "🤝" , "🙏", "🤦", "🤷",  "💪", "💋", "💗");



if ( $action == 'deleteall' ) 
{
//echo " delete all files ";
$files_arr = scandir("_chats/".$chatroom);
   foreach ($files_arr as $key => $value)
   { 
//   echo "<p> $key : $value </p>\r\n";
   $dir_and_file = "_chats/".$chatroom."/".$value;
//   echo "<p>$dir_and_file</p>\r\n";
   if ( ($value!=".") AND ($value!="..") ) 
	   {
	   unlink($dir_and_file); // delete 
	   }
   }
$notification = "OK all files deleted";
}


if ( (sizeof($_FILES) != 0 ) AND ( $action == 'uploadfile') ) 
{
//	echo "files to upload ";
	upload_files($_FILES['upfiles'],$chatroom);
}


if ($chatroom=='') {echo "ERROR, no chatroom"; exit ;}

function upload_files($file_arr,$chatroom)
{
//	echo "<p>Function upload_files : </p>";
//	print_r($file_arr);
	$name = $file_arr['name'];
     $filename = basename($name);
	 $filename = str_replace(' ','_',$filename);
	 $filename = str_replace('é','e',$filename);
	 $filename = str_replace('è','e',$filename);
	 $filename = str_replace('ç','c',$filename);
	 $filename = str_replace('"','_',$filename);
	 $filename = str_replace("'",'_',$filename);
	 $filename = str_replace('à','a',$filename);
//    echo "<p>Filename : $filename</p>";	 
	$type = $file_arr['type'];
	$tmp_name = $file_arr['tmp_name'];	
	$error = $file_arr['error'];	
	$size = $file_arr['size'];	
	if ($error != "0") {echo "<p>ERROR : $error </p>";}
	if (   ( $type == "image/png") 
		OR ( $type == "application/pdf") // .pdf
		OR ( $type == "image/jpeg") // .jpg ou .jpeg
	    OR ( $type == "application/vnd.oasis.opendocument.text") // .odt
		OR ( $type == "application/vnd.oasis.opendocument.spreadsheet" ) // .ods
		OR ( $type == "application/vnd.oasis.opendocument.presentation" ) // .odp
		OR ( $type == "application/vnd.oasis.opendocument.graphics" ) // .odg
		OR ( $type == "text/plain" )  //.txt
		) {
		// copy file to folder
		// $dossier = "_chats/"."/".$chatroom."/";
		
		if (!file_exists ( "_chats/" . $chatroom ))
		{
		mkdir("_chats/" . $chatroom, 0777, true);	
		}
		
		$dir_and_file = "_chats/" . $chatroom . "/" . $filename ;
//		echo "<p>".$dir_and_file." </p>" ;
		$upload_result = move_uploaded_file($tmp_name, $dir_and_file );
		
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$login = $_REQUEST['login'];
		$email = $_REQUEST['email'];
//		$message = "<a href='https://proappli.com/".$dir_and_file."'>".$dir_and_file."</a>";
		$message = "https://proappli.com/".$dir_and_file;		
		// $message = basename($message);
		// $message = mb_convert_encoding($message, 'HTML-ENTITIES', "UTF-8");
		// $message = htmlentities($message, ENT_QUOTES, "UTF-8");
		//$chatroom = $_REQUEST['chatroom'];
		$line = $date."|".$time."|".$login."|".$email."|".$message ;
//		echo "<p>$line</p>";
		$fp = fopen("_chats/".$chatroom."/chats.txt", 'a');
		fwrite($fp, $line."\r\n");
		fclose($fp);		
		
	}
	else
	{
		$message = "ERROR : Only .txt .pdf .jpg .png .odt .ods .odp .odg";
		echo $message ;
	}
//	foreach ($file_arr as $file)
//	{
//		print_r($file);
//	}
}
?>

<html>

<head>

    <link rel="stylesheet" type="text/css" href="https://proappli.com/common.css" />   
    <title>Chat</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes">   
	<link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet"> 

<style>
body{
	font-family: 'Audiowide', cursive;
}

header {
	color : white ;
	background-color: green ; 	
}

</style>

<script>

function smiley_click(val)
{
//	alert(val);
	var text = document.getElementById('message').value ;
	text = text + " " + val ;
	document.getElementById('message').value = text ;
}

function send()
{
	var text = document.getElementById('message').value ;
	var login = document.getElementById('login').value ;
	var email = document.getElementById('email').value ;	
//	alert('sending ...'+text);
	window.frames['chat_frame'].document.getElementById('message').value = text ;
	window.frames['chat_frame'].document.getElementById('login').value = login ;
	window.frames['chat_frame'].document.getElementById('email').value = email ;
	window.frames['chat_frame'].document.getElementById('chatroom').value = "<?=$chatroom?>" ;	
	window.frames['chat_frame'].document.forms['form1'].submit();
	document.getElementById('message').value = "";
//	window.frames['chat_frame'].document.reload();
	var iframe = document.getElementById('chat_messages');
	iframe.src = iframe.src;
}

function toggle_smiley_selector()
{
smileysblock = document.getElementById('smileysblock') ; 

if (smileysblock.style.display == "block" )
{ document.getElementById('smileysblock').style.display = "none" ; }
else
{ document.getElementById('smileysblock').style.display = "block" }

// 	alert('smiley');
}

function toggle_file_selector()
{
	alert('file');
}

function upload_launch()
{
	document.getElementById('action').value = "uploadfile";
	document.forms['form1'].submit() ;
}

function delete_all()
{
	document.getElementById('action').value = "deleteall";
	document.forms['form1'].submit() ;
	}

</script>

</head>

<body>

<menu style="width:100%;max-width:400px;text-align:right">
<button type='button' onclick='delete_all()'>
<img  width='32' height='32'  src='https://proappli.com/_resources/small_icons/appbar.delete.png' alt='Tout effacer' title='Tout effacer' />EFFACE TOUT !
</button>

<button><a href='https://meet.jit.si/<?=$chatroom?>'><img alt='appel video' title='appel video' width='32' height='32' src='https://proappli.com/_resources/images/video-meeting.png' />APPEL VIDEO</a>
</button>

</menu>
<header>
<h1>Chatroom <?=$chatroom?></h1>
</header>
<main  style="margin:50px;width:100%;max-width:500px;text-align:right">
<div><?=$notification?></div>
<div>
<form onsubmit="send()" id='form1' name='form1' enctype="multipart/form-data"  method="post"  >
<?php
	echo "<br />\r\n";
	
if ($login=="")
{
	echo "Pseudo (login) : <input name='login' /> <br />"; 
	echo "Email : <input name='email' /> <br />"; 	
    echo "<input name='chatroom' id='chatroom' value='".$chatroom."' />";
	echo "<input type='submit' name='action' value='OK' />";
}
else 
{
?>
<iframe id='chat_messages' name='chat_messages' width="100%" src="https://proappli.com//chat_messages.php?chatroom=<?=$chatroom?>&login=<?=$login?>"></iframe>

</div>



<table width='100%' style='background-color:lavender'><tr>
<td style='background-color:lavender'>
<button type='button' onclick='toggle_smiley_selector()'>
<img width='24' height='24' src='https://proappli.com/_resources/small_icons/appbar.smiley.png' alt='Smiley' title='Smiley' />
</button>





</td>
<td style='background-color:lavender' width='90%'>
<input onchange="send()"  style='width: 100%' name='message' id='message' placeholder='Ecrivez un message' />
</td>
<td style='background-color:lavender'>
<button type='button' onclick='send()'>
<img width='24' height='24' src='https://proappli.com/_resources/small_icons/chat_send.png' alt='Envoyer' title='Envoyer' />
</button>
</td>

</tr>
<tr>
<td><button type='button' onclick='toggle_file_selector()'>
<img width='24' height='24' src='https://proappli.com/_resources/small_icons/appbar.paperclip.rotated.png' alt='Fichier' title='Fichier' />
</button></td>
<td><input onchange='upload_launch()' type="file" id="upfiles" name="upfiles" accept=".jpg, .jpeg, .png, .pdf, .odt, .ods, .odp, .txt "></td>
</tr>

</table>



<!--
<input type='submit' action='Upload' />
-->

<!--
<div style='font-family: arial;font-size: 25px'>  😀 😆  😎 😉 😊 😍 😇  😘 😋 😛  🤔  🤨 🙄 🤥  😴  😷 🤒 🤮  😲  😳 😧 😢 😱  😠  🤡  👿  ☠  💩  👽  🖖  👌  👍  👎  👏  🤝  🙏  🤦  🤷  💪   💋  💗
</div> 
-->


<div id='smileysblock'style='display: none; font-family: arial;font-size: 25px'>
<?php

foreach ($smileys as $smiley)
{
	echo "<span onclick='smiley_click(this.innerHTML)'>".$smiley."</span>";
}
?>
</div> 

<?php


}
?>
<div style='display: none'>
<input size='1'  name='login' id='login' value='<?=$login?>' />
<input size='1'   name='email' id='email' value='<?=$email?>' />
<input size='1'   name='chatroom' id='chatroom' value='<?=$chatroom?>' />
<input  name='action' id='action' value='' />
</div>
<iframe width='1' height='1' id='chat_frame' name='chat_frame' src='chat_frame.php'></iframe>

</form>
</main>

</body>

</html>
