<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function agreement()
    {
        $userCourse = \App\Models\Course::where('title', 'like', '%' . (auth()->user()->focus == 'frontend' ? 'Front End' : (auth()->user()->focus == 'backend' ? 'Back End' : 'Full Stack Dev')) . '%')->first();
        if (!$userCourse) {
            $userCourse = \App\Models\Course::first();
        }

        return view('exam.agreement', compact('userCourse'));
    }

    public function room()
    {
        $userCourse = \App\Models\Course::where('title', 'like', '%' . (auth()->user()->focus == 'frontend' ? 'Front End' : (auth()->user()->focus == 'backend' ? 'Back End' : 'Full Stack Dev')) . '%')->first();
        if (!$userCourse) {
            $userCourse = \App\Models\Course::first();
        }

        return view('exam.room', compact('userCourse'));
    }
}
