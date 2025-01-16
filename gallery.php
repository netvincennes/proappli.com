<?php
// gallery.php
session_start();
// error_reporting(E_ALL); // debug
include 'configuration.php';
include 'common.php';
include 'session_start.php';
include 'gallery_code.php';
 
 
// Add this near the top of the file, after session_start()
$isSlideEditorSelection = isset($_GET['_action']) && $_GET['_action'] === 'selectforslideeditor';

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>
    <?php 
    //echo $application . " : ";
    echo "image gallery " ;
    ?>
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"> 
        
    <link rel="stylesheet" type="text/css" href="https://<?=$platform?>/common.css" />    
    
    <link href='//<?=$platform?>/<?php echo $application?>/styles/<?php echo $application?>.css' rel='stylesheet' type='text/css' >


<style type="text/css">
	
.svgzoom {
	transform: scale(0.16); 
    transform-origin: 0 0;	
}	
	
div.img
{
margin:13px;
/* border:1px solid #0000ff; */
/* height:auto; */
height: 80 ;
/* width:auto; */
width: 110 ;
float:left;
text-align:center;
}
div.img img
{
display:inline;
margin:3px;
border:1px solid #AAAAAA;
}

a{
color:initial;
}

div.img a:hover img
{
/* border:1px solid #0000ff; */
transform: scale(1.3);
-webkit-transform: scale(1.3); 
-moz-transform: scale(1.3); 
-o-transform: scale(1.3); 
-ms-transform: scale(1.3);
}
div.desc
{
text-align:center;
font-weight:normal;
width:120px;
margin:2px;
}
#dropArea {
    background-color: #DDDDDD;
    /* border: 3px dashed #000000; */
    float: left;
    /* font-size: 48px; */
    font-weight: bold;
    /* height: 530px; */
    /* line-height: 530px; */
    /* margin: 20px;  */
    position: relative;
    text-align: center;
    /* width: 250px;  */
}
#dropArea.hover {
    background-color: #CCCCCC;
}
#dropArea.uploading {
    background: #EEEEEE url(loading.gif) center 30% no-repeat;
}
#result .s, #result .f {
    font-size: 12px;
    margin-bottom: 10px;
    padding: 10px;

    border-radius:10px;
    -moz-border-radius:10px;
    -webkit-border-radius:10px;
}
#result .s {
    background-color: #77fc9f;
}
#result .f {
    background-color: #fcc577;
}
</style>

<script type="text/javascript" language='javascript' src='https://<?=$platform?>/_resources/libs/lazysizes/lazysizes.min.js'></script>
<script type="text/javascript" language="javascript" src="common.js"></script> 


<script type="text/javascript" >
var applicationPath = "<?php echo $application; ?>";
function debugLog(message) {
        var debugOutput = document.getElementById('debug-output');
        debugOutput.innerHTML += message + '<br>';
        debugOutput.scrollTop = debugOutput.scrollHeight;
    }
	
// variables
var dropArea = document.getElementById('dropArea');
var canvas = document.querySelector('canvas');
var context = canvas.getContext('2d');
// var count = document.getElementById('count');
var destinationUrl = document.getElementById('url');
var result = document.getElementById('result');
var list = [];
var totalSize = 0;
//var totalProgress = 0;

