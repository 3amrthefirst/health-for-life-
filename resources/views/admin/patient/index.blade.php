@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.patients')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>
    <div class="row top-20 ml-2">
        <div class="col-md-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('patient.index')}}">{{__('label.patients')}}</a></li>
            </ol>
        </div>
        <div class="col-md-2">
            <div class="mb-3 pb-3">
                <a href="{{route('patient.create')}}" class="btn btn-default mw-120">{{__('label.add_patient')}}</a>
            </div>
        </div>
    </div>

    <div class="col-md-12 top-20 padding-0">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="responsive-table">
                            <table class="table table-striped table-bordered text-center" id="patient">
                                <thead style="background: #F9FAFF;">
                                    <tr>
                                        <th>{{__('label.id')}}</th>
                                        <th>{{__('label.image')}}</th>
                                        <th>{{__('label.full_name')}}</th>
                                        <th>{{__('label.email')}}</th>
                                        <th>{{__('label.mobile_number')}}</th>
                                        <th>{{__('label.date')}}</th>
                                        <th>{{__('label.action')}}</th>
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
        function change_status(id) {
            swal({
                title: "Are you sure!",
                type: "success",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                showCancelButton: true,
            })

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{route("StatusChange")}}',
                data:{id:id}, 
                success: function(resp) {
                    console.log(resp);
                }
            });
        }

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
                ajax:"{{route('patient.store')}}",
                columns: [
                    {data: 'id',name: 'id'},
                    { data: 'profile_img', name: 'profile_img', orderable: false, searchable: false,
                        "render": function (data, type, full, meta) {
                            if(data){
                                return "<img src='{{ image_path('patient') }}/" + data + "' height=50 width=50 class='rounded-circle'/>";
                            } else {
                                return "<img src='{{ asset('assets/imgs/1.jpg') }}' height=50 width=50 />";
                            }
                        },
                    },
                    {data: 'fullname',name: 'fullname'},
                    {data: 'email',name: 'email'},
                    {data: 'mobile_number',name: 'mobile_number'},
                    {data: 'date',name: 'date'},
                    {data: 'Action' ,name: 'Action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection