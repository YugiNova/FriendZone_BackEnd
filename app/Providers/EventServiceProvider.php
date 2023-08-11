<?php

namespace App\Providers;

use App\Events\AcceptFriendRequest;
use App\Events\CreateComment;
use App\Events\CreatePost;
use App\Events\SendFriendRequest;
use App\Listeners\AddPostToCache;
use App\Listeners\CreateAcceptNotification;
use App\Listeners\CreateCommentNotification;
use App\Listeners\CreatePostNotification;
use App\Listeners\CreateRequestNotification;
use App\Listeners\DeleteRequestNotification;
use App\Listeners\IncreaseCommentCount;
use App\Listeners\IncreaseFriendCount;
use App\Listeners\PushAcceptNotification;
use App\Listeners\PushCreateCommentNotification;
use App\Listeners\PushCreatePostNotification;
use App\Listeners\PushRequestNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SendFriendRequest::class => [
            CreateRequestNotification::class,
            PushRequestNotification::class
        ],
        AcceptFriendRequest::class => [
            DeleteRequestNotification::class,
            CreateAcceptNotification::class,
            PushAcceptNotification::class,
            IncreaseFriendCount::class
        ],
        CreatePost::class => [
            AddPostToCache::class,
            CreatePostNotification::class,
            PushCreatePostNotification::class
        ],
        CreateComment::class => [
            IncreaseCommentCount::class,
            CreateCommentNotification::class,
            PushCreateCommentNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
