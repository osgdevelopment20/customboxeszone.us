<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductVariationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductVariationDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.product-variations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('product_name')->get();
        return view('backend.pages.product-variations.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variations' => 'required|array|min:1',
            'variations.*.title' => 'required|string|max:255',
            'variations.*.specifications' => 'nullable|string',
            'variations.*.status' => 'nullable|boolean',
            'variations.*.sort_order' => 'nullable|integer',
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $successCount = 0;

        // Use database transaction for data integrity
        \DB::transaction(function () use ($request, &$successCount) {
            foreach ($request->variations as $variationData) {
                $variation = ProductVariation::create([
                    'product_id' => $request->product_id,
                    'title' => $variationData['title'],
                    'specifications' => $variationData['specifications'] ?? '',
                    'status' => isset($variationData['status']) && $variationData['status'] ? 1 : 0,
                    'sort_order' => $variationData['sort_order'] ?? 0,
                ]);

                // Handle image upload
                if (isset($variationData['image']) && $variationData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variation->addMedia($variationData['image'])->toMediaCollection('variation_image');
                }

                $successCount++;
            }
        });

        return redirect()->route('dashboard.variation.index')->with('success', $successCount . ' Product Variation(s) added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $products = Product::orderBy('product_name')->get();

        // Load all variations for this product
        $product->load('variations');

        return view('backend.pages.product-variations.edit', compact('product', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variations' => 'required|array|min:1',
            'variations.*.id' => 'nullable|exists:product_variations,id',
            'variations.*.title' => 'required|string|max:255',
            'variations.*.specifications' => 'nullable|string',
            'variations.*.status' => 'nullable|boolean',
            'variations.*.sort_order' => 'nullable|integer',
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $updatedCount = 0;
        $createdCount = 0;

        // Use database transaction for data integrity
        \DB::transaction(function () use ($request, &$updatedCount, &$createdCount) {
            foreach ($request->variations as $variationData) {
                // Prepare variation data
                $data = [
                    'product_id' => $request->product_id,
                    'title' => $variationData['title'],
                    'specifications' => $variationData['specifications'] ?? '',
                    'status' => isset($variationData['status']) && $variationData['status'] ? 1 : 0,
                    'sort_order' => $variationData['sort_order'] ?? 0,
                ];

                if (!empty($variationData['id'])) {
                    // Update existing variation
                    $var = ProductVariation::findOrFail($variationData['id']);
                    $var->update($data);
                    $updatedCount++;
                } else {
                    // Create new variation
                    $var = ProductVariation::create($data);
                    $createdCount++;
                }

                // Handle image upload
                if (isset($variationData['image']) && $variationData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $var->clearMediaCollection('variation_image');
                    $var->addMedia($variationData['image'])->toMediaCollection('variation_image');
                }
            }
        });

        $successCount = $updatedCount + $createdCount;
        $message = "Successfully processed {$successCount} variation(s)";

        if ($updatedCount > 0 && $createdCount > 0) {
            $message .= " ({$updatedCount} updated, {$createdCount} created)";
        } elseif ($updatedCount > 0) {
            $message .= " ({$updatedCount} updated)";
        } elseif ($createdCount > 0) {
            $message .= " ({$createdCount} created)";
        }

        return redirect()->route('dashboard.variation.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(ProductVariation $variation)
    {
        $variation->clearMediaCollection('variation_image');
        $variation->delete();

        // Check if it's an AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product Variation deleted successfully'
            ]);
        }

        return back()->with('success', 'Product Variation deleted successfully');
    }
}
