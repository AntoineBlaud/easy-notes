<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">

    <ul class="list-group">
  <li class="list-group-item active">Cras justo odio</li>
  <li class="list-group-item">Dapibus ac facilisis in</li>
  <li class="list-group-item">Morbi leo risus</li>
  <li class="list-group-item">Porta ac consectetur ac</li>
  <li class="list-group-item">Vestibulum at eros</li>
</ul>

    <button id="start" onclick="startRecording()">Start</button>
    <button id="stop" onclick="stopRecording()">Stop</button>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="ajaxPost.js"></script>
<script>





    // store streaming data chunks in array
var record = false;


function activeRecording(){
    navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
    // store streaming data chunks in array
    const chunks = [];
    // create media recorder instance to initialize recording
    const recorder = new MediaRecorder(stream);
    // function to be called when data is received
    recorder.ondataavailable = e => {
      // add stream data to chunks
      chunks.push(e.data);
      console.log("okay");
      // if recorder is 'inactive' then recording has finished
    };
    setTimeout(() => {
        // this will trigger one final 'ondataavailable' event and set recorder state to 'inactive'
        //recorder.stop();
        sendRecording(chunks, recorder);
        if(record == true){activeRecording();}
        else{stream.getAudioTracks()[0].stop();}
    }, 7);
    // start recording with 1 second time between receiving 'ondataavailable' events
    recorder.start(1);
}).catch(console.error);

}

function stopRecording(){
    record = false;
    console.log("stopped");
}
function startRecording(){
    record = true;
    activeRecording();
}

async function sendRecording(chunks,recorder){
    console.log("sending");
    let blob = new Blob(chunks, { type: 'audio/webm' });
    var file = new File([blob], "test", {lastModified: 1534584790000});
    var fd = new FormData();
    fd.append('file', file);
    //fd.append('fname', 'test.wav');
    $.ajax({
        type: 'POST',
        url: 'uploadaudio.php',
        data: fd,
        processData: false,
        contentType: false
    }).done(function(data) {
        console.log(data);
});
        
}

function downloadRecording(chunks,recorder){
    // convert stream data chunks to a 'webm' audio format as a blob
    const blob = new Blob(chunks, { type: 'audio/webm' });
    // convert blob to URL so it can be assigned to a audio src attribute
    var url = URL.createObjectURL(blob),
    el = document.createElement("a");

    document.body.appendChild(el);
    el.style = "display:none";
    el.href = url;
    el.download = "audio.webm";
    el.click();
    URL.revokeObjectURL(url);

}


</script>


  </body>
</html>