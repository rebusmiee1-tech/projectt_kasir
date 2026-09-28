<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::all();

    return $products;
}
    public function store(Request $request)
{
    $product = Product::create([
        'nama' => $request->nama,
        'harga' => $request->harga,
        'stok' => $request->stok,
    ]);

    return $product;
}
}
