<?php

namespace Maksatsaparbekov\KuleshovAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResolveModelMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $modelName = ucfirst($request->route('model'));
        $modelNamespace = "App\\Models\\" . $modelName;

        if (class_exists($modelNamespace)) {
            $modelId = $request->route('id');
            $modelInstance = app($modelNamespace)::find($modelId);

            if ($modelInstance) {
                $request->merge(['modelInstance' => $modelInstance]);
            } else {
                return response()->json(['error' => 'Model not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid model name'], 404);
        }

        return $next($request);
    }
}
