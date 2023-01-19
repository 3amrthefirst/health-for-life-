@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
  <header class="header">
      <div class="title-control">
          <h1 class="page-title">Appointment</h1>
      </div>
      <div class="head-control">
          @include('admin.layout.header_setting')
      </div>
  </header>

  <div class="body-content">
    <!-- mobile title -->
    <h1 class="page-title-sm">Appointment</h1>

    <div class="border-bottom row mb-3">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item">Appointment</li>
            </ol>
        </div>
    </div>

    <!-- Serach Dropdown -->
    <div class="">
      <form class="" action="{{ route('appointment.index')}}" method="GET">
        <div class="form-row">
          <div class="col-md-1 d-flex align-items-center">
            <label for="type">{{__('label.SEARCH')}} :</label>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control" id="doctor" name="doctor">
              <option value="all">All Doctor</option>
                @for ($i = 0; $i < count($doctor); $i++)
                  <option value="{{ $doctor[$i]['id'] }}" @if(isset($_GET['doctor'])){{ $_GET['doctor'] == $doctor[$i]['id'] ? 'selected' : ''}} @endif> {{ $doctor[$i]['first_name'] }}  {{ $doctor[$i]['last_name'] }} </option>
                @endfor
              </select>
            </div>
          </div>
          <div class="col-sm-2 ml-4">
            <button class="btn btn-default" type="submit"> {{__('label.SEARCH')}} </button>
          </div>
        </div>
      </form>
    </div>

    <div class="row">
      @foreach ($data as $key => $value)
      <div class="col-12 col-sm-6 col-md-4 col-xl-3">
        <div class="card video-card">
          <div class="position-relative">
            <img class="card-img-top" src="{{ asset('assets/imgs/appoinment.jpg') }}" alt="">
          </div>
          <div class="card-body">
            <h5 class="card-title mr-5" style="font-size: 14px;">Doctor Name : <?php if(isset($value->doctor)){ ?> {{$value->doctor->first_name}} {{$value->doctor->last_name}} <?php } else {echo "-";}?> </h5>
            <h5 class="card-title mr-5"  style="font-size: 14px;">Patient Name :  <?php if(isset($value->patient)){ ?> {{$value->patient->fullname}} <?php } else {echo "-";}?></h5>
            <div class="dropdown dropright">
              <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
              </a>

              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="{{route('appointment.show', [$value->id] )}}">
                  <img src="{{ asset('assets/imgs/view.png') }}" class="dot-icon" />
                  Details
                </a>
              </div>
            </div>
            <div class="card-details">
              <div class="tag">
                @if($value->status == 1)
                  <button type="button" style="background:#0dceec; font-size:14px; font-weight:bold; letter-spacing:0.1px; border: none; color: white; padding: 5px 15px; outline: none;">Pending</button>
                @elseif($value->status == 2)
                  <button type="button" style="background:#3BB143; font-size:14px; font-weight:bold; letter-spacing:0.1px; border: none; color: white; padding: 5px 15px; outline: none;">Approved</button>
                @elseif($value->status == 3)
                  <button type="button" style="background:#FF9700; font-size:14px; font-weight:bold; letter-spacing:0.1px; border: none; color: white; padding: 5px 15px; outline: none;">Rejected</button>
                @elseif($value->status == 4)
                  <button type="button" style="background:#FF0000; font-size:14px; font-weight:bold; letter-spacing:0.1px; border: none; color: white; padding: 5px 15px; outline: none;">Absent</button>
                @elseif($value->status == 5)
                  <button type="button" style="background:#003300; font-size:14px; font-weight:bold; border: none; color: white; padding: 4px 20px; outline: none;">Completed</button>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center">
      <div> Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries </div>
      <div class="pb-5"> {{ $data->links('pagination::bootstrap-4') }} </div>
    </div>

  </div>
</div>
@endsection