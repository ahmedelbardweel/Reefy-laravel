<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_market_listed', true)
                        ->with('user'); // Load seller info

        // Basic Filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();

        return view('market.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['user', 'reviews.user']);
        
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('market.show', compact('product', 'relatedProducts'));
    }
}
