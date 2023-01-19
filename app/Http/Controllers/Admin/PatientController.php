<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientsModel;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Storage;
use URL;
use Validator;

class PatientController extends Controller
{

    public function index(Request $request)
    {
        $params['data'] = [];

        if ($request->ajax()) {
            $data = PatientsModel::query()->get();
            return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('Action', function ($row) {
                    $delete = ' <form method="POST"  action=" ' . route('patient.destroy', [$row->id]) . ' " " onclick="return confirm(\'Are you sure you want to delete this item\')">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn id="b4"><img src="' . url('/public') . '/assets/imgs/trash.png" /></button></form>';

                    $btn = '<div class="d-flex justify-content-around"><a class="btn float-xl-left" href=" ' . route('patient.edit', [$row->id]) . ' ">';
                    $btn .= '<img src="' . url('/public') . '/assets/imgs/edit.png" />';
                    $btn .= '</a>';
                    $btn .= $delete;
                    $btn .= '<a class="btn float-xl-left btn-link" onclick="change_status(' . $row->id . ' )">';
                    $btn .= $row->status;
                    $btn .= '</a>';
                    $btn .= '</a></div>';

                    return $btn;
                })

                ->rawColumns(['Action'])
                ->make(true);
        }
        return view('admin.patient.index');
    }

    public function create()
    {
        if (Auth::guard('admin')->user()->role_id == 2) {

            return redirect()->route('patient.index')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            return view('admin.patient.add');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'fullname' => 'required',
            'email' => 'required',
            'password' => 'required',
            'mobile_number' => 'required',
            'instagram_url' => 'required',
            'facebook_url' => 'required',
            'twitter_url' => 'required',
            'biodata' => 'required',
        ]);
        if ($validator->fails()) {

            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }
        try {
            $requestData = $request->all();
            if (isset($requestData['profile_img']) && $requestData['profile_img'] != 'undefined') {
                $files = $requestData['profile_img'];
                $ext = $files->extension();
                $path = base_path('/storage/app/public/patient/');
                $name = rand() . time() . "." . $ext;
                $files->move($path, $name);
                $requestData['profile_img'] = $name;

                $requestData['firebase_id'] = '1';
                $requestData['first_name'] = "";
                $requestData['last_name'] = "";
                $requestData['user_name'] = "";
                $requestData['type'] = '1';
                $requestData['location'] = "";
                $requestData['insurance_company_id'] = "";
                $requestData['insurance_no'] = "";
                $requestData['insurance_card_pic'] = "";
                $requestData['allergies_to_medicine'] = "";
                $requestData['current_height'] = "";
                $requestData['current_weight'] = "";
                $requestData['BMI'] = "";
                $requestData['total_points'] = '0';
                $requestData['device_token'] = "";
                $requestData['reference_code'] = "";
                $requestData['date'] = '2022-08-02';
                $requestData['status'] = 'enable';
            }

            $patient = PatientsModel::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($patient->id)) {

                // QRCode
                QRCodeGenerate($patient->id);

                return response()->json(array('status' => 200, 'success' => "Specialties Add Successfully"));
            } else {
                return response()->json(array('status' => 400, 'error' => "Specialties Not Added"));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }

    }

    public function edit($id)
    {
        $params['data'] = PatientsModel::where('id', $id)->first();
        return view('admin.patient.edit', $params);
    }

    public function update(Request $request, $id)
    {
        if (Auth::guard('admin')->user()->role_id == 2) {
            return response()->json(array('status' => 400, 'errors' => ("This User Is Not Access Your Pages")));
        } else {
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required',
                'fullname' => 'required',
                'email' => 'required',
                'password' => 'required',
                'mobile_number' => 'required',
                'instagram_url' => 'required',
                'facebook_url' => 'required',
                'twitter_url' => 'required',
                'biodata' => 'required',
            ]);
            if ($validator->fails()) {

                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            try {
                $requestData = $request->all();

                if (isset($requestData['profile_img']) && $requestData['profile_img'] != 'undefined') {

                    $files = $requestData['profile_img'];
                    $ext = $files->extension();
                    $path = base_path('/storage/app/public/patient/');
                    $name = rand() . time() . "." . $ext;
                    $files->move($path, $name);
                    $requestData['profile_img'] = $name;
                    Storage::disk('public')->delete('patient/' . $requestData['old_image']);

                } else {
                    $requestData['profile_img'] = $requestData['old_image'];
                }
                $requestData = Arr::except($requestData, ['old_image']);

                $patient = PatientsModel::updateOrCreate(['id' => $requestData['id']], $requestData);
                if (isset($patient->id)) {

                    // QRCode
                    QRCodeGenerate($patient->id);

                    return response()->json(array('status' => 200, 'success' => "Patient Update Succesfully"));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Patient Not Updated"));
                }

            } catch (Exception $e) {
                return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
            }

        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::guard('admin')->user()->role_id == 2) {
                return redirect()->route('patient.index')->with('errors', 'This User Is Not Access Your Pages');
            } else {
                $data = PatientsModel::where('id', $id)->first();
                Storage::disk('public')->delete('patient/' . $data->profile_img);

                $data->delete();

                // QRCode
                Storage::disk('public')->delete('patients_qrcode/' . $id . '.png');

                return redirect()->route('patient.index')->with('success', "Patient Delete Succesfully");
            }

        } catch (Exception $e) {
            return redirect()->route('patient.index')->with($e);
        }
    }

    public function change_status(Request $request)
    {
        $patient = PatientsModel::where('id', $request->id)->first();

        if ($patient->status == 'enable') {
            $patient->status = 'disable';
        } else {
            $patient->status = 'enable';
        }

        $patient->save();
        return redirect()->back();
    }
}
