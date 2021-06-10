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

    <!-- Button CSS from Bootstrap documentation: https://getbootstrap.com/docs/4.0/components/forms/ --!>
    <form action="addProduct" method="POST" id="product-form" name="product-form">
        @csrf
        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Quantity In Stock</label>
        <input type="number" name="quantity" required>

        <label>Price Per Item</label>
        <input type="number" name="price" step="any" required>

        <button type="submit" class="btn btn-primary" id="product-button">Submit</button>
    </form>
    <br>

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
        <tbody id="product-list">
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
                <th id="total">${{ number_format($total, 2) }}</th>
            </tr>
        </tbody>
    </table>

    <script>
        // Grabs the 'product-button' 
        let productButton = document.getElementById('product-button');

        // Grabs the form
        let productForm = document.getElementById('product-form');

        // Grabs the current total
        let totalTh = document.getElementById('total');

        // Adds new product after being added
        let productTbody = document.getElementById('product-list');

        // Adds an event listener so we can submit the form without reloading the page using fetch post api
        productButton.addEventListener('click', function() {   
            // Prevents the page from reloading: https://stackoverflow.com/questions/19454310/stop-form-refreshing-page-on-submit
            event.preventDefault();
            
            // Grabs the input from the form with Form Data from https://developer.mozilla.org/en-US/docs/Web/API/FormData
            let formData = new FormData(productForm);

            // This method was a mix of an answer from stack overflow: https://stackoverflow.com/questions/41431322/how-to-convert-formdata-html5-object-to-json 
            // and documentation from MDN: https://developer.mozilla.org/en-US/docs/Web/API/FormData/entries
            // Initializes the data variable
            let productData = {};

            // Then we populate the formData object with the productData
            for (var pair of formData.entries()) {
                productData[pair[0]] = pair[1];        
            }

            // Fetches the addProduct function and populates the request from the form
            fetch('/addProduct', {
                method: 'POST',
                credentials: "same-origin",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'url': '/addProduct',
                    // Grabs the csrf token to prevent mismatch, error: https://stackoverflow.com/questions/65014796/how-do-i-use-javascript-fetch-in-laravel-8
                    "X-CSRF-Token": document.querySelector('input[name=_token]').value
                },
                body: JSON.stringify(productData)
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(productInfo) {
                // Updates the total without reloading the page by grabbing the total from the response: https://www.w3schools.com/js/js_htmldom_html.asp
                totalTh.innerHTML = productInfo.total;
                // Adds the new product to the page without reloading: https://www.w3schools.com/js/js_htmldom_nodes.asp
                let tr = document.createElement("tr");
                let th = document.createElement("th");
                let name = document.createTextNode(productInfo[0].name);

                // Adds the rest of the columns
                let td1 = document.createElement("td");
                let td2 = document.createElement("td");
                let td3 = document.createElement("td");
                let td4 = document.createElement("td");

                let quantity = document.createTextNode(productInfo[0].quantity)
                let price = document.createTextNode(productInfo[0].price);
                let submitted = document.createTextNode(productInfo[0].submitted);
                let total = document.createTextNode(productInfo[0].total);

                // Appends the elements within eachother
                td1.appendChild(quantity); 
                td2.appendChild(price);
                td3.appendChild(submitted);
                td4.appendChild(total);

                th.appendChild(name);

                // Adds the element to the DOM
                tr.appendChild(th);
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
            
                // Adds the element to the page
                productTbody.appendChild(tr);
            })
            .catch(function(error) {
                console.log('Error: ', error);
            });
        });
    </script>
</body>

</html>
