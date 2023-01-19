<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\SettingModel;

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = [
            ['key' => 'app_name' ,'value'=>'DT_Docter'],
            ['key' => 'host_email' ,'value'=>'support@divinetechs.com'],
            ['key' => 'app_version' ,'value'=>'1.0'],
            ['key' => 'author' ,'value'=>'DivineTechs'],
            ['key' => 'email' ,'value'=>'info@streambang.com'],
            ['key' => 'contact' ,'value'=>'123456789'],
            ['key' => 'website', 'value' => 'www.streambang.com'],
            ['key' => 'privacy_policy', 'value' => 'privacy policy'],
            ['key' => 'app_description' ,'value'=>'Superduper Podcasts is your source for up-to-date, detailed information on all funny and entertaining things happening in India. The daily podcast will feature “RJ Hemanth” about both timely, seasonal products in our portfolio alongside material on new-and-noteworthy launches. Our main objective ...'],
            ['key' => 'app_logo' , 'value' =>'1654518021.png'],
            ['key' => 'Banner_Ad', 'value'=>'yes'],
            ['key' => 'Interstital_Ad', 'value'=>'yes'],
            ['key' => 'Reward_Ad', 'value'=>'yes'],
            ['key' => 'Banner_Ad_ID', 'value'=>'1'],
            ['key' => 'Interstital_Ad_ID', 'value'=>'1'],
            ['key' => 'Interstita_Ad_Click', 'value'=>'1'],
            ['key' => 'Reward_Ad_ID', 'value'=>'1'],
            ['key' => 'Reward_Ad_Click', 'value'=>'1'],
            ['key' => 'Banner_Ad_ios', 'value'=>'yes'],
            ['key' => 'Interstital_Ad_ios', 'value'=>'yes'],
            ['key' => 'Reward_Ad_ios', 'value'=>'yes'],
            ['key' => 'Banner_Ad_ID_ios', 'value'=>'1'],
            ['key' => 'Interstital_Ad_ID_ios', 'value'=>'1'],
            ['key' => 'Interstita_Ad_Click_ios', 'value'=>'1'],
            ['key' => 'Reward_Ad_id_iso', 'value'=>'1'],
            ['key' => 'Reward_Ad_click_iso', 'value'=>'1'],
            ['key' => 'fb_rewardvideo_status', 'value'=>'yes'],
            ['key' => 'fb_native_status', 'value'=>'yes'],
            ['key' => 'fb_banner_status', 'value'=>'yes'],
            ['key' => 'fb_interstiatial_status', 'value'=>'yes'],
            ['key' => 'fb_native_full_status', 'value'=>'yes'],
            ['key' => 'fb_rewardvideo_id', 'value'=>'1'],
            ['key' => 'fb_interstiatial_id', 'value'=>'1'],
            ['key' => 'fb_native_id', 'value'=>'1'],
            ['key' => 'fb_native_full_id', 'value'=>'1'],
            ['key' => 'fb_banner_id', 'value'=>'1'],
            ['key' => 'fb_rewardvideo_status_iso', 'value'=>'yes'],
            ['key' => 'fb_native_status_iso', 'value'=>'yes'],
            ['key' => 'fb_banner_status_iso', 'value'=>'yes'],
            ['key' => 'fb_interstiatial_status_iso', 'value'=>'yes'],
            ['key' => 'fb_native_full_status_iso', 'value'=>'yes'],
            ['key' => 'fb_rewardvideo_id_iso', 'value'=>'1'],
            ['key' => 'fb_interstiatial_id_iso', 'value'=>'1'],
            ['key' => 'fb_native_id_iso', 'value'=>'1'],
            ['key' => 'fb_native_full_id_iso', 'value'=>'1'],
            ['key' => 'fb_banner_id_iso', 'value'=>'1'],
            ['key' => 'onesignal_app_id', 'value'=>'49da5dab-d4ed-4900-9f61-d2c9e931ac2d'],
            ['key' => 'onesignal_rest_key', 'value'=>'NjgzYjY1ZTMtYTEwYi00ZTBmLWExNTQtNTI5MjQzZWZmZTU1'],
            ['key' => 'earning_point', 'value'=>'50'],
            ['key' => 'earning_amount', 'value'=>'1'],
            ['key' => 'min_earning_point', 'value'=>'1000'],
            ['key' => 'currency', 'value'=>'USD'],
        ]; 
        SettingModel::insert($setting);
    }
}
