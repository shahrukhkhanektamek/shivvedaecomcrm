@include('user.headers.header')

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">    
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<style>
    .bgColor {
    width: 100%;
    height: 150px;
    background-color: #fff4be;
    border-radius: 4px;
    margin-bottom: 30px;
}

.inputFile {
    padding: 5px;
    background-color: #FFFFFF;
    border: #F0E8E0 1px solid;
    border-radius: 4px;
}

.btnSubmit {
    background-color: #696969;
    padding: 5px 30px;
    border: #696969 1px solid;
    border-radius: 4px;
    color: #FFFFFF;
    margin-top: 10px;
}

#uploadFormLayer {
    padding: 20px;
}

input#crop {
    padding: 5px 25px 5px 25px;
    background: lightseagreen;
    border: #485c61 1px solid;
    color: #FFF;
    visibility: hidden;
}

#cropped_img {
    margin-top: 40px;
}

#cropbox {
    max-width: 100%;
    height: auto;
}

</style>
  



  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper"> 

    <!-- Content Header (Page header) -->

    <div class="content-header sty-one">

      <h1 class="text-black">{{$data['page_title']}}</h1>

      <ol class="breadcrumb">

        <li><a href="{{url('user/dashboard')}}">Home</a></li>

        @foreach($data['pagenation'] as $key => $value)

          <li class="sub-bread"><i class="fa fa-angle-right"></i> {{$value}}</li>

        @endforeach

      </ol>

    </div>

    

    <!-- Main content -->

    <div class="content">

      <div class="row">





            <div class="col-lg-12">
              <div class="info-box">


                <div class="row">
                    <div class="col-lg-6" style="margin: 0 auto;">
                        <label for="formFile" class="form-label">Select Picture</label>
                        <label style="display: block;">
                            <input class="form-control crop-image-upload" type="file" name="image" data-target="image" id="imageUpload" accept="image/*"  required>
                        </label>
                        <div class="crop-div">
                            <img class="upload-img-view img-thumbnail mt-2 mb-2 image" id="imagePreview"
                            onerror="this.src='{{asset('storage/app/public/upload/user.png')}}'"
                            src="{{asset('storage/app/public/upload/')}}/{{@$row->image}}" alt="banner image"/ style="width: 100%;">
                        </div>
                    </div>

                </div>
                <!-- end card -->
                <div class="text-center mt-1 mb-3">
                    <button class="btn btn-success w-sm" id="cropButton">Crop and Upload</button>
                </div>
            

              </div>
            </div>

      </div>

      <!-- Main row --> 

    </div>

    <!-- /.content --> 

  </div>

  <!-- /.content-wrapper -->



@include('user.headers.footer')


<script>
$(document).ready(function() {
    var cropper;
    const imagePreview = document.getElementById('imagePreview');

    // Handle file selection
    $('#imageUpload').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                
                // Initialize Cropper.js when the image loads
                imagePreview.onload = function() {
                    setTimeout(() => {
                        if (cropper) {
                            cropper.destroy();
                        }
                        cropper = new Cropper(imagePreview, {
                            aspectRatio: 1,
                            viewMode: 1,
                            dragMode: 'move',
                            cropBoxResizable: false, // Prevent resizing of the crop box
                            cropBoxMovable: false, 
                        });
                    }, 100);
                };
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle cropping and upload
    $('#cropButton').on('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
               
                width: 300,   // Adjust output width
                height: 300   // Adjust output height
            });
            
            // Convert canvas to data URL and send it to the server
            canvas.toBlob(function(blob) {
                
                
                
                /* Convert data URL to blob */
                function dataURLToBlob(dataURL) {
                    const byteString = atob(dataURL.split(',')[1]);
                    const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
                    const ab = new ArrayBuffer(byteString.length);
                    const ia = new Uint8Array(ab);
                    for (let i = 0; i < byteString.length; i++) {
                        ia[i] = byteString.charCodeAt(i);
                    }
                    return new Blob([ab], { type: mimeString });
                }
                
                
                
                const dataURL = canvas.toDataURL('image/jpeg');
                const formData = new FormData();
                formData.append('croppedImage', dataURLToBlob(dataURL), 'cropped_image.jpg');
                
                
                

                
                // const formData = new FormData();
                // formData.append('croppedImage', blob, 'cropped_image.jpg');
                formData.append('id', '{{Crypt::encryptString(@$row->id)}}');
                
                // Send the cropped image to the server
                $.ajax({
                    url: "{{$data['submit_url']}}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                       },
                   xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                $('#progressBar').css('width', percentComplete + '%');
                                $('#progressText').text(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                     },
                    success: function(response) {
                        loader("hide");
                        response = admin_response_data_check(response);
                        if(response.status==200)
                        {
                            $(".crop-div").html(`
                                <img class="" id="cropbox"
                                src="`+response.data.image+`" alt="banner image" />
                            `);
                            $("#tempimagename").val(response.data.imagename);
                            imageCrop();
                            $(".alert-success").show();
                        }
                        else
                        {
                            error_message(response.message);
                        }
                    },
                    error: function(error) {
                        loader("hide");
                        response = admin_response_data_check(response);
                        if(response.status==200)
                        {
                            $(".crop-div").html(`
                                <img class="" id="cropbox"
                                src="`+response.data.image+`" alt="banner image" />
                            `);
                            $("#tempimagename").val(response.data.imagename);
                            imageCrop();
                            $(".alert-success").show();
                        }
                        else
                        {
                            error_message(response.message);
                        }
                    }
                });
            });
        }
    });
});
</script>