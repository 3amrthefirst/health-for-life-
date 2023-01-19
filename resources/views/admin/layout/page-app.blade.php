<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@if(setting_app_name() != ""){{setting_app_name()}}@else DTCare @endif</title>

    <link rel="shortcut icon" href="{{asset('/assets/imgs/avatar.png')}}">

    <!-- start: Css -->
    <link href="{{asset('/assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link href="{{asset('/assets/css/toastr.min.css')}}" rel="stylesheet" type="text/css">
    <input type="hidden" value="{{URL('')}}" id="base_url">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'></link>   
    <!-- end: Css -->
  </head>    

  <body>
    @yield('content')

    <!--- start java script !---->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{asset('/assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('/assets/js/js.js')}}"></script>
    <script src="{{asset('/assets/js/toastr.min.js')}}"></script>
    <script src="{{asset('/assets/js/plupload.full.min.js')}}"></script>
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script> 

    <script>
      function get_responce_message(resp, form_name, url) {
        if (resp.status == '200') {
          document.getElementById(form_name).reset();
          toastr.success(resp.success);
          setTimeout(function () {
            window.location.replace(url);
          }, 500);

        } else {
          var obj = resp.errors;
          if (typeof obj === 'string') {
            toastr.error(obj);
          } else {
            $.each(obj, function (i, e) {
              toastr.error(e);
            });
          }
        }
      }
    </script>

    <script>
      $(document).ready(function() {
        @if (Session::has('errors'))
          toastr.error('{{ Session::get('errors') }}');
        @elseif(Session::has('success'))
          toastr.success('{{ Session::get('success') }}');
        @endif
      });
    </script>

    @yield('pagescript')
  </body>
</html>
    