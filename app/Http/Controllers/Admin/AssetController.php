<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('condition', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        // Allow only valid sort directions
        $sortDir = in_array(strtolower($sortDir), ['asc', 'desc']) ? $sortDir : 'desc';

        // Allow only valid columns to avoid SQL injection
        $allowedColumns = [
            'asset_code',
            'name',
            'serial_number',
            'purchased_price',
            'purchased_date',
            'condition',
            'warranty_expiry',
            'maintenance_date',
            'created_at'
        ];

        if (in_array($sortBy, $allowedColumns)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($request->wantsJson()) {
            return response()->json($query->get());
        }

        // Paginate assets with query string preservation
        $assets = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.assets.table', compact('assets'));
        }

        return view('admin.assets.index', compact('assets'));
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
