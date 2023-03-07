<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            "site_currency" => "Dollars",
            "site_currency_symbol" => "$",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "employee_prefix" => "#EMP00",
            "footer_title" => "",
            "footer_notes" => "",
            "company_start_time" => "09:00",
            "company_end_time" => "18:00",
            'create_user' => '1',
            'employee_create' => '1',
            'payroll_create' => '1',
            'ticket_create' => '1',
            'award_create' => '1',
            'employee_transfer' => '1',
            'employee_resignation' => '1',
            'employee_trip' => '1',
            'employee_promotion' => '1',
            'employee_complaints' => '1',
            'employee_warning' => '1',
            'employee_termination' => '1',
            'leave_status' => '1',
            'contract'=>'1',
            "default_language" => "en",
            "display_landing_page" => "on",
            "ip_restrict" => "on",
            "title_text" => "",
            "footer_text" => "",
            "gdpr_cookie" => "",
            "cookie_text" => "",
            "metakeyword" => "",
            "metadesc" => "",
            "zoom_apikey" => "",
            'zoom_secret_key' => "",
            'disable_signup_button' => "",
            // "dark_mode"=>"off",
            // "theme_color"=>'theme-3',
            // "is_sidebar_transperent"=>'on',
            "theme_color" => "theme-3",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "SITE_RTL" => "off",
            "company_logo" => 'logo-dark.png',
            "company_logo_light"=> 'logo-light.png',
            "dark_logo" => "logo-dark.png",
            "light_logo" => "logo-light.png",
            "contract_prefix" => "#CON",
        ];

        // foreach ($settings as $key => $value) {
            
        // }
    }
}