// main initialization
(function(){

    // init handlers
    function initHandlers() {
        dropArea.addEventListener('drop', handleDrop, false);
        dropArea.addEventListener('dragover', handleDragOver, false);
    }

    // draw progress
    function drawProgress(progress) {
    //    context.clearRect(0, 0, canvas.width, canvas.height); // clear context

    //    context.beginPath();
    //    context.strokeStyle = '#4B9500';
    //    context.fillStyle = '#4B9500';
    //    context.fillRect(0, 0, progress * 500, 20);
    //    context.closePath();

        // draw progress (as text)
    //    context.font = '16px Verdana';
    //    context.fillStyle = '#000';
    //    context.fillText('Progress: ' + Math.floor(progress*100) + '%', 50, 15);
    }

    // drag over
    function handleDragOver(event) {
        event.stopPropagation();
        event.preventDefault();

        dropArea.className = 'hover';
    }

    // drag drop
    function handleDrop(event) {
        event.stopPropagation();
        event.preventDefault();

        processFiles(event.dataTransfer.files);
    }

    // process bunch of files
    function processFiles(filelist) {
        if (!filelist || !filelist.length || list.length) return;

        totalSize = 0;
        totalProgress = 0;
        result.textContent = '';

        for (var i = 0; i < filelist.length && i < 5; i++) {
            list.push(filelist[i]);
            totalSize += filelist[i].size;
        }
        uploadNext();
    }

    // on complete - start next file
    function handleComplete(size) {
   //     totalProgress += size;
   //     drawProgress(totalProgress / totalSize);
        uploadNext();
    }

    // update progress
    function handleProgress(event) {
    //    var progress = totalProgress + event.loaded;
    //    drawProgress(progress / totalSize);
    }

    // upload file
    function uploadFile_old(file, status) {

			// prepare XMLHttpRequest
			var xhr = new XMLHttpRequest();
			xhr.open('POST', destinationUrl.value);
			xhr.onload = function() {
				result.innerHTML += this.responseText;
				handleComplete(file.size);
			};
			xhr.onerror = function() {
				result.textContent = this.responseText;
				handleComplete(file.size);
			};
			//xhr.upload.onprogress = function(event) {
			//	handleProgress(event); // erreur cannot read property of null
			//};
			xhr.upload.onloadstart = function(event) {
			};

			// prepare FormData
			var formData = new FormData();  
			formData.append('myfile', file); 
			xhr.send(formData);
	
    }

    // upload next file
    function uploadNext() {
        if (list.length) {
            // count.textContent = list.length - 1;
            dropArea.className = 'uploading';

            var nextFile = list.shift();
            if (nextFile.size >= 262144) { // 256kb
                result.innerHTML += '<div class="f">Too big file (max filesize exceeded)</div>';
                handleComplete(nextFile.size);
            } else {
                uploadFile(nextFile, status);
            }
        } else {
            dropArea.className = '';
        }
    }

    initHandlers();
})();
	
	
function show_url_menu() {
        document.getElementById('tab-upload').style.display = "none";
        document.getElementById('tab-create').style.display = "none";
        document.getElementById('tab-search').style.display = "none";
        document.getElementById('tab-url').style.display = "block";
        document.getElementById('menu_upload').style.background = "#ccc";
        document.getElementById('menu_create').style.background = "#ccc";
        document.getElementById('menu_search').style.background = "#ccc";
        document.getElementById('menu_url').style.background = "grey";
    }

function insertImageFromUrl() {
        var imageUrl = document.getElementById('image-url-input').value;
        if (imageUrl) {
            select(imageUrl);
        } else {
            alert("Veuillez entrer une URL d'image valide.");
        }
    }	
	
function slide_create()
{
//	alert('slide create');
	var url = "slide-edit.php/?_action=new&_subdir=<?=$subdir?>" ;
	goto_url(url);	
}	
	
// $imgwebsearch_content   imgweb_search()      imgwebsearch

function imgweb_search()
{
//	alert('imgweb_search ! ');
	document.forms[0].javascript_action.value="imgwebsearch";
    document.forms[0].submit();
}
	
//function cliparts_search()
//{
//	alert('cliparts ! ');
//	document.forms[0].javascript_action.value="cliparts";
//    document.forms[0].submit();
//}	

function checkbox_click()
{
// alert('click on checkbox !');
are_checkbox_checked();
}

function are_checkbox_checked()
{	
        var atleastonechecked = false;
	var elem = document.getElementById('fileForm').elements;
        for(var i = 0; i < elem.length; i++)
        {
            //str += "<b>Type:</b>" + elem[i].type + "&nbsp&nbsp";
	    // alert(elem[i].type);
		if (elem[i].type=='checkbox')
		{ // alert(elem[i].checked);
			if (elem[i].checked) { atleastonechecked=true;}
		}
            //str += "<b>Name:</b>" + elem[i].name + "&nbsp;&nbsp;";
            //str += "<b>Value:</b><i>" + elem[i].value + "</i>&nbsp;&nbsp;";
            //str += "<BR>";
		
        } 
	if (atleastonechecked==true)
	{
		document.getElementById('button_delete').style.display = 'inline';
		document.getElementById('button_copy').style.display = 'inline';
		document.getElementById('button_move').style.display = 'inline';
		document.getElementById('button_create_dir').style.display = 'none';
		document.getElementById('button_draw').style.display = 'none';					
		var tmp = "files to <select name='move_folder'>"+folder_list()+"</select>";
		// alert(tmp);	
		document.getElementById("move_folder_span").innerHTML = tmp;			
	}
	else
	{
		document.getElementById('button_delete').style.display = 'none';
		document.getElementById('button_copy').style.display = 'none';
		document.getElementById('button_move').style.display = 'none';
		document.getElementById("move_folder_span").innerHTML = "";
		document.getElementById('button_create_dir').style.display = 'inline';	
		document.getElementById('button_draw').style.display = 'inline';					
	}
}

function filenamechanged()
{
	upload_file();
}

