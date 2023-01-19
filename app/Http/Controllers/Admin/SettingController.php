<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyModel;
use App\Models\SettingModel;
use App\Models\SmtpModel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Storage;
use Validator;

class SettingController extends Controller
{

    public function index()
    {
        $setting = SettingModel::get();
        $currency = CurrencyModel::get();
        $data = [];

        foreach ($setting as $row) {
            $data[$row->key] = $row->value;

        }
        return view('admin.setting.index', ['result' => $data, 'currency' => $currency]);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required',
            'app_description' => 'required',

        ]);

        if ($validator->fails()) {

            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        } else {
            try {
                $requestData = $request->all();

                if (isset($requestData['app_logo']) && $requestData['app_logo'] != 'undefined') {
                    $files = $requestData['app_logo'];
                    $ext = $files->extension();
                    $path = base_path('/storage/app/public/setting/');
                    $name = rand() . time() . "." . $ext;
                    $files->move($path, $name);
                    $requestData['app_logo'] = $name;

                    Storage::disk('public')->delete('setting/' . $requestData['old_image']);

                } else {
                    $requestData['app_logo'] = $requestData['old_image'];
                }
                $requestData = Arr::except($requestData, ['old_image']);

                foreach ($requestData as $key => $value) {
                    $setting = SettingModel::where('key', $key)->first();
                    if (isset($setting->id)) {
                        $setting->value = $value;
                        $setting->save();

                    }
                }
                return response()->json(array('status' => 200, 'success' => "Setting Update Successfylly"));

                return redirect()->route('Setting');

            } catch (Exception $e) {
                return response()->json(array('status' => 400, 'errors' => $e));
            }
        }
    }

    public function currencysave(Request $request)
    {
        if (Auth::guard('admin')->user()->role_id == 2) {
            return redirect()->route('Setting')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            $data = $request->all();
            unset($data['_token']);

            CurrencyModel::where('status', '=', 1)->update(['status' => 0]);
            CurrencyModel::where('id', $request->currency)->update(['status' => 1]);

            return redirect()->route('Setting');
        }

    }

    public function passwordchange(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'password' => 'required|min:2',
            'new_password' => 'required|min:2',
        ]);

        if ($validator->fails()) {

            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        } else {
            try {

                $old_pass = $request->password;
                $new_pass = $request->new_password;

                $auth = Auth::guard('admin')->user();
                if (Hash::check($old_pass, $auth['password'])) {
                    $auth->fill([
                        'password' => Hash::make($new_pass),
                    ])->save();
                    return response()->json(array('status' => 200, 'success' => "Password Update Successfully"));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Plase Enter Right Old Password."));
                }

            } catch (Exception $e) {
                return response()->json(array('status' => 400, 'errors' => $e));
            }
        }
    }

    public function admobupdate(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);

        foreach ($data as $key => $value) {

            $Generalsetting = SettingModel::where('key', $key)->first();
            //dd($Generalsetting);
            if (isset($Generalsetting->id)) {
                $Generalsetting->value = $value;
                $Generalsetting->save();

            }
        }
        return response()->json(array('status' => 200, 'success' => "Admon Update Successfully"));

        return redirect()->route('Setting');

    }

    public function isoupdate(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);

        foreach ($data as $key => $value) {

            $Generalsetting = SettingModel::where('key', $key)->first();
            //dd($Generalsetting);
            if (isset($Generalsetting->id)) {
                $Generalsetting->value = $value;
                $Generalsetting->save();

            }
        }
        return response()->json(array('status' => 200, 'success' => "Iso Update Successfully"));

        return redirect()->route('Setting');
    }

    public function facebookadmob(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);

        foreach ($data as $key => $value) {

            $Generalsetting = SettingModel::where('key', $key)->first();
            //dd($Generalsetting);
            if (isset($Generalsetting->id)) {
                $Generalsetting->value = $value;
                $Generalsetting->save();

            }
        }
        return response()->json(array('status' => 200, 'success' => "Facebook Admob Update Successfully"));

        return redirect()->route('Setting');
    }

    public function facebookiso(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);

        foreach ($data as $key => $value) {

            $Generalsetting = SettingModel::where('key', $key)->first();
            //dd($Generalsetting);
            if (isset($Generalsetting->id)) {
                $Generalsetting->value = $value;
                $Generalsetting->save();

            }
        }
        return response()->json(array('status' => 200, 'success' => "Facebook Iso Update Successfully"));

        return redirect()->route('Setting');
    }

    public function emailsetting(Request $request)
    {
        $emailsetting = SmtpModel::first();

        return view('admin.setting.emailsetting', ['data' => $emailsetting]);
    }

    public function emailsettingupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required',
            'port' => 'required',
            'user' => 'required',
            'pass' => 'required|min:2',
            'from_name' => 'required',
            'from_email' => 'required',
            'protocol' => 'required',

        ]);

        if ($validator->fails()) {

            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        } else {
            try {
                $data = $request->all();

                $user = SmtpModel::where('id', 1)->first();

                $user->host = $request->host;
                $user->port = $request->port;
                $user->user = $request->user;
                $user->pass = Hash::make($request->pass);
                $user->from_name = $request->from_name;
                $user->from_email = $request->from_email;
                $user->protocol = $request->protocol;
                $user->status = '1';

                if ($user->save()) {
                    return response()->json(array('status' => 200, 'success' => "SMTP Update Successfully"));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "SMTP Not Updated"));
                }

            } catch (Exception $e) {
                return response()->json(array('status' => 400, 'errors' => $e));
            }
        }
    }
}
