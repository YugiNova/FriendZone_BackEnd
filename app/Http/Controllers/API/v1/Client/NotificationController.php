<?php

namespace App\Http\Controllers\API\v1\Client;

use App\Exceptions\NotificationException;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\MyHelper;
use App\Repository\NotificationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private $hepler;
    private $notificationRepository;

    public function __construct(MyHelper $helper, NotificationRepository $notificationRepository){
        $this->hepler = $helper;
        $this->notificationRepository = $notificationRepository;
    }

    public function getNotifications(Request $request){
        try {
            $userId = Auth::user()->id;
            $notifications=$this->notificationRepository->getNotificationsByUser(10,$userId);
            return $this->hepler->custom_response('Get notifications successfull',$notifications);
        } catch (\Exception $e) {
            throw new NotificationException($e);
        }
    }

    public function markAsRead(Request $request){

    }
}
