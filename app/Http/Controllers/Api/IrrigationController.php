<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Irrigation;
use App\Http\Resources\IrrigationResource;
use Illuminate\Http\Request;

class IrrigationController extends Controller
{
    public function index(Request $request)
    {
        // Get irrigations for user's crops
        $irrigations = Irrigation::whereHas('crop', function($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('crop')->latest()->paginate(20);
        
        return IrrigationResource::collection($irrigations);
    }
}
