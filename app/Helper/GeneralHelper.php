<?php 
use App\Models\Setting;
function settingCache($key=null){
    if($key){
        if(session()->get('SiteValue.'.$key)){   
            return session()->get('SiteValue.'.$key);
        }else{  
            $setting = Setting::pluck('value','field_name')->toArray(); 
            foreach($setting as $kkey => $val){
                session()->put('SiteValue.'.$kkey, $val);
            }
            if(isset($setting[$key])){
                return $setting[$key];
            }else {
                return '';
            } 
        }
    }else{
        return '';
    }
}

function setting($key=null){
    if($key){
        $setting = Setting::where('field_name',$key)->first(); 
        if($setting){
            return $setting->value;
        }else{
            return '';
        } 
    }else{
        return '';
    }
}