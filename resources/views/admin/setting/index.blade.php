@extends('admin.layout.page-app')
@section('content')
@include('admin.layout.sidebar')
<div class="right-content">

  <header class="header">
      <div class="title-control">
          <h1 class="page-title">{{__('label.setting')}}</h1>
      </div>
      <div class="head-control">
          @include('admin.layout.header_setting')
      </div>
  </header>

  <div class="row top-20 ml-2 mr-2">
      <div class="col-md-12">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
              <li class="breadcrumb-item active"><a href="{{route('Setting')}}">{{__('label.setting')}}</a></li>
          </ol>
      </div>
  </div>

  <ul class="nav nav-pills custom-tabs inline-tabs ml-3 mr-3" id="pills-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="pills-settings-tab" data-toggle="pill" href="#pills-settings" role="tab"
            aria-controls="pills-settings" aria-selected="true">{{__('label.app_setting')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-password-tab" data-toggle="pill" href="#pills-password" role="tab"
            aria-controls="pills-password" aria-selected="false">{{__('label.change_password')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="pills-admob-tab" data-toggle="pill" href="#pills-admob" role="tab"
            aria-controls="pills-admob" aria-selected="false">{{__('label.admob')}}</a>
    </li>
    <!-- <li class="nav-item">
        <a class="nav-link" id="pills-facebook-tab" data-toggle="pill" href="#pills-facebook" role="tab"
            aria-controls="pills-facebook" aria-selected="false">{{__('label.facebook')}}</a>
    </li> -->
  </ul>

  <div class="tab-content mr-3 ml-3" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-settings" role="tabpanel" aria-labelledby="pills-settings-tab">
      <div class="app-right-btn">
          <a href="{{route('EmailSetting')}}" class="btn btn-default">{{__('label.smtp')}}</a>
      </div> 

      <div class="card custom-border-card">
        <h5 class="card-header">{{__('label.app_configrations')}}</h5>
        <div class="card-body">
          <div class="input-group">
            <div class="col-2">
              <label class="d-flex align-items-center ml-5">{{__('label.api_path')}}</label>
            </div>
            <input type="text" readonly value="{{url('/')}}/api" name="api_path" class="form-control" style="background-color:matte gray;" id="api_path">
            <div class="input-group-text btn" style="background-color:matte gray;" onclick="Function_Api_path()">
              <img src="{{asset('/assets/imgs/copy.png')}}" alt=""/> 
            </div>
          </div>
        </div>
      </div>

      <div class="card custom-border-card">
          <h5 class="card-header">{{__('label.app_setting')}}</h5>
          <div class="card-body">
            <form class="cmxform" autocomplete="off" id="save_setting" enctype="multipart/form-data">
              {!!csrf_field()!!}
                                
              <div class="form-group row">
                <div class="col-md-6">
                  <label>App Name</label>
                  <input type="text" class="form-control" name="app_name" value="{{$result['app_name']}}">
                </div>  
                <div class="col-md-6">
                  <label>{{__('label.host_email')}}</label>
                  <input type="text" class="form-control" name="host_email" value="{{$result['host_email']}}">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label>{{__('label.app_version')}}</label>
                  <input type="text" class="form-control" name="app_version" value="{{$result['app_version']}}">
                </div>
                <div class="col-md-6">
                  <label>{{__('label.author')}}</label>
                  <input type="text" class="form-control" name="author" value="{{$result['author']}}">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label>{{__('label.email')}}</label>
                  <input type="text" class="form-control" name="email" value="{{$result['email']}}">
                </div>
                <div class="col-md-6">
                  <label>{{__('label.contact')}}</label>
                  <input type="text" class="form-control" name="contact" value="{{$result['contact']}}">
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <label for="bio">{{__('label.description')}}</label>
                  <textarea type="text" class="form-control" name="app_description" rows="4" id="app_desripation">{{$result['app_description']}} </textarea>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-12">
                  <label for="bio">{{__('label.privcy_policy')}}</label>
                  <textarea type="text" class="form-control" name="privacy_policy" rows="4" id="privacy_policy">{{$result['privacy_policy']}} </textarea>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-md-6">
                  <label for="input-2">{{__('label.app_image')}}</label>
                  <div class="custom-file">
                      <input type="file" name="app_logo"  class="custom-file-input" id="fileupload">
                      <input type="hidden" name="old_image" value="@if($result){{$result['app_logo']}}@endif">
                      <label class="custom-file-label" for="customFileLangHTML" data-browse="Bestand kiezen">Select Image</label>
                  </div>
                  <p class="noteMsg">Note: Image Size must be lessthan 2MB.Image Height and Width Maximum - 100x200</p>
                </div>
                <div class="col-md-6">
                  <label>{{__('label.website')}}</label>
                  <input type="text" class="form-control" name="website" value="{{$result['website']}}">
                </div>
              </div>
              <div class="col-md-12 mb-2">
                  <?php $app = (image_path('setting')).'/'.$result['app_logo'] ; ?>
                  <img id="preview-image-before-upload" height="150" width="150" alt="preview image" src="{{$app}}">
              </div>

              <div class="border-top pt-3 text-right">
                  <button class="btn btn-default mw-120" type="button" onclick="save_setting()">{{__('label.save')}}</button>
                  <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('Setting')}}">{{__('label.cancel')}}</a>
              </div>
            </form>
          </div>
      </div>
      
      <!-- <div class="card custom-border-card">
        <h5 class="card-header">{{__('label.currency')}}</h5>
        <div class="card-body">
          <form class="cmxform" autocomplete="off"  action="{{route('CurrencySave')}}" method="post" enctype="multipart/form-data">
            {!!csrf_field()!!}
            <div class="form-group">
              <label>{{__('label.set_currency')}}</label>
              <select name="currency" class="form-control">
                <option>
                  Select Currency
                </option>
            
                @foreach($currency as $row)
                <option value="{{$row->id}}" {{ ( $row->status == 1) ? 'selected' : '' }}>
                  ({{$row->symbol}}){{$row->name}}
                </option>
                @endforeach
                
              </select>
            </div>
            <div class="text-right pt-3">
              <button type="submit" class="btn btn-default mw-120">{{__('label.save')}}</button>
            </div>
          </form>
        </div>
      </div> -->
    </div>

    <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-Premium-tab">
        <div class="card custom-border-card mt-3">
            <h5 class="card-header">{{__('label.change_password')}}</h5>
            <div class="card-body">
                <form class="cmxform" autocomplete="off" id="save_update_password" enctype="multipart/form-data">
                <input type="hidden" name="id" value="1">
                {!! csrf_field() !!}
                    <div class="form-group">
                        <label>{{__('label.old_password')}}</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter New Password">
                    </div>
                    
                    <div class="form-group">
                        <label>{{__('label.new_password')}}</label>
                        <input type="password" class="form-control" id="new_password" name="new_password"
                            placeholder="Enter confirm  Password">
                    </div>
                    
                    <div class="form-group">
                        <button type="button" class="btn btn-default mw-120" onclick="save_update_password()">{{__('label.save')}}</button>
                        <a class="btn btn-default btn-dark mw-120" style="background: black;" type="button" href="{{route('Setting')}}">{{__('label.cancel')}}</a>
                    </div>
                  </form>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="pills-admob" role="tabpanel" aria-labelledby="pills-admob-tab">
      <div class="card custom-border-card mt-3">
        <h5 class="card-header">{{__('label.android_settings')}}</h5>
      
        <div class="card-body">
        <form class="cmxform" autocomplete="off" id="save_update_admob" enctype="multipart/form-data">
                    {!!csrf_field()!!}
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.banner_add')}}</label>
                  
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="banner_ad" name="Banner_Ad" {{ ($result['Banner_Ad']=='yes')? "checked" : "" }} value="yes" class="custom-control-input" >
                      <label class="custom-control-label" for="banner_ad">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="banner_ad1" name="Banner_Ad" {{ ($result['Banner_Ad']=='no')? "checked" : "" }} value="yes" class="custom-control-input">
                      <label class="custom-control-label" for="banner_ad1">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Interstital_Ad')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="interstial_ad" name="Interstital_Ad" {{ ($result['Interstital_Ad']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"  >
                      <label class="custom-control-label" for="interstial_ad">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="interstial_ad1" name="Interstital_Ad" {{ ($result['Interstital_Ad']=='no')? "checked" : "" }} value="yes" class="custom-control-input" >
                      <label class="custom-control-label" for="interstial_ad1">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Reward_Ad')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="custom_ad" name="Reward_Ad" {{ ($result['Reward_Ad']=='yes')? "checked" : "" }} value="yes"  class="custom-control-input"   >
                      <label class="custom-control-label" for="custom_ad">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="custom_ad1" name="Reward_Ad" {{ ($result['Reward_Ad']=='no')? "checked" : "" }} value="yes"  class="custom-control-input">
                      <label class="custom-control-label" for="custom_ad1">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Banner_Ad_ID')}}</label>
                  <input type="text" class="form-control" name="Banner_Ad_ID" value="{{$result['Banner_Ad_ID']}}"  placeholder="Enter Reward Ad ID">
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Interstital_Ad_ID')}}</label>
                  <input type="text" class="form-control" name="Interstital_Ad_ID" value="{{$result['Interstital_Ad_ID']}}"  placeholder="Enter Interstital Ad ID">
                </div>
                <div class="form-group">
                  <label>{{__('label.Interstital_Ad_Click')}}</label>
                  <input type="text" class="form-control" name="Interstita_Ad_Click" value="{{$result['Interstita_Ad_Click']}}"  placeholder="Enter Reward Ad Click">
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Reward_Ad_ID')}}</label>
                  <input type="text" class="form-control" name="Reward_Ad_ID" value="{{$result['Reward_Ad_ID']}}"  placeholder="Enter Banner Ad ID">
                </div>
                <div class="form-group">
                  <label>{{__('label.Reward_Ad_Click')}}</label>
                  <input type="text" class="form-control" name="Reward_Ad_Click" value="{{$result['Reward_Ad_Click']}}"  placeholder="Enter Interstital Ad Click">
                </div>
                
              </div>
              
              
            </div>
            <div class="text-right pt-3">
            <button type="button" class="btn btn-default mw-120" onclick="save_update_admob()">{{__('label.save')}}</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card custom-border-card mt-3">
        <h5 class="card-header">{{__('label.iso_setting')}}</h5>
      
        <div class="card-body">
        <form class="cmxform" autocomplete="off" id="save_update_iso" enctype="multipart/form-data">
                    {!!csrf_field()!!}
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.banner_add')}}</label>
                  
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="banner_ad_ios" name="Banner_Ad_ios" {{ ($result['Banner_Ad_ios']=='yes')? "checked" : "" }} value="yes" class="custom-control-input" >
                      <label class="custom-control-label" for="banner_ad_ios">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="banner_ad1_ios" name="Banner_Ad_ios" {{ ($result['Banner_Ad_ios']=='no')? "checked" : "" }} value="yes" class="custom-control-input">
                      <label class="custom-control-label" for="banner_ad1_ios">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Interstital_Ad')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="interstial_ad_ios" name="Interstital_Ad_ios" {{ ($result['Interstital_Ad_ios']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                      <label class="custom-control-label" for="interstial_ad_ios">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="interstial_ad1_ios" name="Interstital_Ad_ios" {{ ($result['Interstital_Ad_ios']=='no')? "checked" : "" }} value="yes" class="custom-control-input" >
                      <label class="custom-control-label" for="interstial_ad1_ios">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Reward')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                    <input type="radio" id="custom_ad_ios" name="Reward_Ad_ios" {{ ($result['Reward_Ad_ios']=='yes')? "checked" : "" }} value="no" class="custom-control-input" >
                      <label class="custom-control-label" for="custom_ad_ios">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="custom_ad1_ios" name="Reward_Ad_ios" {{ ($result['Reward_Ad_ios']=='no')? "checked" : "" }} value="no" class="custom-control-input" >
                      <label class="custom-control-label" for="custom_ad1_ios">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Banner_Ad_ID')}}</label>
                  <input type="text" class="form-control" name="Banner_Ad_ID_ios" value="{{$result['Banner_Ad_ID_ios']}}"  placeholder="Enter Reward Ad ID">
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Interstital_Ad_ID')}}</label>
                  <input type="text" class="form-control" name="Interstital_Ad_ID_ios" value="{{$result['Interstital_Ad_ID_ios']}}"  placeholder="Enter Interstital Ad ID">
                </div>
                <div class="form-group">
                  <label>{{__('label.Interstital_Ad_Click')}}</label>
                  <input type="text" class="form-control" name="Interstita_Ad_Click_ios" value="{{$result['Interstita_Ad_Click_ios']}}"  placeholder="Enter Reward Ad Click">
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Reward_Ad_ID')}}</label>
                  <input type="text" class="form-control" name="Reward_Ad_id_iso" value="{{$result['Reward_Ad_id_iso']}}"  placeholder="Enter Banner Ad ID">
                </div>
                <div class="form-group">
                  <label>{{__('label.Reward_Ad_Click')}}</label>
                  <input type="text" class="form-control" name="Reward_Ad_click_iso" value="{{$result['Reward_Ad_click_iso']}}"  placeholder="Enter Interstital Ad Click">
                </div>
                
              </div>
              
              
            </div>
            <div class="text-right pt-3">
            <button type="button" class="btn btn-default mw-120" onclick="save_update_iso()">{{__('label.save')}}</button>
            </div>
          </form>
        </div>
      </div>
      
    </div>

    <!-- <div class="tab-pane fade" id="pills-facebook" role="tabpanel" aria-labelledby="pills-facebook-tab">
      <div class="card custom-border-card mt-3">
        <h5 class="card-header">{{__('label.android_settings')}}</h5>
      
        <div class="card-body">
        <form class="cmxform" autocomplete="off" id="save_update_facebook_admob" enctype="multipart/form-data">
                    {!!csrf_field()!!}
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.rewar_video_status')}}</label>
                    <div class="radio-group">
                      <div class="custom-control custom-radio">
                        <input type="radio" id="fb_rewardvideo_status" name="fb_rewardvideo_status" {{ ($result['fb_rewardvideo_status']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                        <label class="custom-control-label" for="fb_rewardvideo_status">{{__('label.yes')}}</label>
                      </div>
                      <div class="custom-control custom-radio">
                        <input type="radio" id="fb_rewardvideo_status1" name="fb_rewardvideo_status" {{ ($result['fb_rewardvideo_status']=='no')? "checked" : "" }} value="yes" class="custom-control-input"   >
                        <label class="custom-control-label" for="fb_rewardvideo_status1">{{__('label.no')}}</label>
                      </div>
                    </div>
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Native_Status')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="fb_native_status" name="fb_native_status" {{ ($result['fb_native_status']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                      <label class="custom-control-label" for="fb_native_status">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="fb_native_status1" name="fb_native_status" {{ ($result['fb_native_status']=='no')? "checked" : "" }} value="yes" class="custom-control-input" >
                      <label class="custom-control-label" for="fb_native_status1">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Banner_Status')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                    <input type="radio" id="fb_banner_status" name="fb_banner_status" {{ ($result['fb_banner_status']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                      <label class="custom-control-label" for="fb_banner_status">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                    <input type="radio" id="fb_banner_status1" name="fb_banner_status" {{ ($result['fb_banner_status']=='no')? "checked" : "" }} value="yes" class="custom-control-input" >
                      <label class="custom-control-label" for="fb_banner_status1">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Rewarvideo_Id')}}</label>
                  <input type="text" class="form-control" name="fb_rewardvideo_id" value="{{$result['fb_rewardvideo_id']}}"  placeholder="Enter Reward Ad ID">
                </div>
                <div class="form-group">
                  <label>{{__('label.Interstiatial_Status')}}</label>
                    <div class="radio-group">
                      <div class="custom-control custom-radio">
                        <input type="radio" id="fb_interstiatial_status" name="fb_interstiatial_status" {{ ($result['fb_interstiatial_status']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                        <label class="custom-control-label" for="fb_interstiatial_status">{{__('label.yes')}}</label>
                      </div>
                      <div class="custom-control custom-radio">
                        <input type="radio" id="fb_interstiatial_status1" name="fb_interstiatial_status" {{ ($result['fb_interstiatial_status']=='no')? "checked" : "" }} value="yes" class="custom-control-input"   >
                        <label class="custom-control-label" for="fb_interstiatial_status1">{{__('label.no')}}</label>
                      </div>
                    </div>
                </div>
                <div class="form-group">
                  <label>{{__('label.Interstiatial_Id')}}</label>
                  <input type="text" class="form-control" name="fb_interstiatial_id" value="{{$result['fb_interstiatial_id']}}"  placeholder="Enter Interstital Ad ID">
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Native_Id')}}</label>
                  <input type="text" class="form-control" name="fb_native_id" value="{{$result['fb_native_id']}}"  placeholder="Enter Interstital Ad ID">
                </div>
                <div class="form-group">
                  <label>{{__('label.NativeFull_Status')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="fb_native_full_status" name="fb_native_full_status" {{($result['fb_native_full_status']=='yes')? "checked" : "" }} value="yes"  class="custom-control-input"   >
                      <label class="custom-control-label" for="fb_native_full_status">{{__('label.yes')}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="fb_native_full_status1" name="fb_native_full_status" {{($result['fb_native_full_status']=='no')? "checked" : "" }} value="yes"  class="custom-control-input" >
                      <label class="custom-control-label" for="fb_native_full_status1">{{__('label.no')}}</label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>{{__('label.Native_FUll_id')}}</label>
                  <input type="text" class="form-control" name="fb_native_full_id" value="{{$result['fb_native_full_id']}}"  placeholder="Enter Reward Ad Click">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Banner_ID')}}</label>
                  <input type="text" class="form-control" name="fb_banner_id" value="{{$result['fb_banner_id']}}"  placeholder="Enter Banner Ad ID">
                </div>
              </div>

            </div>
            <div class="border-top pt-3 text-right">
              <button type="button" class="btn btn-default mw-120" onclick="save_update_facebook_admob()">{{__('label.save')}}</button>
            </div>
          </form>
        </div>
      </div>
    
        <div class="card custom-border-card mt-3">
        <h5 class="card-header">{{__('label.iso_setting')}}</h5>
      
        <div class="card-body">
        <form class="cmxform" autocomplete="off" id="save_update_facebook_iso" enctype="multipart/form-data">
                    {!!csrf_field()!!}
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.rewar_video_status')}}</label>
                    <div class="radio-group">
                      <div class="custom-control custom-radio">
                        <input type="radio" id="fb_rewardvideo_status_iso" name="fb_rewardvideo_status_iso" {{ ($result['fb_rewardvideo_status_iso']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                        <label class="custom-control-label" for="fb_rewardvideo_status_iso">Yes</label>
                      </div>
                      <div class="custom-control custom-radio">
                        <input type="radio" id="fb_rewardvideo_status_iso1" name="fb_rewardvideo_status_iso" {{ ($result['fb_rewardvideo_status_iso']=='no')? "checked" : "" }} value="yes" class="custom-control-input"   >
                        <label class="custom-control-label" for="fb_rewardvideo_status_iso1">No</label>
                      </div>
                    </div>
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Native_Status')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="fb_native_status_iso" name="fb_native_status_iso" {{ ($result['fb_native_status_iso']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                      <label class="custom-control-label" for="fb_native_status_iso">Yes</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="fb_native_status_iso1" name="fb_native_status_iso" {{ ($result['fb_native_status_iso']=='no')? "checked" : "" }} value="yes" class="custom-control-input" >
                      <label class="custom-control-label" for="fb_native_status_iso1">No</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Banner_Status')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                    <input type="radio" id="fb_banner_status_iso" name="fb_banner_status_iso" {{ ($result['fb_banner_status_iso']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                      <label class="custom-control-label" for="fb_banner_status">Yes</label>
                    </div>
                    <div class="custom-control custom-radio">
                    <input type="radio" id="fb_banner_status_iso1" name="fb_banner_status_iso" {{ ($result['fb_banner_status_iso']=='no')? "checked" : "" }} value="yes" class="custom-control-input" >
                      <label class="custom-control-label" for="fb_banner_status_iso1">No</label>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Rewarvideo_Id')}}</label>
                  <input type="text" class="form-control" name="fb_rewardvideo_id_iso" value="{{$result['fb_rewardvideo_id_iso']}}"  placeholder="Enter Reward Ad ID">
                </div>

                <div class="form-group">
                  <label>{{__('label.Interstiatial_Status')}}</label>
                    <div class="radio-group">
                      <div class="custom-control custom-radio">
                        <input type="radio" id="fb_interstiatial_status_iso" name="fb_interstiatial_status_iso" {{ ($result['fb_interstiatial_status_iso']=='yes')? "checked" : "" }} value="yes" class="custom-control-input"   >
                        <label class="custom-control-label" for="fb_interstiatial_status_iso">Yes</label>
                      </div>
                      <div class="custom-control custom-radio">
                        <input type="radio" id="fb_interstiatial_status_iso1" name="fb_interstiatial_status_iso" {{ ($result['fb_interstiatial_status_iso']=='no')? "checked" : "" }} value="yes" class="custom-control-input"   >
                        <label class="custom-control-label" for="fb_interstiatial_status_iso1">No</label>
                      </div>
                    </div>
                </div>
                <div class="form-group">
                  <label>{{__('label.Interstiatial_Id')}}</label>
                  <input type="text" class="form-control" name="fb_interstiatial_id_iso" value="{{$result['fb_interstiatial_id_iso']}}"  placeholder="Enter Interstital Ad ID">
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Native_Id')}}</label>
                  <input type="text" class="form-control" name="fb_native_id_iso" value="{{$result['fb_native_id_iso']}}"  placeholder="Enter Interstital Ad ID">
                </div>

                <div class="form-group">
                  <label>{{__('label.NativeFull_Status')}}</label>
                  <div class="radio-group">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="fb_native_full_status_iso" name="fb_native_full_status_iso" {{($result['fb_native_full_status_iso']=='yes')? "checked" : "" }} value="yes"  class="custom-control-input"   >
                      <label class="custom-control-label" for="fb_native_full_status">Yes</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="fb_native_full_status_iso1" name="fb_native_full_status_iso" {{($result['fb_native_full_status_iso']=='no')? "checked" : "" }} value="yes"  class="custom-control-input" >
                      <label class="custom-control-label" for="fb_native_full_status_iso1">No</label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>{{__('label.Native_FUll_id')}}</label>
                  <input type="text" class="form-control" name="fb_native_full_id_iso" value="{{$result['fb_native_full_id_iso']}}"  placeholder="Enter Reward Ad Click">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label>{{__('label.Banner_ID')}}</label>
                  <input type="text" class="form-control" name="fb_banner_id_iso" value="{{$result['fb_banner_id_iso']}}"  placeholder="Enter Banner Ad ID">
                </div>
              </div>
            </div>
            <div class="border-top pt-3 text-right">
            <button type="button" class="btn btn-default mw-120" onclick="save_update_facebook_iso()">{{__('label.save')}}</button>
            </div>
          </form>
        </div>
      </div>

    </div>   -->
  </div>

@endsection

@section('pagescript')
    <script type="text/javascript">
        
        function Function_Api_path(){
            var copyText =document.getElementById("api_path");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand('copy');
            alert("Copied The API Path: " + copyText.value);
        }
    
        $(document).ready(function (e) {
            $('#fileupload').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => { 
                $('#preview-image-before-upload').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]); 
            });
        });
    
        function save_setting(){
            var formData = new FormData($("#save_setting")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("SettingSave") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    
                get_responce_message(resp, 'save_setting', '{{route("Setting")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }

        function save_update_password(){
            var formData = new FormData($("#save_update_password")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("PwdSave") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_update_password', '{{route("Setting")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }

        function save_update_admob(){
            var formData = new FormData($("#save_update_admob")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("AdmobUpdate") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_update_admob', '{{route("Setting")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }

        function save_update_iso(){
            var formData = new FormData($("#save_update_iso")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("IsoUpdate") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_update_iso', '{{route("Setting")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }

        function save_update_facebook_admob(){
            var formData = new FormData($("#save_update_facebook_admob")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("facebookupdate") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_update_facebook_admob', '{{route("Setting")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }

        function save_update_facebook_iso(){
            var formData = new FormData($("#save_update_facebook_iso")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("facebookiso") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function (resp) {
                get_responce_message(resp, 'save_update_facebook_iso', '{{route("Setting")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg,'failed');         
                }
            });
        }
    </script>
@endsection