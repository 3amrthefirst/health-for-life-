@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')

<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.company')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>
    <div class="row top-20 p-0 mr-3 ml-3">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('company.index')}}">{{__('label.company')}}</a></li>
                <li class="breadcrumb-item active"><a href="">{{__('label.add_company')}}</a></li>
            </ol>
        </div>  
    </div>

    <div class="body-content">
        <div class="card custom-border-card mt-3">
            <h5 class="card-header">Add Company </h5>
                <div class="card-body">
                    <form class="cmxform" autocomplete="off" id="save_add_company" enctype="multipart/form-data">
                        {!!csrf_field()!!}
                        <div class="form-group">
                            <label>{{__('label.name')}}</label>
                                <input type="text" class="form-control col-8" name="name" placeholder="Enter Your Company Name">
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button class="btn btn-default mw-120" type="button" onclick="save_add_company()">{{__('label.save')}}</button>
                            <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('company.index')}}">{{__('label.cancel')}}</a>
                        </div>
                    </form>
                </div>
        </div>
    </div>

</div>

@endsection
@section('pagescript')
<script type="text/javascript">
    function save_add_company(){
        var formData = new FormData($("#save_add_company")[0]);
            $.ajax({
                type:'POST',
                url:'{{route("company.store")}}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                   
                get_responce_message(resp, 'save_add_company', '{{route("company.index")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
    }
</script> 
@endsection