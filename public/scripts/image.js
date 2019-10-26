var split
var cropper
var htmlcropper
var im_index = 0
var im_array = []
var im_current

function downloadCapturedImages() {
    $.ajax({
        type: 'POST',
        url: '/getcapturedimages',
        data: '',
        processData: false,
        contentType: false
    }).done(function (data) {

        split = data.split('::')
        if (split[0] != '') {
            printCapturedImages(data)
            initCropper()
        } else {
            alert("No images uploaded")
        }
    })
}

function getNewImages() {
    downloadCapturedImages()
}
/**
 * 
 * @param {string} data 
 */
function printCapturedImages(data) {
    split = data.split('::')
    /** @type {HTMLElement} */
    var slide = document.getElementById('slide')
    for (imageName in split) {
        var div = document.createElement('div')
        div.classList = 'img-wrap'

        var span = document.createElement('span')
        span.classList = 'close'
        span.innerHTML = '&times'

        var im = document.createElement('img')
        im.src = '/uploaded_images/' + split[imageName]
        im.classList = 'img-thumbnail rounded float-left'
        im.onclick = function () {
            cropper.destroy()
            setCropper(this)
        }
        im_array.push(im)

        im.style.width = '200px'
        im.style.height = '180px'
        div.append(span)
        div.append(im)
        slide.append(div)
    }
}

function initCropper() {
    document.getElementById('toolbar').style.display = 'block'
    setCropper(im_array[0])
}
/**
 * 
 * @param {HTMLElement} im 
 */
function setCropper(im) {
    htmlcropper = document.getElementById('cropper')
    // im_c is a cloned img put in cropper
    im_c = im.cloneNode(true)
    im_c.classList = ''
    im_c.style = 'max-height 800px; max-width: 800px;'
    if (typeof im_current !== 'undefined') { im_current.parentNode.removeChild(im_current) }

    im_current = im_c
    htmlcropper.append(im_c)

    cropper = new Cropper(im_c, {
        crop(event) {
            console.log(event.detail.x)
            console.log(event.detail.y)
            console.log(event.detail.width)
            console.log(event.detail.height)
            console.log(event.detail.rotate)
            console.log(event.detail.scaleX)
            console.log(event.detail.scaleY)
        }
    })
}

function getCroppedCanvas() {
    /** @type {HTMLElement} */
    cropped_container = document.getElementById('cropped')
    im = cropper.getCroppedCanvas()

    // Create container
    var cropped_div = document.createElement('div')
    cropped_div.classList = 'list-group-item'
    cropped_div.setAttribute('id', 'cropped_div' + im_index)

    var cropped_input_div_1 = document.createElement('div')
    cropped_input_div_1.classList = 'form-check form-check-inline'

    var cropped_input_div_2 = document.createElement('div')
    cropped_input_div_2.classList = 'form-check form-check-inline'

    var cropped_input_div_3 = document.createElement('div')
    cropped_input_div_3.classList = 'form-check form-check-inline'

    // Create radios
    var cropped_input_1 = document.createElement('input')
    cropped_input_1.setAttribute('type', 'radio')
    cropped_input_1.innerHTML = 'text'
    cropped_input_1.classList = 'form-check-input'
    cropped_input_1.setAttribute('name', 'im' + im_index)
    cropped_input_1.value = 'text'

    var cropped_input_2 = document.createElement('input')
    cropped_input_2.setAttribute('type', 'radio')
    cropped_input_2.innerHTML = 'text'
    cropped_input_2.classList = 'form-check-input'
    cropped_input_2.setAttribute('name', 'im' + im_index)
    cropped_input_2.value = 'shape'

    var cropped_input_3 = document.createElement('input')
    cropped_input_3.setAttribute('type', 'radio')
    cropped_input_3.innerHTML = 'text'
    cropped_input_3.classList = 'form-check-input'
    cropped_input_3.setAttribute('name', 'im' + im_index)
    cropped_input_3.setAttribute('checked', 'checked')
    cropped_input_3.value = 'other'

    // Create labels

    var label_input_1 = document.createElement('label')
    var label_input_2 = document.createElement('label')
    var label_input_3 = document.createElement('label')

    label_input_1.classList = 'form-check-label'
    label_input_2.classList = 'form-check-label'
    label_input_3.classList = 'form-check-label'

    label_input_1.innerHTML = 'Text'
    label_input_2.innerHTML = 'Graphic'
    label_input_3.innerHTML = 'Other'

    // Create delete button
    var close_button = document.createElement('button')
    close_button.classList = 'button close'
    close_button.setAttribute('remove', im_index)
    close_button.style = 'position: absolute;border-color: white;right: 20px;'
    var close_icon = document.createElement('i')
    close_icon.classList = 'fas fa-times'

    close_button.onclick = function () {
        removeId = 'cropped_div' + this.getAttribute('remove')
        removeE = document.getElementById(removeId)
        removeE.parentNode.removeChild(removeE)
    }

    /**  Create Canvas Elements */
    /**@type {HTMLCanvasElement} */
    var canvas = cropper.getCroppedCanvas()
    canvas.style.maxHeight = "90px";
    canvas.style.maxWidth = "160px"
    cropped_div.append(canvas)

    /** Join Elements */
    close_button.append(close_icon)
    cropped_input_div_1.append(cropped_input_1)
    cropped_input_div_2.append(cropped_input_2)
    cropped_input_div_3.append(cropped_input_3)

    cropped_input_div_1.append(label_input_1)
    cropped_input_div_2.append(label_input_2)
    cropped_input_div_3.append(label_input_3)

    cropped_div.append(cropped_input_div_1)
    cropped_div.append(cropped_input_div_2)
    cropped_div.append(cropped_input_div_3)
    cropped_div.append(close_button)
    cropped_container.append(cropped_div)

    // increment
    im_index = im_index + 1
}


