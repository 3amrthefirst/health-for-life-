<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentModel;
use App\Models\DoctorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->doctor != null && $request->doctor != "all") {
            $data = AppointmentModel::select('*')
                ->where('doctor_id', $request->doctor)
                ->with('doctor')
                ->with('patient')
                ->latest()
                ->paginate(12);
        } else {
            $data = AppointmentModel::select('*')
                ->with('doctor')
                ->with('patient')
                ->latest()
                ->paginate(12);
        }
        $doctor = DoctorModel::get();

        return view('admin.appointment.index', ['data' => $data, 'doctor' => $doctor]);
    }

    public function show($id)
    {
        $data = AppointmentModel::where('id', $id)->with('doctor')->with('patient')->first();
        if (isset($data)) {
            return view('admin.appointment.details', ['data' => $data]);
        } else {
            return back();
        }
    }

}
