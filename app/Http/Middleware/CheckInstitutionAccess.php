<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstitutionAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // If user is an institution owner, they can access their own data
        if ($user->role === 'org_owner') {
            return $next($request);
        }
        
        // If user has an institution_id, check if they belong to the same institution
        if ($user->institution_id) {
            // For now, allow access if user has institution_id
            // You can add more specific checks here if needed
            return $next($request);
        }
        
        // If user doesn't have institution_id and is not org_owner, deny access
        abort(403, 'غير مصرح لك بالوصول لهذه البيانات');
    }
} 