function select(url)
{              
	debugLog('Select function called with URL: ' + url);
	
	var field = "<?PHP echo $field;?>";
	var wysiwyg_field = "<?PHP echo $wysiwyg_field;?>"; 
//  alert(wysiwyg_field); 
    var isSlideEditorSelection = <?php echo json_encode($isSlideEditorSelection); ?>;

    debugLog('field value: ' + field);
    debugLog('wysiwyg_field value: ' + wysiwyg_field);
    debugLog('isSlideEditorSelection: ' + isSlideEditorSelection);

	var url_array = url.split('.');
	var last_split_number = url_array.length - 1 ;
	var file_extension = url_array[last_split_number];
	
    debugLog('File extension detected: ' + file_extension);	
	
	var len = url.length ;
    var last10car = url.substring(len-10);
	// alert(file_extension); // test for debug
	
	 if (isSlideEditorSelection) {
            // For slide editor selection
			debugLog('Entering slide editor selection mode');
			//debugLog('Attempting to send postMessage to opener');
			//alert('Attempting to send postMessage to opener');
            try {
                if (window.opener && !window.opener.closed) {
                    // Construct the full URL
                    var fullUrl = url.startsWith('http') ? url : 'https://proappli.com/' + applicationPath + '/images/' + url;
                    window.opener.postMessage({type: 'insertImage', url: fullUrl}, '*');
                    debugLog('postMessage sent to opener with URL: ' + fullUrl);
					//alert('postMessage sent to opener with URL: ' + fullUrl);
                    setTimeout(function() {
                        window.close();
                    }, 500); // Close after a short delay to ensure the message is sent
                } else {
                    debugLog('Error: Opener window is not available');
					//alert('Error: Opener window is not available');
                }
            } catch (error) {
                debugLog('Error: ' + error.message);
				//alert('Error: ' + error.message);
            }

        } 
	
	else if (field != "")
		{
			debugLog('Entering field selection mode');
//		alert(url);
//    alert('select() , galleryreturn and self.close()');
//    window.close();
//		window.opener.galleryreturn('<?PHP echo $field;?>',url);
		// window.opener.copy_value_to_elementid(url,'<?PHP echo $field;?>');
		// self.close();
//    alert('should close windows now ! ');

        try {
            if (!window.opener) {
                debugLog('Error: No opener window found');
                return;
            }
            if (typeof window.opener.galleryreturn !== 'function') {
                debugLog('Error: galleryreturn function not found in opener');
                return;
            }
            window.opener.galleryreturn('<?PHP echo $field;?>', url);
            window.close();
        } catch (error) {
            debugLog('Error in image selection: ' + error.message);
        }


		}
	else if (wysiwyg_field != "")
		{
		debugLog('Entering wysiwyg selection mode');
		// alert(url);
//      alert('select() , galleryreturn and self.close()');
//		window.close();
//		window.opener.galleryreturn_to_htmleditor(wysiwyg_field,url);
//      alert('should close windows now ! ');

        try {
            if (!window.opener) {
                debugLog('Error: No opener window found');
                return;
            }
            if (typeof window.opener.galleryreturn_to_htmleditor !== 'function') {
                debugLog('Error: galleryreturn_to_htmleditor function not found in opener');
                return;
            }
            window.opener.galleryreturn_to_htmleditor(wysiwyg_field, url);
            window.close();
        } catch (error) {
            debugLog('Error in image selection: ' + error.message);
        }

		}    
	else
	{       
	debugLog('Entering image editor mode');
//		if (last10car=='.slide.svg')
//		{
//		var svgediturl = "slide-edit.php/?_file="+url ;
//		goto_url(svgediturl);
//		}
		if (file_extension == "svg") {
    // https://appli.pro/_resources/libs/svg-edit-2.8.1/editor/appli.pro-svgeditor.php?file=../boutons/office1.svg&url=../../../../netvincennes/images/boutons/office1.svg
//		var dir = "../../applications/" + "<?=$application?>" + "/images/";
//		var dir = "../../../" + "<?=$application?>" + "/images/";	
      
//			var dir = "";  
//			if (url.substring(0,4)=='http')
//			{ 
//			dir = "";
//			}
//			else
//			{
//				dir = "../../../../" + "<?=$application?>" + "/images/";		    	
//			}
//				var svgediturl = "_resources/libs/svg-edit-2.8.1/editor/appli.pro-svgeditor.php?file="+url+"&url=" + dir + url ;    

				var svgediturl = "https://proappli.com/slide-edit.php?_url=images/" + url ;	

				goto_url(svgediturl);
		}
		else {
		// .jpg , .png .gif or other ...
//		dir_and_file = "<?=$application?>" + "/images/" + url;

		var image_edit_url = "https://proappli.com/image-edit.php?link="+url+"&_url="+url+"&_subdir=images&_finename=" ;	
		goto_url(image_edit_url);		
		}		
	}
}

