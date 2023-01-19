<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestAppointmentModel;
use App\Models\TestAppointmentSchedulModel;
use App\Models\TestAppointmentSlotsModel;
use Illuminate\Http\Request;
use Validator;

class TestController extends Controller
{

    private $folder2 = "patient";

    public function GetTestTimeSlot(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'date' => 'required',
                ],
                [
                    'date.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('date');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $per_slot_user_limit = 0;
            $wwekDay = date('w', strtotime($_POST['date']));

            if ($wwekDay == 0) {
                $wwekDay = 7;
            }
            $date = $request->date;

            $appointment_slots = TestAppointmentSlotsModel::where('week_day', $wwekDay)->get();

            $goodData = array();
            foreach ($appointment_slots as $key => $slotsvalue) {

                $result = TestAppointmentSchedulModel::where('week_day', $wwekDay)->where('test_appointment_slots_id', $slotsvalue['id'])->get();

                $docTimeSlot = [];
                foreach ($result as $key => $value) {
                    $value['slot_status'] = 1;
                    $value['totle_booking_user'] = 0;
                    $docTimeSlot[$value['start_time']] = $value;
                }

                $resultAppointment = TestAppointmentModel::where('date', $date)->get();

                foreach ($resultAppointment as $key => $value) {

                    if (isset($docTimeSlot[$value['startTime']])) {
                        $docTimeSlot[$value['startTime']]['totle_booking_user'] += 1;

                        if ($per_slot_user_limit != 0) {
                            if ($docTimeSlot[$value['startTime']]['totle_booking_user'] >= $per_slot_user_limit) {
                                $docTimeSlot[$value['startTime']]['slot_status'] = 0;
                            }
                        } else {
                            $docTimeSlot[$value['startTime']]['slot_status'] = 0;
                        }
                    }
                }
                sort($docTimeSlot);
                $slotsvalue['slot'] = $docTimeSlot;

                $goodData[] = $slotsvalue;
            }
            return APIResponse(200, __('api_msg.get_record_successfully'), $goodData);

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function MakeTestAppoinment(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'patient_id' => 'required|numeric',
                'test_appointment_slots_id' => 'required|numeric',
                'date' => 'required',
                'startTime' => 'required',
                'endTime' => 'required',
                'description' => 'required',
            ],
            [
                'patient_id.required' => __('api_msg.please_enter_required_fields'),
                'test_appointment_slots_id.required' => __('api_msg.please_enter_required_fields'),
            ]
        );
        if ($validation->fails()) {

            $errors1 = $validation->errors()->first('patient_id');
            $errors2 = $validation->errors()->first('test_appointment_slots_id');
            $data['status'] = 400;
            if ($errors1) {
                $data['message'] = $errors1;
            } else if ($errors2) {
                $data['message'] = $errors2;
            } else {
                $data['message'] = __('api_msg.please_enter_required_fields');
            }
            return $data;
        }

        $date = date('Y-m-d');
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
        $symptoms = isset($_REQUEST['symptoms']) ? $_REQUEST['symptoms'] : '';
        $medicines_taken = isset($_REQUEST['medicines_taken']) ? $_REQUEST['medicines_taken'] : '';
        $insurance_details = isset($_REQUEST['insurance_details']) ? $_REQUEST['insurance_details'] : '';

        $appointment['patient_id'] = $_REQUEST['patient_id'];
        $appointment['date'] = date('Y-m-d', strtotime($_REQUEST['date']));
        $appointment['test_appointment_slots_id'] = $_REQUEST['test_appointment_slots_id'];
        $appointment['startTime'] = $_REQUEST['startTime'];
        $appointment['endTime'] = $_REQUEST['endTime'];
        $appointment['description'] = $_REQUEST['description'];
        $appointment['symptoms'] = $symptoms;
        $appointment['medicines_taken'] = $medicines_taken;
        $appointment['insurance_details'] = $insurance_details;
        $appointment['status'] = 1;
        $appointment['mobile_number'] = isset($_REQUEST['mobile_number']) ? $_REQUEST['mobile_number'] : '';

        $appointment_id = TestAppointmentModel::insert($appointment);
        if ($appointment_id) {
            return APIResponse(200, __('api_msg.test_book_appointment'));
        } else {
            return APIResponse(400, __('api_msg.data_not_save'));
        }
    }

    public function GetTestPatientAppoinment(Request $request)
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

        $result = TestAppointmentModel::where('patient_id', $patient_id)->with('patients')->latest()->get();

        if (sizeof($result) > 0) {
            for ($i = 0; $i < count($result); $i++) {
                // Patient
                if ($result[$i]['patients'] != null) {
                    $result[$i]['patients_name'] = $result[$i]['patients']['fullname'];
                    $result[$i]['patients_email'] = $result[$i]['patients']['email'];
                    $result[$i]['patients_mobile_number'] = $result[$i]['patients']['mobile_number'];
                    if (!empty($result[$i]['patients']['profile_img'])) {
                        $path = Get_Image($this->folder2, $result[$i]['patients']['profile_img']);
                        $result[$i]['patients_profile_img'] = $path;
                    } else {
                        $result[$i]['patients_profile_img'] = asset('/assets/imgs/1.jpg');
                    }
                    $result[$i]['patients_qrcode_img'] = env('Img_path') . '/patients_qrcode' . '/' . $result[$i]['patient']['id'] . '.png';
                } else {
                    $result[$i]['patients_name'] = "";
                    $result[$i]['patients_email'] = "";
                    $result[$i]['patients_mobile_number'] = "";
                    $result[$i]['patients_profile_img'] = "";
                    $result[$i]['patients_qrcode_img'] = "";
                }
                unset($result[$i]['patients']);
            }
            return APIResponse(200, __('api_msg.order_the_notifications_with_the_most_recent_being_the_first_one_and_the_oldest_one_as_the_last_one_in_both_the_doctor_and_patient_apps'), $result);
        } else {
            return APIResponse(400, __('api_msg.data_not_found'));
        }
    }

}
