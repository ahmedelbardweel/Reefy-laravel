<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('user');
        
        if ($request->has('category')) {
             $query->where('category', $request->category);
        }
        
        if ($request->has('search')) {
             $query->where('name', 'like', '%' . $request->search . '%')
                   ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->latest()->paginate(20);
        
        return ProductResource::collection($products);
    }
    
    public function show(Product $product)
    {
        return new ProductResource($product->load('user'));
    }
}
