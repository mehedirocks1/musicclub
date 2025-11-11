<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch; // ✅ import the Branch model
use App\Models\Gallery; 
class FrontendController extends Controller
{
    public function branches()
    {
        $branches = Branch::all();
        // Use the existing Blade file
        return view('frontend.branch', compact('branches'));
    }

    // Show single branch using route model binding
    public function branchShow(Branch $branch)
    {
        return view('frontend.branches-show', compact('branch'));
    }

      public function gallery()
    {
        $galleries = Gallery::all(); // Fetch all gallery items
        return view('frontend.gallery', compact('galleries'));
    }
}
