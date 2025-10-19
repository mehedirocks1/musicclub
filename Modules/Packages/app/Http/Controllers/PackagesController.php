<?php

namespace Modules\Packages\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
    {
        $packages = Package::where('status','active')
            ->where('visibility','public')
            ->orderBy('sort_order')->paginate(12);

        return view('packages::index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('packages::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
public function show(string $slug)
    {
        $package = Package::where('slug',$slug)
            ->where('status','active')->firstOrFail();

        return view('packages::show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('packages::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