function saveCanvas() {

    console.log("starting")

    /**@type {array} */
    var result = new Array()

    /** @type {HTMLElement} */
    var cropped_container = document.getElementById('cropped');

    console.log(result)
    // List images
    if (cropped_container.hasChildNodes()) {

        /** @type {array.<HTMLElement>} */
        var cropped_container_childrens = cropped_container.childNodes;

        for (var i = 0; i < cropped_container_childrens.length; i++) {
            /** @type {HTMLElement} */
            var cropped_div = cropped_container_childrens[i]
            var cropped_div_childrens = cropped_div.childNodes;
            result[i] = new Array(2);

            // Read Images data (canvas + radio)
            for (var j = 0; j < cropped_div_childrens.length - 1; j++) {

                console.log(j)
                if (j == 0) {
                    result[i][0] = cropped_div_childrens[j].toDataURL()
                }
                else if (cropped_div_childrens[j].firstChild != null) {
                    if (cropped_div_childrens[j].firstChild.checked) {
                        result[i][1] = cropped_div_childrens[j].firstChild.value
                        // Break
                    }
                }
            }
        }
    }
    console.log(result)
    return result
}
/**
 * 
 * @param {array} result 
 */
function printNewCanvas(result) {

    /** @type {HTMLElement} */
    var container = document.getElementById("uploadedImage")
    container.classList = 'list-group'
    var im_index = 0;

    for (var i = 0; i < result.length; i++) {

        /** Append new Image */
        /** @type {HTMLCanvasElement} */
        var canvas = result[i][0];
        /** @type {string} */
        var type = result[i][1];
        /** @type {HTMLElement} */
        var image_div = document.createElement('div')
        image_div.classList = "list-group-item"

        var im = new Image();
        im.src = canvas
        im.style.maxHeight = "90px";
        im.style.maxWidth = "160px";
        image_div.appendChild(im)
        container.appendChild(image_div);

        /** Create close button */
        var close_button = document.createElement('button')
        close_button.classList = 'btn btn-link '
        close_button.setAttribute('remove', im_index)
        close_button.style = 'position: absolute;border-color: white;right: 10px;'
        var close_icon = document.createElement('i')
        close_icon.classList = 'fas fa-times'

        close_button.onclick = function () {
            removeId = 'cropped_div' + this.getAttribute('remove')
            removeE = document.getElementById(removeId)
            removeE.parentNode.removeChild(removeE)
        }
        close_button.append(close_icon)

        /** Append to image div */
        image_div.appendChild(close_button)


        if (type == "text") {
            /** Create Copy button */
            var copy_button = document.createElement('button')
            copy_button.classList = 'btn btn-link'
            copy_button.setAttribute('copy', im_index)
            copy_button.disabled = true;
            copy_button.style = 'position: absolute;border-color: white;right: 40px;'
            var copy_icon = document.createElement('i')
            copy_icon.classList = 'fas fa-copy'

            copy_button.onclick = function () { }
            copy_button.append(copy_icon)


            /** Create edit button */

            var edit_button = document.createElement('button')
            edit_button.classList = 'btn btn-link'
            edit_button.setAttribute('edit', im_index)
            edit_button.disabled = true;
            edit_button.style = 'position: absolute;border-color: white;right: 70px;'
            var edit_icon = document.createElement('i')
            edit_icon.classList = 'fas fa-edit'

            edit_button.onclick = function () { }
            edit_button.append(edit_icon)


            /** Append to image div */
            image_div.appendChild(copy_button)
            image_div.appendChild(edit_button)

            sendImage(canvas)
        }


    }
    // Supprimer Cropped
    /** @type {HTMLElement} */
    var cropped_container = document.getElementById('cropped');
    cropped_container.innerHTML = "";

    var close = document.getElementById("closeCapturedImagesModal");
    close.click()


}


function treatCroppedCanvas() {
    result = saveCanvas();
    printNewCanvas(result);
}

function sendImage(canvas) {

    var file = canvas 
    console.log(canvas)
    var fd = new FormData()
    fd.append('file', file)
    $.ajax({
        type: 'POST',
        url: '/imageToText',
        data: fd,
        processData: false,
        contentType: false
    }).done(function (data) {
        console.log(data)
    })
}

