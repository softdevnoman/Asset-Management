<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Models\Asset;

class AssetController extends Controller
{
    public function index()
    {
        return view('assets.index');
    }

    public function store(AssetRequest $request)
    {
        Asset::create($request->validated());

        return response()->json(['message' => 'Asset created successfully.'], 201);
    }

    public function update(AssetRequest $request, Asset $asset)
    {
        $asset->update($request->validated());

        return response()->json(['message' => 'Asset updated successfully.']);
    }
}
