<?php

namespace App\Http\Controllers;

use App\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $partner = Partner::all();
        return response()->json($partner);
    }

    public function show($id)
    {
        $partner = Partner::find($id);
        return response()->json($partner);
    }

    public function store(Request $request)
    {
        $partner = Partner::create($request->all());
        return response()->json($partner);
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::find($id);
        $partner->fill($request->all());
        $partner->save();

        return response()->json($partner);
    }

    public function destroy($id)
    {
        $partner = Partner::find($id);

        $partner->delete();
        return response()->json(['message' => 'Partner deleted']);
    }
}
