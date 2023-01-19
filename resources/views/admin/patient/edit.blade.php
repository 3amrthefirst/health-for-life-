@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.patients')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>
    <div class="row top-20 padding-0">
        <div class="col-md-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{_('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('patient.index')}}">{{__('label.patients')}}</a></li>
                <li class="breadcrumb-item active"><a href="">{{__('label.edit_patient')}}</a></li>
            </ol>
        </div>
    </div>

    <div class="body-content">
        <div class="card custom-border-card mt-3">
            <h5 class="card-header">{{__('label.edit_patient')}}</h5>
                <div class="card-body">
                    <form class="cmxform" autocomplete="off" id="save_patient" enctype="multipart/form-data">
                                	         {!!csrf_field()!!}
                        <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>{{__('label.patient_id')}}</label>
                                        <input type="text" class="form-control" name="patient_id" value="@if($data){{$data->patient_id}}@endif">
                                </div>
                                <div class="col-md-6">
                                    <label>{{__('label.full_name')}}</label>
                                        <input type="text" class="form-control" name="fullname" value="@if($data){{$data->fullname}}@endif">
                                </div>
                            </div>
                        </div>   

                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>{{__('label.email')}}</label>
                                        <input type="text" class="form-control" name="email" value="@if($data){{$data->email}}@endif">
                                </div>
                                <div class="col-md-6">
                                    <label>{{__('label.password')}}</label>
                                        <input type="password" class="form-control" name="password" value="@if($data){{$data->password}}@endif">
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>{{__('label.mobile_number')}}</label>
                                        <input type="text" class="form-control" name="mobile_number" value="@if($data){{$data->mobile_number}}@endif">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>{{__('label.instragram_url')}}</label>
                                        <input type="text" class="form-control" name="instagram_url" value="@if($data){{$data->instagram_url}}@endif">
                                </div>
                                <div class="col-md-6">
                                    <label>{{__('label.facebook_url')}}</label>
                                        <input type="text" class="form-control" name="facebook_url" value="@if($data){{$data->facebook_url}}@endif">
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>{{__('label.twitter_url')}}</label>
                                        <input type="text" class="form-control" name="twitter_url" value="@if($data){{$data->twitter_url}}@endif">
                                </div>
                                <div class="col-md-6">
                                    <label>{{__('label.bio_data')}}</label>
                                        <input type="text" class="form-control" name="biodata" value="@if($data){{$data->biodata}}@endif">
                                </div>
                            </div>
                        </div> 

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="mb-1">{{__('label.image')}}</label>
                                <input type="file" id="fileupload" onchange="readURL(this,'idLogo')" class="@if($data){{$data->profile_img}}@endif"  hidden name="profile_img" />
                                <input type="hidden" name="old_image" value="@if($data){{$data->profile_img}}@endif">
                                <label for="fileupload" class="form-control file-control">
                                    <img src="{{asset('/assets/imgs/file-upload.png')}}" alt="" />
                                    <span>Select Image </span>
                                </label>
                                <div class="thumbnail-img" id="idMainLogo">
                                    @if($data && $data->profile_img)
                                    <?php $app = (image_path('patient')).'/'.$data->profile_img ;?>
                                    <img id="idLogo" src="{{$app}}" />
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button class="btn btn-default mw-120" type="button" onclick="save_patient()">{{__('label.save')}}</button>
                            <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('patient.index')}}">{{__('label.cancel')}}</a>
                            <input type="hidden" name="_method" value="PATCH">
                        </div>   
                    </form>
                </div>
        </div>
    </div>

</div>
@endsection
@section('pagescript')
<script type="text/javascript">
      $(document).ready(function() {
        $('#fileupload').change(function(event) {
            if (event.target.files && event.target.files[0]) {
                var html = '<button class="close" type="button"> <span aria-hidden="true">&times;</span></button>';
                html = '<img src="" id="idLogo"/>';
                $('#idMainLogo').html(html)
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('idLogo');
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
                $(this).valid()
            }
        });
    });
    function save_patient(){
        var formData = new FormData($("#save_patient")[0]);
            $.ajax({
                type:'POST',
                url:'{{route("patient.update",[$data->id])}}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_patient', '{{route("patient.index")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }
</script>	

@endsection