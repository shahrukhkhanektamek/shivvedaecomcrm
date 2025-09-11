@include("salesman/include/header")
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Face Scan & Upload</title>
  <style>
    #camera-container {
      position: relative;
      width: 300px;
      height: 300px;
      margin: 0 auto;
    }
    video, #preview {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }
    #overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 5px solid red; /* default red */
      border-radius: 50%;
      pointer-events: none;
    }
    #preview {
      display: none;
    }
    button {
      margin-top: 15px;
      padding: 10px 20px;
      font-size: 16px;
      background: green;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    div#scanArea {
      display: grid;
      width: 100%;
    }
  </style>
  <!-- Face API JS -->
  <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

</head>
<body>

<div id="scanArea">
  <div id="camera-container">
    <video id="video" autoplay playsinline></video>
    <img id="preview" alt="Preview" />
    <div id="overlay"></div>
  </div>
  <button id="reloadBtn" style="display:none;margin: 10px auto;">Reload</button>
  <button id="checkBtn" style="display:none;margin: 10px auto;">Face Check</button>
  <canvas id="canvas" style="display:none;"></canvas>
  <div style="margin-top:14px">
    <strong>Result:</strong>
    <div id="result" style="margin-top:8px"></div>
  </div>
  <div id="orderData"></div>

  <div id="okGo" style="display:none;text-align: center;">
    <a href="{{url('salesman/')}}" class="btn btn-danger" style="margin: 10px auto;">Cancel</a>
    <button class="btn btn-success" style="margin: 10px auto;">Ok Checkout</button>    
  </div>

</div>

<div class="container-fluid">
  <div class="row gx-3 ">
    <div class="col w-100">
      <form class="account__form form_data" 
            action="{{route('salesman.checkout.place_order')}}" 
            style="display:none;" 
            method="post" enctype="multipart/form-data" 
            id="form_data_submit" novalidate>
        @csrf

        <input type="hidden" name="faceId" id="faceId" value="">

        <div class="form-floating mb-3">
          <input type="text" class="form-control" placeholder="Enter Name" value="" name="name" required>
          <label>Name</label>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" placeholder="Enter Email" value="" name="email" required>
          <label>Email</label>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" placeholder="Enter Phone" value="" name="phone" required>
          <label>Phone</label>
        </div>

        <div class="form-floating mb-3">
          <select class="form-select mb-3" aria-label="Default select example" name="state" required>
            <option value="">Select</option>
            @php($states = DB::table('states')->get())
            @foreach($states as $key => $value)
              <option value="{{$value->id}}" @if(@$orders->state==$value->id) selected @endif>
                {{$value->name}}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" placeholder="Enter City" value="" name="city" required>
          <label>City</label>
        </div>

        <div class="form-floating mb-3">
          <input type="text" class="form-control" placeholder="Enter Pincode" value="" name="pincode" required>
          <label>Pincode</label>
        </div>

        <button type="submit" class="btn btn-lg btn-theme w-100 mb-3">Checkout Complete</button>
      </form>
    </div>
  </div>
</div>

<script>
  const video = document.getElementById('video');
  const overlay = document.getElementById('overlay');
  const canvas = document.getElementById('canvas');
  const preview = document.getElementById('preview');
  const checkBtn = document.getElementById('checkBtn');
  const reloadBtn = document.getElementById('reloadBtn');
  const okGo = document.getElementById('okGo');
  const form_data_submit = document.getElementById('form_data_submit');
  const scanArea = document.getElementById('scanArea');

  // Load face-api models first
  Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri("{{url('public/')}}/models")
  ]).then(startCamera);

  // Camera open
  function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(err => {
        alert("Camera access denied: " + err);
      });
  }

  // Face detection loop
  video.addEventListener('play', () => {
    const interval = setInterval(async () => {
      const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions());
      
      if (detections.length > 0) {
        overlay.style.border = "5px solid green"; // Face detected
        clearInterval(interval);
        takePhoto();
      } else {
        overlay.style.border = "5px solid red"; // No face
      }
    }, 500);
  });

let fileFace = null;

function takePhoto() {
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  const ctx = canvas.getContext('2d');
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
  
  // ✅ Convert canvas → Blob → File
  canvas.toBlob(function(blob) {
    const file = new File([blob], "capture.png", { type: "image/png" });

    // Preview ke liye
    const url = URL.createObjectURL(blob);
    preview.src = url;
    preview.style.display = "block";
    video.style.display = "none";
    checkBtn.style.display = "block";
    reloadBtn.style.display = "block";

    // Global store
    fileFace = file;
    window.capturedFile = file;

    // ✅ Yahan file ready hai
    
  }, "image/png");
}



  const btn = document.getElementById('checkBtn');
  const resDiv = document.getElementById('result');

  okGo.addEventListener('click', async () => {
      form_data_submit.style.display = "block";
      scanArea.style.display = "none";
  });
  reloadBtn.addEventListener('click', async () => {
      location.reload();
  });

  btn.addEventListener('click', async () => {
    resDiv.innerHTML = '<em>Checking…</em>';

    

    const formData = new FormData();
    // formData.append('image', dataUrl);
    formData.append('image', fileFace);

    try {
      const resp = await fetch("{{url('salesman/checkout/')}}/compare-faces", {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      });

      const data = await resp.json();

      if (!resp.ok) {
        resDiv.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
        return;
      }

      if (data.match === true) {

        $("#faceId").val(data.target);
        resDiv.innerHTML = `
          <div style="padding:10px;border-radius:8px;background:#ecfdf5;border:1px solid #bbf7d0">
            <strong style="color:#065f46">Match found ✅</strong>
            <div style="margin-top:8px">
               <!-- <div><strong>Target:</strong> ${data.target || ''}</div> -->
              <!-- <div><strong>Similarity:</strong> ${data.similarity ? data.similarity.toFixed(2) : ''}</div> -->
            </div>
          </div>
        `;
        $("#orderData").html(data.orderView);

        okGo.style.display = "block";
        

      } else {
        resDiv.innerHTML = `<div style="padding:10px;border-radius:8px;background:#fff7ed;border:1px solid #ffd8a8"><strong>No match found</strong></div>`;
      }
    } catch (err) {
      resDiv.innerHTML = `<pre>${err.message}</pre>`;
    }
  });
</script>



</body>
</html>
@include("salesman/include/footer")
