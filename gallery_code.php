<?php
// gallery_code.php
error_reporting(E_ALL);
$folder_list = "";
$message = "";
$application = $_SESSION['application'];
$selected_files = array();
$title = "";
$url = "";
$key = "";
$album = "";
if ( isset($_REQUEST['album'])) { $album = $_REQUEST['album']; }
if (isset($_REQUEST['javascript_action'])) 
    { $javascript_action = $_REQUEST['javascript_action']; } 
    else { $javascript_action='';  }

// $cliparts = $_REQUEST['cliparts'];
// if ($cliparts=='') {$cliparts='cliparts';}

//if (isset($_REQUEST['cliparts'])) 
//    { $cliparts = $_REQUEST['cliparts']; } 
//    else { $cliparts='cliparts';  }

if (isset($_REQUEST['imgwebsearch'])) 
    { $imgwebsearch = $_REQUEST['imgwebsearch']; } 
    else { $imgwebsearch='smiley';  }


$subdir = "";

// ------------------------------------------------------------------------------

if ($javascript_action=='imgwebsearch')
{
	$opts = array(
      'https'=>array(
          'method'=>"GET",
//          'method'=>"POST",          
//          'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
//          'header'=> "Content-type: application/xml\r\n"

          'header'=>"Content-Type: text/html; charset=utf-8"
	   )
	 );

$context = stream_context_create($opts);
//$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

	
    $tmp = "https://proappli.com/_resources/libs/searchbrave/index.php?source=gallery&q=";
	$tmp .= urlencode($_REQUEST['imgwebsearch']) ;
	$tmp .= "&type=image&search_lang=fr&country=fr&result_filter=web&display=html&OK=Envoyer";
    //readfile($tmp);
    //echo "<hr />";
//    echo "<a href='$tmp'>$tmp</a><br />\r\n"; //debug
    // exit;
   $imgwebsearch_content = file_get_contents($tmp, false, $context);
//    $tmp2 = file_get_contents($tmp);

	}

// ------------------------------------------------------------------------------
// old code does not work, to remove 
if ($javascript_action=='cliparts')
{
	$opts = array(
      'https'=>array(
          'method'=>"GET",
//          'method'=>"POST",          
//          'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
          'header'=> "Content-type: application/xml\r\n"

//          'header'=>"Content-Type: text/html; charset=utf-8"
	   )
	 );

$context = stream_context_create($opts);
//$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));

	
	$tmp = "https://openclipart.org/api/search/?page=1&sort=downloads&query=".urlencode($_REQUEST['cliparts']);
	
//	$tmp = "https://proappli.com/_resources/libs/searchbrave/index.php?q=".urlencode($_REQUEST['imgwebsearch'])."&type=image&search_lang=fr&country=fr&result_filter=web&display=xml&OK=Envoyer";
	
//    $tmp = "https://openclipart.org/api/search/?sort=downloads&query=cliparts";
//    $tmp = "https://openclipart.org/search/json/?sort=downloads&query=cliparts";
//    $tmp = "https://openclipart.org/search/?query=ufo";
    
    //readfile($tmp);
    //echo "<hr />";
//    echo "<a href='$tmp'>$tmp</a><br />\r\n"; //debug
    // exit;
   $tmp2 = file_get_contents($tmp, false, $context);
//    $tmp2 = file_get_contents($tmp);

//    echo $tmp2;   
//    echo "<hr />";    
    //exit ;
//    $tmp2 = str_replace("<media:thumbnail","<thumbnail",$tmp2);
//    $tmp2 = str_replace("</media:thumbnail>","</thumbnail>",$tmp2); 
    //echo $tmp2;   
    //echo "<hr />";
    $cliparts_xml = simplexml_load_string($tmp2);
//    print_r($cliparts_xml);   // debub only
    //echo "<hr />";
//    $cliparts_xml = simplexml_load_file($tmp);
	}
// ---------------------------------------------------------------------	

if (   
	    ($javascript_action=="album_onchange") 
  and ($_REQUEST['album']!="")	
	) 
{
//	$album_dir_and_file = "applications/".$application."/images/albums/".$_REQUEST['album'].".xml";    
	$album_dir_and_file = $application."/images/albums/".$_REQUEST['album'].".xml";    

	$simplexml_album = simplexml_load_file($album_dir_and_file);
	$subdir = $simplexml_album->head->dir ;
	$_POST['subdir'] = $subdir ;
	$_REQUEST['subdir'] = $subdir ;	
}	
// ---------------------------------------------------------------
if (isset($_REQUEST['album_title']))
{ 	$album_title = $_REQUEST['album_title']; }
else
{ $album_title = ""; }

