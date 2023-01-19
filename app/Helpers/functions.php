<?php

use App\Models\LanguageModel;

function setting_app_name()
{
    $setting = \App\Models\SettingModel::get();
    $data = [];
    foreach ($setting as  $value) {
        $data[$value->key] = $value->value;
    }
    return $data['app_name'];
}

function setting_app_image()
{
    $setting = \App\Models\SettingModel::get();
    $data = [];
    foreach ($setting as  $value) {
        $data[$value->key] = $value->value;
    }
    return $data['app_logo'];
}

function image_path($image = "", $folder = "")
{
    $path = config('app.Img_path' . $folder) . '/' . $image;
    return ($path);
}

// function getWeekDay()
// {
//     return [
//         [
//             'key' => '1',
//             'value' => 'Monday',
//         ],
//         [
//             'key' => '2',
//             'value' => 'Tuesday',
//         ],
//         [
//             'key' => '3',
//             'value' => 'Wednesday',
//         ],
//         [
//             'key' => '4',
//             'value' => 'Thursday',
//         ],
//         [
//             'key' => '5',
//             'value' => 'Friday',
//         ],
//         [
//             'key' => '6',
//             'value' => 'Saturday',
//         ],
//         [
//             'key' => '7',
//             'value' => 'Sunday',
//         ],
//     ];
// }

function getWeekDay()
{
    return [
        "1" =>"Monday",
        "2" =>"Tuesday",
        "3" =>"Wednesday",
        "4" =>"Thursday",
        "5" =>"Friday",
        "6" =>"Saturday",
        "7" =>"Sunday"
    ];
}

function imagepath($img_ext, $folder)
{
    $filename = time() . '.' . $img_ext->getClientOriginalExtension();

    $path = $img_ext->move(public_path('image' . $folder), $filename);

    return ($filename);

}

function language()
{
    $language = LanguageModel::select('*')->get();
    return ($language);
}

// API Function
function Get_Image($folder = "", $name = "")
{
    $data = config('app.Img_path') . '/' . $folder . '/' . $name;
    return ($data);
}

function saveImage($org_name, $folder)
{
    $img_ext = $org_name->getClientOriginalExtension();
    $filename = time() . '.' . $img_ext;
    $path = $org_name->move(base_path('storage/app/public/' . $folder), $filename);
    return $filename;
}

function APIResponse($status, $message, $result = "")
{
    $data['status'] = $status;
    $data['message'] = $message;

    if ($result != "") {
        if (isset($result)) {
            $data['result'] = $result;
        }
    }

    // if (!empty($result) ) {
    //     $data['result'] = $result;
    // } else {
    //     $data['result'] = [];
    // }

    return $data;
}

function SaveNotification($title, $from_user_id, $doctor_id, $patient_id, $appointment_id, $type)
{
    $data['title'] = $title;
    $data['form_user_id'] = $from_user_id;
    $data['doctor_id'] = $doctor_id;
    $data['patient_id'] = $patient_id;
    $data['appointment_id'] = $appointment_id;
    $data['type'] = $type; // 1-like,2- comment , 3- following

    App\Models\NotificationModel::insert($data);
}

function QRCodeGenerate($user_id)
{
    QrCode::format('png')->size(200)->generate($user_id, storage_path('app/public/patients_qrcode/' . $user_id . '.png'));
}
