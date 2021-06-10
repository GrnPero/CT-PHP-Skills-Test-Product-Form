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
        // The Async/Await code was from 2 online resources: https://stackoverflow.com/questions/50623279/js-event-handler-async-function/50623441 
        // and https://www.freecodecamp.org/news/how-to-use-fetch-api/
        productButton.addEventListener('click', async () => {   
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
            const response = await fetch('/addProduct', {
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
            .catch(function(error) {
                console.log('Error: ', error);
            });

            // Grabs the response from the controller
            const productInfo = await response.json(); 
            
            // Updates the total without reloading the page by grabbing the total from the response: https://www.w3schools.com/js/js_htmldom_html.asp
            // toFixed from: https://www.tutorialspoint.com/How-to-format-a-number-with-two-decimals-in-JavaScript
            totalTh.innerHTML = "$"+(productInfo.total).toFixed(2);

            // Adds the new product to the page without reloading: https://www.w3schools.com/js/js_htmldom_nodes.asp
            let tr = document.createElement("tr");
            let th = document.createElement("th");
            let name = document.createTextNode(productInfo[0].name);

            // Adds the rest of the columns
            let td1 = document.createElement("td");
            let td2 = document.createElement("td");
            let td3 = document.createElement("td");
            let td4 = document.createElement("td");


            // To format the price and subtotal with 2 decimal places
            let price = 0;            
            price = productInfo[0].price;
            price = price.toFixed(2);

            let total = 0;
            total = productInfo[0].total
            total = total.toFixed(2); 

            let quantity = document.createTextNode(productInfo[0].quantity)
            price = document.createTextNode("$"+price);
            let submitted = document.createTextNode(productInfo[0].submitted);
            total = document.createTextNode("$"+total);

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
        });
    </script>
</body>

</html>
