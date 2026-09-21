<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource for Admin.
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()->notifications()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Display a listing of the resource for Employee Portal.
     */
    public function portalIndex(Request $request): View
    {
        $notifications = $request->user()->notifications()->paginate(10);

        return view('notifications.portal', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
