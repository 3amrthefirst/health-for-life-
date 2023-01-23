<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppointmentModel;
use App\Models\DoctorModel;
use App\Models\NotificationModel;
use App\Models\PatientsModel;
use App\Models\SpecialtieModel;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Validator;

class UsersController extends Controller
{
    private $folder = "setting";
    private $folder1 = "specialties";
    private $folder2 = "patient";
    private $folder3 = "doctor";
    private $folder4 = "insurance_card";

    public function Login(Request $request)
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

            if (isset($_REQUEST['email'])) {

                $email = $request->email;
                $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
                $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
                $device_token = isset($_REQUEST['device_token']) ? $_REQUEST['device_token'] : '';

                if ($type == '1') {

                    $user_data = PatientsModel::where('email', $email)->where('password', $password)->first();
                    if (isset($user_data['id'])) {

                        $data_device_token = array(
                            'device_token' => $device_token,
                        );

                        $user_data = PatientsModel::where('id', $user_data['id']);
                        $user_data->update($data_device_token);
                        $data = $user_data->first();

                        if (!empty($data['profile_img'])) {
                            $path = Get_Image($this->folder2, $data['profile_img']);
                            $data['profile_img'] = $path;
                        } else {
                            $data['profile_img'] = asset('/assets/imgs/1.jpg');
                        }
                        if (!empty($data['insurance_card_pic'])) {
                            $path = Get_Image($this->folder4, $data['insurance_card_pic']);
                            $data['insurance_card_pic'] = $path;
                        }

                        return APIResponse(200, __('api_msg.login_successfully'), array($data));
                    } else {
                        return APIResponse(400, __('api_msg.email_pass_worng'));
                    }
                } else {
                    return APIResponse(400, __('api_msg.change_type'));
                }
            } else {
                return APIResponse(400, __('api_msg.enter_email_and_pasword'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Registration(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'email' => 'required|unique:patients|email',
                    'password' => 'required',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'mobile_number' => 'required|numeric|digits:10',
                ],
                [
                    'email.required' => __('api_msg.please_enter_required_fields'),
                    'mobile_number.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('email');
                $errors1 = $validation->errors()->first('mobile_number');
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
            $insurance_company_id = isset($request->insurance_company_id) ? $request->insurance_company_id : '';
            $insurance_no = isset($request->insurance_no) ? $request->insurance_no : '';

            $data = array(
                'patient_id' => Str::random(8),
                'firebase_id' => "",
                'fullname' => $first_name . ' ' . $last_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'user_name' => '',
                'email' => $email,
                'password' => $password,
                'profile_img' => "",
                'instagram_url' => "",
                'facebook_url' => "",
                'twitter_url' => "",
                'biodata' => "",
                'type' => 1,
                'mobile_number' => $mobile_number,
                'location' => "",
                'insurance_company_id' => $insurance_company_id,
                'insurance_no' => $insurance_no,
                'device_token' => $device_token,
                'allergies_to_medicine' => "",
                'current_height' => "",
                'current_weight' => "",
                'BMI' => "",
                'reference_code' => "",
                'total_points' => 0,
                'date' => date('Y-m-d'),
                'status' => "enable",
            );

            if (isset($_FILES['insurance_card_pic']) && $_FILES['insurance_card_pic'] != '') {

                $image = $request->file('insurance_card_pic');
                $files = $image;
                $ext = $files->extension();
                $path = base_path('storage/app/public/insurance_card/');
                $name = rand() . time() . "." . $ext;
                $files->move($path, $name);
                $data['insurance_card_pic'] = $name;

            } else {
                $data['insurance_card_pic'] = "";
            }

            $user_id = PatientsModel::insertGetId($data);
            if (isset($user_id)) {

                $user_data = PatientsModel::where('id', $user_id)->first();

                if (!empty($user_data['profile_img'])) {
                    $path = Get_Image($this->folder2, $user_data['profile_img']);
                    $user_data['profile_img'] = $path;
                } else {
                    $user_data['profile_img'] = asset('/assets/imgs/1.jpg');
                }
                if (!empty($user_data['insurance_card_pic'])) {
                    $path = Get_Image($this->folder4, $user_data['insurance_card_pic']);
                    $user_data['insurance_card_pic'] = $path;
                }

                // QRCode
                //QRCodeGenerate($user_data['id']);
                event(new Registered($user_data));
                return APIResponse(200, __('api_msg.User_registration_sucessfuly'), array($user_data));
            } else {
                return APIResponse(400, __('api_msg.data_not_save'));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function PasswordChange(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'password' => 'required',
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
                } else {
                    $data['message'] = __('api_msg.please_enter_required_fields');
                }
                return $data;
            }

            $user_id = $request->user_id;
            $password = $request->password;

            $Data = array(
                'password' => $password,
            );

            $user_id = PatientsModel::where('id', $user_id)->update($Data);
            $data['status'] = 200;
            $data['message'] = __('api_msg.password_change_successfully');
            return $data;

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function Profile(Request $request)
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

            $user_data = PatientsModel::where('id', $user_id)->with('insurance_company')->first();
            if (!empty($user_data)) {

                if (!empty($user_data['profile_img'])) {
                    $path = Get_Image($this->folder2, $user_data['profile_img']);
                    $user_data['profile_img'] = $path;
                } else {
                    $user_data['profile_img'] = asset('/assets/imgs/1.jpg');
                }
                if (!empty($user_data['insurance_card_pic'])) {
                    $path = Get_Image($this->folder4, $user_data['insurance_card_pic']);
                    $user_data['insurance_card_pic'] = $path;
                }

                if (!empty($user_data['insurance_company'])) {
                    $user_data['insurance_company_name'] = $user_data['insurance_company']['name'];
                } else {
                    $user_data['insurance_company_name'] = "";
                }
                unset($user_data['insurance_company']);
                $user_data['patients_qrcode_img'] = env('Img_path') . '/patients_qrcode' . '/' . $user_data['id'] . '.png';

                return APIResponse(200, __('api_msg.get_record_successfully'), array($user_data));
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function UpdateProfile(Request $request)
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
            $data = array();

            $P_data = PatientsModel::where('id', $user_id)->first();
            if (!empty($P_data)) {

                if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
                    $data['email'] = $_REQUEST['email'];
                }
                if (isset($_REQUEST['first_name']) && $_REQUEST['first_name'] != '') {
                    $data['first_name'] = $_REQUEST['first_name'];
                }
                if (isset($_REQUEST['last_name']) && $_REQUEST['last_name'] != '') {
                    $data['last_name'] = $_REQUEST['last_name'];
                }
                if (isset($_REQUEST['user_name']) && $_REQUEST['user_name'] != '') {
                    $data['user_name'] = $_REQUEST['user_name'];
                }
                if (isset($_REQUEST['instagram_url']) && $_REQUEST['instagram_url'] != '') {
                    $data['instagram_url'] = $_REQUEST['instagram_url'];
                }
                if (isset($_REQUEST['twitter_url']) && $_REQUEST['twitter_url'] != '') {
                    $data['twitter_url'] = $_REQUEST['twitter_url'];
                }
                if (isset($_REQUEST['facebook_url']) && $_REQUEST['facebook_url'] != '') {
                    $data['facebook_url'] = $_REQUEST['facebook_url'];
                }
                if (isset($_REQUEST['biodata']) && $_REQUEST['biodata'] != '') {
                    $data['biodata'] = $_REQUEST['biodata'];
                }
                if (isset($_REQUEST['mobile_number']) && $_REQUEST['mobile_number'] != '') {
                    $data['mobile_number'] = $_REQUEST['mobile_number'];
                }
                if (isset($_REQUEST['location']) && $_REQUEST['location'] != '') {
                    $data['location'] = $_REQUEST['location'];
                }
                if (isset($_REQUEST['insurance_company_id']) && $_REQUEST['insurance_company_id'] != '') {
                    $data['insurance_company_id'] = $_REQUEST['insurance_company_id'];
                }
                if (isset($_REQUEST['insurance_no']) && $_REQUEST['insurance_no'] != '') {
                    $data['insurance_no'] = $_REQUEST['insurance_no'];
                }
                if (isset($_POST['allergies_to_medicine']) && $_POST['allergies_to_medicine'] != '') {
                    $data['allergies_to_medicine'] = $_POST['allergies_to_medicine'];
                }
                if (isset($_POST['current_height']) && $_POST['current_height'] != '') {
                    $data['current_height'] = $_POST['current_height'];
                }
                if (isset($_POST['current_weight']) && $_POST['current_weight'] != '') {
                    $data['current_weight'] = $_POST['current_weight'];
                }
                if (isset($_POST['BMI']) && $_POST['BMI'] != '') {
                    $data['BMI'] = $_POST['BMI'];
                }

                if (isset($_FILES['profile_img']) && $_FILES['profile_img'] != '') {

                    $image = $request->file('profile_img');
                    $old_image = $P_data['profile_img'];

                    if ($old_image != "" && $old_image != null) {

                        $files = $image;
                        $ext = $files->extension();
                        $path = base_path('storage/app/public/patient/');
                        $name = rand() . time() . "." . $ext;
                        $files->move($path, $name);
                        $data['profile_img'] = $name;

                        Storage::disk('public')->delete('patient/' . $old_image);
                    } else {

                        $files = $image;
                        $ext = $files->extension();
                        $path = base_path('storage/app/public/patient/');
                        $name = rand() . time() . "." . $ext;
                        $files->move($path, $name);
                        $data['profile_img'] = $name;
                    }
                }

                if (isset($_FILES['insurance_card_pic']) && $_FILES['insurance_card_pic'] != '') {

                    $image = $request->file('insurance_card_pic');
                    $old_image = $P_data['insurance_card_pic'];

                    if ($old_image != "" && $old_image != null) {

                        $files = $image;
                        $ext = $files->extension();
                        $path = base_path('storage/app/public/insurance_card/');
                        $name = rand() . time() . "." . $ext;
                        $files->move($path, $name);
                        $data['insurance_card_pic'] = $name;

                        Storage::disk('public')->delete('insurance_card/' . $old_image);
                    } else {

                        $files = $image;
                        $ext = $files->extension();
                        $path = base_path('storage/app/public/insurance_card/');
                        $name = rand() . time() . "." . $ext;
                        $files->move($path, $name);
                        $data['insurance_card_pic'] = $name;
                    }
                }

                $user_data = PatientsModel::where('id', $user_id)->update($data);
                $Data['status'] = 200;
                $Data['message'] = __('api_msg.update_profile_sucessfuly');
                return $Data;
            } else {
                return APIResponse(400, __('api_msg.user_id_worng'));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function GetAllAppoinment(Request $request)
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

        $D_data = AppointmentModel::where('patient_id', $user_id)->with('doctor')->with('patient')->latest()->get();

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

    public function GetPatientAppoinment(Request $request)
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

        $N_Data = NotificationModel::where('patient_id', $patient_id)->where('status', 1)->with('doctor')->latest()->get();

        if (sizeof($N_Data) > 0) {

            for ($i = 0; $i < count($N_Data); $i++) {

                // Patient
                if ($N_Data[$i]['doctor'] != null) {

                    $N_Data[$i]['doctor_name'] = $N_Data[$i]['doctor']['first_name'] . '' . $N_Data[$i]['doctor']['last_name'];
                    if (!empty($N_Data[$i]['doctor']['profile_img'])) {

                        $path = Get_Image($this->folder2, $N_Data[$i]['doctor']['profile_img']);
                        $N_Data[$i]['doctor_profile_img'] = $path;
                    } else {

                        $N_Data[$i]['doctor_profile_img'] = asset('/assets/imgs/1.jpg');
                    }
                } else {

                    $N_Data[$i]['doctor_name'] = "";
                    $N_Data[$i]['doctor_profile_img'] = "";
                }
                unset($N_Data[$i]['doctor']);
            }
            return APIResponse(200, __('api_msg.order_the_notifications_with_the_most_recent_being_the_first_one_and_the_oldest_one_as_the_last_one_in_both_the_doctor_and_patient_apps'), $N_Data);
        } else {
            return APIResponse(400, __('api_msg.data_not_found'));
        }
    }

    public function VerifyAccount(Request $request)
    {
        $user = PatientsModel::where('email' , $request->email)->first();
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
