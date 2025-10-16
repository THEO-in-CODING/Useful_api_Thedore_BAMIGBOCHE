<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\Module;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleActive {
    public function handle(Request $request, Closure $next): Response {
        $user = auth()->user();
        $moduleId = $request->route('id');
        $module = Module::find($moduleId);
        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }
        $isActive = $user->modules()->where('module_id', $moduleId)->where('active', true)->exists();
        if (!$isActive) {
            return response()->json(['error' => 'Module inactive. Please activate this module to use it.'], 403);
        }
        return $next($request);
    }
}
