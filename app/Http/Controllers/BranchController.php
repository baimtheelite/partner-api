<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $branch = Branch::all();
        return response()->json($branch);
    }

    public function show($id)
    {
        $branch = Branch::find($id);
        return response()->json($branch);
    }

    public function store(Request $request)
    {
        $branch = Branch::create($request->all());
        return response()->json($branch);
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);
        $branch->fill($request->all());
        $branch->save();

        return response()->json($branch);
    }

    public function destroy($id)
    {
        $branch = Branch::find($id);

        $branch->delete();
        return response()->json(['message' => 'Branch deleted']);
    }
}