if (isset($_REQUEST['subdir'])) 
{
	if ($_REQUEST['subdir']=="")
	{
		$subdir = "";
	}
	else
	{
//		$subdir = $_REQUEST['subdir']."/"; // bug here
		$subdir = $_REQUEST['subdir'];		
	}
}
// echo "subdir = ".$subdir."<br />";

if ($subdir!="")
{
//	$dossier = "applications/".$application."/images/".$subdir."/";
	$dossier = $application."/images/".$subdir."/";

	}
else
{
//	$dossier = "applications/".$application."/images/";
	$dossier = $application."/images/";

	}

// $dossier_albums = "applications/".$application."/images/albums";
$dossier_albums = $application."/images/albums";
if (file_exists($dossier_albums)) {
	$album_filenames = scandir($dossier_albums); //error_log	
	// echo "there is an albums folder"; // debug only
}
else {
	// echo "there is no albums";
	$album_filenames = ""; // debug only
}



$filename = "";
if (isset($_REQUEST['filename'])) {$filename = $_REQUEST['filename'];}

$application = "";
if (isset($_SESSION['application'])) {$application = $_SESSION['application'];} 

$field = "";     
if (isset($_REQUEST['field'])) { $field = $_REQUEST['field']; }

$wysiwyg_field = "";     
if (isset($_REQUEST['wysiwyg_field'])) { $wysiwyg_field = $_REQUEST['wysiwyg_field']; }

// ----------------------------
if ($javascript_action=="create_dir")
{
  $system_message = create_dir($dossier,$filename);
}


// ----------------------------

//    if (isset($_REQUEST['delete'])){
if ($javascript_action=="delete") {
  $message = delete_files($dossier);
}

if ($javascript_action=="uploadok") {
  $message = "UPLOAD:OK";
}

if ($javascript_action=="copy") {
  // echo "copy ..."; exit ;
  $message = copier_fichiers($dossier);
}

if ($javascript_action=="move") {
  //echo "move ..."; exit;
  $message = deplacer_fichiers($dossier);
}


//    if (isset($_REQUEST['submitBtn'])){
// =============================================================================
// ==== album_onchange : check checkbox of selected pics =======
// =============================================================================
if (  
//	  ( 
	    ($javascript_action=="album_onchange") 
  and ($_REQUEST['album']!="")
//	 )
//	OR
//	  ( ($javascript_action=="select_album") and ($_REQUEST['album']!="") )	
	) 
	{
//	$album_dir_and_file = "applications/".$application."/images/albums/".$_REQUEST['album'].".xml";    
	$album_dir_and_file = $application."/images/albums/".$_REQUEST['album'].".xml";    

	$simplexml_album = simplexml_load_file($album_dir_and_file);
	// echo "dir : ".$simplexml_album->head->dir ; 
	// $subdir = $simplexml_album->head->dir ;
  if ( $simplexml_album == FALSE ) 
		{
      $message .= "ERROR READING FILE $album_dir_and_file";
      echo "ERROR READING FILE $album_dir_and_file";
      exit;
    }
	unset($selected_files); // init
  foreach ($simplexml_album->body->item as $item) 
		{
		  $selected_files[] = $item;
	  }   
	}