function svg_edit()
{
//    var url = "libs/svg-edit-2.5.1/svg-editor.php?_application=<?=$application?>";
//    var url = "libs/svg-edit-2.6/svg-editor.php?_application=<?=$application?>&_subdir=<?=$subdir?>";
//    var url = "libs/svg-edit-2.7.1/?_application=<?=$application?>&_subdir=<?=$subdir?>";
    var link = "_resources/libs/svg-edit-2.7.1/?_subdir=<?=$subdir?>";

//    var link = "libs/svg-edit-2.7.1/;
    goto_url(link);
}

function copy_files()
{
    document.forms[0].javascript_action.value="copy";
    document.forms[0].submit();
}

function move_files()
{
    document.forms[0].javascript_action.value="move";
    document.forms[0].submit();
}

function delete_files()
{
  if (confirm("Delete selected files ?")) { // Clic sur OK
// alert('should delete files');
    document.forms[0].javascript_action.value="delete";
    document.forms[0].submit();
  }
}

function create_dir()
{
var filename = prompt("Dir name ?", "newdir");
document.forms[0].filename.value=filename;
document.forms[0].javascript_action.value="create_dir";
document.forms[0].submit();
}

function subdir(dir)
{
	if ("<?=$subdir?>"!="")
	{
	document.forms[0].subdir.value = "<?=$subdir?>"+'/'+dir ; // bug ici ...
	}
	else
	{
	document.forms[0].subdir.value = dir ; // bug ici ...	
	}	

document.forms[0].javascript_action.value="subdir_change";
document.forms[0].submit();
}


function upload_file()
{
document.forms[0].javascript_action.value="upload";
document.forms[0].submit();
}

function save_album()
{
document.forms[0].javascript_action.value = "save_album" ;
document.forms[0].submit();
}

function album_onchange()
{
  if ( document.forms[0].album.value == "new" )
  {
	  // show save button
	document.getElementById('albumsavebutton').style.display = 'inline';
	document.getElementById('albumdisplaybutton').style.display = 'none';	
	document.getElementById('album_title').style.display = 'none';		
  }
  else if (document.forms[0].album.value == "" )
  {
	document.getElementById('albumsavebutton').style.display = 'none';	  
	document.getElementById('albumdisplaybutton').style.display = 'none';	
	document.getElementById('album_title').style.display = 'none';			
  }
  else
  {
//  document.getElementById('albumsavebutton').style.display = 'inline';
//  document.getElementById('albumdisplaybutton').style.display = 'inline';	
//  document.getElementById('album_title').style.display = 'inline';	
  document.forms[0].javascript_action.value = "album_onchange" ;
  document.forms[0].submit();
  }
}


function select_album()
{
// if album != 'new'
  if ( document.forms[0].album.value != "new" )
  {
//  alert('display album pics');
  document.forms[0].javascript_action.value="select_album";
  document.forms[0].submit();
  }
}

function select_all_or_none(thiselement)
{
var myform = document.forms[0];
 if (thiselement.type == 'checkbox' && thiselement.checked)
 {
// 	alert("check all ! ");
  for (i=0 ; i<= myform.length-1 ; i++)
  {
  if ( myform[i].type == 'checkbox' ) {
       // alert(myform[i].checked);
       try { myform[i].checked = true ; } catch(e) { }
       }
  
  }
 }
 
 if (thiselement.type == 'checkbox' && thiselement.checked==false)
 {
// 	alert("uncheck all ! ");
  for (i=0 ; i<= myform.length-1 ; i++)
  {
  if ( myform[i].type == 'checkbox' ) {
      // alert(myform[i].checked);
      try { myform[i].checked = false ; } catch(e) {  }
    }
  }
 } 
  
}


function show_url_menu()
{
document.getElementById('tab-url').style.display = "block"; 	
document.getElementById('tab-upload').style.display = "none"; 
document.getElementById('tab-create').style.display = "none"; 
document.getElementById('tab-search').style.display = "none"; 
document.getElementById('menu_upload').style.background = "grey"; 
document.getElementById('menu_create').style.background = "#ccc"; 
document.getElementById('menu_search').style.background = "#ccc"; 
}

function show_upload_menu()
{
document.getElementById('tab-url').style.display = "none"; 	
document.getElementById('tab-upload').style.display = "block"; 
document.getElementById('tab-create').style.display = "none"; 
document.getElementById('tab-search').style.display = "none"; 
document.getElementById('menu_upload').style.background = "grey"; 
document.getElementById('menu_create').style.background = "#ccc"; 
document.getElementById('menu_search').style.background = "#ccc"; 
}

function show_create_menu()
{
document.getElementById('tab-url').style.display = "none"; 	

document.getElementById('tab-upload').style.display = "none"; 
document.getElementById('tab-create').style.display = "block";
document.getElementById('tab-search').style.display = "none"; 
document.getElementById('menu_create').style.background = "grey"; 
document.getElementById('menu_upload').style.background = "#ccc"; 
document.getElementById('menu_search').style.background = "#ccc"; 
}

