<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->update(['is_read' => 1]);

        return response()->json([
            'success' => true
        ]);
    }
}
