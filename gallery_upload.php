<?php
session_start();
error_reporting(E_ALL);
include 'files_code.php';
include 'common.php';
include 'session_start.php';

// set error reporting level
//if (version_compare(phpversion(), '5.3.0', '>=') == 1)
//  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
//else
//  error_reporting(E_ALL & ~E_NOTICE);
  
$application = "";
if (isset($_SESSION['application'])) {$application = $_SESSION['application'];}  

$ok_files = array(
//                  "doc", "docx", "odt", "rtf",
//                  "html", "html", "url",
//                  "ppt", "pptx", "odp",
//                  "xls", "xlsx", "ods",
//                  "txt", "csv", "tsv",
                  "jpg","jpeg","gif","png", "ai", "psd","xcf","odg","svg" ,
//                  "zip",
//                  "pdf",
//                  "mp3", "ogg",
//                  "avi","ogv", "mp4", "3gp"
//                  , "wmv"
                 );

function bytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}

if (isset($_FILES['myfile'])) {
    $sFileName = $_FILES['myfile']['name'];
//    $filename = basename($filename_tmp);
    $file_extension = substr(strrchr($sFileName, '.'), 1); // last '.'   
    $file_tmp = $_FILES['myfile']['tmp_name'];   
    $filename = basename($_FILES['myfile']['name']);      
    $sFileType = $_FILES['myfile']['type'];
    $sFileSize = bytesToSize1024($_FILES['myfile']['size'], 1);
   
    // echo "<p>up : ".$file_tmp ."|</p><p>". $dossier ."</p><p>|". $filename . "</p>";
    if (in_array($file_extension, $ok_files)) {
//         $dossier = "applications/".$application."/documents/".$subdir;
//         $dossier = $application."/documents/".$subdir;
         $dossier = $application."/images/".$subdir;         

         //echo "<p>".$file_tmp ."|</p><p>". $dossier ."</p><p>|". $filename . "</p>";
		 $upload_result = move_uploaded_file($file_tmp, $dossier . $filename);
		 if($upload_result) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
			{
			  $message = "UPLOAD OK !";
	//          echo '<p>Upload effectué avec succès !</p>';  
	//		  header("Location: files.php?message=$message");          
			}
			else //Sinon (la fonction renvoie FALSE).
			{
			  echo "<p>Upload failed : $filename</p>";
			  echo "<p>".$upload_result."</p>";
			  print_r($_FILES);
			  echo "<p>Maybe file is too big</p>";
	//          echo "<p>".$_FILES['upfile']['error']."</p>";                    
			}


        }
      else
        {
          echo "<p>File type ".$file_extension." , ".$file_type." not autorized</p>";
          exit;
        }

//foreach ($files as $file)
//{
//      $file_array = explode(".", $file);
//      $tmp_count = count($file_array) - 1 ;
//      $extension = $file_array[$tmp_count];    
//      if (($file !='.') and ($file!='..') and ($extension!='htaccess') )  {
//        $dir_and_file = $dossier.$file ;
//        echo "<p>".$file."</p>";
//    }
//}    


//    echo <<<EOF
//<div class="s">
//    <p>Your file: {$sFileName} has been successfully received.</p>
//    <p>Type: {$sFileType}</p>
//    <p>extension : {$file_extension} </p>
//    <p>Size: {$sFileSize}</p>
//</div>
//EOF;
} else {
		echo '<div class="f">An error occurred</div>';
}

// $files = scandir($dossier);


//          $message = "UPLOAD:OK!";
//         echo '<p>Upload effectué avec succès !</p>';  
//          header("Location: gallery.php?message=$message"); 
// display_list_files($dossier,$files);
// display_gallery_images($dossier,$files);
