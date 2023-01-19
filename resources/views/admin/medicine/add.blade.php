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
    <div class="row top-20 padding-0 ml-3 mr-3">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('medicine.index')}}">{{__('label.Medicine')}}</a></li>
                <li class="breadcrumb-item active"><a href="">{{__('label.Add_Medicine')}}</a></li>
            </ol>
        </div>
    </div>

    <div class="body-content">
        <div class="card custom-border-card mt-3">
            <h5 class="card-header">{{__('label.Add_Medicine')}}</h5>
            <div class="card-body">
                <form class="cmxform" autocomplete="off" id="save_medicine" enctype="multipart/form-data">
                    {!!csrf_field()!!}
                    <input type="hidden" name="id" value="">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label>{{__('label.name')}}</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Medicine Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>{{__('label.Form')}}</label>
                            <select class="form-control required" name="form">
                                <option value="">{{__('label.Select Form')}}</option>
                                <option value="Tablet">{{__('label.Tablet')}}</option>
                                <option value="Liquid">{{__('label.Liquid')}}</option>
                                <option value="Capsule">{{__('label.Capsule')}}</option>
                                <option value="Syrup">{{__('label.Syrup')}}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>{{__('label.Power')}}</label>
                            <input type="text" class="form-control" name="power" placeholder="Enter Medicine Power">
                        </div>
                    </div>  

                    <div class="border-top pt-3 text-right">
                        <button class="btn btn-default mw-120" type="button" onclick="save_medicine()">{{__('label.save')}}</button>
                        <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('medicine.index')}}">{{__('label.cancel')}}</a>
                    </div>   
                  
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
@section('pagescript')
<script type="text/javascript">
    function save_medicine(){
        
        $("#dvloader").show();
        var formData = new FormData($("#save_medicine")[0]);
        $.ajax({
            type:'POST',
            url:'{{route("medicine.store")}}',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function (resp) {
                $("#dvloader").hide();
                get_responce_message(resp, 'save_medicine', '{{route("medicine.index")}}');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#dvloader").hide();
                toastr.error(errorThrown.msg,'failed');         
            }
        });
    }
</script>	
@endsection