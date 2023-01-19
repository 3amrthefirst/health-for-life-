@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">
    
    <header class="header">
        <div class="title-control">
            <h1 class="page-title">{{__('label.dashboard')}}</h1>
        </div>
        <div class="head-control">
            @include('admin.layout.header_setting')
        </div>
    </header>

    <div class="body-content">
        <div class="row counter-row">
            <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                <div class="db-color-card">
                    <img src="{{asset('/assets/imgs/appointment-time.png')}}" class="card-icon" />
                        <h2 class="counter">{{$result['appointment']}}
                            <span>{{__('label.appointment')}}</span>
                        </h2>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                <div class="db-color-card cate-card">
                    <img src="{{asset('/assets/imgs/medical-doctor.png')}}" class="card-icon" />
                        <h2 class="counter">{{$result['doctor']}}
                            <span>{{__('label.doctor')}}</span>
                        </h2>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                <div class="db-color-card user-card">
                    <img src="{{asset('/assets/imgs/user-brown.png')}}" class="card-icon" />
                        <h2 class="counter">{{$result['patient']}}
                            <span>{{__('label.patient')}}</span>
                        </h2>
                </div>
            </div>

            <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                <div class="db-color-card artist-card">
                    <img src="{{asset('/assets/imgs/doctors-bag.png')}}" class="card-icon" />
                        <h2 class="counter">{{$result['specialtie']}}
                            <span>{{__('label.Specialities')}}</span>
                        </h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection