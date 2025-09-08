
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
</head>
<body>

  
  <div id="scanArea">
      <div id="camera-container">
        <video id="video" autoplay playsinline></video>
        <img id="preview" alt="Preview" />
        <div id="overlay"></div>
      </div>
      <button id="uploadBtn" style="display:none;margin: 10px auto;">Face Check</button>
      <canvas id="canvas" style="display:none;"></canvas>
  </div>



    <div class="container-fluid">
      <div class="row gx-3 ">
          <div class="col w-100">
              
              
              <form class="account__form form_data" action="{{route('salesman.checkout.place_order')}}" style="display:none;" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                  @csrf

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
                          <option value=""  >Select</option>
                          @php($states = DB::table('states')->get())
                          @foreach($states as $key => $value)
                              <option value="{{$value->id}}" @if(@$orders->state==$value->id) selected @endif >{{$value->name}}</option>
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
    const uploadBtn = document.getElementById('uploadBtn');
    const form_data_submit = document.getElementById('form_data_submit');
    const scanArea = document.getElementById('scanArea');

    // Camera open
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(err => {
        alert("Camera access denied: " + err);
      });

    // Simulated "face detection" after 3 sec
    setTimeout(() => {
      overlay.style.border = "5px solid green"; // Border color change
      
      // Auto click photo
      takePhoto();
    }, 3000);

    function takePhoto() {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      
      const dataUrl = canvas.toDataURL("image/png");
      preview.src = dataUrl;
      preview.style.display = "block";
      video.style.display = "none";
      uploadBtn.style.display = "block";
      

      // Save captured image for upload
      uploadBtn.onclick = () => {
        form_data_submit.style.display = "block";
        scanArea.style.display = "none";
        console.log(dataUrl);
        return false;

        fetch("upload.php", {
          method: "POST",
          body: JSON.stringify({ image: dataUrl }),
          headers: { "Content-Type": "application/json" }
        })
        .then(res => res.text())
        .then(data => {
          alert("Uploaded successfully: " + data);
        })
        .catch(err => {
          alert("Upload failed: " + err);
        });
      };
    }






  </script>
</body>
</html>
@include("salesman/include/footer")
