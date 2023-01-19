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

    <div class="row top-20 mr-2 ml-2">
     <div class="col-md-12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{route('Notification')}}">{{__('label.notification')}}</a></li>
          
        </ol>
    </div>  
</div>

<div class="col-md-12 top-20 padding-0">
    <div class="col-md-12">
        <div class="panel">
            <div class="border-bottom mb-3 pb-3">
                <a href="{{route('NotificationAdd')}}" class="btn btn-default mw-120">{{__('label.add')}}</a>
                <a href="{{route('NotificationSetting')}}" class="btn float-right btn-default mw-120">{{__('label.notification_setting')}}</a>
            </div>
            <div class="panel-body">
                <div class="responsive-table">
                    <table class="table table-striped table-bordered text-center" id="Notification">
                        <thead style="background: #F9FAFF;">
                            <tr>
                                <th>{{__('label.id')}}</th>
                                <th>{{__('label.image')}}</th>
                                <th>{{__('label.title')}}</th>
                                <th>{{__('label.message')}}</th>
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

@endsection

@section('pagescript')
    <script type="text/javascript">
        $(function () {
            var table = $('#Notification').DataTable({
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
            
                ajax:"{{route('Notification')}}",
                columns: [
                
                {data: 'id',name: 'id'},
                {data: 'image', name: 'app_logo', 
                    "render": function (data, type, full, meta) {
                    return "<img src='{{image_path('notification')}}/"+ data + "' \" height=\"50\" Width=\"60\"></span>";
                    },
                },
                {data: 'title' ,name: 'title'},
                {data: 'message', name: 'description'},
                {data: 'date', name: 'date'},
                ]
            });
        });
    </script>
@endsection