function show_search_menu()
{
document.getElementById('tab-url').style.display = "none"; 	

document.getElementById('tab-upload').style.display = "none"; 
document.getElementById('tab-create').style.display = "none";
document.getElementById('tab-search').style.display = "block";  
document.getElementById('menu_upload').style.background = "#ccc"; 
document.getElementById('menu_create').style.background = "#ccc"; 
document.getElementById('menu_search').style.background = "grey"; 
}




</script>
</head>
<body>

<?php
// uniquement si 
?>

      <form class='screen_computer' action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="fileForm" id="fileForm" enctype="multipart/form-data">
        <h3 style='opacity:1' > <a href="gallery.php">
        <!--
        <img src='_resources/images/gallery.png' width='32' height='32' />
        -->
        
        <img src='_resources/images/files-folder-images.png' width='45' height='45' />
        
         Images </a></h3>

        <h3><span class='noprint' style='background-color: yellow; font-style: italic;'><?php echo $message; ?></span></h3>


<!--        <input type="submit" name="delete" value="Delete">
-->        
<button id='button_delete' style='display:none' class="normalbutton" type="button" title='Delete' onClick="delete_files()">
<img src="_resources/images/delete_files.png" width="32" height="32" alt="Delete" border="0"/>
</button> 
 
<button id='button_copy' style='display:none' class="normalbutton" title='Copy to' type="button" onClick="copy_files()">
<img src='_resources/images/copy.png' title='copy to' alt='copy to' width='32'  height='32' border='0' />
</button>

<button id='button_move' style='display:none' class="normalbutton" title='Move to' type="button" onClick="move_files()">
<img src='_resources/images/files_folder_move.png' alt='Move to' width='32'  height='32' border='0' />	
</button>

<span id='move_folder_span'></span>




        <div id='menu'>
        <ul style="border:1px solid #ccc !important; list-style-type:none;overflow:hidden;padding:0;margin:0;background-color:#FFFFFF !important">
			<li id='upload_menu' style="border-top-left-radius: 6px; border-top-right-radius: 6px; color:black; background: rgb(204, 204, 204) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204) ! important; float: left; padding: 3px; margin: 2px;"><a href='javascript:show_upload_menu()'><?=$traduction[5];//upload?></a></li>  
			<li id='create_menu' style="border-top-left-radius: 6px; border-top-right-radius: 6px; color:black; background: rgb(204, 204, 204) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204) ! important; float: left; padding: 3px; margin: 2px;"><a href='javascript:show_create_menu()'><?=$traduction[35];//Create New file ?></a></li>   
			<li id='search_menu' style="border-top-left-radius: 6px; border-top-right-radius: 6px; color:black; background: rgb(204, 204, 204) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204) ! important; float: left; padding: 3px; margin: 2px;"><a href='javascript:show_search_menu()'><?=$traduction[12];// Search ?></a></li> <li id='menu_url' style="border-top-left-radius: 6px; border-top-right-radius: 6px; color:black; background: rgb(204, 204, 204) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204) ! important; float: left; padding: 3px; margin: 2px;"><a href='javascript:show_url_menu()'>URL</a></li>      
        </ul>
        </div>
        <div id='tabs'>
        		<div id='tab-upload' style="display:block"> 
        		

        <p><input onchange='filenamechanged()' name="upfile[]" type="file" multiple>
        <!--
        (test : <input type="file" accept="image/*" capture="camera" id="capture">  )
        -->
<!--
        <input type="submit" name="submitBtn" value="Upload">

        <button class="normalbutton" type="button" onClick="upload_file()">
        <img src="multimedia/icons/cloud_upload.png" width="32" height="32" 
        alt="Upload" border="0"/> Upload
        </button> 
--> 
                		
        		</div>

        		<div id='tab-create' style="display:none">
        		
        		

<button id='button_draw' class="normalbutton" type="button" title='Draw'  onClick="svg_edit()">
<img src="_resources/images/svgedit.png" width="32" height="32" alt="Drawing editor" border="0"/>
</button>

<button id='button_slide' class="normalbutton" type="button" title='New slide'  onClick="slide_create()">
<img src="_resources/images/slide_create.png" width="32" height="32" alt="Slide editor" border="0"/>
</button>

<button id='button_create_dir' class="normalbutton" type="button" title='create folder' onClick="create_dir()">
<img src='_resources/images/files_folder_create.png'  alt='Directory' width='32'  height='32' border='0' />
</button>        		
        		
        		</div>
        		<div id='tab-search' style="display:none">
        		
<?php
//if ($javascript_action=="cliparts")
//{  
//echo "<input name='cliparts' id='cliparts' size='8' placeholder='clipart' value='".$cliparts."' />";
//	}
echo "<input title='web Search' name='imgwebsearch' id='imgwebsearch' size='8' placeholder='web search' value='" ;
echo $imgwebsearch."' />";