// =================================================================
// ============ Select : display only pics of selected album =======
// =================================================================
    if ( ($javascript_action=="select_album") and ($_REQUEST['album']!="") ) {
//    echo "select_album <br />\r\n";
//    $album_dir_and_file = "applications/".$application."/images/albums/".$_REQUEST['album'].".xml";    
    $album_dir_and_file = $application."/images/albums/".$_REQUEST['album'].".xml";    

    $simplexml_album = simplexml_load_file($album_dir_and_file); 
      if ( $simplexml_album == FALSE ) {
        $message .= "ERROR READING FILE $album_dir_and_file";
        echo "ERROR READING FILE $album_dir_and_file";
        exit;
      }
    unset($display_files); // init 
    unset($display_url); // init 
    unset($display_title); // init
    
    foreach ($simplexml_album->body->item as $item) {
//           echo $item."<br />\r\n";
           $display_files[] = $item;
           $display_url[] = $item['url'];  // bug here ?
           $display_title[] = $item['title'];  // bug here ?
      }  
    unset($selected_files); // init
    foreach ($simplexml_album->body->item as $item) 
	{
	$selected_files[] = $item;
	}
    }
    else
    {
    // echo "looking at files in $dossier";
    $display_files = scandir($dossier);
    // print_r($display_files);
    unset($display_files[0]);
    unset($display_files[1]);
    unset($display_files['albums']);
    // but index remains unchanged !
    // remove ".", ".." and "albums"
    unset($display_url); // init 
    unset($display_title); // init 

//    $album_dir_and_file = "applications/".$application."/images/albums/".$_REQUEST['album'].".xml";    
//    $simplexml_album = simplexml_load_file($album_dir_and_file); 
    	foreach ($display_files as $key => $display_file)
		{
		    if ( ($display_file!=".") AND ($display_file!="..") AND (isset($simplexml_album->body->item)) ) 
			{
                try {
                
    			    foreach ($simplexml_album->body->item as $item)    // error here ! error_log
    				{
    				// echo "comparing $item =?= $display_file ? <br />\r\n";
    				if ($item == $display_file) {
    					// echo "comparing $item =?= $display_file ? <br />\r\n";
    					$display_url[$key] = $item['url'];
    					$display_title[$key] = $item['title'];
    
    					// echo "url = ".$item['url']."<br />\r\n";
    					} 
    				// else {$display_url[]="";} // bug here !
    				}
                }
                catch (Exception $e) {
                    echo 'Error : Exception : ',  $e->getMessage(), "\n";
                   	$display_url[$key] = "";
    				$display_title[$key] = "";
                }
			}	
		}
    }
