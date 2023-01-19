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
    <div class="row top-20 ml-3 mr-3">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('specialties.index')}}">{{__('label.specialitie')}}</a></li>
                <li class="breadcrumb-item active"><a href="">{{__('label.edit_specialitie')}}</a></li>
            </ol>
        </div>
     
    </div>

<div class="body-content">
    <div class="card custom-border-card mt-3">
        <h5 class="card-header">{{__('label.edit_specialitie')}}</h5>
        <div class="card-body">
            <form class="cmxform" autocomplete="off" id="edit_specialties" enctype="multipart/form-data">
                                	         {!!csrf_field()!!}
                    <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
                   
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{__('label.name')}}</label>
                            <input type="text" class="form-control" name="name" value="@if($data){{$data->name}}@endif">
                        </div>
                      
                    </div>  
                    
                    <div class="col-sm-12">
                            <div class="form-group">
                                <label class="mb-1">{{__('label.image')}}</label>
                                <input type="file" id="fileupload" onchange="readURL(this,'idLogo')" class="@if($data){{$data->image}}@endif"  hidden name="image" />
                                <input type="hidden" name="old_image" value="@if($data){{$data->image}}@endif">
                                <label for="fileupload" class="form-control file-control">
                                    <img src="{{asset('/assets/imgs/file-upload.png')}}" alt="" />
                                    <span>Select Image </span>
                                </label>
                                <div class="thumbnail-img" id="idMainLogo">
                                    @if($data && $data->image)
                                    <?php $app = (image_path('specialties')).'/'.$data->image ;?>
                                    <img id="idLogo" src="{{$app}}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    
    
                    <div class="border-top pt-3 text-right">
                        <button class="btn btn-default mw-120" type="button" onclick="edit_specialties()">{{__('label.save')}}</button>
                        <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('specialties.index')}}">{{__('label.cancel')}}</a>
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

        function edit_specialties(){

            var formData = new FormData($("#edit_specialties")[0]);
            $.ajax({
                type:'POST',
                url:'{{route("specialties.update" ,[$data->id])}}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'edit_specialties', '{{route("specialties.index")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }
</script>	

@endsection