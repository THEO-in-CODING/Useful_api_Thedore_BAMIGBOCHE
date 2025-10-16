<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::all();
        return response()->json($modules, 200);
    }

    public function activate(Request $request, $id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        $userModule = UserModule::firstOrCreate(
            ['user_id' => auth()->id(), 'module_id' => $id],
            ['active' => true]
        );

        if (!$userModule->active) {
            $userModule->active = true;
            $userModule->save();
        }

        return response()->json(['message' => 'Module activated'], 200);
    }

    public function deactivate(Request $request, $id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['error' => 'Module not found'], 404);
        }

        $userModule = UserModule::where('user_id', auth()->id())
                                ->where('module_id', $id)
                                ->first();

        if ($userModule) {
            $userModule->active = false;
            $userModule->save();
        }

        return response()->json(['message' => 'Module deactivated'], 200);
    }
}