?>
 
<!-- <button id='button_cliparts' class="normalbutton" type="button" title='Search Cliparts'  onClick="cliparts_search()">
<img src="_resources/images/cliparts.png" width="32" height="32" alt="Search Cliparts" border="0"/>
</button>
-->
<button id='button_imgwebsearch' class="normalbutton" type="button" title='Search images'  onClick="imgweb_search()">
<img src="_resources/images/search-icon.png" width="32" height="32" alt="Img web search" border="0"/>
</button>
        		
        		
        		
        	</div>
			
			<div id='tab-url' style="display:none">
            <input type="text" id="image-url-input" placeholder="Entrez l'URL de l'image" style="width: 300px;">
            <button onclick="insertImageFromUrl()">Insérer l'image</button>
        </div>
			
			
        </div>

        </td></tr>
       
        
        <span>
        Album : <select name='album' onchange='album_onchange()'>
        <option></option>
        <option value='new'>New</option>
<?php
        foreach($album_filenames as $album_filename)
          {
            if ( ($album_filename != ".") AND ($album_filename != "..")   )
              {
                if ($album_filename==$_REQUEST['album'].".xml") {$tmp_selected_option=" selected='selected' ";} else {$tmp_selected_option='';}
                echo "<option $tmp_selected_option >".substr($album_filename, 0, -4)."</option>\r\n";    // pour enlever .xml � la fin
              }
          }
?>        
        </select> 
        <!-- <input name='add_to_album' value='Add to album' type='submit' />  -->
 
<?php
//     if ( ($javascript_action=="select_album") and ($_REQUEST['album']!="") and ($_REQUEST['album']!="new") ) {
     if ( ($album=="") or ($album=="new") ) {

     echo "   <button id='albumsavebutton' class='normalbutton' style='display:none' type='button' onClick='save_album()'>
        Save       
        </button> ";
        
?>        

<?php
     }
     else
     {   
		 echo "<input id='album_title' name='album_title' size='10' placeholder='album title' value='".$simplexml_album->head->title."' />";  
?>

        <button id='albumsavebutton' class="normalbutton" type="button" onClick="save_album()">
        Save       
        </button>

        
        <button id='albumdisplaybutton' class="normalbutton" type="button" onClick="select_album()">
        Display       
        </button>

<?php
     }
?>
 
<!--        <input name='select_album' value='Select' type='submit' />    -->
        </span>
        
        <?php
         if ($subdir!="")
         {
          echo "<p><img src='_resources/images/files-folder.png' alt='Directory' width='32'  height='32' /> <a href='gallery.php'>images</a>/$subdir</p>";
         }
        ?>  
                       
        <fieldset name='field'><legend><input type='checkbox' onchange='select_all_or_none(this)' /> Images  </legend>
        <span width='100%' id='dropArea'>
			<div width='100%' class='info' >
        
