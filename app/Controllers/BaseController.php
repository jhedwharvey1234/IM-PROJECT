<?php

namespace App\Controllers;

use App\Models\Notification;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RedirectResponse;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        $renderer = service('renderer');
        $renderer->setVar('headerUnreadNotifications', 0);

        if (session()->get('user_id') && $this->hasFullAccessRole()) {
            try {
                $notificationModel = new Notification();
                $notificationModel->syncScheduledAlerts();
                $renderer->setVar('headerUnreadNotifications', $notificationModel->getUnreadCount());
            } catch (\Throwable $e) {
                $renderer->setVar('headerUnreadNotifications', 0);
            }
        }

        $renderer->setVar('headerCanFullAccess', $this->hasFullAccessRole());
        $renderer->setVar('headerIsSuperadmin', $this->hasSuperadminRole());
        $renderer->setVar('headerUserRole', (string) (session()->get('usertype') ?? ''));

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    protected function hasFullAccessRole(): bool
    {
        $role = strtolower(trim((string) session()->get('usertype')));
        return in_array($role, ['superadmin', 'readandwrite'], true);
    }

    protected function hasSuperadminRole(): bool
    {
        $actualRole = strtolower(trim((string) session()->get('usertype_actual')));
        if ($actualRole !== '') {
            if ($actualRole === 'superadmin') {
                return true;
            }

            $userId = (int) (session()->get('user_id') ?? 0);
            if ($userId > 0) {
                try {
                    $userModel = new \App\Models\User();
                    $user = $userModel->find($userId);
                    $dbRole = strtolower(trim((string) ($user['usertype'] ?? '')));
                    if ($dbRole !== '' && $dbRole !== $actualRole) {
                        $sessionRole = $dbRole === 'readandwrite' ? 'superadmin' : $dbRole;
                        session()->set('usertype_actual', $dbRole);
                        session()->set('usertype', $sessionRole);
                        return $dbRole === 'superadmin';
                    }
                } catch (\Throwable $e) {
                }
            }

            return false;
        }

        $role = strtolower(trim((string) session()->get('usertype')));
        return $role === 'superadmin';
    }

    protected function requireAuthenticated(): ?RedirectResponse
    {
        if (!session()->get('user_id')) {
            return redirect()->to('login');
        }

        return null;
    }

    protected function requireFullAccess(): ?RedirectResponse
    {
        if ($redirect = $this->requireAuthenticated()) {
            return $redirect;
        }

        if (!$this->hasFullAccessRole()) {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        return null;
    }

    protected function requireSuperadmin(): ?RedirectResponse
    {
        if ($redirect = $this->requireAuthenticated()) {
            return $redirect;
        }

        if (!$this->hasSuperadminRole()) {
            return redirect()->to('dashboard')->with('error', 'Unauthorized access');
        }

        return null;
    }
}
