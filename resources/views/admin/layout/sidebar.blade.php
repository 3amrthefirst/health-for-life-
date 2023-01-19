<!-- start:Left Menu -->
<div class="sidebar">
    <div class="side-head">
      @if(setting_app_name() == "")
      <a href="{{route('admin.dashboard')}}">
        <img src="{{asset('/assets/imgs/DT1.png')}}" class="side-logo" />
      </a>
      @else
      <a href="{{route('admin.dashboard')}}" style="color:#4e45b8;">
        <h3>{{setting_app_name()}}</h3>
      </a>
      @endif
        <button class="btn side-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <ul class="side-menu">
      
      <li class="{{ (request()->routeIs('admin.dashboard*')) ? 'active' : '' }}"><a href="{{route('admin.dashboard')}}">
        <img src="{{asset('/assets/imgs/dashboard.png')}}" class="menu-icon" />
        <span> {{__('label.dashboard')}} </span>
        </a>
      </li>

      <li class="{{ (request()->routeIs('appointment*')) ? 'active' : '' }}"><a href="{{route('appointment.index')}}">
        <img src="{{asset('/assets/imgs/watch.png')}}" class="menu-icon" />
        <span> {{__('label.appointment')}} </span>
        </a>
      </li>

      <li class="{{ (request()->routeIs('doctor*')) ? 'active' : '' }}"><a href="{{route('doctor.index')}}">
        <img src="{{asset('/assets/imgs/doctor.png')}}" class="menu-icon" />
        <span> {{__('label.doctor')}} </span>
        </a>
      </li>

      <li class=" {{ (request()->routeIs('patient*')) ? 'active' : '' }} "><a href="{{route('patient.index')}}">
        <img src="{{asset('/assets/imgs/user.png')}}" class="menu-icon" />
        <span> {{__('label.patient')}} </span>
        </a>
      </li>
      
      <li class="{{ (request()->routeIs('specialties*')) ? 'active' : '' }}  "><a href="{{route('specialties.index')}} ">
        <img src="{{asset('/assets/imgs/dashboard.png')}}" class="menu-icon" />
        <span> {{__('label.specialitie')}} </span>
        </a>
      </li>

      <li class="{{ (request()->routeIs('medicine*')) ? 'active' : '' }}  "><a href="{{route('medicine.index')}} ">
        <img src="{{asset('/assets/imgs/medicine.png')}}" class="menu-icon" />
        <span> {{__('label.Medicine')}} </span>
        </a>
      </li>

      <li class="{{ (request()->routeIs('review*')) ? 'active' : '' }} "><a href="{{route('review.index')}}">
        <img src="{{asset('/assets/imgs/comment.png')}}" class="menu-icon" />
        <span> {{__('label.review')}} </span>
        </a>
      </li>

      <li class="{{ (request()->routeIs('company*')) ? 'active' : '' }}"><a href="{{route('company.index')}}">
        <img src="{{asset('/assets/imgs/company.png')}}" class="menu-icon"  alt=""/>
        <span> {{__('label.company')}} </span>
        </a>
      </li>

      <li class="{{ request()->is('admin/page*') ? 'active' : '' }}">
        <a href="{{ route('page.index') }}">
          <img class="menu-icon" src="{{ asset('assets/imgs/pages.png') }}" alt="" />
          <span>{{__('label.Page')}}</span>
        </a>
      </li>

      <!-- <li class="{{ (request()->routeIs('Notification*')) ? 'active' : '' }} "><a href="{{route('Notification')}}">
        <img src="{{asset('/assets/imgs/message.png')}}" class="menu-icon" alt="" />
        <span> {{__('label.notification')}} </span>
        </a>
      </li> -->

      <li class="{{ (request()->routeIs('Setting*')) ? 'active' : '' }} {{ (request()->routeIs('EmailSetting')) ? 'active' : ''}} "><a href="{{route('Setting')}}">
        <img src="{{asset('/assets/imgs/settings.png')}}" class="menu-icon" alt="" />
        <span> {{__('label.setting')}} </span>
        </a>
      </li>


      <li class=" "><a href="{{route('Logout')}}">
        <img src="{{asset('/assets/imgs/logout.png')}}" class="menu-icon" />
          <span> {{__('label.logout')}} </span>
        </a>
      </li>

    </ul>
</div>
<!-- end: Left Menu -->