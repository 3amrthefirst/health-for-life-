<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use App\Models\SettingModel;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        $params['data'] = [];
        if ($request->ajax()) {
            $data = NotificationModel::query();

            return Datatables::of($data)
                ->addIndexColumn()

                ->make(true);
        } else {
            return view('admin.notification.index', $params);
        }
    }

    public function create()
    {
        if (Auth::guard('admin')->user()->role_id == 2) {
            return redirect()->route('Notification')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            return view('admin.notification.add');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }
        try {
            $requestData = $request->all();
            if (isset($requestData['image']) && $requestData['image'] != 'undefined') {
                $files = $requestData['image'];
                $ext = $files->extension();
                $path = base_path('/storage/app/public/notification/');
                $name = rand() . time() . "." . $ext;
                $files->move($path, $name);
                $requestData['image'] = $name;
                $requestData['type'] = '1';
                $requestData['status'] = '1';
                $requestData['date'] = '2022-08-02';

            }
            $notification = NotificationModel::updateOrCreate(['id' => $requestData['id']], $requestData);

            if (isset($notification->id)) {
                return response()->json(array('status' => 200, 'success' => "Notification Add Successfully"));
            } else {
                return response()->json(array('status' => 400, 'error' => "Notification Not Added"));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function notificationsetting(Request $request)
    {
        $setting = SettingModel::get();

        foreach ($setting as $row) {
            $data[$row->key] = $row->value;
        }
        return view('admin.notification.notificationsetting', ['first' => $data]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'onesignal_app_id' => 'required',
            'onesignal_rest_key' => 'required',
        ]);

        if ($validator->fails()) {

            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        } else {
            try {
                $data = $request->all();
                unset($data['_token']);
                foreach ($data as $key => $value) {
                    $setting = SettingModel::where('key', $key)->first();
                    if (isset($setting->id)) {
                        $setting->value = $value;
                        $setting->save();
                    }
                }

                if ($setting->save()) {
                    return response()->json(array('status' => 200, 'success' => "Notification Setting Update Successfully"));
                }

            } catch (Exception $e) {
                return response()->json(array('status' => 400, 'errors' => $e));
            }
        }
    }

}
