<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppointmentModel;
use App\Models\Appointment_schedulModel;
use App\Models\Appointment_slotsModel;
use App\Models\CompanyModel;
use App\Models\DoctorModel;
use App\Models\NotificationModel;
use App\Models\PatientsModel;
use App\Models\PrescriptionModel;
use App\Models\SettingModel;
use App\Models\SpecialtieModel;
use Illuminate\Http\Request;
use Validator;

class HomeController extends Controller
{
    private $folder = "setting";
    private $folder1 = "specialties";
    private $folder2 = "patient";
    private $folder3 = "doctor";
    private $folder4 = "insurance_card";

    public function GeneralSetting()
    {
        try {
            $g_setting = SettingModel::select('id', 'key', 'value')->get();

            if ($g_setting) {

                for ($i = 0; $i < count($g_setting); $i++) {
                    $g_setting[$i]['id'] = (string) $g_setting[$i]['id'];
                }

                if (!empty($g_setting[9]->value)) {
                    $path = Get_Image($this->folder, $g_setting[9]->value);
                    $g_setting[9]->value = $path;
                }

                $data['id'] = "55";
                $data['key'] = "about-us";
                $data['value'] = route('AboutUS');

                $data1['id'] = "56";
                $data1['key'] = "privacy-policy";
                $data1['value'] = route('privacyPolicy');

                $data2['id'] = "57";
                $data2['key'] = "terms-and-conditions";
                $data2['value'] = route('TermsAndConditions');

                $g_setting[] = $data;
                $g_setting[] = $data1;
                $g_setting[] = $data2;

                return APIResponse(200, __('api_msg.get_record_successfully'), $g_setting);
            } else {
                return APIResponse(200, __('api_msg.get_record_successfully'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetSpecialities()
    {
        try {
            $S_data = SpecialtieModel::get();
            if (sizeof($S_data) > 0) {

                for ($i = 0; $i < count($S_data); $i++) {

                    if (!empty($S_data[$i]['image'])) {
                        $path = Get_Image($this->folder1, $S_data[$i]['image']);
                        $S_data[$i]['image'] = $path;
                    } else {
                        $S_data[$i]['image'] = asset('/assets/imgs/1.jpg');
                    }
                }
                return APIResponse(200, __('api_msg.get_record_successfully'), $S_data);
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function DoctorDetail(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'id' => 'required|numeric',
                ],
                [
                    'id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $id = $request->id;
            $D_data = DoctorModel::where('id', $id)->with('doctor')->first();
            if (!empty($D_data)) {

                $date = date('Y-m-d');
                $timeSlots = $this->getTimeSlotByDoctorIdreturn($D_data['id'], $date);
                $D_data['working_time'] = $timeSlots;

                if (!empty($D_data->profile_img)) {
                    $path = Get_Image($this->folder3, $D_data->profile_img);
                    $D_data['profile_img'] = $path;
                    $D_data['doctor_image'] = $path;
                } else {
                    $D_data['profile_img'] = asset('/assets/imgs/1.jpg');
                    $D_data['doctor_image'] = asset('/assets/imgs/1.jpg');
                }

                if($D_data['doctor'] != null && isset($D_data)){
                    $D_data['specialities_name'] = $D_data['doctor']['name'];
                } else {
                    $D_data['specialities_name'] = "";
                }
                unset($D_data['doctor']);

                $D_data['review'] = 0;
                $D_data['toitla_rating'] = "0";
                $D_data['rating_avg'] = 0;

                return APIResponse(200, __('api_msg.get_record_successfully'), array($D_data));
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function getTimeSlotByDoctorIdreturn($doctor_id, $date)
	{
		try {
			$wwekDay =  date('w', strtotime($date));
			if ($wwekDay == '0') {
				$wwekDay = '7';
			}

            $appointment_slots = Appointment_slotsModel::where('doctor_id', $doctor_id)->where('week_day', $wwekDay)->get();

            $goodData = [];
            foreach ($appointment_slots as $key => $slotsvalue) {
                $startTime = date('h:i A', strtotime($slotsvalue['start_time']));
                $end_time = date('h:i A', strtotime($slotsvalue['end_time']));
                $goodData[] = $startTime . ' to ' . $end_time;
            }

            $working_time = '';
            if (count($goodData) > 0) {
				$working_time = implode(',', $goodData);
			}

            return $working_time;
		} catch (Exception $e) {
			$res = array('status' => 400, 'message' => $this->lang->line('error'));
			return $res;
		}
	}


    public function PatientAppoinment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('user_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $user_id = $request->user_id;
            $date = date('Y-m-d');

            $array = [];

            // Today Appoinment
            $D_data = AppointmentModel::where('date', '=', $date)->where('patient_id', $user_id)->with('doctor')->with('patient')->get();
            if (count($D_data) > 0) {

                for ($i = 0; $i < count($D_data); $i++) {

                    $D_data[$i]['mobile_number'] = "";

                    // Doctor
                    if ($D_data[$i]['doctor'] != null) {

                        // Specialities
                        $S_data = SpecialtieModel::where('id', $D_data[$i]['doctor']['specialties_id'])->first();
                        if (!empty($S_data)) {
                            $D_data[$i]['specialities_name'] = $S_data['name'];
                            if (!empty($S_data['image'])) {
                                $path = Get_Image($this->folder1, $S_data['image']);
                                $D_data[$i]['specialities_image'] = $path;
                            } else {
                                $D_data[$i]['specialities_image'] = asset('/assets/imgs/1.jpg');
                            }
                        } else {
                            $D_data[$i]['specialities_name'] = "";
                            $D_data[$i]['specialities_image'] = "";
                        }

                        $D_data[$i]['doctor_name'] = $D_data[$i]['doctor']['first_name'] . $D_data[$i]['doctor']['last_name'];
                        if (!empty($D_data[$i]['doctor']['profile_img'])) {
                            $path = Get_Image($this->folder, $D_data[$i]['doctor']['profile_img']);
                            $D_data[$i]['doctor_image'] = $path;
                        } else {
                            $D_data[$i]['doctor_image'] = asset('/assets/imgs/1.jpg');
                        }
                        $D_data[$i]['doctor_email'] = $D_data[$i]['doctor']['email'];
                        $D_data[$i]['doctor_mobile_number'] = $D_data[$i]['doctor']['mobile_number'];

                    } else {

                        $D_data[$i]['specialities_name'] = "";
                        $D_data[$i]['specialities_image'] = "";
                        $D_data[$i]['doctor_name'] = "";
                        $D_data[$i]['doctor_image'] = "";
                        $D_data[$i]['doctor_email'] = "";
                        $D_data[$i]['doctor_mobile_number'] = "";
                    }

                    // Patient
                    if ($D_data[$i]['patient'] != null) {

                        $D_data[$i]['patients_name'] = $D_data[$i]['patient']['fullname'];
                        $D_data[$i]['patients_email'] = $D_data[$i]['patient']['email'];

                        if (!empty($D_data[$i]['patient']['profile_img'])) {
                            $path = Get_Image($this->folder2, $D_data[$i]['patient']['profile_img']);
                            $D_data[$i]['patients_profile_img'] = $path;
                        } else {
                            $D_data[$i]['patients_profile_img'] = asset('/assets/imgs/1.jpg');
                        }
                        $D_data[$i]['patients_mobile_number'] = $D_data[$i]['patient']['mobile_number'];
                        $D_data[$i]['insurance_company_id'] = $D_data[$i]['patient']['insurance_company_id'];
                        $D_data[$i]['insurance_no'] = $D_data[$i]['patient']['insurance_no'];
                        $D_data[$i]['insurance_card_pic'] = $D_data[$i]['patient']['insurance_card_pic'];
                        $D_data[$i]['allergies_to_medicine'] = $D_data[$i]['patient']['allergies_to_medicine'];
                        $D_data[$i]['patients_qrcode_img'] = env('Img_path') . '/patients_qrcode' . '/' . $D_data[$i]['patient']['id'] . '.png';
                    } else {

                        $D_data[$i]['patients_name'] = "";
                        $D_data[$i]['patients_email'] = "";
                        $D_data[$i]['patients_profile_img'] = "";
                        $D_data[$i]['patients_mobile_number'] = "";
                        $D_data[$i]['insurance_company_id'] = "";
                        $D_data[$i]['insurance_no'] = "";
                        $D_data[$i]['insurance_card_pic'] = "";
                        $D_data[$i]['allergies_to_medicine'] = "";
                        $D_data[$i]['patients_qrcode_img'] = "";
                    }

                    unset($D_data[$i]['patient']);
                    unset($D_data[$i]['doctor']);
                }

                $array['today'] = $D_data;
            } else {
                $array['today'] = [];
            }

            // Past Appoinment
            $D_data = AppointmentModel::where('date', '<', $date)->where('patient_id', $user_id)->with('doctor')->with('patient')->get();
            if (count($D_data) > 0) {

                for ($i = 0; $i < count($D_data); $i++) {

                    $D_data[$i]['mobile_number'] = "";

                    // Doctor
                    if ($D_data[$i]['doctor'] != null) {

                        // Specialities
                        $S_data = SpecialtieModel::where('id', $D_data[$i]['doctor']['specialties_id'])->first();
                        if (!empty($S_data)) {
                            $D_data[$i]['specialities_name'] = $S_data['name'];
                            if (!empty($S_data['image'])) {
                                $path = Get_Image($this->folder1, $S_data['image']);
                                $D_data[$i]['specialities_image'] = $path;
                            } else {
                                $D_data[$i]['specialities_image'] = asset('/assets/imgs/1.jpg');
                            }
                        } else {
                            $D_data[$i]['specialities_name'] = "";
                            $D_data[$i]['specialities_image'] = "";
                        }

                        $D_data[$i]['doctor_name'] = $D_data[$i]['doctor']['first_name'] . $D_data[$i]['doctor']['last_name'];
                        if (!empty($D_data[$i]['doctor']['profile_img'])) {
                            $path = Get_Image($this->folder, $D_data[$i]['doctor']['profile_img']);
                            $D_data[$i]['doctor_image'] = $path;
                        } else {
                            $D_data[$i]['doctor_image'] = asset('/assets/imgs/1.jpg');
                        }
                        $D_data[$i]['doctor_email'] = $D_data[$i]['doctor']['email'];
                        $D_data[$i]['doctor_mobile_number'] = $D_data[$i]['doctor']['mobile_number'];

                    } else {

                        $D_data[$i]['specialities_name'] = "";
                        $D_data[$i]['specialities_image'] = "";
                        $D_data[$i]['doctor_name'] = "";
                        $D_data[$i]['doctor_image'] = "";
                        $D_data[$i]['doctor_email'] = "";
                        $D_data[$i]['doctor_mobile_number'] = "";
                    }

                    // Patient
                    if ($D_data[$i]['patient'] != null) {

                        $D_data[$i]['patients_name'] = $D_data[$i]['patient']['fullname'];
                        $D_data[$i]['patients_email'] = $D_data[$i]['patient']['email'];

                        if (!empty($D_data[$i]['patient']['profile_img'])) {
                            $path = Get_Image($this->folder2, $D_data[$i]['patient']['profile_img']);
                            $D_data[$i]['patients_profile_img'] = $path;
                        } else {
                            $D_data[$i]['patients_profile_img'] = asset('/assets/imgs/1.jpg');
                        }
                        $D_data[$i]['patients_mobile_number'] = $D_data[$i]['patient']['mobile_number'];
                        $D_data[$i]['insurance_company_id'] = $D_data[$i]['patient']['insurance_company_id'];
                        $D_data[$i]['insurance_no'] = $D_data[$i]['patient']['insurance_no'];
                        $D_data[$i]['insurance_card_pic'] = $D_data[$i]['patient']['insurance_card_pic'];
                        $D_data[$i]['allergies_to_medicine'] = $D_data[$i]['patient']['allergies_to_medicine'];
                        $D_data[$i]['patients_qrcode_img'] = env('Img_path') . '/patients_qrcode' . '/' . $D_data[$i]['patient']['id'] . '.png';
                    } else {

                        $D_data[$i]['patients_name'] = "";
                        $D_data[$i]['patients_email'] = "";
                        $D_data[$i]['patients_profile_img'] = "";
                        $D_data[$i]['patients_mobile_number'] = "";
                        $D_data[$i]['insurance_company_id'] = "";
                        $D_data[$i]['insurance_no'] = "";
                        $D_data[$i]['insurance_card_pic'] = "";
                        $D_data[$i]['allergies_to_medicine'] = "";
                        $D_data[$i]['patients_qrcode_img'] = "";
                    }

                    unset($D_data[$i]['patient']);
                    unset($D_data[$i]['doctor']);
                }

                $array['past'] = $D_data;
            } else {
                $array['past'] = [];
            }

            return APIResponse(200, __('api_msg.get_record_successfully'), $array);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function AppoinmentDetail(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'id' => 'required|numeric',
                ],
                [
                    'id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $id = $request->id;
            $D_data = AppointmentModel::where('id', $id)->with('doctor')->with('patient')->first();
            if (!empty($D_data)) {

                $D_data['mobile_number'] = "";

                // Doctor
                if ($D_data['doctor'] != null) {

                    // Specialities
                    $S_data = SpecialtieModel::where('id', $D_data['doctor']['specialties_id'])->first();
                    if (!empty($S_data)) {
                        $D_data['specialities_name'] = $S_data['name'];
                        if (!empty($S_data['image'])) {
                            $path = Get_Image($this->folder1, $S_data['image']);
                            $D_data['specialities_image'] = $path;
                        } else {
                            $D_data['specialities_image'] = asset('/assets/imgs/1.jpg');
                        }
                    } else {
                        $D_data['specialities_name'] = "";
                        $D_data['specialities_image'] = "";
                    }

                    $D_data['doctor_name'] = $D_data['doctor']['first_name'] . $D_data['doctor']['last_name'];
                    if (!empty($D_data['doctor']['profile_img'])) {
                        $path = Get_Image($this->folder3, $D_data['doctor']['profile_img']);
                        $D_data['doctor_image'] = $path;
                    } else {
                        $D_data['doctor_image'] = asset('/assets/imgs/1.jpg');
                    }
                    $D_data['doctor_email'] = $D_data['doctor']['email'];
                    $D_data['doctor_mobile_number'] = $D_data['doctor']['mobile_number'];

                } else {

                    $D_data['specialities_name'] = "";
                    $D_data['specialities_image'] = "";
                    $D_data['doctor_name'] = "";
                    $D_data['doctor_image'] = "";
                    $D_data['doctor_email'] = "";
                    $D_data['doctor_mobile_number'] = "";
                }

                // Patient
                if ($D_data['patient'] != null) {

                    $D_data['patients_name'] = $D_data['patient']['fullname'];
                    $D_data['patients_email'] = $D_data['patient']['email'];

                    if (!empty($D_data['patient']['profile_img'])) {
                        $path = Get_Image($this->folder2, $D_data['patient']['profile_img']);
                        $D_data['patients_profile_img'] = $path;
                    } else {
                        $D_data['patients_profile_img'] = asset('/assets/imgs/1.jpg');
                    }
                    $D_data['patients_mobile_number'] = $D_data['patient']['mobile_number'];
                    $D_data['allergies_to_medicine'] = $D_data['patient']['allergies_to_medicine'];
                } else {

                    $D_data['patients_name'] = "";
                    $D_data['patients_email'] = "";
                    $D_data['patients_profile_img'] = "";
                    $D_data['patients_mobile_number'] = "";
                    $D_data['allergies_to_medicine'] = "";
                }

                unset($D_data['patient']);
                unset($D_data['doctor']);

                return APIResponse(200, __('api_msg.get_record_successfully'), array($D_data));
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function UpdateNotificationStatus(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'id' => 'required|numeric',
                    'status' => 'required|integer|between:0,1',
                ],
                [
                    'id.required' => __('api_msg.please_enter_required_fields'),
                    'status.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('id');
                $errors1 = $validation->errors()->first('status');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } else if ($errors1) {
                    $data['message'] = $errors1;
                }
                return $data;
            }

            $id = $request->id;
            $status = $request->status;

            $N_data = NotificationModel::where('id', $id)->first();

            if (!empty($N_data)) {

                $N_data->status = $status;
                $N_data->save();

                $data['status'] = 200;
                $data['message'] = __('api_msg.status_update_successfully');
                return $data;

            } else {
                return APIResponse(400, __('api_msg.notification_id_worng'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetPrescriptionDetail(Request $request)
    {
        if (isset($request->patient_id)) {
            try {
                $patient_id = $request->patient_id;
                $result = AppointmentModel::where('patient_id', $patient_id)->where('status', 5)->orderBy('id', 'desc')->get();

                $rows = [];
                foreach ($result as $ra) {
                    $data = [];

                    $appointment_slots = PrescriptionModel::where('appointment_id', $ra['id'])->with('medicine')->get();
                    for ($i = 0; $i < count($appointment_slots); $i++) {
                        if ($appointment_slots[$i]['medicine'] != null) {
                            $appointment_slots[$i]['name'] = $appointment_slots[$i]['medicine']['name'];
                            $appointment_slots[$i]['form'] = $appointment_slots[$i]['medicine']['form'];
                            $appointment_slots[$i]['power'] = $appointment_slots[$i]['medicine']['power'];
                        } else {
                            $appointment_slots[$i]['name'] = "";
                            $appointment_slots[$i]['form'] = "";
                            $appointment_slots[$i]['power'] = "";
                        }
                        unset($appointment_slots[$i]['medicine']);
                    }

                    $data['symptoms'] = $ra['doctor_symptoms'];
                    $data['diagnosis'] = $ra['doctor_diagnosis'];
                    $data['date'] = $ra['date'];
                    $data['prescription'] = $appointment_slots;
                    $rows[] = $data;
                }

                return APIResponse(200, __('api_msg.get_record_successfully'), $rows);
            } catch (Exception $e) {
                return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
            }
        }
    }

    public function GetCompany()
    {
        try {
            $C_data = CompanyModel::get();
            if (sizeof($C_data) > 0) {
                return APIResponse(200, __('api_msg.get_record_successfully'), $C_data);
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetDoctor()
    {
        try {

            $D_data = DoctorModel::with('doctor')->get();

            if (sizeof($D_data) > 0) {

                for ($i = 0; $i < count($D_data); $i++) {

                    if ($D_data[$i]['doctor'] != null) {
                        $D_data[$i]['specialities_name'] = $D_data[$i]['doctor']['name'];
                    } else {
                        $D_data[$i]['specialities_name'] = "";
                    }
                    unset($D_data[$i]['doctor']);

                    if (!empty($D_data[$i]['profile_img'])) {
                        $path = Get_Image($this->folder3, $D_data[$i]['profile_img']);
                        $D_data[$i]['doctor_image'] = $path;
                    } else {
                        $D_data[$i]['doctor_image'] = asset('/assets/imgs/1.jpg');
                    }
                }
                return APIResponse(200, __('api_msg.get_record_successfully'), $D_data);
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function SearchDoctor(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ],
                [
                    'name.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('name');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $name = $request->name;

            $D_data = DoctorModel::where('first_name', 'LIKE', "%{$name}%")->orWhere('last_name', 'LIKE', "%{$name}%")->with('doctor')->latest()->get();
            if (sizeof($D_data) > 0) {

                for ($i = 0; $i < count($D_data); $i++) {

                    if ($D_data[$i]['doctor'] != null) {
                        $D_data[$i]['specialities_name'] = $D_data[$i]['doctor']['name'];
                    } else {
                        $D_data[$i]['specialities_name'] = "";
                    }
                    unset($D_data[$i]['doctor']);

                    if (!empty($D_data[$i]['profile_img'])) {
                        $path = Get_Image($this->folder3, $D_data[$i]['profile_img']);
                        $D_data[$i]['doctor_image'] = $path;
                    } else {
                        $D_data[$i]['doctor_image'] = asset('/assets/imgs/1.jpg');
                    }
                }

                return APIResponse(200, __('api_msg.get_record_successfully'), $D_data);
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function UpcomingAppoinment(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|numeric',
            ],
            [
                'user_id.required' => __('api_msg.please_enter_required_fields'),
            ]
        );
        if ($validation->fails()) {

            $errors = $validation->errors()->first('user_id');
            $data['status'] = 400;
            if ($errors) {
                $data['message'] = $errors;
            }
            return $data;
        }

        $date = date('Y-m-d');
        $user_id = $request->user_id;

        $D_data = AppointmentModel::where('date', '>=', $date)->where('patient_id', $user_id)->with('doctor')->where('status', '!=', 5)->with('patient')->latest()->get();

        if (count($D_data) > 0) {

            for ($i = 0; $i < count($D_data); $i++) {

                $D_data[$i]['mobile_number'] = "";

                // Doctor
                if ($D_data[$i]['doctor'] != null) {

                    // Specialities
                    $S_data = SpecialtieModel::where('id', $D_data[$i]['doctor']['specialties_id'])->first();
                    if (!empty($S_data)) {
                        $D_data[$i]['specialities_name'] = $S_data['name'];

                        if (!empty($S_data['image'])) {
                            $path = Get_Image($this->folder1, $S_data['image']);
                            $D_data[$i]['specialities_image'] = $path;
                        } else {
                            $D_data[$i]['specialities_image'] = asset('/assets/imgs/1.jpg');
                        }

                    } else {
                        $D_data[$i]['specialities_name'] = "";
                        $D_data[$i]['specialities_image'] = "";
                    }

                    $D_data[$i]['doctor_name'] = $D_data[$i]['doctor']['first_name'] . $D_data[$i]['doctor']['last_name'];

                    if (!empty($D_data[$i]['doctor']['profile_img'])) {
                        $path = Get_Image($this->folder3, $D_data[$i]['doctor']['profile_img']);
                        $D_data[$i]['doctor_image'] = $path;
                    } else {
                        $D_data[$i]['doctor_image'] = asset('/assets/imgs/1.jpg');
                    }

                    $D_data[$i]['doctor_email'] = $D_data[$i]['doctor']['email'];
                    $D_data[$i]['doctor_mobile_number'] = $D_data[$i]['doctor']['mobile_number'];

                } else {

                    $D_data[$i]['specialities_name'] = "";
                    $D_data[$i]['specialities_image'] = "";
                    $D_data[$i]['doctor_name'] = "";
                    $D_data[$i]['doctor_image'] = "";
                    $D_data[$i]['doctor_email'] = "";
                    $D_data[$i]['doctor_mobile_number'] = "";
                }

                // Patient
                if ($D_data[$i]['patient'] != null) {

                    $D_data[$i]['patients_name'] = $D_data[$i]['patient']['fullname'];
                    $D_data[$i]['patients_email'] = $D_data[$i]['patient']['email'];

                    if (!empty($D_data[$i]['patient']['profile_img'])) {
                        $path = Get_Image($this->folder2, $D_data[$i]['patient']['profile_img']);
                        $D_data[$i]['patients_profile_img'] = $path;
                    } else {
                        $D_data[$i]['patients_profile_img'] = asset('/assets/imgs/1.jpg');
                    }

                    $D_data[$i]['patients_mobile_number'] = $D_data[$i]['patient']['mobile_number'];
                    $D_data[$i]['insurance_company_id'] = $D_data[$i]['patient']['insurance_company_id'];
                    $D_data[$i]['insurance_no'] = $D_data[$i]['patient']['insurance_no'];

                    if (!empty($D_data[$i]['patient']['insurance_card_pic'])) {
                        $path = Get_Image($this->folder4, $D_data[$i]['patient']['insurance_card_pic']);
                        $D_data[$i]['insurance_card_pic'] = $path;
                    } else {
                        $D_data[$i]['insurance_card_pic'] = "";
                    }

                    $D_data[$i]['allergies_to_medicine'] = $D_data[$i]['patient']['allergies_to_medicine'];
                    $D_data[$i]['patients_qrcode_img'] = env('Img_path') . '/patients_qrcode' . '/' . $D_data[$i]['patient']['id'] . '.png';

                } else {

                    $D_data[$i]['patients_name'] = "";
                    $D_data[$i]['patients_email'] = "";
                    $D_data[$i]['patients_profile_img'] = "";
                    $D_data[$i]['patients_mobile_number'] = "";
                    $D_data[$i]['insurance_company_id'] = "";
                    $D_data[$i]['insurance_no'] = "";
                    $D_data[$i]['insurance_card_pic'] = "";
                    $D_data[$i]['allergies_to_medicine'] = "";
                    $D_data[$i]['patients_qrcode_img'] = "";
                }

                unset($D_data[$i]['patient']);
                unset($D_data[$i]['doctor']);
            }
            return APIResponse(200, __('api_msg.get_record_successfully'), $D_data);
        } else {
            return APIResponse(400, __('api_msg.data_not_found'));
        }
    }

    public function GetMedicineHistory(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'patient_id' => 'required|numeric',
            ],
            [
                'patient_id.required' => __('api_msg.please_enter_required_fields'),
            ]
        );
        if ($validation->fails()) {

            $errors = $validation->errors()->first('patient_id');
            $data['status'] = 400;
            if ($errors) {
                $data['message'] = $errors;
            }
            return $data;
        }

        $patient_id = $request->patient_id;

        $D_data = AppointmentModel::where('patient_id', $patient_id)->with('doctor')->where('status', '=', 5)->with('patient')->latest()->get();
        if (count($D_data) > 0) {

            for ($i = 0; $i < count($D_data); $i++) {

                $D_data[$i]['mobile_number'] = "";

                // Doctor
                if ($D_data[$i]['doctor'] != null) {

                    // Specialities
                    $S_data = SpecialtieModel::where('id', $D_data[$i]['doctor']['specialties_id'])->first();
                    if (!empty($S_data)) {
                        $D_data[$i]['specialities_name'] = $S_data['name'];

                        if (!empty($S_data['image'])) {
                            $path = Get_Image($this->folder1, $S_data['image']);
                            $D_data[$i]['specialities_image'] = $path;
                        } else {
                            $D_data[$i]['specialities_image'] = asset('/assets/imgs/1.jpg');
                        }

                    } else {
                        $D_data[$i]['specialities_name'] = "";
                        $D_data[$i]['specialities_image'] = "";
                    }

                    $D_data[$i]['doctor_name'] = $D_data[$i]['doctor']['first_name'] . $D_data[$i]['doctor']['last_name'];

                    if (!empty($D_data[$i]['doctor']['profile_img'])) {
                        $path = Get_Image($this->folder3, $D_data[$i]['doctor']['profile_img']);
                        $D_data[$i]['doctor_image'] = $path;
                    } else {
                        $D_data[$i]['doctor_image'] = asset('/assets/imgs/1.jpg');
                    }

                    $D_data[$i]['doctor_email'] = $D_data[$i]['doctor']['email'];
                    $D_data[$i]['doctor_mobile_number'] = $D_data[$i]['doctor']['mobile_number'];

                } else {

                    $D_data[$i]['specialities_name'] = "";
                    $D_data[$i]['specialities_image'] = "";
                    $D_data[$i]['doctor_name'] = "";
                    $D_data[$i]['doctor_image'] = "";
                    $D_data[$i]['doctor_email'] = "";
                    $D_data[$i]['doctor_mobile_number'] = "";
                }

                // Patient
                if ($D_data[$i]['patient'] != null) {

                    $D_data[$i]['patients_name'] = $D_data[$i]['patient']['fullname'];
                    $D_data[$i]['patients_email'] = $D_data[$i]['patient']['email'];

                    if (!empty($D_data[$i]['patient']['profile_img'])) {
                        $path = Get_Image($this->folder2, $D_data[$i]['patient']['profile_img']);
                        $D_data[$i]['patients_profile_img'] = $path;
                    } else {
                        $D_data[$i]['patients_profile_img'] = asset('/assets/imgs/1.jpg');
                    }

                    $D_data[$i]['patients_mobile_number'] = $D_data[$i]['patient']['mobile_number'];
                    $D_data[$i]['insurance_company_id'] = $D_data[$i]['patient']['insurance_company_id'];
                    $D_data[$i]['insurance_no'] = $D_data[$i]['patient']['insurance_no'];

                    if (!empty($D_data[$i]['patient']['insurance_card_pic'])) {
                        $path = Get_Image($this->folder4, $D_data[$i]['patient']['insurance_card_pic']);
                        $D_data[$i]['insurance_card_pic'] = $path;
                    } else {
                        $D_data[$i]['insurance_card_pic'] = "";
                    }

                    $D_data[$i]['allergies_to_medicine'] = $D_data[$i]['patient']['allergies_to_medicine'];
                    $D_data[$i]['patients_qrcode_img'] = env('Img_path') . '/patients_qrcode' . '/' . $D_data[$i]['patient']['id'] . '.png';

                } else {

                    $D_data[$i]['patients_name'] = "";
                    $D_data[$i]['patients_email'] = "";
                    $D_data[$i]['patients_profile_img'] = "";
                    $D_data[$i]['patients_mobile_number'] = "";
                    $D_data[$i]['insurance_company_id'] = "";
                    $D_data[$i]['insurance_no'] = "";
                    $D_data[$i]['insurance_card_pic'] = "";
                    $D_data[$i]['allergies_to_medicine'] = "";
                    $D_data[$i]['patients_qrcode_img'] = "";
                }

                unset($D_data[$i]['patient']);
                unset($D_data[$i]['doctor']);
            }
            return APIResponse(200, __('api_msg.get_record_successfully'), $D_data);
        } else {
            return APIResponse(400, __('api_msg.data_not_found'));
        }
    }

    public function MakeAppoinment(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'doctor_id' => 'required|numeric',
                'patient_id' => 'required|numeric',
                'appointment_slots_id' => 'required|numeric',
                'date' => 'required',
                'startTime' => 'required',
                'endTime' => 'required',
                'description' => 'required',
            ],
            [
                'doctor_id.required' => __('api_msg.please_enter_required_fields'),
                'patient_id.required' => __('api_msg.please_enter_required_fields'),
                'appointment_slots_id.required' => __('api_msg.please_enter_required_fields'),
            ]
        );
        if ($validation->fails()) {

            $errors = $validation->errors()->first('doctor_id');
            $errors1 = $validation->errors()->first('patient_id');
            $errors2 = $validation->errors()->first('appointment_slots_id');
            $data['status'] = 400;
            if ($errors) {
                $data['message'] = $errors;
            } else if ($errors1) {
                $data['message'] = $errors1;
            } else if ($errors2) {
                $data['message'] = $errors2;
            } else {
                $data['message'] = __('api_msg.please_enter_required_fields');
            }
            return $data;
        }

        $date = date('Y-m-d');
        $symptoms = isset($_REQUEST['symptoms']) ? $_REQUEST['symptoms'] : '';
        $medicines_taken = isset($_REQUEST['medicines_taken']) ? $_REQUEST['medicines_taken'] : '';
        $insurance_details = isset($_REQUEST['insurance_details']) ? $_REQUEST['insurance_details'] : '';

        $appointment['doctor_id'] = $_REQUEST['doctor_id'];
        $appointment['patient_id'] = $_REQUEST['patient_id'];
        $appointment['appointment_slots_id'] = $_REQUEST['appointment_slots_id'];
        $appointment['date'] = date('Y-m-d', strtotime($_REQUEST['date']));
        $appointment['startTime'] = $_REQUEST['startTime'];
        $appointment['endTime'] = $_REQUEST['endTime'];
        $appointment['description'] = $_REQUEST['description'];
        $appointment['symptoms'] = $symptoms;
        $appointment['doctor_symptoms'] = "";
        $appointment['doctor_diagnosis'] = "";
        $appointment['medicines_taken'] = $medicines_taken;
        $appointment['insurance_details'] = $insurance_details;
        $appointment['status'] = 1;
        $appointment['mobile_number'] = isset($_REQUEST['mobile_number']) ? $_REQUEST['mobile_number'] : '';

        $appointment_id = AppointmentModel::insert($appointment);

        if ($appointment_id) {

            $patient = PatientsModel::where('id', $_REQUEST['patient_id'])->first();
            if (!empty($patient)) {

                // Notification
                $title = ucfirst($patient['fullname']) . ' has booked an appointment. Date:' . $appointment['date'];
                SaveNotification($title, $_REQUEST['patient_id'], $_REQUEST['doctor_id'], 0, $appointment_id, 1);

            }
            $data['status'] = 200;
            $data['message'] = __('api_msg.book_appointment');
            return $data;
        } else {
            return APIResponse(400, __('api_msg.data_not_save'));
        }
    }

    public function GetTimeSlotByDoctorId(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'doctor_id' => 'required|numeric',
                'date' => 'required',
            ],
            [
                'doctor_id.required' => __('api_msg.please_enter_required_fields'),
            ]
        );
        if ($validation->fails()) {

            $errors = $validation->errors()->first('doctor_id');
            $data['status'] = 400;
            if ($errors) {
                $data['message'] = $errors;
            } else {
                $data['message'] = __('api_msg.please_enter_required_fields');
            }
            return $data;
        }

        $wwekDay = date('w', strtotime($_POST['date']));
        if ($wwekDay == '0') {
            $wwekDay = '7';
        }
        $date = $_POST['date'];
        $doctor_id = $_POST['doctor_id'];

        $appointment_slots = Appointment_slotsModel::where('doctor_id', $doctor_id)->where('week_day', $wwekDay)->get();
        $goodData = [];
        foreach ($appointment_slots as $key => $slotsvalue) {

            $result = Appointment_schedulModel::where('doctor_id', $doctor_id)->where('week_day', $wwekDay)->where('appointment_slots_id', $slotsvalue['id'])->get();

            $docTimeSlot = [];
            foreach ($result as $key => $value) {
                $value['slot_status'] = 1;
                $docTimeSlot[$value['start_time']] = $value;
            }

            $resultAppointment = AppointmentModel::where('doctor_id', $doctor_id)->where('date', $date)->get();

            foreach ($resultAppointment as $key => $value) {

                if (isset($docTimeSlot[$value['startTime']])) {
                    $docTimeSlot[$value['startTime']]['slot_status'] = 0;
                }
            }
            sort($docTimeSlot);
            $slotsvalue['slot'] = $docTimeSlot;

            $goodData[] = $slotsvalue;
        }

        return APIResponse(200, __('api_msg.get_record_successfully'), $goodData);
    }

}
