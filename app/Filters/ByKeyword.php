<?php 
    namespace App\Filters;

    class ByKeyword{
        public function handle($request, \Closure $next)
        {
            
            $builder = $next($request);
            
            if(request()->query('keyword')){
                return $builder->where('display_name','like',request()->query('keyword').'%')
                            ->orWhere('nickname','like',request()->query('keyword').'%');
            }
            
            return $builder;
        } 
    }
?>