// =================================================================
// =================== SAVE ALBUM ============================
// =================================================================
    if ($javascript_action=="save_album") {                      
//    $dossier_albums = "applications/".$application."/images/albums/" ;
    $dossier_albums = $application."/images/albums/" ;

    $album_files = scandir($dossier_albums);    
//      $album_files = scandir($dossier."albums/");
      // print_r($files);
      if ( $album_files == FALSE ) { 
//      echo "there is not 'albums' folder" ; 
      mkdir($dossier_albums, 0777);
  //    print_r($_REQUEST);
//      print_r($file);
      $album_filename = "1.xml";
      }
      if ($_REQUEST['album'] == 'new')
      {
      $tmp_count = count($album_files) - 2 + 1 ; // minus . & ..
      $album_filename = $tmp_count . ".xml";      
      }
      else
      {
      $album_filename = $_REQUEST['album'].".xml";
      }
//      $album_filename = "1.xml";
            
      $album_xml = "<xml>\r\n";
      $album_xml .= " <head>\r\n";
      $album_xml .= "   <title>".$album_title."</title>\r\n";
      $album_xml .= "   <dir>".$subdir."</dir>\r\n";      
      $album_xml .= " </head>\r\n";
      $album_xml .= " <body>\r\n";     
     
//      echo "trying to add selected pics to album ".$_REQUEST['album'];
      // debug :

//      print_r($_REQUEST);
//      echo "<hr /> <p>file : </p>\r\n ";
	  
      print_r( $_REQUEST['file'] );
//      echo "<hr /> <p>url : </p>\r\n";
      print_r( $_REQUEST['url']);
//      exit; // debug only
	$count_file = 0 ;

    	if (isset($_REQUEST['file']) && isset($_REQUEST['url']) && count($_REQUEST['file']) == count($_REQUEST['url'])) {  
//      if ( count( $_REQUEST['file'] )== count($_REQUEST['url']) ) { 
	$save_mode = "selectdisplaysave";
//       echo "select,display, save : only the pics of one album <br />"; 
        foreach($_REQUEST['file']  as $key => $addtoalbum)
          {
          $url = $_REQUEST['url']["$key"] ;
          //echo $url."<br />";
        // $url = htmlentities($url); // bug here ! 
        $url = urlencode($url);
        //echo $url."<br />";
        $title = $_REQUEST['title'][$key]; // bug here !
          $tmp_file_array = explode('|',$addtoalbum);
          $tmp_file = $tmp_file_array[1];
          if ($tmp_file_array[1]!='') 
            {
            $tmp_file = $tmp_file_array[1];
            } 
          else 
            {
            $tmp_file = $addtoalbum ; 
            }  
          $album_xml .= "   <item url='".$url."' title='".$title."'>".$tmp_file."</item>\r\n";  
          }
       }
      else
      {  
          $save_mode = "selectsave"; 
  //      echo "select, save : all pics, no filtered for an album <br />"; 
        foreach($_REQUEST['file']  as $file_key => $file_value)
          {
//            $file_value_array = explode("<sep>", $file_value);
            $file_value_array = explode("|", $file_value);            
  //          echo "file:".$file_value_array[0].":".$file_value_array[1]."<br />" ;
            $tmp = $file_value_array[0];
            $file_new_array[$tmp]=$file_value_array[1];
          }
  //        echo "file_new_array : <br />";
  //        print_r($file_new_array); 
        foreach($_REQUEST['url']  as $url_key => $url_value)
          {
//            $url_value_array = explode("<sep>", $url_value);
            $url_value_array = explode("|", $url_value);            
            $tmp = $url_value_array[0];
            $url_new_array[$tmp]=$url_value_array[1];
          }
        foreach($_REQUEST['title']  as $title_key => $title_value)
          {
//            $title_value_array = explode("<sep>", $title_value);
            $title_value_array = explode("|", $title_value);            
            $tmp = $title_value_array[0];
            $title_new_array[$tmp]=$title_value_array[1];
          }
  //        echo "url_new_array : <br />";
  //        print_r($url_new_array); 
        foreach( $file_new_array as $key => $file )
          {
              $album_xml .= "   <item url='".$url_new_array[$key]."' title='".$title_new_array[$key]."'>".$file."</item>\r\n";  
          }
      }
            

//      foreach($_REQUEST['file']  as $key => $addtoalbum)
//        {
//	      $url = $_REQUEST['url'][$key]; // bug here ! 
//        $album_xml .= "   <item url='".$url."'>".$addtoalbum."</item>\r\n";  
//        }
// ========================================================================
      $album_xml .= " </body>\r\n";  
      $album_xml .= "</xml>\r\n";  
//      echo $album_xml ;
//      exit ; // debug only
//      $album_folder_and_filename = $dossier."albums/".$album_filename ;
      $album_folder_and_filename = $dossier_albums.$album_filename ;

 //     echo $album_folder_and_filename."<br />";
      
//      $fp = fopen($dossier."albums/".$album_filename, 'w');
      $fp = fopen($dossier_albums.$album_filename, 'w');

// if ( fwrite($fp, $template_meta_content) == FALSE )
      if ( fwrite($fp, $album_xml) == FALSE )
      { 
          $message .= "<br />Error trying to save file <br />";
      //    echo "<br />Error trying to save file <br /> ";
      } // if 
      else
      {
      //echo "OK, album $album_filename saved  <br />";
      $message = "OK, album $album_filename saved <br />";
      header("Location: gallery.php?message=$message"); 
      // exit; // or reload page
      // reload to http://myquickapps.com/apps/gallery.php
      } // else
      fclose($fp);

      // if $_REQUEST['album'] == 'new'  ...
      // check if there is an album, if no there album_id = '1'
      // if there are albums, find the biggest number
    }

