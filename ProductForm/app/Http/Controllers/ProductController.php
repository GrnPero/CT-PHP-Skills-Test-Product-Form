<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// To use the Product Model
use App\Models\Product;

class ProductController extends Controller
{
    // Grabs all the products from the database
    public function index() {
        // Creates an array of products from the Product model
        $products = Product::all();

        // Sends the products array to the main page
        return view('index')->with('products', $products);
    }

    // Adds the product to the database
    public function addProduct(Request $request) {
        // Grabs the input of the form
        $name = $request->input('name');
        $quantity = $request->input('quantity');
        $price = $request->input('price');

        // Inserts the product in the database
        Product::insert([
            'name' => $name,
            'quantity' => $quantity,
            'price' => $price,
            'submitted' => now(),
            'total' => ($quantity * $price)
        ]); 
    }

    // Updates the product to the database
    public function updateProduct(Request $request, $id) {

    }

    // Deletes the product from the database
    public function deleteProduct($id) {

    }
}
