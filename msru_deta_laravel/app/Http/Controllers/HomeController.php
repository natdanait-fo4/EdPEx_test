<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $banners = \App\Models\Banner::where('is_active', true)->orderBy('order_index')->get();
        
        // Calculate Assessment Stats using new models
        $totalUsers = \App\Models\AssessmentResponse::count();
        $allAnswers = \App\Models\AssessmentAnswer::all();
        $overallScore = 0;
        
        if ($totalUsers > 0 && $allAnswers->count() > 0) {
            $overallScore = round($allAnswers->avg('score'), 1);
        }

        return view('home.index', compact('banners', 'totalUsers', 'overallScore'));
    }
}
