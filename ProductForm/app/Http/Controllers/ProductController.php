<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// https://laravel.com/docs/8.x/filesystem#storing-files
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Grabs all the products from the database
    public function index() {
        // If file does not exist
        if (Storage::disk('local')->missing('products.json')) {
            $total = 0;
            return view('index')->with('total', $total);
        }

        // Creates an array of products from the JSON file
        $products = Storage::get('products.json');
    
        
        // Makes the json string readable for Laravel, I learned this from this article: https://hdtuto.com/article/how-to-read-and-write-json-file-in-php-laravel
        $products = json_decode($products); 
    
        // To save the total of all products
        $total = 0;

        // Adds the total from the JSON array
        foreach($products as $product) {
            $total += $product->total;
        }

        // Sends the products array to the main page
        return view('index')
        ->with('products', $products)->with('total', $total);
    }

    // Adds the product to the database
    public function addProduct(Request $request) { 
        if (Storage::disk('local')->missing('products.json')) {
            // Grabs all the variables from the form
            $name = $request->input('name');
            // Initializes it so it can be a numeric value
            $quantity = 0;
            $price = 0;
   
            // Make sure the variable is a number value 
            $quantity += $request->input('quantity');
            $price += $request->input('price');

            $products = array(
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'submitted' => now(),
                'total' => ($price * $quantity)
            );
            $products = json_encode(array($products)); 

        
            // I learned how to save json files from laravel's documentation https://laravel.com/docs/8.x/filesystem#storing-files
            Storage::put('products.json', $products);
        } else {
            // Creates an array of products from the JSON file
            $products = Storage::get('products.json');           
            $products = json_decode($products);

             // Grabs all the variables from the form
            $name = $request->input('name');           
            $quantity = 0;
            $price = 0;

            // Make sure the variable is a number value 
            $quantity += $request->input('quantity');
            $price += $request->input('price');

            $product = array(
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'submitted' => now(),
                'total' => ($price * $quantity)
            );
            
            array_push($products, $product);
            
            $products = json_encode($products);
            Storage::put('products.json', $products);
        }
        
        return back();
    }

    // Updates the product to the database
    public function updateProduct(Request $request, $id) {

    }

    // Deletes the product from the database
    public function deleteProduct($id) {

    }
}
