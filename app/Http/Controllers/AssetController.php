<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(Asset::orderBy('created_at', 'desc')->get());
        }
        return view('assets.index');
    }

    public function store(AssetRequest $request)
    {
        try {
            $data = $request->validated();
            $data['purchased_price'] = $data['purchase_price'] ?? null;
            $data['purchased_date'] = $data['purchase_date'] ?? null;
            unset($data['purchase_price'], $data['purchase_date']);

            $asset = Asset::create($data);

            return response()->json([
                'message' => 'Asset created successfully.',
                'asset' => $asset
            ], 201);
        } catch (\Exception $e) {
            Log::error('Store asset error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Asset $asset)
    {
        return response()->json($asset);
    }

    public function update(AssetRequest $request, Asset $asset)
    {
        try {
            $data = $request->validated();
            $data['purchased_price'] = $data['purchase_price'] ?? null;
            $data['purchased_date'] = $data['purchase_date'] ?? null;
            unset($data['purchase_price'], $data['purchase_date']);

            $asset->update($data);

            return response()->json([
                'message' => 'Asset updated successfully.',
                'asset' => $asset
            ]);
        } catch (\Exception $e) {
            Log::error('Update asset error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return response()->json([
            'message' => 'Asset deleted successfully.'
        ]);
    }
}
