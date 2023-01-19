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

    <div class="row top-20 ml-2">
        <div class="col-md-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('specialties.index')}}">{{__('label.specialitie')}}</a></li>
            </ol>
        </div>
        <div class="col-md-2">
            <div class="mb-3 pb-3">
                <a href="{{route('specialties.create')}}" class="btn btn-default mw-120">{{__('label.add_specialitie')}}</a>
            </div>
        </div>
     
    </div>

    <div class="col-md-12 top-20 padding-0">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="responsive-table">
                            <table class="table table-striped table-bordered text-center" id="specialties">
                                <thead style="background: #F9FAFF;">
                                    <tr>
                                        <th>{{__('label.id')}}</th>
                                        <th>{{__('label.icon')}}</th>
                                        <th>{{__('label.name')}}</th>
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
        $(function () {
            var table = $('#specialties').DataTable({
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
                ajax:"{{route('specialties.store')}}",
                columns: [
                
                {data: 'id',name: 'id'},
                { data: 'image', name: 'image', orderable: false, searchable: false,
                    "render": function (data, type, full, meta) {
                        if(data && data != null){
                        return "<img src='{{image_path('specialties')}}/" + data + "' height=50 Width=50>";
                        } else {
                        return "<img src='{{ asset('assets/imgs/1.png') }}' height='50' width=50>";
                        }
                    },
                },
                {data: 'name', name: 'name'},
                {data: 'Action' ,name: 'Action', orderable: false, searchable: false},
                
                ]
            });
        });
    </script>
@endsection