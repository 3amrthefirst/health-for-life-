@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.setting')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>
    <div class="row top-20 padding-0">
        <div class="col-md-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('Setting')}}">{{__('label.setting')}} </a></li>
            </ol>
        </div>
    </div>

    <div class="body-content">
    <div class="card custom-border-card mt-3">
        <h5 class="card-header">Form </h5>
        <div class="card-body">
            <div class="card-body">
                

            <form class="cmxform" autocomplete="off" id="save_smtp" enctype="multipart/form-data">
                    {!!csrf_field()!!}
                    <div class="form-group row">
                        <div class="col-sm-6 ">
                            <label for="email">{{__('label.host_email')}}</label>
                            <input type="text" value="{{$data->host}}" class="form-control" name="host" id="host">
                        </div>
                        <div class="col-sm-6 ">
                            <label for="Port">{{__('label.port')}}</label>
                            <input type="text" value="{{$data->port}}" class="form-control" name="port" id="port">
                        </div>
                                        
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="protocol">{{__('label.user_name')}}</label>
                            <input type="text" value="{{$data->user}}" class="form-control" name="user" id="user">
                        </div>
                        <div class="col-sm-6">
                             <label for="password">{{__('label.password')}}</label>
                            <input type="password" value="{{$data->pass}}" class="form-control" name="pass" id="pass">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="name">{{__('label.from_name')}}</label>
                            <input type="text" value="{{$data->from_name}}" class="form-control" name="from_name" id="from_name">
                        </div>
                        <div class="col-sm-6">
                            <label for="email">From Email</label>
                            <input type="text" value="{{$data->from_email}}" class="form-control" name="from_email" id="from_email">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="name">Protocol</label>
                            <input type="text" value="{{$data->protocol}}" class="form-control" name="protocol" id="protocol">
                        </div>
                        
                    </div>

                   
                    <div class="form-group row">
                         <div class="text pt-3">
                         <button class="btn btn-default mw-120" type="button" onclick="save_smtp()">
                            Save
                        </button>
                        <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('Setting')}}">Cancel
                        </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    
</div>
@endsection
@section('pagescript')
<script type="text/javascript">

        function save_smtp(){

            var formData = new FormData($("#save_smtp")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("EmailUpdate") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                   
                get_responce_message(resp, 'save_smtp', '{{route("Setting")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }
</script>  
@endsection