<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment_schedulModel;
use App\Models\Appointment_slotsModel;
use App\Models\DoctorModel;
use App\Models\SpecialtieModel;
use DataTables;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Storage;
use URL;
use Validator;

class DoctorController extends Controller
{

    public function index(Request $request)
    {
        $params['data'] = [];
        if ($request->ajax()) {
            $data = DoctorModel::with('doctor')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('Action', function ($row) {

                    $delete = '<form method="POST"  action=" ' . route('doctor.destroy', [$row->id]) . ' ">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Delete" class="btn "><img src="' . url('/public') . '/assets/imgs/trash.png" /></button></form>';

                    $btn = '<div class="d-flex justify-content-around"><a class="btn float-xl-left" title="Edit" href=" ' . route('doctor.edit', [$row->id]) . ' ">';
                    $btn .= '<img src="' . url('/public') . '/assets/imgs/edit.png" />';
                    $btn .= '</a>';
                    $btn .= $delete;
                    $btn .= '</a>';
                    $btn .= '<a class="btn float-xl-left" title="Add Timing" href=" ' . route('doctor.show', [$row->id]) . ' ">';
                    $btn .= '<img src="' . url('/public') . '/assets/imgs/stopwatch.png" />';
                    $btn .= '</a></div>';
                    return $btn;
                })
                ->rawColumns(['Action'])
                ->make(true);
        }
        return view('admin.doctor.index', $params);
    }

