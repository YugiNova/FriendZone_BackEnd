<?php 
    namespace App\Filters;

    class Paginate{
        public function handle($request, \Closure $next)
        {
            
            $builder = $next($request);
            return $builder->orderBy('id','desc')->paginate(1);
        } 
    }
?>