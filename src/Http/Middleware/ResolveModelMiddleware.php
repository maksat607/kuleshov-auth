<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResolveModelMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->route('model') == '') {
            return $next($request);
        }

        $modelName = ucfirst($request->route('model'));
        $modelNamespace = "App\\Models\\" . $modelName;

        if (!class_exists($modelNamespace)) {
            Log::error('Model class does not exist', ['modelNamespace' => $modelNamespace]);
            return response()->json(['error' => 'Invalid model name'], 404);
        }

        $modelId = (int)($request->route('modelId'));

        if ($modelId != null) {
            $modelInstance = app($modelNamespace)::find($modelId);

            if (!$modelInstance) {
                Log::error('Model instance not found', ['model' => $modelName, 'id' => $modelId]);
                return response()->json(['error' => 'Model not found'], 404);
            }

            $request->merge(['modelInstance' => $modelInstance]);

        }
        $request->merge(['modelName' => strtolower($modelName)]);
        $request->merge(['modelNamespace' => $modelNamespace]);
        return $next($request);
    }

}
