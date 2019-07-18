<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function generalSettings()
    {
        \View::share('global_page_title', 'General Settings');
        \View::share('global_menu', 48);
        $company_id = \Session::get('company')['company_id'];

        $setList = Settings::where('company_id', $company_id)->first();
        return view('settings.settings', ['setList' => $setList]);
    }

    public function saveSettings(Request $request)
    {
        $rdMethod = $request->rdMethod;
        $rdCategory = $request->rdCategory;
        $company_id = \Session::get('company')['company_id'];

        $is_exists = Settings::where('company_id', $company_id)->first();
        
        if(isset($is_exists->company_id)) 
        {
            Settings::where('company_id', $company_id)->update(['accounting_method' => $rdMethod, 'category' => $rdCategory]);
        } 
        else 
        {
            $saveSettings = new Settings;
            $saveSettings->company_id = $company_id;
            $saveSettings->accounting_method = $rdMethod;
            $saveSettings->category = $rdCategory;
            $saveSettings->save();
        }

        
    }
}
