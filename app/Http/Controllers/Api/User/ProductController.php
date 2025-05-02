<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    public function GetProducts(Request $request)
    {
        $getProducts = Product::all();
        return response()->json([
            'message' => 'Products retrieved successfully',
            'products' => $getProducts,
        ], 200);
    }
}