// ==============================================================
//                      UPLOAD
// ==============================================================

    if ($javascript_action=="upload") {
//     $dossier = 'upload/';
     $width_max = 1300 ;
     $ok_files = array(
                  "jpg","jpeg","gif","png", "svg", "odg", "ico" 
//                  "ai", "psd","xcf","odg",
//                  "zip",
//                  "pdf",
//                  "mp3", "ogg",
//                  "avi","ogv", "mp4", "3gp", "wmv"
                 );

          $i = 0 ;
     foreach ($_FILES['upfile']['name'] as $filename_encoded)
     {
     $filename = basename($filename_encoded);
     $filename = strtolower($filename);
     $filename = trim($filename);
     $filename = str_replace(' ','_',$filename);
	 $filename = str_replace('é','e',$filename);
	 $filename = str_replace('è','e',$filename);
	 $filename = str_replace('ç','c',$filename);
	 $filename = str_replace('"','_',$filename);
	 $filename = str_replace("'",'_',$filename);
	 $filename = str_replace('à','a',$filename);
	 //$filename = preg_replace('/[^A-Za-z0-9\-]/', '', $filename);
     $file_extension = substr(strrchr($filename, '.'), 1); // last '.'
     $file_type = $_FILES['upfile']['type'][$i];
     $file_tmp = $_FILES['upfile']['tmp_name'][$i];
     $file_error = $_FILES['upfile']['error'][$i]; 
     $file_size = $_FILES['upfile']['size'][$i];      
     echo "$filename : ext=$file_extension , type=$file_type <br />\r\n";

     if (in_array($file_extension, $ok_files)) {

//          $dossier = "applications/".$application."/images/".$subdir;
          $dossier = $application."/images/".$subdir;

          // echo "subdir=".$subdir."<br />\r\n"; // debug
          // echo "dossier=".$dossier."<br />"; // debug
          // exit;  // debug
        }
      else
        {
          echo "<p>File type ".$file_extension." , ".$file_type." not autorized</p>";
          exit;
        }

      list($width, $height) = getimagesize($file_tmp);
// $newwidth = $width * $percent;
// $newheight = $height * $percent;
//      echo $fichier . "<br />\r\n";
//      echo "width : $width px , height : $height px <br />\r\n";
      if ( ( $width > $width_max ) AND ($file_extension=='jpg') )
        {
        $width_new = $width_max ;
        $ratio = $width / $width_max ;
        $height_new = $height / $ratio ;
//      echo "image is too large : width ".$width."px -> ".$width_new."px<br />\r\n";      
//      Chargement
        $image_new = imagecreatetruecolor($width_new, $height_new);
        $source = imagecreatefromjpeg($file_tmp);
//      Redimensionnement
        imagecopyresized($image_new, $source, 0, 0, 0, 0, $width_new, $height_new, $width, $height);
        imagejpeg($image_new,$file_tmp);      
//      exit;
        } //if file type is jpg and too large       
        
     $upload_result = move_uploaded_file($file_tmp, $dossier .'/'. $filename);
     if($upload_result) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
        {
          $message = "UPLOAD OK !";
//          echo '<p>Upload effectué avec succès !</p>';  
          header("Location: gallery.php?message=$message");          
        }
        else //Sinon (la fonction renvoie FALSE).
        {
          echo "<p>Upload failed : $filename</p>";
          echo "<p>".$upload_result."</p>";
          print_r($_FILES);
          echo "<p>Maybe file is too big</p>";
//          echo "<p>".$_FILES['upfile']['error']."</p>";                    
        }
     $i++;
     } // end foreach ($_FILES['upfile']['name'] ...


//     $fichier = basename($_FILES['upfile']['name']);
     
          
//     $fichier_array = explode(".", $fichier);
// bug si fichier s'apelle toto.exe.jpg ! 
//     print_r($fichier_array );
//   make it lowercase () minuscule )
     // $fichier_array[1] = strtolower($fichier_array[1]);
//     $file_ext   = strtolower($fichier_array[1]);
//     echo "type : ".$fichier_array[1]." <br />";     
//     $type_array = explode("/",$_FILES['upfile']['type']);
//     if ($type_array[0]=='image') {
//     if ( ($file_ext=='jpg') OR ($file_ext=='jpeg') OR ($file_ext=='png') 
//     OR ($file_ext=='gif') OR ($fichier_array[1]=='svg') ) {
//          $dossier = "applications/".$application."/images/".$subdir;
//     }
//     else
//     {
//     echo "<p>File type ".$_FILES['upfile']['type']." not autorized</p>";
//     exit;
//     }
     
     
//      list($width, $height) = getimagesize($_FILES['upfile']['tmp_name']);
// $newwidth = $width * $percent;
// $newheight = $height * $percent;
//    echo $fichier . "<br />\r\n";
//      echo "width : $width px , height : $height px <br />\r\n";
//     if ( ( $width > $width_max ) AND ($file_ext=='jpg') )
//      {
//      $width_new = $width_max ;
//      $ratio = $width / $width_max ;
//      $height_new = $height / $ratio ;
//      echo "image is too large : width ".$width."px -> ".$width_new."px<br />\r\n";
      
      // Chargement
//      $image_new = imagecreatetruecolor($width_new, $height_new);
//      $source = imagecreatefromjpeg($_FILES['upfile']['tmp_name']);

// Redimensionnement
//     imagecopyresized($image_new, $source, 0, 0, 0, 0, $width_new, $height_new, $width, $height);
//      imagejpeg($image_new,$_FILES['upfile']['tmp_name']);
      
      //exit;
//      }
      

     //echo "<p>type:".$type_array[0]."</p>";
//     $pieces = explode(" ", $pizza);
//     if(move_uploaded_file($_FILES['upfile']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
//     if(move_uploaded_file($_FILES['upfile']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
//     {
//          $message = "UPLOAD OK !";
//         echo '<p>Upload effectué avec succès !</p>';  
//          header("Location: gallery.php?message=$message");          
//     }
//     else //Sinon (la fonction renvoie FALSE).
//     {
//          echo '<p>Echec de l\'upload !</p>';
//          echo "<p>".$_FILES['upfile']['error']."</p>";                    
//     }
    } // endif ($javascript_action=="upload")

