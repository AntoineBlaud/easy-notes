// store streaming data chunks in array
var record = false
var compt = 0

function activeRecording () {
  navigator.mediaDevices
    .getUserMedia({ audio: true })
    .then(stream => {
      // store streaming data chunks in array
      const chunks = []
      // create media recorder instance to initialize recording
      const recorder = new MediaRecorder(stream)
      // function to be called when data is received
      recorder.ondataavailable = e => {
        // add stream data to chunks
        chunks.push(e.data)
        console.log('okay')
        // if recorder is 'inactive' then recording has finished
      }
      setTimeout(() => {
        // this will trigger one final 'ondataavailable' event and set recorder state to 'inactive'
        // recorder.stop();
        sendRecording(chunks, recorder)
        if (record == true) {
          activeRecording()
        } else {
          stream.getAudioTracks()[0].stop()
        }
      }, 13500)
      // start recording with 1 second time between receiving 'ondataavailable' events
      recorder.start(1)
    })
    .catch(console.error)
}

function stopRecording () {
  document.getElementById('micAnimation').style.animationPlayState = 'paused'
  record = false
  console.log('stopped')
}
function startRecording () {
  document.getElementById('micAnimation').style.animationPlayState = 'running'
  record = true
  activeRecording()
}

async function sendRecording (chunks, recorder) {
  console.log('sending')
  let blob = new Blob(chunks, { type: 'audio/webm' })
  var file = new File([blob], 'test' + compt, { lastModified: 1534584790000 })
  var fd = new FormData()
  fd.append('file', file)
  // fd.append('fname', 'test.wav');
  $.ajax({
    type: 'POST',
    url: '/uploadaudio',
    data: fd,
    processData: false,
    contentType: false
  }).done(function (data) {
    console.log(data)
  })
  compt += 1
}

function downloadRecording (chunks, recorder) {
  // convert stream data chunks to a 'webm' audio format as a blob
  const blob = new Blob(chunks, { type: 'audio/webm' })
  // convert blob to URL so it can be assigned to a audio src attribute
  var url = URL.createObjectURL(blob)

  var el = document.createElement('a')

  document.body.appendChild(el)
  el.style = 'display:none'
  el.href = url
  el.download = 'audio.webm'
  el.click()
  URL.revokeObjectURL(url)
}
