<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Http\Controllers\Controller;
use App\Models\DoctorModel;
use App\Models\LanguageModel;
use App\Models\PatientsModel;
use App\Models\SpecialtieModel;
use Session;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = DoctorModel::get()->count();
        $patient = PatientsModel::get()->count();
        $specialtie = SpecialtieModel::get()->count();
        $appointment = DoctorModel::get()->count();

        $data['doctor'] = $doctor;
        $data['patient'] = $patient;
        $data['specialtie'] = $specialtie;
        $data['appointment'] = $appointment;

        return view('admin.dashboard', ['result' => $data]);
    }

    public function language($id)
    {
        LanguageModel::where('status', '1')->update(['status' => 0]);
        $language = LanguageModel::where('id', $id)->first();
        if (isset($language->id)) {
            $language->status = 1;
            if ($language->save()) {
                App::setLocale($language->lag_code);
                session()->put('locale', $language->lag_code);

                return redirect()->back();
            }
        }
    }
}
