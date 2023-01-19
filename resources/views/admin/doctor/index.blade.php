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
        <!-- mobile title -->
        <h1 class="page-title-sm"> {{__('label.doctor')}} </h1>

        <div class="border-bottom row mb-3">
            <div class="col-sm-10">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                    <li class="breadcrumb-item">{{__('label.doctor')}}</li>
                </ol>
            </div>
            <div class="col-sm-2 d-flex align-items-center justify-content-end" style="margin-top:-14px">
                <a href="{{route('doctor.create')}}" class="btn btn-default mw-120">{{__('label.add_doctor')}}</a>
            </div>     
        </div>

        <div class="table-responsive table">
            <table class="table table-striped text-center table-bordered" id="doctor">
                <thead>
                <tr style="background: #F9FAFF;">
                    <th>{{__('label.id')}}</th>
                    <th>{{__('label.image')}}</th>
                    <th>{{__('label.name')}}</th>
                    <th>{{__('label.email')}}</th>
                    <th>{{__('label.mobile_number')}}</th>
                    <th>{{__('label.Specialities')}}</th>
                    <th>{{__('label.action')}}</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('pagescript')
    <script type="text/javascript">
        $(function () {
            var table = $('#doctor').DataTable({
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
                order: [[0, "desc"]],
                ajax:"{{route('doctor.store')}}",
                columns: [
                    {data: 'id',name: 'id'},
                    { data: 'profile_img', name: 'profile_img', orderable: false, searchable: false,
                        "render": function (data, type, full, meta) {
                            if(data){
                            return "<img src='{{ image_path('doctor') }}/" + data + "' height=50 width=50 class='rounded-circle'/>";
                            } else {
                            return "<img src='{{ asset('assets/imgs/1.jpg') }}' height=50 width=50 />";
                            }
                        },
                    },
                    {data: 'first_name',name: 'first_name'},
                    {data: 'email',name: 'email'},
                    {data: 'mobile_number',name: 'mobile_number',
                        "render": function (data, type, full, meta) {
                            if(data) {
                            return data;
                            } else {
                            return "-";
                            }
                        },
                    },
                    {data: 'doctor.name', name: 'name', orderable: false, searchable: false,
                        "render": function (data, type, full, meta) {
                            if(data) {
                            return data;
                            } else {
                            return "-";
                            }
                        },
                    },
                    {data: 'Action' ,name: 'Action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection