<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $banners = \App\Models\Banner::where('is_active', true)->orderBy('order_index')->get();
        $plos = \App\Models\Plo::where('is_active', true)->orderBy('order_index')->get();
        
        $plosBachelor = $plos->where('degree_level', 'bachelor')->values();
        $plosMaster = $plos->where('degree_level', 'master')->values();
        $plosDoctoral = $plos->where('degree_level', 'doctoral')->values();
        
        // Calculate Assessment Stats using new models
        $totalUsers = \App\Models\AssessmentResponse::count();
        $allAnswers = \App\Models\AssessmentAnswer::all();
        $overallScore = 0;
        
        if ($totalUsers > 0 && $allAnswers->count() > 0) {
            $overallScore = round($allAnswers->avg('score'), 1);
        }

        return view('home.index', compact('banners', 'plosBachelor', 'plosMaster', 'plosDoctoral', 'plos', 'totalUsers', 'overallScore'));
    }
}
