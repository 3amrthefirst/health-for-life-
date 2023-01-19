 
   <div>
        <a href="{{route('Setting')}}" class="btn head-btn" aria-haspopup="true" aria-expanded="false">
            <img src="{{asset('/assets/imgs/setting-colored.png')}}">
        </a>
    </div>

    <!-- <div class="dropdown dropright">
        <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{asset('/assets/imgs/lang.png')}}">
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @foreach(language() as $item)
                <a class="dropdown-item" href="{{route('Language',$item->id)}}">{{ $item->language}}</a>
            @endforeach
        </div>
    </div> -->

    <div class="dropdown dropright">
        <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{asset('/assets/imgs/avatar.png')}}" class="avatar-img" />
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{route('Logout')}}">Logout</a>
        </div>

        <div class="dropdown-menu p-2 mt-2" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#">
            <br>
            </a>
            <a class="dropdown-item" href="{{ url('/') }}" style="color:#4E45B8;"><span><img src="{{ asset('assets/imgs/Logout-sm.png') }}" class="mr-2"></span>{{__('label.logout')}}</a>
        </div>
    </div>