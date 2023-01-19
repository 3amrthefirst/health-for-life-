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
        <div class="col-sm-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                <li class="breadcrumb-item">Appointment</li>
            </ol>
        </div>
        <div class="col-md-2">
            <div class="d-flex align-items-center justify-content-end">
                <a href="{{route('appointment.index')}}" class="btn btn-default mw-120">Appointment</a>
            </div>
        </div>
    </div>

    <div class="card custom-border-card">
        <table class="table table-striped table-hover table-bordered w-75 text-center" style="margin-left:auto; margin-right:auto">
            <thead>
                <tr class="table-info">
                    <th colspan="2">Appointment Details</th>
                </tr>
            </thead>
            <tbody >
                <tr>
                    <td> Doctor Name </td>
                    <td>{{$data->doctor->first_name}} {{$data->doctor->last_name}}</td>
                </tr>
                <tr>
                    <td> Patient Name </td>
                    <td>{{$data->patient->fullname ?? "-"}}</td>
                </tr>
                <tr>
                    <td> Date </td>
                    <td>{{$data->date ?? "-"}}</td>
                </tr>
                <tr>
                    <td> Start Time</td>
                    <td>{{$data->startTime}}</td>
                </tr>
                <tr>
                    <td> End Time </td>
                    <td>{{$data->endTime}}</td>
                </tr>
                <tr>
                    <td> Description </td>
                    <td>{{$data->description}}</td>
                </tr>      
                <tr>
                    <td> Symptoms </td>
                    <td>
                        @if($data->symptoms)
                            {{$data->symptoms}}
                        @else
                            -
                        @endif  
                    </td>
                </tr>      
                <tr>
                    <td> Doctor Symptoms </td>
                    <td>
                        @if($data->doctor_symptoms)
                            {{$data->doctor_symptoms}}
                        @else
                            -
                        @endif  
                    </td>
                </tr>
                <tr>
                    <td> Doctor Diagnosis </td>
                    <td>
                        @if($data->doctor_diagnosis)
                            {{$data->doctor_diagnosis}}
                        @else
                            -
                        @endif    
                    </td>
                </tr>
                <tr>
                    <td> Medicines Taken </td>
                    <td>
                        @if($data->medicines_taken)
                            {{$data->medicines_taken}}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td> Insurance Details </td>
                    <td>
                        @if($data->insurance_details)
                            {{$data->insurance_details}}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td> Mobile Number </td>
                    <td>
                        @if($data->mobile_number)
                            {{$data->mobile_number}}
                        @else
                            -
                        @endif
                    </td>
                </tr>       
            </tbody>
        </table>
    </div>

  </div>
</div>
@endsection