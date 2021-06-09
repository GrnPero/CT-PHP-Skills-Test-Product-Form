<!DOCTYPE html>

<html lang="en">

<head>
    <title>Product Form</title>

    <!-- Scripts and CSS lines from https://www.itsolutionstuff.com/post/laravel-8-install-bootstrap-example-tutorialexample.html --!>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> 
</head>

<body>
    <h1>Product Form</h1>

    <form action="addProduct" method="POST">
        @csrf
        <label>Product Name</label>
        <input type="text" name="name">

        <label>Quantity In Stock</label>
        <input type="number" name="quantity">

        <label>Price Per Item</label>
        <input type="number" name="price" step="any">

        <input type="submit" value="Submit">
    </form>

    <!-- For the column design: https://getbootstrap.com/docs/4.0/content/tables/#striped-rows --!>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Date Time Submitted</th>
                <th scope="col">Total Value Number</th>
            </tr>
        </thead>
        <tbody> 
        <!-- From Laravel docs if the variable is null: https://laravel.com/docs/8.x/blade#if-statements --!>
        @isset($products)
        @foreach($products as $product)
            <tr>
                <th>{{ $product->name }}</th>
                <td>{{ $product->quantity }}</td>
                <!-- number_format from https://www.php.net/manual/en/function.number-format.php --!>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->submitted }}</td>
                <td>${{ number_format($product->total, 2) }} </td>
            </tr>
        @endforeach
        @endisset
        </tbody>
    </table>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scoped="col">Sum Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>${{ number_format($total, 2) }}</th>
            </tr>
        </tbody>
    </table>
</body>

</html>
