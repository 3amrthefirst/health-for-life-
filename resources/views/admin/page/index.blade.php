@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
  <header class="header">
    <div class="title-control">
      <h1 class="page-title">{{__('label.Page')}}</h1>
    </div>
    <div class="head-control">
      @include('admin.layout.header_setting')
    </div>
  </header>

  <div class="body-content">
    
    <!-- mobile title -->
    <h1 class="page-title-sm"> {{__('label.Page')}} </h1>

    <div class="border-bottom row mb-3">
      <div class="col-sm-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.Dashboard')}}</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">
            {{__('label.Page')}}
          </li>
        </ol>
      </div>
    </div>

    <div class="table-responsive table">
      <table class="table table-striped text-center table-bordered" id="datatable">
        <thead>
          <tr style="background: #F9FAFF;">
            <th> {{__('label.title')}} </th>
            <th> {{__('label.action')}} </th>
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
  <script>
    $(document).ready(function() {
      $(function () {
        var table = $('#datatable').DataTable({
          "responsive": true,
          "autoWidth": false,
          language: {
            paginate: {
              previous: "<img src='{{asset('assets/imgs/left-arrow.png')}}' >",
              next: "<img src='{{asset('assets/imgs/left-arrow.png')}}' style='transform: rotate(180deg)'>"
            }
          },
          processing: true,
          serverSide: false,
          ajax: "{{ route('page.store') }}",
          columns: [
            {data: 'title', name:'title', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
          ],
        });
      });
    });
  </script>
@endsection