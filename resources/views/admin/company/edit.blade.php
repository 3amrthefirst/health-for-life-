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
    <div class="row top-20 p-0 pl-3 pr-3">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('company.index')}}">{{__('label.company')}}</a></li>
                <li class="breadcrumb-item active"><a href="">{{__('label.edit_company')}}</a></li>
            </ol>
        </div>  
    </div>

    <div class="body-content">
        <div class="card custom-border-card mt-3">
            <h5 class="card-header">{{__('label.edit_company')}} </h5>
                <div class="card-body">
                    <form class="cmxform" autocomplete="off" id="company_edit" enctype="multipart/form-data">
                        {!!csrf_field()!!}
                        <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
                        <div class="form-group">
                            <label>{{__('label.name')}}</label>
                            <input type="text" class="form-control col-8" name="name" name="id" value="@if($data){{$data->name}}@endif">
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button class="btn btn-default mw-120" type="button" onclick="company_edit()">{{__('label.save')}}</button>
                            <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('company.index')}}">{{__('label.cancel')}}</a>
                            <input type="hidden" name="_method" value="PATCH">
                        </div>
                    </form>
                </div>
        </div>
    </div>

</div>

@endsection
@section('pagescript')
<script>
    function company_edit() {
        $("#dvloader").show();
        var formData = new FormData($("#company_edit")[0]);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            enctype: 'multipart/form-data',
            type: 'POST',
            url: '{{route("company.update", [$data->id])}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(resp) {
                $("#dvloader").hide();
                get_responce_message(resp, 'company_edit', '{{route("company.index")}}');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#dvloader").hide();
                toastr.error(errorThrown.msg, 'failed');
            }
        });
    }
</script>
@endsection