    public function create()
    {
        if (Auth::guard('admin')->user()->role_id == 2) {

            return redirect()->route('doctor.index')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            $specialtie = SpecialtieModel::select('*')->get();
            return view('admin.doctor.add', ['data' => $specialtie]);
        }

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:2|max:10',
            'mobile_number' => 'required|min:10',
            'specialties_id' => 'required',
            'address' => 'required',
            'about_us' => 'required',
            'services' => 'required',
            'health_care' => 'required',
            'profile_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }
        try {

            $requestData = $request->all();

            if ($request->instagram_url) {
                $requestData['instagram_url'] = $request->instagram_url;
            } else {
                $requestData['instagram_url'] = "";
            }
            if ($request->facebook_url) {
                $requestData['facebook_url'] = $request->facebook_url;
            } else {
                $requestData['facebook_url'] = "";
            }
            if ($request->twitter_url) {
                $requestData['twitter_url'] = $request->twitter_url;
            } else {
                $requestData['twitter_url'] = "";
            }
            if (isset($requestData['profile_img']) && $requestData['profile_img'] != 'undefined') {
                $files = $requestData['profile_img'];
                $ext = $files->extension();
                $path = base_path('/storage/app/public/doctor/');
                $name = rand() . time() . "." . $ext;
                $files->move($path, $name);
                $requestData['profile_img'] = $name;
            }
            $requestData['working_time'] = "";
            $requestData['latitude'] = "";
            $requestData['longitude'] = "";
            $requestData['type'] = 1;
            $requestData['reference_code'] = "";
            $requestData['total_points'] = 0;
            $requestData['device_token'] = "";
            $requestData['status'] = '1';

            $doctor = DoctorModel::updateOrCreate(['id' => $requestData['id']], $requestData);

            if (isset($doctor->id)) {
                return response()->json(array('status' => 200, 'success' => "Doctor Add Successfully"));
            } else {
                return response()->json(array('status' => 400, 'error' => "Doctor Not Added"));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function show($doctor_id = 0)
    {
        $getWeekDay = $this->getWeekDay($doctor_id);
        $filanArray = [];
        foreach ($getWeekDay as $key => $value) {

            $appointment_slots = Appointment_slotsModel::where('doctor_id', $doctor_id)->where('week_day', $value->week_day)->get();

            $datas = [];
            foreach ($appointment_slots as $key => $slotsvalue) {

                $result = Appointment_schedulModel::where('doctor_id', $doctor_id)->where('week_day', $slotsvalue['week_day'])->where('appointment_slots_id', $slotsvalue['id'])->get();

                $slotsvalue['time_schedul'] = $result;
                $datas[] = $slotsvalue;
            }
            $value->time_slotes = $datas;
            $filanArray[] = $value;
        }
        $data['doctor_id'] = $doctor_id;
        $data['weekDay'] = getWeekDay();
        $data['filanArray'] = $filanArray;

        return view('admin.doctor.doctortiming', $data);
    }

    public function doctor_timing(Request $request)
    {
        if (Auth::guard('admin')->user()->role_id == 2) {
            return redirect()->route('Setting')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            $doctor_schedul = $_REQUEST;

            $doctor_id = $doctor_schedul['doctor_id'];

            Appointment_schedulModel::where('doctor_id', $doctor_id)->delete();
            Appointment_slotsModel::where('doctor_id', $doctor_id)->delete();

            unset($doctor_schedul['arr'][0]);

            foreach ($doctor_schedul['arr'] as $key => $value) {
                if (isset($value['start_time']) && $value['start_time'] != '') {
                    for ($i = 0; $i < count($value['start_time']); $i++) {

                        $doctor_id = $doctor_schedul['doctor_id'];
                        $insert = [];
                        $insert['doctor_id'] = $doctor_schedul['doctor_id'];
                        $insert['week_day'] = $key;
                        $insert['start_time'] = $value['start_time'][$i];
                        $insert['end_time'] = $value['end_time'][$i];
                        $insert['time_duration'] = $value['duration'][$i];
                        $insert['status'] = 1;
                        $appointment_slots_id = Appointment_slotsModel::insertGetId($insert);

                        $slots = $this->getTimeSlot($value['duration'][$i], $value['start_time'][$i], $value['end_time'][$i]);

                        foreach ($slots as $slotsvalue) {
                            $insert1 = [];
                            $insert1['doctor_id'] = $doctor_schedul['doctor_id'];
                            $insert1['week_day'] = $key;
                            $insert1['start_time'] = $slotsvalue['start'];
                            $insert1['end_time'] = $slotsvalue['end'];
                            $insert1['appointment_slots_id'] = $appointment_slots_id;
                            $insert1['status'] = 1;
                            Appointment_schedulModel::insert($insert1);
                        }
                    }
                }
            }

            return response()->json(array('status' => 200, 'success' => "Record Update Sucessfully."));
        }
    }

    public function getTimeSlot($interval, $start, $end)
    {
        $start = new DateTime($start);
        $end = new DateTime($end);
        $start_time = $start->format('H:i'); // Get time Format in Hour and minutes
        $end_time = $end->format('H:i');
        $i = 0;
        while (strtotime($start_time) <= strtotime($end_time)) {
            $start = $start_time;
            $end = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($start_time)));
            $start_time = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($start_time)));
            $i++;
            if (strtotime($start_time) <= strtotime($end_time)) {
                $time[$i]['start'] = $start;
                $time[$i]['end'] = $end;
            }
        }
        return $time;
    }

    public function edit($id)
    {
        $params['data'] = DoctorModel::where('id', $id)->first();
        $specialtie = SpecialtieModel::select('*')->get();
        return view('admin.doctor.edit', $params, ['result' => $specialtie]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:2|max:10',
            'mobile_number' => 'required|min:10',
            'specialties_id' => 'required',
            'address' => 'required',
            'about_us' => 'required',
            'services' => 'required',
            'health_care' => 'required',
            'profile_img' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }
        try {
            if (Auth::guard('admin')->user()->role_id == 2) {
                return response()->json(array('status' => 400, 'errors' => ("This User Is Not Access Your Pages")));
            } else {

                $requestData = $request->all();

                if ($request->instagram_url) {
                    $requestData['instagram_url'] = $request->instagram_url;
                } else {
                    $requestData['instagram_url'] = "";
                }
                if ($request->facebook_url) {
                    $requestData['facebook_url'] = $request->facebook_url;
                } else {
                    $requestData['facebook_url'] = "";
                }
                if ($request->twitter_url) {
                    $requestData['twitter_url'] = $request->twitter_url;
                } else {
                    $requestData['twitter_url'] = "";
                }

                if (isset($requestData['profile_img']) && $requestData['profile_img'] != 'undefined') {

                    $files = $requestData['profile_img'];
                    $ext = $files->extension();
                    $path = base_path('/storage/app/public/doctor/');
                    $name = rand() . time() . "." . $ext;
                    $files->move($path, $name);
                    $requestData['profile_img'] = $name;
                    Storage::disk('public')->delete('doctor/' . $requestData['old_image']);
                } else {
                    $requestData['profile_img'] = $requestData['old_image'];
                }
                $requestData = Arr::except($requestData, ['old_image']);

                $doctor = DoctorModel::updateOrCreate(['id' => $requestData['id']], $requestData);
                if (isset($doctor->id)) {
                    return response()->json(array('status' => 200, 'success' => "Doctor Update Succesfully"));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Doctor Not Updated"));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::guard('admin')->user()->role_id == 2) {
                return redirect()->route('doctor.index')->with('errors', 'This User Is Not Access Your Pages');
            } else {
                $data = DoctorModel::where('id', $id)->first();
                Storage::disk('public')->delete('doctor/' . $data->profile_img);

                $data->delete();
                return redirect()->route('doctor.index')->with('success', "Doctor Delete Succesfully");
            }

        } catch (Exception $e) {
            return redirect()->route('doctor.index')->with($e);
        }
    }

    public function getWeekDay($doctor_id)
    {
        $week_day = Appointment_slotsModel::select('week_day')->where('doctor_id', $doctor_id)->groupBy('week_day')->get();
        return $week_day;
    }

    public function generateslot()
    {
        $slot = $this->getTimeSlot($_REQUEST['duration'], $_REQUEST['start_time'], $_REQUEST['end_time']);

        $text = '<div class="col-md-12 row">';
        foreach ($slot as $key => $value) {
            $text .= '<div class="slotshow col-2">' . $value['start'] . '</div>';
        }
        $text .= '</div>';
        return $text;
        // echo $text;exit;
    }

}