<?php    
//    print_r($files);  
    if ($javascript_action=='imgwebsearch')
    {
		//print_r($cliparts_xml); // debug only
		//echo "<hr />";
		echo $imgwebsearch_content ;
		// $imgwebsearch_content   imgweb_search()      imgwebsearch
//		foreach($cliparts_xml->channel->item as $item)
//		{ 
//			echo "<a href='javascript:select(\"".$item->enclosure['url']."\")'>";
//			// echo $item->title."<br />";
//			echo "<img height='64' src='".$item->thumbnail['url']."' />";
//			echo "</a>\r\n";
//		}
	}
    else
    {

    foreach ($display_files as $key =>  $file)
    {
      // echo $dir_and_file."<br />"; // debug     
      $dir_and_file = $dossier.$file ;
      if (
           is_dir($dir_and_file)
           and ($file!='albums')
         )
      {
//      echo "<span style='display:table'>";
      echo "<span>";

      echo "<span style='display:table-row'>";  
      echo "<span style='display:table-cell'>";            
//      echo "<tr><td>";
      //echo "<div>";
      echo "<a href='javascript:subdir(\"".$file."\")'>";
//      echo "<img src='multimedia/icon_files.png' height='80' title='$file' />";
      echo "<img src='_resources/images/files-folder.png' height='80' title='$file' />";
      echo "</a>";
      echo "</span>"; //cell
      echo "</span>"; //row      
//      echo "<div>".substr($file,0,8)."</div>";
      //echo "<br />";
      //echo "</div><div>";
//      echo "</td></tr><tr><td>";
      echo "<span style='display:table-row'>";  
      echo "<span style='display:table-cell;text-align:center'>";   
      echo "<a href='javascript:subdir(\"".$file."\")'>";
      echo $file ;
      echo "</a>";
      //echo "</div>";
//      echo "</td></tr>";
      echo "</span>"; //cell
      echo "</span>"; //row
      echo "</span>"; // table
      $folder_list .= $file."," ; 
      }
    }
    //echo "<hr />\r\n";

    foreach ($display_files as $key =>  $file)
    {
      $dir_and_file = $dossier.$file ;
//      if (
//           is_dir($dir_and_file)
//           and ($file!='albums')
//         )
//      {
//      echo "<a href='javascript:subdir(\"".$file."\")'>";
//      echo "<img src='multimedia/icon_files.png' height='80' title='$file' />";
//      echo "<img src='multimedia/icons/icon_folder.png' height='80' title='$file' />";
//      echo "<div>".substr($file,0,8)."</div>";
//      echo "</a>";
//      }
      if (
             ($file !='.') 
         and ($file!='..') 
         and ($file!='albums')
         and ($file!='.htaccess')         
         and !is_dir($dir_and_file)
         ) 
      {


      try {
		if ( isset($display_url) AND ( isset($display_url[$key]) ) AND ( isset($display_title[$key])  ) ) {      
        $url = $display_url[$key];    // ERROR VARIABLE NOT DEFINED ! error_log
        $url = urldecode($url);        // ERROR VARIABLE NOT DEFINED ! error_log
        $title = $display_title[$key];   // ERROR VARIABLE NOT DEFINED ! error_log
        }
      }
      catch (Exception $e) {
        echo 'Error : Exception : ',  $e->getMessage(), "\n";
        $url = "";
        $title = "";
        }

// Ensure $selected_files is defined and initialized as an array
$selected_files = isset($selected_files) && is_array($selected_files) ? $selected_files : [];


      // print_r($selected_files); // debug
      //echo "<br />";
      //echo "is this file in : ".$file ;
      //echo "<br /><hr />" ; 
    	if (in_array($file, $selected_files)) 
	        { $tmp_checked = " checked='checked' "; }
        	else
	        {  $tmp_checked = ""; }

//      $imagesize = getimagesize($file);
//      $width = 100 ;
      $height = 80 ;
//      $height = $imagesize[1] * 100 / $imagesize[0] ;
      
      echo "\r\n<div class='img'>\r\n";
//      echo "<a href='javascript:window.opener.galleryreturn(\"".$file."\")'>";



      if ( ($javascript_action=="select_album") and ($_REQUEST['album']!="") and ($_REQUEST['album']!="new") ) 
	    {      
      	  echo "<div class='url'>\r\n";
//	echo $key;
	        echo "<input name=title[] value='$title' size='8' alt='Title' placeholder='Title' /></div>\r\n";

        	echo "<input name=url[] value='$url' size='8' alt='URL' placeholder='URL' /></div>\r\n";
      // checkbox en hidden :

//      echo "<div class='desc'><input name=file[] value='$file' type=checkbox $tmp_checked /></div>\r\n";
         echo "<div class='desc'><input onclick='checkbox_click()' name=file[] type='hidden' value='$file' /></div>\r\n";

      }
	    else
	    { 
		      echo "<div class='url'>\r\n";
//	        echo $key;
//		echo "<input name=url[]  value='$key<sep>$url' placeholder='URL' size='8' />";
//		echo "<input name=title[] value='$key<sep>$title' type='hidden' />";
		      echo "<input name=title[] value='$key|$title' type='hidden' />";

//		echo "<input name=url[] value='$key<sep>$url' type='hidden' />";
	      	echo "<input name=url[] value='$key|$url' type='hidden' />";

	      	echo "</div>\r\n";
//      echo "<div class='desc'><input name=file[] value='$key<sep>$file' type=checkbox $tmp_checked /></div>\r\n";
          echo "<div class='desc'><input name=file[] onclick='checkbox_click()' value='$key|$file' type=checkbox $tmp_checked /></div>\r\n";

	    }



      if ($subdir!="")
      {
      echo "<a href='javascript:select(\"".$subdir."/".$file."\")'>";      
      }
      else
      {
      echo "<a href='javascript:select(\"".$file."\")'>";      
      }

//      echo "<img src='$dir_and_file' width='$width' height='$height' /></a>";
//      echo "<img alt='$file' src='$dir_and_file' width='$width' /></a>";
      $file_extension = substr(strrchr($file, '.'), 1); // last '.'
 //     echo  $file_extension  ;
      if ( ($file_extension == 'svg') OR ($file_extension =='SVG') )
      {
//      echo "<span style='border-style:groove;border-width:2px'>";
//      echo "SVG <object data='$dir_and_file' height='$height'  type='image/svg+xml' >";
//      echo "<img alt='$file' src='$dir_and_file' height='$height' />";
//      echo "</object></span>";
      $tmp_svg_content = file_get_contents("$dir_and_file"); 
//      echo "<span style='border-style:groove;border-width:2px'>";
      echo "<span style=''>";

//      echo $file ;
      echo "<svg class='svgzoom' width='650px' height='500px' version='1.1'
     xmlns='http://www.w3.org/2000/svg' xmlns:xlink= 'http://www.w3.org/1999/xlink'  >";
      echo $tmp_svg_content ;
      echo "</svg>";  
            
//      echo "<iframe class='svgframe' src='$dir_and_file' width='100' height='80' sandbox> ";
//            echo "<img alt='$file' src='$dir_and_file' height='$height' />";
//      echo "</iframe>";
      
      echo "</span>";  
      }
      else
      {
      echo "<img alt='$file' src='$dir_and_file' height='$height'  class='lazyload'  />";
      }
//      echo "<img alt='$file' src='$dir_and_file' height='$height' />";
      
 //     echo "<iframe src='$dir_and_file' frameborder='0' scrolling='no'  height='$height' sandbox>
//      <img src='$dir_and_file' alt='$file' height='$height' />
//      </iframe>";
      
      // echo "<object data='".$dir_and_file."' width='50' height='".$height."' type='image/svg+xml'></object>";
//       echo "<embed src='".$dir_and_file."' width='50' height='".$height."' type='image/svg+xml'></object>";
//      echo "<svg alt='$file' src='$dir_and_file' height='$height' />";
      echo "</a>";      

//      echo "<div class='desc'><input name=file[] value='$file' type=checkbox $tmp_checked /></div>\r\n";
      echo "</div>\r\n";
      }  // if file is not .htaccess or . or .. 
    }  // for each

//    if (isset($_REQUEST['delete_old'])){

//    echo "<br /><h1>should delele files ...</h1>";
//    foreach($_REQUEST['file'] as $filetodelete)
//    {
//    $dir_and_filetodelete = $dossier . $filetodelete ;
//    $delete_result = unlink($dir_and_filetodelete);
//    echo $delete_result ; 
//    $message = "OK, files deleted !";
//    }
//    print_r($_REQUEST['file']);
//    exit;
//    header("Location: gallery.php?message=$message");  
//    }


    if (isset($_REQUEST['submitBtn_old'])){

//     $dossier = 'upload/';

     $fichier = basename($_FILES['upfile']['name']);
     $fichier_array = explode(".", $fichier);
// bug si fichier s'apelle toto.exe.jpg ! 
//     print_r($fichier_array );
//   make it lowercase () minuscule )
     $fichier_array[1] = strtolower($fichier_array[1]);
     echo "type : ".$fichier_array[1]." <br />";     
     $type_array = explode("/",$_FILES['upfile']['type']);
//     if ($type_array[0]=='image') {
     if ( 
        ($fichier_array[1]=='jpg') OR ($fichier_array[1]=='jpeg') 
     OR ($fichier_array[1]=='png') OR ($fichier_array[1]=='gif') 
     OR ($fichier_array[1]=='svg') OR ($fichier_array[1]=='ico') OR ($fichier_array[1]=='webp') //caro
     ) 
     {
//          $dossier = "applications/".$application."/images/";
          $dossier = $application."/images/";

     }
     else
     {
     echo "<p>File type ".$_FILES['upfile']['type']." not autorized</p>";
     exit;
     }

     echo "<p>type:".$type_array[0]."</p>";
     $pieces = explode(" ", $pizza);
     if(move_uploaded_file($_FILES['upfile']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que �a a fonctionn�...
     {
          $message = "UPLOAD OK !";
//         echo '<p>Upload effectu� avec succ�s !</p>';  
//          header("Location: gallery.php?message=$message");          
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          echo '<p>Echec de l\'upload !</p>';
          echo "<p>".$_FILES['upfile']['error']."</p>";
                    
     }
    }
} // else action == cliparts
?>
            <div id="result">        
<?php                    

//			display_list_files($dossier,$files);        
?>
            
            </div>
            
            <canvas width="100%" height="20"></canvas>
</div> <!-- class = info -->
</span> <!-- dropArea -->
</fieldset>

<div id="debug-output"></div>


<script src='gallery_upload.js'>
</script>
<?php    
   echo "<script>\r\n";
   echo "function folder_list() \r\n";
   echo "{ \r\n";
   echo "var text = ''; \r\n";
   $folder_list_array = explode(",",$folder_list);
   foreach ( $folder_list_array as $folder )
   {
   echo "text += '<option>".$folder."</option>'; \r\n";
   }
   //echo "// ".$folder_list;
   echo "return text; \r\n" ;
   echo "} \r\n";
   echo "</script>\r\n" 
?>
<input  type='hidden'  name='javascript_action' />
<input type='hidden' name='filename' />
<input type='hidden' name='field' value="<?=$field?>" />
<input type='hidden' name='wysiwyg_field' value="<?=$wysiwyg_field?>" />

<input type='hidden' name='subdir' value="<?=$subdir?>" />

</form>
</body>  
</html>
