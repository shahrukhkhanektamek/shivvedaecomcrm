@include("salesman/include/header")



        <!-- content -->
        <div class="container mt-3 mt-lg-4 mt-xl-5" id="main-content">
           


            <div class="row gx-3 align-items-center justify-content-center py-3 mt-auto z-index-1 height-dynamic" >
                <div class="col login-box maxwidth-400">
                    
                    <form class="account__form form_data" action="{{$data['submit_url']}}" method="post" enctype="multipart/form-data" id="form_data_submit" novalidate>
                        @csrf
                        <div class="position-relative">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="checkstrength" placeholder="Enter your new password" required>
                                <label for="checkstrength">New Password</label>
                            </div>
                            <button type="button" class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2 " onclick="togglePasswordVisibility(this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        
                        <div class="position-relative">
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="passwdconfirm" placeholder="Confirm your new password" required>
                                <label for="passwdconfirm">Confirm Password</label>
                            </div>
                            <button type="button" class="btn btn-square btn-link text-theme-1 position-absolute end-0 top-0 mt-2 me-2 " onclick="togglePasswordVisibility(this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <button class="btn btn-lg btn-theme w-100 mb-4">Change Now</button>
                    </form>

                    
                </div>
            </div>

        
        </div>        

 @include("salesman/include/footer")