<html>
	<head>
		<meta http-equiv="refresh" content="5" />
<style>
.chatlogin {
	font-size: 9px ;
	margin-top: 5px ;
}

.chattext {
	border-width: 1px ;
	border-style: solid ;
	border-color: grey ;
	border-radius: 8px ;
	font-size: 15px ;
	padding: 5px ;
}
</style>
</head>
<body>
<?php 
$login = $_REQUEST['login'];
$chatroom = $_REQUEST['chatroom'];

// Vérifiez si le répertoire existe, sinon créez-le
$chatroom_dir = "_chats/" . $chatroom;
if (!is_dir($chatroom_dir)) {
    mkdir($chatroom_dir, 0777, true);
}


// echo "<p>login = $login</p>";
// Chemin complet vers le fichier chats.txt
$chat_file = $chatroom_dir . "/chats.txt";

// Vérifiez si le fichier existe, sinon créez-le
if (!file_exists($chat_file)) {
    file_put_contents($chat_file, ""); // Crée un fichier vide
}

$lines = file('_chats/'.$chatroom."/chats.txt");

// Loop through our array, show HTML source as HTML source; and line numbers too.
foreach ($lines as $line_num => $line) {
    //echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
	$line_arr = explode("|",htmlspecialchars($line));
    
    if ( $line_arr[2] == $login )
    {
    $messagecolor =  "LightGreen" ;

	echo "<div style='text-align: right; font-size:9px' class='chatlogin'>".$line_arr[2]."</div>\r\n" ;
	echo "<div style='margin-left: 30px; background-color: ".$messagecolor."'  class='chattext'>";    
    
    }
    else
    {
    $messagecolor = "White" ;
    
	echo "<div style='text-align: left; font-size:9px' class='chatlogin'>".$line_arr[2]."</div>\r\n" ;
	echo "<div style='background-color: ".$messagecolor."'  class='chattext'>";    
    
    }
    


	if (substr($line_arr[4],0,4)=='http')
	{
	$tmp_len = strlen($line_arr[4]);
	$last4 = substr($line_arr[4],$tmp_len - 6,4) ;
	$link_arr = explode("/", $line_arr[4]);
	// echo "$last4";
	if ( ($last4 == '.jpg') OR ( $last4 == '.png' ) )
	{
	echo "<a target='_new' href='$line_arr[4]'>"; 
	echo "<img style='max-width:100%;height:100px' src='".$line_arr[4]."' /></a>";
		}
	else if ($last4 == '.pdf')
	{
		// https://proappli.com/_resources/images/office_pdf.png
	echo "<a target='_new' href='$line_arr[4]'>";
	echo "<img src='https://proappli.com/_resources/images/office_pdf.png' width='24' height='24' />";
	echo $link_arr[5]."</a>";			
	}	
	else if ($last4 == '.odt')
	{
	echo "<a target='_new' href='$line_arr[4]'>";
	echo "<img src='https://proappli.com/_resources/public_icons/odt.png' width='24' height='24' />";
	echo $link_arr[5]."</a>";			
	}	
	else if ($last4 == '.ods')
	{
	echo "<a target='_new' href='$line_arr[4]'>";
	echo "<img src='https://proappli.com/_resources/public_icons/ods.png' width='24' height='24' />";
	echo $link_arr[5]."</a>";			
	}	
	else if ($last4 == '.odp')
	{
	echo "<a target='_new' href='$line_arr[4]'>";
	echo "<img src='https://proappli.com/_resources/public_icons/odp.png' width='24' height='24' />";
	echo $link_arr[5]."</a>";			
	}	
	else if ($last4 == '.odg')
	{
	echo "<a target='_new' href='$line_arr[4]'>";
	echo "<img src='https://proappli.com/_resources/public_icons/odg.png' width='24' height='24' />";
	echo $link_arr[5]."</a>";			
	}	
	else if ($last4 == '.txt')
	{
	echo "<a target='_new' href='$line_arr[4]'>";
	echo "<img src='https://proappli.com/_resources/public_icons/office_txt.png' width='24' height='24' />";
	echo $link_arr[5]."</a>";			
	}	

	else
	{
	echo "<a target='_new' href='$line_arr[4]'>".$link_arr[5]."</a>";		}	
	}
	else
	{
		echo $line_arr[4];
	}
		echo "</div>\r\n" ;


}


?>
</body>
</html>
