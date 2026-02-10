<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return response()->json($plans);
    }

    public function show($id)
    {
        $plan = Plan::findOrFail($id);
        return response()->json($plan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'required|string|max:50',
            'validity_period' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $plan = Plan::create($request->all());

        return response()->json([
            'message' => 'Plan created successfully',
            'plan' => $plan
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'speed' => 'sometimes|string|max:50',
            'validity_period' => 'sometimes|integer|min:1',
            'price' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string'
        ]);

        $plan->update($request->all());

        return response()->json([
            'message' => 'Plan updated successfully',
            'plan' => $plan
        ]);
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->json([
            'message' => 'Plan deleted successfully'
        ]);
    }
}