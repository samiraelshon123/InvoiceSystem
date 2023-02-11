<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function seeAll(){
        $notifications = Notification::get();
        foreach($notifications as $notification){
            $notification->update(['is_seen' => 1]);
        }
        return back();
    }

}
