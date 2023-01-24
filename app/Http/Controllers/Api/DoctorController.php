<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppointmentModel;
use App\Models\Appointment_schedulModel;
use App\Models\Appointment_slotsModel;
use App\Models\DoctorModel;
use App\Models\MedicineModel;
use App\Models\NotificationModel;
use App\Models\PrescriptionModel;
use App\Models\SpecialtieModel;
use DateTime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Validator;
use function PHPUnit\Framework\throwException;

class DoctorController extends Controller
{
    private $folder = "doctor";
    private $folder1 = "specialties";
    private $folder2 = "patient";

    public function DoctorRegistration(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|unique:doctors|email',
                    'password' => 'required',
                    'mobile_number' => 'required|numeric|digits:10',
                ],
                [
                    'email.required' => __('api_msg.please_enter_required_fields'),
                    'mobile_number.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('mobile_number');
                $errors1 = $validation->errors()->first('email');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } elseif ($errors1) {
                    $data['message'] = $errors1;
                } else {
                    $data['message'] = __('api_msg.please_enter_required_fields');
                }
                return $data;
            }

            $first_name = $request->first_name;
            $last_name = $request->last_name;
            $email = $request->email;
            $password = $request->password;
            $mobile_number = $request->mobile_number;
            if ($request->device_token) {
                $device_token = $request->device_token;
            } else {
                $device_token = "";
            }

            $Data = new DoctorModel();
            $Data->first_name = $first_name;
            $Data->last_name = $last_name;
            $Data->email = $email;
            $Data->profile_img = "";
            $Data->instagram_url = "";
            $Data->facebook_url = "";
            $Data->twitter_url = "";
            $Data->services = "";
            $Data->about_us = "";
            $Data->working_time = "";
            $Data->health_care = "";
            $Data->address = "";
            $Data->latitude = "";
            $Data->longitude = "";


            $SId = SpecialtieModel::first();
            if(!empty($SId)){
                $Data->specialties_id = $SId['id'];
            } else {
                return APIResponse(400, __('api_msg.specialities_id_worng'));
            }
            $Data->password = $password;
            $Data->type = 1;
            $Data->mobile_number = $mobile_number;
            $Data->reference_code = "";
            $Data->total_points = 0;
            $Data->device_token = $device_token;
            $Data->status = 1;

            if ($Data->save()) {

                $D_data = DoctorModel::where('id', $Data->id)->with('doctor')->first();

                if (!empty($D_data->profile_img)) {
                    $path = Get_Image($this->folder, $D_data->profile_img);
                    $D_data['profile_img'] = $path;
                } else {
                    $D_data['profile_img'] = asset('/assets/imgs/1.jpg');
                }

                if($D_data['doctor'] != null && isset($D_data)){
                    $D_data['specialities_name'] = $D_data['doctor']['name'];
                } else {
                    $D_data['specialities_name'] = "";
                }
                unset($D_data['doctor']);
                event(new Registered($Data));
                return APIResponse(200, __('api_msg.User_registration_sucessfuly'), array($D_data));

            } else {
                return APIResponse(400, __('api_msg.data_not_save'));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function DoctorLogin(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                    'type' => 'required',
                ],
                [
                    'email.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('email');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } else {
                    $data['message'] = __('api_msg.please_enter_required_fields');
                }
                return $data;
            }

            $email = $request->email;
            $password = $request->password;
            if ($request->device_token) {
                $device_token = $request->device_token;
            } else {
                $device_token = "";
            }

            if ($request->type == '1') {

                $D_data = DoctorModel::where('email', $email)->where('password', $password)->where('type', 1)->first();
                if (!empty($D_data)) {

                    if (!empty($D_data->profile_img)) {
                        $path = Get_Image($this->folder, $D_data->profile_img);
                        $D_data['profile_img'] = $path;
                    } else {
                        $D_data['profile_img'] = asset('/assets/imgs/1.jpg');
                    }
                    $D_data['token'] = $D_data->createToken('passport_token')->accessToken;
                    return APIResponse(200, __('api_msg.login_successfully'), array($D_data));
                } else {
                    return APIResponse(400, __('api_msg.email_pass_worng'));
                }
            } else {
                return APIResponse(400, __('api_msg.change_type'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function DoctorForgotPassword(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                ],
                [
                    'email.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('email');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $email = $request->email;

            $D_data = DoctorModel::where('email', $email)->first();
            if (!empty($D_data)) {

                $data['status'] = 200;
                $data['message'] = __('api_msg.new_password_send');
                return $data;
            } else {
                return APIResponse(400, __('api_msg.email_address_is_not_registered'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function DoctorUpcomingAppoinment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'doctor_id' => 'required|numeric',
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
                }
                return $data;
            }

            $doctor_id = $request->doctor_id;
            $date = date('Y-m-d');

            $D_data = AppointmentModel::where('date', '>=', $date)->where('doctor_id', $doctor_id)->with('doctor')->where('status', '!=', 5)->with('patient')->latest()->get();
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
                    } else {

                        $D_data[$i]['patients_name'] = "";
                        $D_data[$i]['patients_email'] = "";
                        $D_data[$i]['patients_profile_img'] = "";
                        $D_data[$i]['patients_mobile_number'] = "";
                    }

                    unset($D_data[$i]['patient']);
                    unset($D_data[$i]['doctor']);
                }
                return APIResponse(200, __('api_msg.get_record_successfully'), $D_data);
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function AppoinmentStatusUpdate(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'appointment_id' => 'required|numeric',
                    'status' => 'required|numeric|between:1,5',
                ],
                [
                    'appointment_id.required' => __('api_msg.please_enter_required_fields'),
                    'status.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('appointment_id');
                $errors1 = $validation->errors()->first('status');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } else if ($errors1) {
                    $data['message'] = $errors1;
                }
                return $data;
            }

            $appointment_id = $request->appointment_id;
            $status = $request->status;

            $D_data = AppointmentModel::where('id', $appointment_id)->first();
            if (!empty($D_data)) {

                $D_data->status = $status;
                $D_data->save();

                $appointment = AppointmentModel::where('id', $appointment_id)->first();
                $doctor = DoctorModel::where('id', $appointment['doctor_id'])->first();

                // Notification
                $status_detail = '';
                switch ($_REQUEST['status']) {
                    case '2':
                        $status_detail = 'approved';
                        break;
                    case '3':
                        $status_detail = 'reject';
                        break;
                    case '4':
                        $status_detail = 'absent';
                        break;
                    case '5':
                        $status_detail = 'completed';
                        break;
                    default:
                        $status_detail = 'pending';
                        break;
                }
                $title = ' Dr.' . ucfirst($doctor['first_name']) . ' ' . ucfirst($doctor['last_name']) . ' has ' . $status_detail . ' an appointment. Date:' . $appointment['date'];
                SaveNotification($title, $appointment['doctor_id'], 0, $appointment['patient_id'], $_REQUEST['appointment_id'], 1);

                $data['status'] = 200;
                $data['message'] = __('api_msg.update_appointment_sucessfuly');
                return $data;
            } else {
                return APIResponse(400, __('api_msg.appointment_id_worng'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetAllDoctorAppoinment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'doctor_id' => 'required|numeric',
                    'status' => 'integer|between:1,5',
                ],
                [
                    'doctor_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('doctor_id');
                $errors1 = $validation->errors()->first('status');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } else if ($errors1) {
                    $data['message'] = $errors1;
                }
                return $data;
            }

            $doctor_id = $request->doctor_id;
            if ($request->status) {
                $status = $request->status;
            } else {
                $status = "";
            }

            if ($status != "") {

                $D_data = AppointmentModel::where('doctor_id', $doctor_id)->where('status', $status)->with('doctor')->with('patient')->get();
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
                            $D_data[$i]['allergies_to_medicine'] = $D_data[$i]['patient']['allergies_to_medicine'];
                        } else {

                            $D_data[$i]['patients_name'] = "";
                            $D_data[$i]['patients_email'] = "";
                            $D_data[$i]['patients_profile_img'] = "";
                            $D_data[$i]['patients_mobile_number'] = "";
                            $D_data[$i]['allergies_to_medicine'] = "";
                        }

                        unset($D_data[$i]['patient']);
                        unset($D_data[$i]['doctor']);
                    }
                    return APIResponse(200, __('api_msg.get_record_successfully'), $D_data);
                } else {
                    return APIResponse(400, __('api_msg.data_not_found'));
                }
            } else {

                $D_data = AppointmentModel::where('doctor_id', $doctor_id)->with('doctor')->with('patient')->latest()->get();
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
                            $D_data[$i]['allergies_to_medicine'] = $D_data[$i]['patient']['allergies_to_medicine'];
                        } else {

                            $D_data[$i]['patients_name'] = "";
                            $D_data[$i]['patients_email'] = "";
                            $D_data[$i]['patients_profile_img'] = "";
                            $D_data[$i]['patients_mobile_number'] = "";
                            $D_data[$i]['allergies_to_medicine'] = "";
                        }

                        unset($D_data[$i]['patient']);
                        unset($D_data[$i]['doctor']);
                    }
                    return APIResponse(200, __('api_msg.get_record_successfully'), $D_data);
                } else {
                    return APIResponse(400, __('api_msg.data_not_found'));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetDoctorAppoinment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'doctor_id' => 'required|numeric',
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
                }
                return $data;
            }

            $doctor_id = $request->doctor_id;

            $N_Data = NotificationModel::where('doctor_id', $doctor_id)->where('status', 1)->with('patient')->latest()->get();
            if (sizeof($N_Data) > 0) {

                for ($i = 0; $i < count($N_Data); $i++) {

                    // Patient
                    if ($N_Data[$i]['patient'] != null) {

                        $N_Data[$i]['patient_name'] = $N_Data[$i]['patient']['fullname'];
                        if (!empty($N_Data[$i]['patient']['profile_img'])) {

                            $path = Get_Image($this->folder2, $N_Data[$i]['patient']['profile_img']);
                            $N_Data[$i]['patient_profile_img'] = $path;
                        } else {

                            $N_Data[$i]['patient_profile_img'] = asset('/assets/imgs/1.jpg');
                        }
                    } else {

                        $N_Data[$i]['patient_name'] = "";
                        $N_Data[$i]['patient_profile_img'] = "";
                    }
                    unset($N_Data[$i]['patient']);
                }
                return APIResponse(200, __('api_msg.get_record_successfully'), $N_Data);

            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function DoctorUpdateProfile(Request $request)
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

            $R_data = array();

            $user_id = $_REQUEST['user_id'];
            $D_data = DoctorModel::where('id', $user_id)->first();
            if (!empty($D_data)) {

                if (isset($_REQUEST['first_name']) && $_REQUEST['first_name'] != '') {
                    $R_data['first_name'] = $_REQUEST['first_name'];
                }

                if (isset($_REQUEST['last_name']) && $_REQUEST['last_name'] != '') {
                    $R_data['last_name'] = $_REQUEST['last_name'];
                }

                if (isset($_REQUEST['mobile_number']) && $_REQUEST['mobile_number'] != '') {
                    $R_data['mobile_number'] = $_REQUEST['mobile_number'];
                }

                if (isset($_REQUEST['instagram_url']) && $_REQUEST['instagram_url'] != '') {
                    $R_data['instagram_url'] = $_REQUEST['instagram_url'];
                }

                if (isset($_REQUEST['twitter_url']) && $_REQUEST['twitter_url'] != '') {
                    $R_data['twitter_url'] = $_REQUEST['twitter_url'];
                }

                if (isset($_REQUEST['facebook_url']) && $_REQUEST['facebook_url'] != '') {
                    $R_data['facebook_url'] = $_REQUEST['facebook_url'];
                }

                if (isset($_REQUEST['services']) && $_REQUEST['services'] != '') {
                    $R_data['services'] = $_REQUEST['services'];
                }

                if (isset($_REQUEST['about_us']) && $_REQUEST['about_us'] != '') {
                    $R_data['about_us'] = $_REQUEST['about_us'];
                }

                if (isset($_REQUEST['working_time']) && $_REQUEST['working_time'] != '') {
                    $R_data['working_time'] = $_REQUEST['working_time'];
                }

                if (isset($_REQUEST['health_care']) && $_REQUEST['health_care'] != '') {
                    $R_data['health_care'] = $_REQUEST['health_care'];
                }

                if (isset($_REQUEST['address']) && $_REQUEST['address'] != '') {
                    $R_data['address'] = $_REQUEST['address'];
                }

                if (isset($_REQUEST['specialties_id']) && $_REQUEST['specialties_id'] != '') {
                    $R_data['specialties_id'] = $_REQUEST['specialties_id'];
                }

                if (isset($_REQUEST['password']) && $_REQUEST['password'] != '') {
                    $R_data['password'] = $_REQUEST['password'];
                }

                if (isset($_REQUEST['latitude']) && $_REQUEST['latitude'] != '') {
                    $R_data['latitude'] = $_REQUEST['latitude'];
                }

                if (isset($_REQUEST['longitude']) && $_REQUEST['longitude'] != '') {
                    $R_data['longitude'] = $_REQUEST['longitude'];
                }

                if (isset($_FILES['profile_img']) && $_FILES['profile_img'] != '') {

                    $image = $request->file('profile_img');
                    $old_image = $D_data['profile_img'];

                    if ($old_image != "" && $old_image != null) {

                        $files = $image;
                        $ext = $files->extension();
                        $path = base_path('storage/app/public/doctor/');
                        $name = rand() . time() . "." . $ext;
                        $files->move($path, $name);
                        $R_data['profile_img'] = $name;

                        Storage::disk('public')->delete('doctor/' . $old_image);
                    } else {

                        $files = $image;
                        $ext = $files->extension();
                        $path = base_path('storage/app/public/doctor/');
                        $name = rand() . time() . "." . $ext;
                        $files->move($path, $name);
                        $R_data['profile_img'] = $name;
                    }
                }

                $Save = DoctorModel::where('id', $user_id)->update($R_data);

                $this->check_doctor_status($user_id);

                $data['status'] = 200;
                $data['message'] = __('api_msg.update_profile_sucessfuly');
                return $data;

            } else {
                return APIResponse(400, __('api_msg.user_id_worng'));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function check_doctor_status($doctor_id)
    {
        try {

            $doctor = DoctorModel::where('id', $doctor_id)->first();

            $status = '0';
            if ($doctor['first_name'] != '' && $doctor['last_name'] != '' && $doctor['email'] != '' && $doctor['profile_img'] != '' && $doctor['services'] != '' && $doctor['about_us'] != '' && $doctor['health_care'] != '' && $doctor['address'] != '' && $doctor['specialties_id'] != '' && $doctor['mobile_number'] != '') {
                $status = '1';
            }

            $AS_data = Appointment_schedulModel::where('doctor_id', $doctor_id)->get();
            $appointment_status = 0;
            if (count($AS_data) > 0) {
                $appointment_status = 1;
            }

            $data['status'] = '0';
            if ($appointment_status == 1 && $status == 1) {
                $data['status'] = '1';
            }

            $doctor->status = $data['status'];
            $doctor->save();
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetSelectedTimeslote(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'doctor_id' => 'required|numeric',
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
                }
                return $data;
            }

            $doctor_id = $request->doctor_id;

            $getWeekDay = Appointment_slotsModel::select('week_day')->where('doctor_id', $doctor_id)->groupBy('week_day')->get();
            $finalArray = [];
            $weekDay = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7];

            foreach ($getWeekDay as $key => $value) {
                unset($weekDay[$value->week_day]);

                $appointment_slots = Appointment_slotsModel::where('doctor_id', $doctor_id)->where('week_day', $value->week_day)->latest()->get();

                $datas = [];
                foreach ($appointment_slots as $key => $slotsvalue) {
                    $result = Appointment_schedulModel::where('doctor_id', $doctor_id)->where('week_day', $slotsvalue['week_day'])->where('appointment_slots_id', $slotsvalue['id'])->get();

                    $slotsvalue['time_schedul'] = $result;
                    $datas[] = $slotsvalue;
                }
                $value->time_slotes = $datas;
                $finalArray[] = $value;
            }

            if (count($weekDay) > 0) {
                foreach ($weekDay as $key => $value) {
                    $wekDayNew['week_day'] = (string) $value;
                    $wekDayNew['time_slotes'] = array();

                    array_push($finalArray, $wekDayNew);
                }
            }

            return APIResponse(200, __('api_msg.get_record_successfully'), $finalArray);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function UpdateDoctorTimeSlots(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'doctor_schedul' => 'required',
                ],
                [
                    'doctor_schedul.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('doctor_schedul');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $doctor_schedul = $_POST['doctor_schedul'];
            $data = json_decode($doctor_schedul);

            $doctor_id = $data[0]->doctor_id;
            $week_day = $data[0]->week_day;

            Appointment_schedulModel::where('doctor_id', $doctor_id)->where('week_day', $week_day)->delete();
            Appointment_slotsModel::where('doctor_id', $doctor_id)->where('week_day', $week_day)->delete();

            foreach ($data as $key => $value) {
                $insert = [];
                $insert['doctor_id'] = $value->doctor_id;
                $insert['week_day'] = $value->week_day;
                $insert['start_time'] = $value->start_time;
                $insert['end_time'] = $value->end_time;
                $insert['time_duration'] = $value->time_duration;
                $insert['status'] = 1;
                $appointment_slots_id = Appointment_slotsModel::insert($insert);

                $slots = $this->getTimeSlot($value->time_duration, $value->start_time, $value->end_time);

                foreach ($slots as $slotsvalue) {
                    $insert1 = [];
                    $insert1['doctor_id'] = $value->doctor_id;
                    $insert1['week_day'] = $value->week_day;
                    $insert1['start_time'] = $slotsvalue['start'];
                    $insert1['end_time'] = $slotsvalue['end'];
                    $insert1['appointment_slots_id'] = $appointment_slots_id;
                    $insert1['status'] = 1;
                    Appointment_schedulModel::insert($insert1);
                }
            }
            return APIResponse(200, __('api_msg.record_add_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
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

    public function DeleteDoctorTimeSlots(Request $request)
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

            Appointment_slotsModel::where('id', $id)->delete();
            Appointment_schedulModel::where('appointment_slots_id', $id)->delete();

            $data['status'] = 200;
            $data['message'] = __('api_msg.delete_success');
            return $data;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function SearchMedicine(Request $request)
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

            $M_data = MedicineModel::where('name', 'LIKE', "%{$name}%")->get();
            if (sizeof($M_data) > 0) {
                return APIResponse(200, __('api_msg.get_record_successfully'), $M_data);
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function AddPrescription(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'appointment_id' => 'required|numeric',
                    'medicine_id' => 'required|numeric',
                    'amount' => 'required',
                    'how_may' => 'required',
                    'how_long' => 'required',
                    'when_to_take' => 'required',
                    'note' => 'required',
                ],
                [
                    'appointment_id.required' => __('api_msg.please_enter_required_fields'),
                    'medicine_id.required' => __('api_msg.please_enter_required_fields'),
                    'amount.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('appointment_id');
                $errors1 = $validation->errors()->first('medicine_id');
                $errors2 = $validation->errors()->first('amount');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } elseif ($errors1) {
                    $data['message'] = $errors1;
                } elseif ($errors2) {
                    $data['message'] = $errors2;
                } else {
                    $data['message'] = __('api_msg.please_enter_required_fields');
                }
                return $data;
            }

            $insert = [];
            $insert['appointment_id'] = $request->appointment_id;
            $insert['medicine_id'] = $request->medicine_id;
            $insert['amount'] = $request->amount;
            $insert['how_may'] = $request->how_may;
            $insert['how_long'] = $request->how_long;
            $insert['when_to_take'] = $request->when_to_take;
            $insert['note'] = $request->note;

            $id = PrescriptionModel::insertGetId($insert);

            $data['status'] = 200;
            $data['message'] = __('api_msg.record_add_successfully');
            $data['id'] = $id;
            return $data;

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetPrescription(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'appointment_id' => 'required|numeric',
                ],
                [
                    'appointment_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('appointment_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $appointment_id = $request->appointment_id;
            $P_data = PrescriptionModel::where('appointment_id', $appointment_id)->with('medicine')->latest()->get();

            if (sizeof($P_data) > 0) {

                for ($i = 0; $i < count($P_data); $i++) {
                    if ($P_data != null) {
                        $P_data[$i]['name'] = $P_data[$i]['medicine']['name'];
                        $P_data[$i]['form'] = $P_data[$i]['medicine']['form'];
                        $P_data[$i]['power'] = $P_data[$i]['medicine']['power'];
                    } else {
                        $P_data[$i]['name'] = "";
                        $P_data[$i]['form'] = "";
                        $P_data[$i]['power'] = "";
                    }
                    unset($P_data[$i]['medicine']);
                }
                return APIResponse(200, __('api_msg.get_record_successfully'), $P_data);
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function DeletePrescription(Request $request)
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

            PrescriptionModel::where('id', $id)->deletE();
            $data['status'] = 200;
            $data['message'] = __('api_msg.delete_success');
            return $data;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function UpdateAppoinment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'appointment_id' => 'required|numeric',
                ],
                [
                    'appointment_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('appointment_id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $appointment_id = $request->appointment_id;
            $doctor_symptoms = isset($request->doctor_symptoms) ? $request->doctor_symptoms : '';
            $doctor_diagnosis = isset($request->doctor_diagnosis) ? $request->doctor_diagnosis : '';

            $Data = array(
                'doctor_symptoms' => $doctor_symptoms,
                'doctor_diagnosis' => $doctor_diagnosis,
            );

            AppointmentModel::where('id', $appointment_id)->update($Data);
            $data['status'] = 200;
            $data['message'] = __('api_msg.add_successfully');
            return $data;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function VerifyAccount(Request $request)
    {
        $user = DoctorModel::where('email' , $request->email)->first();
        if($request->code == "068902")
        {
            $user->markEmailAsVerified();
            return response()->json(array(
                'code' => 200,
                'status' => 1 ,
                'errors' => null ,
                'message' => "you're verified now " ,
                'token' => $user->createToken('passport_token')->accessToken));
        }else{
            throw new BadRequestHttpException('incorrect verify code');
        }
    }
}
