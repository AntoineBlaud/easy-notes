var split;
var cropper;
var htmlcropper;
var im_index = 0;
var im_array = [];
var im_current;

function downloadCapturedImages() {
  $.ajax({
    type: "POST",
    url: "/getcapturedimages",
    data: "",
    processData: false,
    contentType: false,
  }).done(function(data) {
    printCapturedImages(data);
    initCropper();
  });
}

function getNewImages() {
  downloadCapturedImages();
}

function printCapturedImages(data) {
  split = data.split("::");
  var slide = document.getElementById("slide");
  for (imageName in split) {
    var div = document.createElement("div");
    div.classList = "img-wrap";

    var span = document.createElement("span");
    span.classList = "close";
    span.innerHTML="&times"

    var im = document.createElement("img");
    im.src = "/uploaded_images/" + split[imageName];
    im.classList = "img-thumbnail rounded float-left";
    im.onclick = function() {
      cropper.destroy();
      setCropper(this);
    };
    im_array.push(im);

    im.style.width = "200px";
    im.style.height = "180px";
    div.append(span);
    div.append(im);
    slide.append(div);
  }
}

function initCropper() {
  document.getElementById("toolbar").style.display ="block"
  setCropper(im_array[0]);
}

function setCropper(im) {
  htmlcropper = document.getElementById("cropper");
  // im_c is a cloned img put in cropper
  im_c = im.cloneNode(true);
  im_c.classList = "";
  im_c.style = "max-height 500px; max-width: 500px;";
  if (typeof im_current != "undefined")
    im_current.parentNode.removeChild(im_current);

  im_current = im_c;
  htmlcropper.append(im_c);

  cropper = new Cropper(im_c, {
    crop(event) {
      console.log(event.detail.x);
      console.log(event.detail.y);
      console.log(event.detail.width);
      console.log(event.detail.height);
      console.log(event.detail.rotate);
      console.log(event.detail.scaleX);
      console.log(event.detail.scaleY);
    },
  });
}

function getCroppedCanvas() {
  cropped_container = document.getElementById("cropped");
  im = cropper.getCroppedCanvas();

  // Create container
  cropped_div = document.createElement("div");
  cropped_div.classList = "list-group-item";
  cropped_div.setAttribute("id", "cropped_div" + im_index);

  cropped_input_div_1 = document.createElement("div");
  cropped_input_div_1.classList = "form-check form-check-inline";

  cropped_input_div_2 = document.createElement("div");
  cropped_input_div_2.classList = "form-check form-check-inline";

  cropped_input_div_3 = document.createElement("div");
  cropped_input_div_3.classList = "form-check form-check-inline";

  // Create radios
  cropped_input_1 = document.createElement("input");
  cropped_input_1.setAttribute("type", "radio");
  cropped_input_1.innerHTML = "text";
  cropped_input_1.classList = "form-check-input";
  cropped_input_1.setAttribute("name", "im");
  cropped_input_1.value = "text";

  cropped_input_2 = document.createElement("input");
  cropped_input_2.setAttribute("type", "radio");
  cropped_input_2.innerHTML = "text";
  cropped_input_2.classList = "form-check-input";
  cropped_input_2.setAttribute("name", "im");
  cropped_input_2.value = "shape";

  cropped_input_3 = document.createElement("input");
  cropped_input_3.setAttribute("type", "radio");
  cropped_input_3.innerHTML = "text";
  cropped_input_3.classList = "form-check-input";
  cropped_input_3.setAttribute("name", "im");
  cropped_input_3.value = "other";

  // Create labels

  label_input_1 = document.createElement("label");
  label_input_2 = document.createElement("label");
  label_input_3 = document.createElement("label");

  label_input_1.classList = "form-check-label";
  label_input_2.classList = "form-check-label";
  label_input_3.classList = "form-check-label";

  label_input_1.innerHTML = "Text";
  label_input_2.innerHTML = "Graphic";
  label_input_3.innerHTML = "Other";

  // Create delete button
  close_button = document.createElement("button");
  close_button.classList = "button close";
  close_button.setAttribute("remove", im_index);
  close_button.style = "position: absolute;border-color: white;right: 20px;";
  close_icon = document.createElement("i");
  close_icon.classList = "fas fa-times";

  close_button.onclick = function() {
    removeId = "cropped_div" + this.getAttribute("remove");
    removeE = document.getElementById(removeId);
    removeE.parentNode.removeChild(removeE);
  };

  // Join elements
  cropped_div.append(cropper.getCroppedCanvas({ width: 160, height: 90 }));

  close_button.append(close_icon);
  cropped_input_div_1.append(cropped_input_1);
  cropped_input_div_2.append(cropped_input_2);
  cropped_input_div_3.append(cropped_input_3);

  cropped_input_div_1.append(label_input_1);
  cropped_input_div_2.append(label_input_2);
  cropped_input_div_3.append(label_input_3);

  cropped_div.append(cropped_input_div_1);
  cropped_div.append(cropped_input_div_2);
  cropped_div.append(cropped_input_div_3);
  cropped_div.append(close_button);
  cropped_container.append(cropped_div);

  // increment
  im_index = im_index + 1;
}
