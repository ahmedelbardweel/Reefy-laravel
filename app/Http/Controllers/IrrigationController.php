<?php

namespace App\Http\Controllers;

use App\Models\Irrigation;
use Illuminate\Http\Request;

class IrrigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $irrigations = Irrigation::with('crop')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        $totalToday = Irrigation::where('user_id', auth()->id())
            ->whereDate('date', now())
            ->sum('amount_liters');

        return view('irrigations.index', compact('irrigations', 'totalToday'));
    }
}
