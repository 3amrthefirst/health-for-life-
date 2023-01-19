@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.notification')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>
    <div class="row top-20 padding-0">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('Notification')}}">{{__('label.notification')}}</a></li>
            </ol>
        </div>
    </div>
    <div class="body-content">
    <div class="card custom-border-card mt-3">
        <h5 class="card-header">{{__('label.notification')}} </h5>
        <div class="card-body">
            <div class="card-body">
                <form class="cmxform" autocomplete="off" id="save_update_notification" enctype="multipart/form-data">
                                	         {!!csrf_field()!!}
                    <div class="form-group">
                        <label>{{__('label.OneSignal App ID')}}</label>
                        <input type="text" class="form-control col-6" name="onesignal_app_id" value="{{$first['onesignal_app_id']}}">
                    </div>

                    <div class="form-group">
                        <label>{{__('label.OneSignal Rest Key')}}</label>
                        <input type="text" class="form-control col-6" name="onesignal_rest_key" value="{{$first['onesignal_rest_key']}}">
                    </div>

                    <div class="form-group">
                        <button class="btn btn-default mw-120" type="button" onclick="save_update_notification()">{{__('label.save')}}</button>
                        <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('Notification')}}">{{__('label.cancel')}}</a>
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
    function save_update_notification(){
        var formData = new FormData($("#save_update_notification")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("NotificationUpdate") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_update_notification', '{{route("Notification")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
    }
</script>
@endsection