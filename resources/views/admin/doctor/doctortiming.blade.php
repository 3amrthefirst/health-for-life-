@extends('admin.layout.page-app')
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        <header class="header">
            <div class="title-control">
                <h1 class="page-title">{{__('label.add_doctor')}}</h1>
            </div>
            <div class="head-control">
                @include('admin.layout.header_setting')
            </div>
        </header>

        <div class="body-content">

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active"><a href="{{route('doctor.index')}}">{{__('label.doctor')}}</a></li>
                        <li class="breadcrumb-item active"><a href="">{{__('label.doctor_timing')}}</a></li>
                    </ol>
                </div>
            </div>

            <div class="card-body">
                <div class="card-title">
                    <div class="row" style="display: flex;justify-content: center;">
                        <div class="col-8">
                            <div class="card">
                                <form class="cmxform" autocomplete="off" id="save_appointment_slot" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctor_id;?>">
                                        <input type="hidden" name="siteurl" id="siteurl" value="{{base_path()}}">

                                        <div id="accordion" class="custom-accordion">
                                            <?php
                                                foreach ($filanArray as $key => $value) {
                                                    $datas[$value->week_day] = $value->time_slotes;
                                                }

                                                foreach ($weekDay as $key => $value) {?>

                                                    <div class="shadow-none">
                                                        <input type="hidden" name="arr[]" id="day_id_<?php echo $key; ?>" value="<?php echo $key; ?>">

                                                        <a href="#day<?php echo $key; ?>" class="text-dark collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="day<?php echo $key; ?>">
                                                            <div class="card-header border mt-1 border-secondary" id="head<?php echo $key; ?>">
                                                                <h6 class="m-0"><?php echo $value;?><i class="fa fa-angle-double-down float-right"></i></h6>
                                                            </div>
                                                        </a>

                                                        <div id="day<?php echo $key; ?>" class="collapse" aria-labelledby="head<?php echo $key; ?>" data-parent="#accordion" style="">
                                                            <div class="card-body border border-dark">

                                                                <div class="row">
                                                                    <div class="form-group change ml-3 mb-0">
                                                                        <button type="button" class="btn btn-success" onclick="addnewslot('<?php echo $key; ?>')">Add Time</button>
                                                                    </div>
                                                                </div>

                                                                <div id="day_<?php echo $key; ?>">

                                                                <?php if(isset($datas[$key]) AND count($datas[$key]) >0){
                                                                    foreach ($datas[$key] as $v =>$day) { 
                                                                    ?>
                                                                        <div class="slotdiv slotsecond border border-secondary p-2 bg-light mt-3" id="slotdiv<?php echo $key.$v; ?>">

                                                                            <div class="row">

                                                                                <div class="col-md-3">
                                                                                    <label for="formrow-firstname-input">START TIME</label>
                                                                                    <input type="time" class="form-control" id="start_time_<?php echo $key; ?>_<?php echo $v; ?>" name="arr[<?php echo $key; ?>][start_time][]" value="<?php echo $day['start_time']?>" onchange="getslot(this.value,'<?php echo $key; ?>','<?php echo $v; ?>')">
                                                                                </div>

                                                                                <div class="col-md-3">
                                                                                    <label for="formrow-firstname-input">END TIME</label>
                                                                                    <input type="time" class="form-control" id="end_time_<?php echo $key; ?>_<?php echo $v; ?>" name="arr[<?php echo $key; ?>][end_time][]"  value="<?php echo $day['end_time']?>" onchange="getslot(this.value,'<?php echo $key; ?>','<?php echo $v; ?>')">
                                                                                </div>

                                                                                <div class="col-md-3">
                                                                                    <label for="formrow-firstname-input">Duration</label>
                                                                                    <select class="form-control" name="arr[<?php echo $key; ?>][duration][]" id="duration_<?php echo $key; ?>_<?php echo $v; ?>" onchange="getslot(this.value,'<?php echo $key; ?>','<?php echo $v; ?>')">
                                                                                        <option value="">Select Duration</option>
                                                                                        <option value='15' <?php if($day['time_duration'] == '15'){?>selected='selected'<?php } ?>>15 Minutes</option>
                                                                                        <option value='30' <?php if($day['time_duration'] == '30'){?>selected='selected'<?php } ?>>30 Minutes</option>
                                                                                        <option value='45' <?php if($day['time_duration'] == '45'){?>selected='selected'<?php } ?>>45 Minutes</option>
                                                                                    </select>
                                                                                </div>

                                                                                <?php if($v >0 ){?>
                                                                                    <div class="col-md-3" style="margin-top: 30px;">
                                                                                        <button type="button" class="btn btn-danger" onclick="removescdehule('<?php echo $key;?>','<?php echo $v;?>')"> Delete </button>
                                                                                    </div>
                                                                                <?php } ?>	

                                                                                <!-- <div class="col-md-3" style="margin-top: 28px;"> </div> -->
                                                                            </div>

                                                                            <div class="row p-2" id="slot_<?php echo $key; ?>_<?php echo $v; ?>">
                                                                                <?php foreach ($day['time_schedul'] as $time_schedul) {?>
                                                                                    <div class="border border-secondary  ml-2 mt-3 pt-2 pb-2 col-md-2 text-center d-flex justify-content-between">
                                                                                        <span class="slotshow"><?php echo $time_schedul['start_time'];?></span> To <span class="slotshow"><?php echo $time_schedul['end_time'];?></span> 
                                                                                    </div>
                                                                                <?php }?>
                                                                            </div>

                                                                        </div>
                                                                    <?php 
                                                                    }?> </div> <?php  }  
                                                                ?>
                                                                <input type="hidden" id="total_slot_day_<?php echo $key; ?>" value="3">

                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php }
                                            ?>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <button class="btn btn-default mw-120" type="button" onclick="save_appointment_slot()">{{__('label.update')}}</button>
                                        </div> 
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function save_appointment_slot(){
            var formData = new FormData($("#save_appointment_slot")[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            type:'POST',
            url:'{{route("AppointmentSlot")}}',
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function (resp) {
                get_responce_message(resp, 'save_appointment_slot', '{{route("doctor.index")}}');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#dvloader").hide();
                toastr.error(errorThrown.msg,'failed');         
            }
            });
        }

        function checkduration(day_id, cid) {

            if ($("#start_time_" + day_id + "_" + cid).val() == "") {
                alert($("#start1val").val());
            } else {
                var strStartTime = $("#start_time_" + day_id + "_" + cid).val();
                var strEndTime = $("#end_time_" + day_id + "_" + cid).val();
                
                var startTime = new Date().setHours(GetHours(strStartTime), GetMinutes(strStartTime), 0);   
                var endTime = new Date(startTime)
                endTime = endTime.setHours(GetHours(strEndTime), GetMinutes(strEndTime), 0);

                if (startTime > endTime) {

                    // alert($("#sge").val());
                    alert("Start Time is Greater Than End Time.");
                    $("#start_time_" + day_id + "_" + cid).val("");
                    $("#end_time_" + day_id + "_" + cid).val("");
                }
                if (startTime == endTime) {

                    // alert($("#sequale").val());
                    alert("Start Time is Equal to End Time.");
                    $("#start_time_" + day_id + "_" + cid).val("");
                    $("#end_time_" + day_id + "_" + cid).val("");
                }
                // if (startTime < endTime) {
                //     $.ajax({
                //         url: $("#siteurl").val() + "/findpossibletime",
                //         method: "get",
                //         data: {
                //             start_time: strStartTime,
                //             end_time: $("#end_time_" + day_id + "_" + cid).val(),
                //             duration: $("#duration_" + day_id + "_" + cid).val()
                //         },
                //         success: function(data) {
                //             var duval = $("#duration_" + day_id + "_" + cid).val();
                //             if ($("#duration_" + day_id + "_" + cid).val() != "") {
                //                 $.ajax({
                //                     url: '{{route("doctor.GenerateSlot")}}',
                //                     method: "get",
                //                     data: {
                //                         start_time: $("#start_time_" + day_id + "_" + cid).val(),
                //                         end_time: $("#end_time_" + day_id + "_" + cid).val(),
                //                         duration: $("#duration_" + day_id + "_" + cid).val()
                //                     },
                //                     success: function(data) {
                //                         console.log(data);
                //                         $("#slot_" + day_id + "_" + cid).html(data);
                //                     }
                //                 });
                //             }
                //             $("#duration_" + day_id + "_" + cid).html(data);
                //         }
                //     });
                // }
            }
        }

        function getslot(value, day_id, cid) {

            if ($("#start_time_" + day_id + "_" + cid).val() == "") {
                alert($("#start1val").val());
            } else {

                var strStartTime = $("#start_time_" + day_id + "_" + cid).val();
                var strEndTime = $("#end_time_" + day_id + "_" + cid).val();

                var startTime = new Date().setHours(GetHours(strStartTime), GetMinutes(strStartTime), 0);
                var endTime = new Date(startTime)
                endTime = endTime.setHours(GetHours(strEndTime), GetMinutes(strEndTime), 0);

                if (startTime > endTime) {

                    // alert($("#sge").val());
                    alert("Start Time is Greater Than End Time.");
                    $("#start_time_" + day_id + "_" + cid).val("");
                    $("#end_time_" + day_id + "_" + cid).val("");
                }
                if (startTime == endTime) {

                    // alert($("#sequale").val());
                    alert("Start Time is Equal to End Time.");
                    $("#start_time_" + day_id + "_" + cid).val("");
                    $("#start_time_" + day_id + "_" + cid).val("");
                }
                // if (startTime < endTime) {
                //     if ($("#duration_" + day_id + "_" + cid) != "") {
                //         $.ajax({
                //             url: $("#siteurl").val() + "admin/doctors/generateslot",
                //             method: "get",
                //             data: {
                //                 start_time: $("#start_time_" + day_id + "_" + cid).val(),
                //                 end_time: $("#end_time_" + day_id + "_" + cid).val(),
                //                 duration: $("#duration_" + day_id + "_" + cid).val()
                //             },
                //             success: function(data) {
                //                 console.log(data);
                //                 $("#slot_" + day_id + "_" + cid).html(data);
                //             }
                //         });
                //     } else {
                //         alert($("#selduration").val());
                //     }
                // }
            }
        }

        function GetHours(d) {

            var h = parseInt(d.split(':')[0]);
            if (d.split(':')[1].split(' ')[1] == "PM") {
                h = h + 12;
            }
            return h;
        }

        function GetMinutes(d) {
            // return d;
            return parseInt(d.split(':')[1].split(' ')[0]);
        }

        function addnewslot(day_id) {
            var slotid = $("#total_slot_day_" + day_id).val();
            var txt = '<div class="slotdiv slotsecond border border-secondary p-2 bg-light mt-3" id="slotdiv' + day_id + slotid + '">';
                txt += '<div class="row"><div class="col-md-3">';
                txt += '<label for="formrow-firstname-input"> START TIME </label>';
                txt += '<input type="time" class="form-control" id="start_time_' + day_id + '_' + slotid + '" required="" name="arr[' + day_id + '][start_time][]" onchange="checkduration(' + day_id + ',' + slotid + ')">';
                txt += '</div><div class="col-md-3"><label for="formrow-firstname-input"> END TIME </label>';
                txt += '<input type="time" required="" class="form-control" id="end_time_' + day_id + '_' + slotid + '" name="arr[' + day_id + '][end_time][]"  onchange="checkduration(' + day_id + ',' + slotid + ')"></div>';
                txt += '<div class="col-md-3">';
                txt += '<label for="formrow-firstname-input">Duration</label>';
                txt += '<select class="form-control" required="" name="arr[' + day_id + '][duration][]" id="duration_' + day_id + '_' + slotid + '" onchange="getslot(this.value,' + day_id + ',' + slotid + ')">';
                txt += '<option value="">Select Duration</option>';
                txt += '<option value="15">15 Minutes</option>';
                txt += '<option value="30">30 Minutes</option>';
                txt += '<option value="45">45 Minutes</option>';
                txt += '</select>';
                txt += '</div>';
                txt += '<div class="col-md-3" style="margin-top: 28px;">';
                txt += '<button type="button" class="btn btn-danger" onclick="removescdehule(' + day_id + ',' + slotid + ')"> Delete </button>';
                txt += '</div>';
                txt += '</div>';
                txt += '<div class="row boxmargin" id="slot_' + day_id + '_' + slotid + '">';
                txt += '</div>';
                txt += '</div>';

            $("#day_" + day_id).append(txt);
            $("#total_slot_day_" + day_id).val(parseInt(slotid) + parseInt(1));
        }

        function removescdehule(day_id, cid) {
            $("#slotdiv" + day_id + cid).remove();
        }
    </script>
@endsection