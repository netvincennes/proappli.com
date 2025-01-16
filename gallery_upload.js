// variables
var dropArea = document.getElementById('dropArea');
var canvas = document.querySelector('canvas');
var context = canvas.getContext('2d');
// var count = document.getElementById('count');
var destinationUrl = document.getElementById('url');
var result = document.getElementById('result');
var list = [];
var totalSize = 0;
var number_of_files = 0;
var file_current_number = 0 ;
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
    	  number_of_files = filelist.length ;
        if (!filelist || !filelist.length || list.length) return;

        totalSize = 0;
        totalProgress = 0;
        result.textContent = '';

        for (var i = 0; i < filelist.length && i < 5; i++) {
            list.push(filelist[i]);
            totalSize += filelist[i].size;
        }
        //alert(filelist.length + ' = filelist.length ' );
        //alert(list.length + ' = list.length ' );
        uploadNext();
    }

    // on complete - start next file
    function handleComplete(size) {
   //     totalProgress += size;
   //     drawProgress(totalProgress / totalSize);
        uploadNext();
//        file_current_number ++ ;
//        alert(' uploaded '+ file_current_number + ' / ' + number_of_files );
//        location.reload(true);
    }

    // update progress
    function handleProgress(event) {
    //    var progress = totalProgress + event.loaded;
    //    drawProgress(progress / totalSize);
    }

    // upload file
    function uploadFile(file, status) {
//        alert(file); // debug only
        // prepare XMLHttpRequest
        var xhr = new XMLHttpRequest();
//        xhr.open('POST', destinationUrl.value);
        xhr.open('POST', 'gallery_upload.php');

        xhr.onload = function() {
            result.innerHTML += this.responseText;
            handleComplete(file.size);
        };
        xhr.onerror = function() {
            result.textContent = this.responseText;
            handleComplete(file.size);
        };
        xhr.upload.onprogress = function(event) {
            handleProgress(event);
        }
        xhr.upload.onloadstart = function(event) {
        }

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
            // upload finished ?
            //alert(' upload finished ');
            //location.reload(true);
        }
        
        file_current_number ++ ;
//        alert(' uploaded '+ file_current_number + ' / ' + number_of_files );
        if ( file_current_number > number_of_files )
        {
//        location.reload(true);
          document.forms[0].javascript_action.value="uploadok";
          document.forms[0].submit();
          //url = "https://appli.pro/gallery.php?message=Upload:OK!";  
          //goto_url(url);     
        }
        
    }

    initHandlers();
})();

