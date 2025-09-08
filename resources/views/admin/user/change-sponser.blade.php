<!doctype html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg"

    data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />

    <title>{{$data['page_title']}} | {{env("APP_NAME")}}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Start Include Css -->

    @include('admin.headers.maincss')

    <!-- End Include Css -->

</head>

<body>

    <!-- Begin page -->

    <div id="layout-wrapper">

        <!-- Start Include Header -->

        @include('admin.headers.header')

        <!-- End Include Header -->

        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">

                    <!-- start page title -->

                    <div class="row">

                        <div class="col-12">

                            <div

                                class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                                <h4 class="mb-sm-0">{{$data['page_title']}}</h4>

                                <div class="page-title-right">

                                    <ol class="breadcrumb m-0">

                                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>

                                        <li class="breadcrumb-item active">{{$data['page_title']}}

                                        </li>

                                    </ol>

                                </div>

                            </div>

                        </div>

                    </div>

                    @include('admin.user.profile-card')

                    <!-- end page title -->

                    <form class="mt-2 needs-validation form_data" action="{{route('user.change-sponser-action')}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>

                        @csrf

                        <input type="hidden" name="id" value="{{Crypt::encryptString(@$row->id)}}">
                        <input type="hidden" name="pin" id="setPin" value="">

                        <div class="row">

                            <div class="col-lg-12">

                                <div class="card">

                                    <div class="card-body frm">

                                        <div class="row">

                                            

                                            <div class="col-lg-4">

                                                <label class="form-label" for="product-title-input">User Sponser </label>

                                                <select class="form-control kyc" id="user-sponser" disabled>

                                                    <option value="{{$row->sponser_id}}">{{$row->sponser_id}}/{{$row->sponser_name}}</option>

                                                </select>

                                            </div>

                                            

                                            <div class="col-lg-4">

                                                <label class="form-label" for="product-title-input">Select New Sponser </label>

                                                <select class="form-control kyc" id="new-sponser" name="sponser_id">

                                                    <option value="">Select</option>

                                                </select>

                                            </div>



                                            <div class="col-lg-4">

                                                <label for="formFile" class="form-label">Placement</label>

                                                <select class="form-select mb-3" aria-label="Default select example" name="placement">

                                                    <option value="0" >Select Placement</option>

                                                    <option value="1" @if(@$row->placement==1)selected @endif >Left</option>

                                                    <option value="2" @if(@$row->placement==2)selected @endif >Right</option>

                                                </select>

                                            </div>



                                            

                                            

                                        </div>

                                        <!-- end card -->

                                        <div class="text-center mt-5 mb-3">

                                            <button type="button" id="sponser-change-modal-open" class="btn btn-success w-sm">Save</button>
                                            <button type="submit" id="submitBtn" class="btn btn-success w-sm" style="display:none;">Save</button>

                                        </div>

                                    </div>

                                </div>

                                <!-- end card -->

                            </div>

                            <!-- end col -->

                        </div>

                    </form>

                </div>

            </div>

            <!-- End Page-content -->

            <!-- Start Include Footer -->

            @include('admin.headers.footer')

            <!-- End Include Footer -->

        </div>

    </div>



    <div class="modal fade zoomIn" id="ChangeSponserPinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close" id="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Enter Sponser Change PIN</h4>
                            <input type="number" class="form-control" id="sponser-change-pin">
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-danger "
                            id="change-sponser-confirm">Sponser Change Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- END layout-wrapper -->

    <!-- Start Include Script -->

    @include('admin.headers.mainjs')

    <!-- End Include Script -->










    <script>

        $(document).on("click", "#sponser-change-modal-open",(function(e) {
              $("#ChangeSponserPinModal").modal('show');
        }));
        $(document).on("keyup", "#sponser-change-pin",(function(e) {
              $("#setPin").val($(this).val());
        }));
        $(document).on("click", "#change-sponser-confirm",(function(e) {
            $("#submitBtn").trigger('click');
        }));

        $('#new-sponser').select2({

          ajax: {

            url: "{{route('search-sponser')}}",

            method:"post",

            "headers": {

            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

           },

            data: function (params) {

              var query = {

                search: params.term,

                type: 'public'

              }



              // Query parameters will be ?search=[term]&type=public

              return query;

            }

          }

        });

    </script>



</body>

</html>