// include 'common.php';

// ---------------------------------------------------------
//                         FUNCTIONS
// ---------------------------------------------------------

function create_dir($dossier,$filename)
{
// $handle = fopen($dossier.$filename, "x+");
// preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $filename);   // remove special car
$filename = preg_replace('/[^A-Za-z0-9\-]/', '', $filename);
echo $filename;
mkdir($dossier.$filename, 0755);
chmod($dossier.$filename, 0755);  // notation octale : valeur du mode correcte
//rajouter un .htaccess pour ne pas permettre à visiteur anonyme de fouiller dans dossier

  $file_tmp = $dossier.$filename."/.htaccess";
  $fopen_tmp = fopen($file_tmp, 'w');  
  $file_tmp_content = "IndexIgnore *.*";
  if ( fwrite($fopen_tmp, $file_tmp_content) == FALSE )
  { 
      $system_message .= "<br />Error trying to save file <br />";
      return $system_message ;
      echo "<br />Error trying to save file htaccess...  <br /> ";
  }
}
// -----------------------------------------------------
//function copy_files() // strange bug here ...
//{
//}

function copier_fichiers($dossier)
{
	$application = $_SESSION['application'];
	// $message = "should copy files ";
	$copy_folder = $_REQUEST['move_folder'];
	$num_copy = 0 ; 
	foreach($_POST['file'] as $filetocopy)
    {
    $tmp = explode("|", $filetocopy);    
    $dir_and_filetocopy = $dossier . $tmp[1] ;
//    $dir_and_filetocopy2 = "applications/".$application . "/images/". $copy_folder . "/". $tmp[1] ;    
    $dir_and_filetocopy2 = $application . "/images/". $copy_folder . "/". $tmp[1] ;    

    // $message .= $filetocopy . " ";
    // $message .= $dir_and_filetocopy . " to ".$dir_and_filetocopy2 ;
    $result = copy($dir_and_filetocopy,$dir_and_filetocopy2);
    $num_copy += $result ;
    //$message = $result ;
	}
	$message = $num_copy . " files copied";
	return $message ;
	}
	
function deplacer_fichiers($dossier)
{
	$application = $_SESSION['application'];
    //$message = "should move files ";
    $move_folder = $_REQUEST['move_folder'];	
    $num_move = 0 ;
	foreach($_POST['file'] as $filetomove)
    {
    $tmp = explode("|", $filetomove); 
    // $message .= $filetomove . " ";   
    $dir_and_filetomove = $dossier . $tmp[1] ;
//    $dir_and_filetomove2 = "applications/".$application."/images/".$move_folder ."/". $tmp[1] ;    
    $dir_and_filetomove2 = $application."/images/".$move_folder ."/". $tmp[1] ;    

	//$message .= $dir_and_filetomove . " to ".    $dir_and_filetomove2;
	$result = rename($dir_and_filetomove,$dir_and_filetomove2);
	$num_move += $result ;
	// $message = $result ;
	}
	$message = $num_move. " files moved ";
	return $message ;
	}	

//function move_files() // strange bug here ....
//{
//}

function delete_files($dossier)
{
// $system_message = delete_files($dossier,$_POST['file']);
//    echo "<br /><h1>should delete files ...</h1>";
    foreach($_POST['file'] as $filetodelete)
    {
//    $tmp = explode("<sep>", $filetodelete);
    $tmp = explode("|", $filetodelete);    
    $dir_and_filetodelete = $dossier . $tmp[1] ;
    // echo "trying to delete ".$dir_and_filetodelete."<br />";
    $delete_result = unlink($dir_and_filetodelete);
//    echo $delete_result ; 
    $message = "OK, files deleted !";
    return $message ;
    }
//    print_r($_REQUEST['file']);
//    exit;
//    header("Location: gallery.php?message=$message"); 
}
?>
