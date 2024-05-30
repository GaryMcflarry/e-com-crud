<?php

//CORS error handling
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

//Obtaining the API request method
$requestMethod = $_SERVER["REQUEST_METHOD"];
//Obraining the current url path for the HTTP request
$Path = $_SERVER["PATH_INFO"];

//Bringing in the function php file
include('../back-end/function.php');

try {

    //Allowing for two API requests to occur
    if ($requestMethod == 'OPTIONS') {
        header("HTTP/1.1 200 Ok");
        die();
    }
    //If it is this path
    if ($Path == '/login') {

        //Ensuring correct method
        if ($requestMethod === 'POST') {
            loginHud();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/user/signup') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            signUpHud();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/user/remove') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            removeUser();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if (preg_match('/^\/products\/branch\/(\d+)$/', $Path, $matches)) {
        //Ensuring correct method
        if ($requestMethod === 'GET') {
            // Extract branch ID from the URL
            $branchId = $matches[1];

            productsByBranch($branchId);
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/edit/product') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            editProduct();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/edit/user') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            editUser();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if (preg_match('/^\/orders\/branch\/(\d+)$/', $Path, $matches)) {
        //Ensuring correct method
        if ($requestMethod === 'GET') {
            // Extract branch ID from the URL
            $branchId = $matches[1];

            ordersByBranch($branchId);
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if (preg_match('/^\/users\/branch\/(\d+)$/', $Path, $matches)) {
        //Ensuring correct method
        if ($requestMethod === 'GET') {
            // Extract branch ID from the URL
            $branchId = $matches[1];

            usersByBranch($branchId);
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/branch') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            getBranch();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/product') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            getProduct();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/user') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            getProfile();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/wishlist/products') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            wishProducts();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/add/wish') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            addwish();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/remove/wish') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            removeWish();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/search/products') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            searchProducts();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/add/cart') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            addToCart();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/user/cart') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            getUserCartByID();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/user/order') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            publishOrder();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/remove/order') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            removeOrder();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/remove/cart') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            removeCart();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
        //If it is this path
    } else if ($Path == '/remove/cartItem') {
        //Ensuring correct method
        if ($requestMethod === 'POST') {
            removeFromCart();
        } else {
            throw new Exception("Method Not Allowed", 405);
        }
    } else {
        throw new Exception("Not Found", 404);
    }
    //Catch for all exceptions
} catch (Exception $e) {
    $status = $e->getCode() ?: 500;
    $message = $e->getMessage() ?: 'Internal Server Error';

    header("HTTP/1.1 $status");
    echo json_encode(['status' => $status, 'message' => $message]);
}

function loginHud()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo login($data);
    } else {
        throw new Exception("Invalid JSON data provided", 400);
    }
}

function signUpHud()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo signUp($data);
    } else {
        throw new Exception("Invalid JSON data provided", 400);
    }
}

function removeUser()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo removeUserItem($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function productsByBranch($data)
{

    if (!empty($data)) {
        echo getDisplayProductsByBranch($data);
    } else {
        throw new Exception("Invalid JSON data provided", 400);
    }
}

function usersByBranch($data)
{

    if (!empty($data)) {
        echo getDisplayUsersByBranch($data);
    } else {
        throw new Exception("Invalid JSON data provided", 400);
    }
}

function ordersByBranch($data)
{

    if (!empty($data)) {
        echo getDisplayOrdersByBranch($data);
    } else {
        throw new Exception("Invalid JSON data provided", 400);
    }
}

function getBranch()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo getBranchInfo($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function getProduct()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo getProductInfo($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function getProfile()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo getProfileInfo($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function editProduct()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo editProductItem($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function editUser()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo editUserItem($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function wishProducts()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo wishProductIDs($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function addWish()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo addWishItem($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function removeWish()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo removeWishItem($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function searchProducts()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo searchProductsByInput($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function addToCart()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo addCartItem($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function removeFromCart()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo removeCartItem($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function getUserCartByID()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo getUserCart($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function publishOrder()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo insertOrders($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function removeOrder()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo removeOrderItem($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}

function removeCart()
{
    //Gathering HTTP payload
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);

    if ($data !== null) {
        echo deleteUserCart($data);
    } else {

        throw new Exception("Invalid JSON data provided", 400);
    }
}
