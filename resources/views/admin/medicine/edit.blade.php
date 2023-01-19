@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.Medicine')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>
    <div class="row top-20 ml-3 mr-3">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('medicine.index')}}">{{__('label.Medicine')}}</a></li>
                <li class="breadcrumb-item active"><a href="">{{__('label.Edit_Medicine')}}</a></li>
            </ol>
        </div>
    </div>

    <div class="body-content">
        <div class="card custom-border-card mt-3">
            <h5 class="card-header">{{__('label.Edit_Medicine')}}</h5>
            <div class="card-body">
                <form class="cmxform" autocomplete="off" id="edit_medicine" enctype="multipart/form-data">
                    {!!csrf_field()!!}
                    <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
                   
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label>{{__('label.name')}}</label>
                            <input type="text" class="form-control" name="name" value="@if($data){{$data->name}}@endif">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{__('label.Form')}}</label>
                            <select class="form-control required" name="form">
                                <option value="">{{__('label.Select Form')}}</option>
                                <option value="Tablet" {{ $data->form == "Tablet"  ? 'selected' : ''}}>{{__('label.Tablet')}}</option>
                                <option value="Liquid" {{ $data->form == "Liquid"  ? 'selected' : ''}}>{{__('label.Liquid')}}</option>
                                <option value="Capsule" {{ $data->form == "Capsule"  ? 'selected' : ''}}>{{__('label.Capsule')}}</option>
                                <option value="Syrup" {{ $data->form == "Syrup"  ? 'selected' : ''}}>{{__('label.Syrup')}}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>{{__('label.Power')}}</label>
                            <input type="text" class="form-control" name="power" value="@if($data){{$data->power}}@endif" placeholder="Enter Medicine Power">
                        </div>
                    </div>  
    
                    <div class="border-top pt-3 text-right">
                        <button class="btn btn-default mw-120" type="button" onclick="edit_medicine()">{{__('label.save')}}</button>
                        <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('medicine.index')}}">{{__('label.cancel')}}</a>
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
    function edit_medicine(){

        var formData = new FormData($("#edit_medicine")[0]);
        $.ajax({
            type:'POST',
            url:'{{route("medicine.update" ,[$data->id])}}',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function (resp) {
            get_responce_message(resp, 'edit_medicine', '{{route("medicine.index")}}');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#dvloader").hide();
                toastr.error(errorThrown.msg,'failed');         
            }
        });
    }
</script>	
@endsection