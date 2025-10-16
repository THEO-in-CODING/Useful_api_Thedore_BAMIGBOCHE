<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Module;
use Symfony\Component\HttpFoundation\Response;

class ModuleController extends Controller {
    public function index(): Response {
        $modules = Module::all();
        return response()->json($modules, 200);
    }
    public function activate(Request $request, int $id): Response {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }
        $user = auth()->user();
        $user->modules()->syncWithoutDetaching([$id => ['active' => true]]);
        return response()->json(['message' => 'Module activated'], 200);
    }
    public function deactivate(Request $request, int $id): Response {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }
        $user = auth()->user();
        $user->modules()->syncWithoutDetaching([$id => ['active' => false]]);
        return response()->json(['message' => 'Module deactivated'], 200);
    }
}
