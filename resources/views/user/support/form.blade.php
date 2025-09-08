@include('user.headers.header')

@php($cartDetail = \App\Models\MemberModel::cartDetail(@$row->user_id))



  

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

        





       





        <div class="col-lg-12 mt-3">

          <div class="card card-outline">

            <div class="card-header bg-blue">

              <h5 class="text-white m-b-0">Create Ticket </h5>

            </div>

            <div class="card-body row ">

              <div class="info-box">

                  <form class="form row form_data1" action="{{$data['submit_url']}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>

                    @csrf                



                    <div class="col-md-12">

                      <div class="form-group">

                        <label>Subject</label>

                        <div class="input-group">

                          <input class="form-control" placeholder="Subject" type="text" name="subject" id="subject" value="" required>

                        </div>

                      </div>

                    </div>



                    <div class="col-md-12">

                      <div class="form-group">

                        <label>Detail</label>

                        <div class="input-group">

                          <textarea name="message" class="form-control" rows="5" id="message"></textarea>

                        </div>

                      </div>

                    </div>



                    <div class="col-md-12">

                      <button type="button" class="btn btn-success waves-effect waves-light m-r-10" id="support-submit">Submit</button>

                    </div>







                  </form>

              </div>

            </div>

          </div>

        </div>













      </div>

      

    </div>

    <!-- /.content --> 

  </div>

  <!-- /.content-wrapper -->












<script type="module">

    import { firebaseConfig} from '<?=url('/') ?>/firebase.js';
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.1/firebase-app.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.12.1/firebase-analytics.js";
    import { getDatabase, ref, set, child, update, remove, onValue  } from "https://www.gstatic.com/firebasejs/10.12.1/firebase-database.js";
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
    const db = getDatabase();




    $(document).on("click", "#support-submit",(function(e) {      
      event.preventDefault();
      loader("show");
      var form = new FormData();
      
      form.append("subject",$("#subject").val());
      form.append("message",$("#message").val());

      


      var settings = {
        "url": "{{$data['submit_url']}}",
        "method": "POST",
        "timeout": 0,
        "processData": false,
        "headers": {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
         },
        "mimeType": "multipart/form-data",
        "contentType": false,
        "dataType": "json",
        "data": form
      };
      $.ajax(settings).always(function (response) {
          loader("hide");
          response = admin_response_data_check(response);
          // setUpdateDeposit(response.url);

          const depositRef = ref(db, 'supportupdate/1');
          set(depositRef, {
              status: 1,
              dateTime: Date.now(),
          }).then(() => {
              update(depositRef, {
                  status: 0,
                  dateTime: Date.now(),
              }).then(() => {
                  // console.log("Status updated to 0");

                  window.location.href = response.url; // Redirect after update

              }).catch(error => console.error("Error updating status:", error));              
          }).catch(error => console.error("Error setting status:", error));


      });
      
   }));


    
</script>









@include('user.headers.footer')