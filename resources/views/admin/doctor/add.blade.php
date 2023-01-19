@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.doctor')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>

    <div class="body-content">
        <div class="border-bottom row mb-3">
            <div class="col-sm-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('doctor.index')}}">{{__('label.doctor')}}</a></li>
                    <li class="breadcrumb-item active"><a href="">{{__('label.add_doctor')}}</a></li>
                </ol>
            </div>
        </div>

        <div class="card custom-border-card mt-3">
            <div class="card-body">
                <form class="cmxform" autocomplete="off" id="save_doctor" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{__('label.first_name')}}</label>
                                <input type="text" class="form-control" name="first_name" placeholder="Enter First Name">
                            </div>
                            <div class="col-md-6">
                                <label>{{__('label.last_name')}}</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name">
                            </div>
                        </div>
                    </div>   
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{__('label.email')}}</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email">
                            </div>
                            <div class="col-md-6">
                                <label>{{__('label.password')}}</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter Password">
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{__('label.mobile_number')}}</label>
                                <input type="number" class="form-control" name="mobile_number" placeholder="Enter Mobile Number">
                            </div>
                            <div class="col-md-6">
                                <label for="specialties_id">{{__('label.speciatiles')}}</label>
                                <select class="form-control" name="specialties_id">
                                    <option value="">Select Speciatiles</option>
                                    @foreach ($data as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <!-- <div class="col-md-6">
                                <label>{{__('label.working_time')}}</label>
                                <input type="text" class="form-control" name="working_time">
                            </div> -->
                            <div class="col-md-12">
                                <label>{{__('label.address')}}</label>
                                <input type="text" class="form-control" name="address" placeholder="Enter Address">
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="about_us">{{__('label.about_us')}}</label>
                                <textarea class="form-control" name="about_us" id="about_us" rows="3" placeholder="Describe yourself here..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="services">{{__('label.service')}}</label>
                                <textarea class="form-control" name="services" id="services" rows="3" placeholder="Describe Your Service..."></textarea>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="health_care">{{__('label.health_Care')}}</label>
                                <textarea class="form-control" name="health_care" id="health_care" placeholder="Enter Health Care..." rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>{{__('label.instragram_url')}}</label>
                                <input type="text" class="form-control" name="instagram_url" placeholder="Enter Url">
                            </div>
                            
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>{{__('label.facebook_url')}}</label>
                                <input type="text" class="form-control" name="facebook_url" placeholder="Enter Url">
                            </div>
                            <div class="col-md-6">
                                <label>Twitter Url</label>
                                <input type="text" class="form-control" name="twitter_url" placeholder="Enter Url">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="mb-1">{{__('label.image')}}</label>
                            <input type="file" onchange="readURL(this,'idLogo')" id="fileupload" class="" hidden name="profile_img" />
                            <label for="fileupload" class="form-control file-control">
                                <img src="{{asset('/assets/imgs/file-upload.png')}}" alt="" />
                                <span>Select Image </span>
                            </label>
                            <div class="thumbnail-img" id="idMainLogo">
                                <img id="idLogo" src="{{asset('/assets/imgs/noimag.png')}}" />
                            </div>
                        </div>
                    </div> 
                    <div class="border-top pt-3 text-right">
                        <button class="btn btn-default mw-120" type="button" onclick="save_doctor()">{{__('label.save')}}</button>
                        <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('doctor.index')}}">{{__('label.cancel')}}
                        </a>
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
        function save_doctor(){
            var formData = new FormData($("#save_doctor")[0]);
            $.ajax({
                type:'POST',
                url:'{{route("doctor.store")}}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    get_responce_message(resp, 'save_doctor', '{{route("doctor.index")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }
    </script>	
@endsection