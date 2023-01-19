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
                <li class="breadcrumb-item active"><a href="">{{__('label.add')}}</a></li>
            </ol>
        </div>  
    </div>
    <div class="body-content">
        <div class="card custom-border-card mt-3">
            <h5 class="card-header">Form </h5>
                <div class="card-body">
                    <form class="cmxform" autocomplete="off" method="post" id="save_add_sendnotification" enctype="multipart/form-data">
                        {!!csrf_field()!!}
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label>{{__('label.title')}}</label>
                                <input type="text" class="form-control col-12" name="title" placeholder="Enter Title">
                        </div>

                        <div class="form-group">
                            <label>{{__('label.message')}}</label>
                                <input type="text" class="form-control col-12" name="message" placeholder="Enter Message">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="input-2">{{__('label.image')}}</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                        <p class="noteMsg">Note: Image Size must be lessthan 2MB.Image Height and Widthlessthan 1000px.</p>
                            </div>
                            <div class="col-md-6">
                                <img id="preview-image-before-upload" src="{{asset('/assets/imgs/2.jpg')}}"
                                        alt="preview image" style="height: 100px;">
                                </div>
                        </div>
                    
                        <div class="form-group">
                            <button class="btn btn-default mw-120" type="button" onclick="save_add_sendnotification()">{{__('label.save')}}</button>
                            <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('Notification')}}">{{__('label.cancel')}}</a>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>

@endsection
@section('pagescript')
 
<script type="text/javascript">
    $(document).ready(function (e) {
        $('#image').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => { 
            $('#preview-image-before-upload').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]); 
        });
    });	
</script>
<script>
    function save_add_sendnotification(){
        var formData = new FormData($("#save_add_sendnotification")[0]);
            $.ajax({
                type:'POST',
                url:'{{route("NotificationSave")}}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_add_sendnotification', '{{route("Notification")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
    }
</script>

@endsection