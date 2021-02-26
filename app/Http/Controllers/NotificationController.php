<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ajax');
    }

    public function markAsRead($id)
    {
        $user = User::find(request()->user()->id);
        $notifications = $user->unreadNotifications;
        if (count($notifications)) {
            $notifications->toQuery()
            ->where('id', $id)
            ->first()->delete();
        }
        $user->refresh();
        $response = (object)NULL;
        $response->notifications = $user->unreadNotifications;
        return json_encode($response);
    }

    public function markAllAsRead()
    {
        $user = User::find(request()->user()->id);
        // Mark all as read
        $user->notifications()->delete();

        return 1;
    }
}
