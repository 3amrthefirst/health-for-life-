@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.commnet')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>
    
    <div class="row top-20 ml-2 mr-2">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('review.index')}}">{{__('label.commnet')}}</a></li>
            </ol>
        </div>
    </div>

    <div class="col-md-12 top-20 padding-0">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="responsive-table">
                            <table class="table table-striped table-bordered" id="patient">
                                <thead style="background: #F9FAFF;">
                                    <tr>
                                        <th>{{__('label.id')}}</th>
                                        <th>{{__('label.doctor_name')}}</th>
                                        <th>{{__('label.patient_name')}}</th>
                                        <th>{{__('label.commnet')}}</th>
                                        <th>{{__('label.date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>
	        </div>
        </div>
    </div>
</div>    
@endsection

@section('pagescript')
    <script type="text/javascript">
        $(function () {
            var table = $('#patient').DataTable({
                "responsive": true,
                "autoWidth": false,
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
                processing: true,
                serverSide: true,
                language: {
                    paginate: {
                    previous: " <img src='{{url('assets/imgs/left-arrow.png')}}'>",
                    next: " <img src='{{url('assets/imgs/left-arrow.png')}}' style='transform:rotate(180deg)'>"
                    }
                },
                order: [[0, "DESC"]],
                ajax:"{{route('review.index')}}",
                columns: [
                
                {data: 'id',name: 'id'},
                {data: 'doctor_id',name: 'doctor_id'},
                {data: 'patient_id',name: 'fullname'},
                {data: 'comment',name: 'comment'},
                {data: 'created_at',name: 'created_at'},
                
                
                ]
            });
        });
    </script>
@endsection