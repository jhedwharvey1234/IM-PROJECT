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
        if ($redirect = $this->requireAuthenticated()) {
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
        return $this->requireFullAccess();
    }
}
