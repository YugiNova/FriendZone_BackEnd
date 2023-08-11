<?php
namespace App\Repository;

use App\Models\Notification;

class NotificationRepository{

    public function getNotificationsByUser($itemPerTime=5,$userId) {
        $notifications = Notification::where('recieve_id',$userId);
        $total = $notifications->count();
        $notifications = $notifications
                        ->orderBy('created_at','desc')
                        ->orderBy('updated_at','desc')
                        ->cursorPaginate($itemPerTime);

        if($notifications->hasMorePages()){
            $nextCursor = $notifications->nextCursor()->encode();
        }
        $nextPageUrl = $notifications->nextPageUrl();
        $data = $notifications->map(function ($notification) {
                                    $notification->sendUser;
                                    $notification->recieveUser;
                                    return $notification;
                                });

        return [
            'total' => $total,
            'nextCursor' =>$nextCursor ?? "",
            'nexPageUrl' => $nextPageUrl,
            'notifications' => $data
        ];
    }
} 
?>