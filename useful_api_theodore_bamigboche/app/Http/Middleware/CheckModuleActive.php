<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleActive
{
    public function handle(Request $request, Closure $next, $moduleId)
    {
        $user = auth()->user();
        $module = Module::find($moduleId);

        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        $userModule = UserModule::where('user_id', $user->id)
                                ->where('module_id', $moduleId)
                                ->first();

        if (!$userModule || !$userModule->active) {
            return response()->json(['error' => 'Module inactive. Please activate this module to use it.'], 403);
        }

        return $next($request);
    }
}
