<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class UserBlock
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is blocked using the UserRepository
        if(!resolve(UserRepository::class)->isBlocked()) {
            return $next($request);
            // not blocked, proceed with the request
        }else {
            // If the user is not blocked, return a JSON response indicating the block
            return response(['message' => 'You are blocked from performing this action.'], HttpFoundationResponse::HTTP_FORBIDDEN);
        }
    }
}
