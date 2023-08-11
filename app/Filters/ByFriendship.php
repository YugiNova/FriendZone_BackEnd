<?php 
    namespace App\Filters;

use App\Models\Friendship;
use Illuminate\Support\Facades\Auth;

    class ByFriendship{
        public function handle($request, \Closure $next)
        {
            
            $builder = $next($request);
        } 
     
    }
?>