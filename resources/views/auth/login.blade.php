@extends('admin.layout.page-app')
@section('content')

<div class="h-100">
    <div class="h-100 no-gutters row">
        <div class="d-none d-lg-block h-100 col-lg-5 col-xl-4">
            <div class="left-caption">
                <img src="{{asset('/assets/imgs/login.jpg')}}" class="bg-img" />
                <div class="caption">
                    <div> 
                        @if(setting_app_name() == "")
                            <img src="{{asset('/assets/imgs/DT2.png')}} " />
                        @else
                            <h1>{{setting_app_name()}}</h1>
                        @endif                        
                        <p class="text">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto quaerat fuga optio voluptatibus ullam
                            aliquam consectetur, quam, veritatis facilis dolor id perspiciatis distinctio ratione! Reprehenderit
                            rerum
                            provident vero praesentium molestiae?
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-100 d-flex login-bg justify-content-center align-items-lg-center col-md-12 col-lg-7 col-xl-8">
            <div class="mx-auto app-login-box col-sm-12 col-md-10 col-xl-8">
                <div class="py-5 p-3">
                    <!-- logo -->
                    <div class="app-logo mb-4">
                        <a href="#" class="mb-4 d-block d-lg-none">
                            <img src="{{asset('/assets/imgs/DT2.png')}}" alt="" class="img-fluid" />
                        </a>
                        <h3 class="primary-color mb-0 font-weight-bold">Login</h3>
                    </div>
                    <!-- end logo -->

                    <h4 class="mb-0 font-weight-bold">
                        <span class="d-block mb-2">Welcome ,</span>
                        <span>Please sign in to your account.</span>
                    </h4>
                   
                    <form class="cmxform" autocomplete="off" id="Login_success" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="form-row mt-4">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail" class="">Email</label>
                                        <input type="text" name="email" value="" class="form-control" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="examplePassword" class="">Password</label>
                                        <input type="password" name="password" value="" class="form-control" required autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="custom-control custom-checkbox mr-sm-2">
                            <input type="checkbox" class="custom-control-input" id="customControlAutosizing">
                        </div> -->
                        <div class="form-row mt-2">
                            <div class="col-sm-6 text-center text-sm-left">
                                <button type="button" class="btn btn-default my-3 mw-120" onclick="Login_success()">Log in</button>
                            </div>
                            <div class="col-sm-6 d-flex align-items-center justify-content-center justify-content-sm-end">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('pagescript')
<script>
    function Login_success() {

      var formData = new FormData($("#Login_success")[0]);
      $.ajax({
        type: 'POST',
        url: '{{ route("LoginGet") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(resp) {
          get_responce_message(resp, 'Login_success', '{{route("admin.dashboard")}}');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $("#dvloader").hide();
          toastr.error(errorThrown.msg, 'failed');
        }
      });
    }
  </script>
@endsection