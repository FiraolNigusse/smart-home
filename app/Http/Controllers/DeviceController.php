<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller{
    public function toggle(Device $device){
    $device->state = $device->state === 'on' ? 'off' : 'on';
    $device->save();

    ActivityLog::create([
        'user_id'=>auth()->id(),
        'device_id'=>$device->id,
        'action'=>'toggle',
        'result'=>'success'
    ]);

    return back();
}

}
