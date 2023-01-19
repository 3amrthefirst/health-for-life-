@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.specialitie')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>
    <div class="row top-20 padding-0 ml-3 mr-3">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('specialties.index')}}">{{__('label.specialitie')}}</a></li>
                <li class="breadcrumb-item active"><a href="">{{__('label.add_specialitie')}}</a></li>
            </ol>
        </div>
     
    </div>

<div class="body-content">
    <div class="card custom-border-card mt-3">
        <h5 class="card-header">{{__('label.add_specialitie')}}</h5>
        <div class="card-body">
            <form class="cmxform" autocomplete="off" id="save_specialties" enctype="multipart/form-data">
                                	         {!!csrf_field()!!}
                    <input type="hidden" name="id" value="">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{__('label.name')}}</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Specialties Name">
                        </div>
                      
                    </div>   
                    
             
                    <div class="col-md-12">
                            <div class="form-group">
                                <label class="mb-1">{{__('label.image')}}</label>
                                <input type="file" onchange="readURL(this,'idLogo')" id="fileupload" class="" hidden name="image" />
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
                        <button class="btn btn-default mw-120" type="button" onclick="save_specialties()">{{__('label.save')}}</button>
                        <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('specialties.index')}}">{{__('label.cancel')}}</a>
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


        function save_specialties(){

            var formData = new FormData($("#save_specialties")[0]);
            $.ajax({
                type:'POST',
                url:'{{route("specialties.store")}}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_specialties', '{{route("specialties.index")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }
</script>	
@endsection