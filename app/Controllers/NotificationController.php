<?php

namespace App\Controllers;

use App\Models\Notification;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new Notification();
    }

    public function index()
    {
        if ($redirect = $this->ensureSuperadmin()) {
            return $redirect;
        }

        try {
            $this->notificationModel->syncScheduledAlerts();
        } catch (\Throwable $e) {
        }

        $data['notifications'] = $this->notificationModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $this->notificationModel->markAllAsRead();
        service('renderer')->setVar('headerUnreadNotifications', 0);

        $data['title'] = 'Notifications';

        return view('notifications/index', $data);
    }

    private function ensureSuperadmin()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        if (session()->get('usertype') !== 'superadmin') {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        return null;
    }
}
