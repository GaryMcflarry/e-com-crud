<?php

//Obtaining php file for SQL connection
require '../back-end/PHP-API/inc/dbcon.php';

//Obtaining imports for the JWT token dependency
require '../back-end/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;



try {
    // function getDisplay()
    // {

    //     global $conn;

    //     $userquery = "SELECT  users.*, wishlist.wishID, wishlist.productID FROM users LEFT JOIN wishlist ON users.userID = wishlist.userID";
    //     $branchquery = "SELECT branches.*, products.productID, products.name, products.rating, products.amountRated, products.category, products.cutType, products.quantity, products.price, products.image, orders.orderID, orders.userID, orders.orderProductID, orders.orderDate, orders.totalAmount FROM branches LEFT JOIN products ON branches.branchID = products.branchID LEFT JOIN orders ON branches.branchID = orders.branchID AND products.productID = orders.orderProductID";

    //     $userQuery_run = mysqli_query($conn, $userquery);
    //     $branchQuery_run = mysqli_query($conn, $branchquery);

    //     if ($userQuery_run && $branchQuery_run) {

    //         if (mysqli_num_rows($userQuery_run) > 0 && mysqli_num_rows($branchQuery_run) > 0) {

    //             $userRes = [];
    //             while ($row = mysqli_fetch_assoc($userQuery_run)) {
    //                 $userID = $row['userID'];
    //                 if (!isset($userRes[$userID])) {
    //                     // $rowArray = implode($row);
    //                     // echo "Not user Empty $userID  \n";
    //                     // echo "\n";
    //                     // echo "\n";
    //                     // echo "$rowArray \n \n";

    //                     //Initialize user data
    //                     $userData = [
    //                         'userID' => $row['userID'],
    //                         'username' => $row['username'],
    //                         'password' => $row['password'],
    //                         'contact' => $row['contact'],
    //                         'admin' => $row['admin'],
    //                         'chosenBranch' => $row['chosenBranch'],
    //                         'icon' => $row['icon'],
    //                         'wishlist' => []
    //                     ];
    //                     $userRes[$userID] = $userData;
    //                 }
    //                 //add wishlist item to the user's wishlist
    //                 if (!empty($row['wishID'])) {
    //                     // echo "Not wish Empty \n";
    //                     $wishID = $row['wishID'];
    //                     $wishList = [
    //                         'wishID' => $row['wishID'],
    //                         'userID' => $row['userID'],
    //                         'productID' => $row['productID']
    //                     ];
    //                     $userRes[$userID]['wishlist'][$wishID] = $wishList;
    //                 }
    //             }

    //             $branchRes = array();
    //             while ($row = mysqli_fetch_assoc($branchQuery_run)) {
    //                 // $rowArray = implode($row);
    //                 $branchID = $row['branchID'];
    //                 if (!isset($branchRes[$branchID])) {
    //                     // //Initialize user data
    //                     $branchData = [
    //                         'branchID' => $row['branchID'],
    //                         'branchName' => $row['branchName'],
    //                         'address' => $row['address'],
    //                         'contact' => $row['contact'],
    //                         'products' => [],
    //                         'orders' => [],
    //                     ];
    //                     $branchRes[$branchID] = $branchData;
    //                 }
    //                 if (!empty($row['productID'])) {

    //                     //echo "$rowArray \n \n";
    //                     $productID = $row['productID'];
    //                     if (!isset($branchRes[$branchID]['products'][$productID])) {
    //                         $productData = [
    //                             'productID' => $row['productID'],
    //                             'name' => $row['name'],
    //                             'rating' => $row['rating'],
    //                             'amountRated' => $row['amountRated'],
    //                             'category' => $row['category'],
    //                             'cutType' => $row['cutType'],
    //                             'quantity' => $row['quantity'],
    //                             'price' => $row['price'],
    //                             'image' => $row['image']
    //                         ];
    //                         $branchRes[$branchID]['products'][$productID][] = $productData;
    //                     }
    //                 }

    //                 if (!empty($row['orderID'])) {
    //                     $orderID = $row['orderID'];
    //                     if (!isset($branchRes[$branchID]['orders'][$orderID])) {
    //                         $orderData = [
    //                             'orderID' => $row['orderID'],
    //                             'userID' => $row['userID'],
    //                             'productID' => $row['orderProductID'],
    //                             'orderDate' => $row['orderDate'],
    //                             'totalAmount' => $row['totalAmount'],
    //                         ];
    //                         $branchRes[$branchID]['orders'][$orderID][] = $orderData;
    //                     }
    //                 }
    //             }

    //             $data = [
    //                 'status' => 200,
    //                 'message' => 'DB Fetched Successfully',
    //                 'Users' => $userRes,
    //                 'Branches' => $branchRes
    //             ];
    //             header("HTTP/1.1 200 OK");
    //             return json_encode($data);
    //         } else {
    //             $data = [
    //                 'status' => 404,
    //                 'message' => 'No DB INFO Found',
    //             ];
    //             header("HTTP/1.1 404 Not Found");
    //             return json_encode($data);
    //         }
    //     } else {
    //         $data = [
    //             'status' => 500,
    //             'message' => 'Internal Server Error',
    //         ];
    //         header("HTTP/1.1 500 Internal Server Error");
    //         return json_encode($data);
    //     }
    // }

    function getBranchInfo($ID)
    {

        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['branchID'];
        foreach ($requiredFields as $field) {
            if (!isset($ID[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL Injection
        $branchID = mysqli_real_escape_string($conn, $ID['branchID']);
        //SQL query
        $branchQuery = "SELECT branches.* FROM branches WHERE branches.branchID = '$branchID'";
        //Make connection
        $branchQuery_run = mysqli_query($conn, $branchQuery);

        if ($branchQuery) {

            if (mysqli_num_rows($branchQuery_run) > 0) {

                $branchRes = [];
                while ($row = mysqli_fetch_assoc($branchQuery_run)) {
                    $branchID = $row['branchID'];
                    if (!isset($branchRes[$branchID])) {
                        
                        // Object
                        $branchData = [
                            'branchID' => $row['branchID'],
                            'branchName' => $row['branchName'],
                            'address' => $row['address'],
                            'contact' => $row['contact']
                        ];
                        $branchRes = $branchData;
                    };
                };
                //response
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    "data" => $branchRes,
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            } else {
                throw new Exception('No BD INFO Found', 404);
            }
        } else {
            throw new Exception('Internal Server Error', 500);
        }
    }

    function getProductInfo($ID)
    {

        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['productID'];
        foreach ($requiredFields as $field) {
            if (!isset($ID[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection and query
        $productID = mysqli_real_escape_string($conn, $ID['productID']);
        $productQuery = "SELECT products.* FROM products WHERE products.productID = '$productID'";

        //Make connection
        $productQuery_run = mysqli_query($conn, $productQuery);

        if ($productQuery) {

            if (mysqli_num_rows($productQuery_run) > 0) {

                while ($row = mysqli_fetch_assoc($productQuery_run)) {
                    $productID = $row['productID'];
                    if (!isset($productRes[$productID])) {
                        // Object
                        $productData = [
                            'productID' => $row['productID'],
                            'name' => $row['name'],
                            'category' => $row['category'],
                            'quantity' => $row['quantity'],
                            'rating' => $row['rating'],
                            'price' => $row['price'],
                            'discount' => $row['discount'],
                            'image' => $row['image']
                        ];
                    };
                };
                //Response
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    "data" => $productData,
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            } else {
                throw new Exception('No BD INFO Found', 404);
            }
        } else {
            throw new Exception('Internal Server Error', 500);
        }
    }

    function getProfileInfo($ID)
    {

        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['userID'];
        foreach ($requiredFields as $field) {
            if (!isset($ID[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection and query
        $userID = mysqli_real_escape_string($conn, $ID['userID']);
        $userQuery = "SELECT users.* FROM users WHERE users.userID = '$userID'";

        //Make connection
        $userQuery_run = mysqli_query($conn, $userQuery);

        if ($userQuery_run) {

            if (mysqli_num_rows($userQuery_run) > 0) {

                while ($row = mysqli_fetch_assoc($userQuery_run)) {

                    //Object
                        $userData = [
                            'userID' => $row['userID'],
                            'username' => $row['username'],
                            'contact' => $row['contact']
                        ];
                };
                //Response
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    "data" => $userData,
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            } else {
                throw new Exception('No BD INFO Found', 404);
            }
        } else {
            throw new Exception('Internal Server Error', 500);
        }
    }

    function getDisplayProductsByBranch($branch)
    {
        global $conn;

        //SQL query
        $productQuery = "SELECT products.* FROM products WHERE products.branchID = '$branch'";
        //Make connection
        $productQuery_run = mysqli_query($conn, $productQuery);

        if ($productQuery_run) {
            if (mysqli_num_rows($productQuery_run) > 0) {
                $categoryRes = [];
                $productRes = [];
                while ($row = mysqli_fetch_assoc($productQuery_run)) {
                    $category = $row['category'];
                    // If the category array doesn't exist, create it
                    if (!isset($categoryRes[$category])) {
                        $categoryRes[$category] = [];
                    }

                    // Object
                    $productData = [
                        'productID' => $row['productID'],
                        'branchID' => $row['branchID'],
                        'name' => $row['name'],
                        'category' => $row['category'],
                        'quantity' => $row['quantity'],
                        'rating' => $row['rating'],
                        'price' => $row['price'],
                        'discount' => $row['discount'],
                        'image' => $row['image'],
                    ];
                    // Add product data to the category array
                    $categoryRes[$category][] = $productData;
                    $productRes[] = $productData;
                }
                //Response
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    'data' => $categoryRes,
                    'data2' => $productRes
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            } else {
                throw new Exception('No BD INFO Found', 404);
            }
        } else {
            throw new Exception('Internal Server Error', 500);
        }
    }

    function getDisplayUsersByBranch($branch)
    {
        global $conn;

        //SQL query
        $usersQuery = "SELECT users.* FROM users WHERE users.chosenBranch = '$branch'";
        //Make connection
        $usersQuery_run = mysqli_query($conn, $usersQuery);

        if ($usersQuery_run) {
            if (mysqli_num_rows($usersQuery_run) > 0) {
                $usersRes = [];
                while ($row = mysqli_fetch_assoc($usersQuery_run)) {
                    // Object
                    $userData = [
                        'userID' => $row['userID'],
                        'username' => $row['username'],
                        'password' => $row['password'],
                        'contact' => $row['contact'],
                        'admin' => $row['admin'],
                        'chosenBranch' => $row['chosenBranch'],
                        
                    ];
                    // Add product data to the category array
                    $userRes[] = $userData;
                }
                //Response
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    'data' => $userRes,
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            } else {
                throw new Exception('No BD INFO Found', 404);
            }
        } else {
            throw new Exception('Internal Server Error', 500);
        }
    }

    function getDisplayOrdersByBranch($branch)
    {
        global $conn;

        //SQL query
        $ordersQuery = "SELECT orders.* FROM orders WHERE orders.branchID = '$branch'";
        //Make connection
        $ordersQuery_run = mysqli_query($conn, $ordersQuery);

        if ($ordersQuery_run) {
            if (mysqli_num_rows($ordersQuery_run) > 0) {
                while ($row = mysqli_fetch_assoc($ordersQuery_run)) {
                    // Object
                    $orderData = [
                        'orderID' => $row['orderID'],
                        'branchID' => $row['branchID'],
                        'userID' => $row['userID'],
                        'productID' => $row['orderProductID'],
                        'orderDate' => $row['orderDate'],
                        'quantity' => $row['quantity'],
                    ];
                    // Add product data to the category array
                    $orderRes[] = $orderData;
                }
                //Response
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    'data' => $orderRes,
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            } else {
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    'data' => null,
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            }
        } else {
            throw new Exception('Internal Server Error', 500);
        }
    }

    function wishProductIDs($userID)
    {
        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['userID'];
        foreach ($requiredFields as $field) {
            if (!isset($userID[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }

        //SQL injection and query
        $userID = mysqli_real_escape_string($conn, $userID['userID']);
        $wishQuery = "SELECT wishlist.productID FROM wishlist WHERE wishlist.userID = '$userID'";
        //Make connection
        $wishQuery_run = mysqli_query($conn, $wishQuery);

        if ($wishQuery_run) {

            $wishRes = [];

            if (mysqli_num_rows($wishQuery_run) > 0) {

                $num = 0;
                while ($row = mysqli_fetch_assoc($wishQuery_run)) {
            
                        $wishData = [ 'productID' =>$row['productID']];
                        $wishRes[$num] = $wishData;
                        $num++;
                };
                // Response
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    "data" => $wishRes,
                    // var_dump($name) // Add this line for debugging
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            } else {

                $wishData = [ 'productID' => 0];
                $wishRes[0] = $wishData;
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    "data" => $wishRes
                    // var_dump($name) // Add this line for debugging
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data);
            }
        } else {
            throw new Exception('Internal server error', 500);
        }
    }

    function addWishItem($wish)
    {
        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['userID', 'productID'];
        foreach ($requiredFields as $field) {
            if (!isset($wish[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection and Query
        $userID = mysqli_real_escape_string($conn, $wish['userID']);
        $productID = mysqli_real_escape_string($conn, $wish['productID']);
        $wishQuery = "INSERT INTO wishlist (userID, productID) VALUES ('$userID', '$productID')";
        //Make connection
        $wishQuery_run = mysqli_query($conn, $wishQuery);

        if ($wishQuery_run) {
            //Response
            $data = [
                'status' => 200,
                'message' => 'Added to wishlist',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($data);
        } else {
            throw new Exception('Failed to add item to wishlist', 500);
        }
    }

    function removeWishItem($wish)
    {
        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['userID', 'productID'];
        foreach ($requiredFields as $field) {
            if (!isset($wish[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection and query
        $userID = mysqli_real_escape_string($conn, $wish['userID']);
        $productID = mysqli_real_escape_string($conn, $wish['productID']);
        $wishQuery = "DELETE FROM wishlist WHERE userID = '$userID' AND productID = '$productID'";
        //Make connection
        $wishQuery_run = mysqli_query($conn, $wishQuery);

        if ($wishQuery_run) {
            //Response
            $data = [
                'status' => 200,
                'message' => 'Item removed',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($data);
        } else {
            throw new Exception('Failed to remove item to wishlist', 500);
        }
    }

    function searchProductsByInput($search)
{
    global $conn;
    // Ensure all required fields are present in the data
    $requiredFields = ['query', 'branchID'];
    foreach ($requiredFields as $field) {
        if (!isset($search[$field])) {
            throw new Exception('Missing required field: ' . $field, 400);
        }
    }
    //SQL injection
    $query = mysqli_real_escape_string($conn, $search['query']);
    $branch = mysqli_real_escape_string($conn, $search['branchID']);
    
    // If the query is empty, return an empty array
    if (empty($query)) {
        $data = [
            'status' => 200,
            'message' => 'No query provided',
            'data' => []
        ];
        header("HTTP/1.1 200 OK");
        return json_encode($data);
    }
    //SQL query
    $searchQuery = "SELECT products.* FROM products WHERE products.branchID = '$branch' AND (products.category LIKE '%$query%' OR products.name LIKE '%$query%')";
    //Make connecton
    $searchQuery_run = mysqli_query($conn, $searchQuery);

    if ($searchQuery_run) {
        if (mysqli_num_rows($searchQuery_run) > 0) {
            $productRes = [];
            $num = 0;
            while ($row = mysqli_fetch_assoc($searchQuery_run)) {
                //Object
                $productData = [
                    'productID' => $row['productID'],
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'quantity' => $row['quantity'],
                    'rating' => $row['rating'],
                    'price' => $row['price'],
                    'discount' => $row['discount'],
                    'image' => $row['image']
                ];
                $productRes[$num] = $productData;
                $num++;
            }
            //Response
            $data = [
                'status' => 200,
                'message' => 'DB Fetched Successfully',
                "data" => $productRes,
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 200,
                'message' => 'No DB info found',
                'data' => []
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($data);
        }
    } else {
        throw new Exception('Internal Server Error', 500);
    }
    }

    function addCartItem($item)
    {
        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['userID', 'productID'];
        foreach ($requiredFields as $field) {
            if (!isset($item[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection and query
        $userID = mysqli_real_escape_string($conn, $item['userID']);
        $productID = mysqli_real_escape_string($conn, $item['productID']);
        $cartQuery = "INSERT INTO cart (userID, productID) VALUES ('$userID', '$productID')";
        //Make connection
        $cartQuery_run = mysqli_query($conn, $cartQuery);

        if ($cartQuery_run) {
            //Response
            $data = [
                'status' => 200,
                'message' => 'Added to cart',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($data);
        } else {
            throw new Exception('Failed to add item to wishlist', 500);
        }
    }

    function removeCartItem($cart)
    {
        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['userID', 'productID'];
        foreach ($requiredFields as $field) {
            if (!isset($cart[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection and query
        $userID = mysqli_real_escape_string($conn, $cart['userID']);
        $productID = mysqli_real_escape_string($conn, $cart['productID']);
        $wishQuery = "DELETE FROM cart WHERE cart.userID = '$userID' AND cart.productID = '$productID'";
        //Make connection
        $wishQuery_run = mysqli_query($conn, $wishQuery);

        if ($wishQuery_run) {
            //Response
            $data = [
                'status' => 200,
                'message' => 'Item removed',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($data);
        } else {
            throw new Exception('Failed to remove item to wishlist', 500);
        }
    }

    function getUserCart($user)
    {
        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['userID'];
        foreach ($requiredFields as $field) {
            if (!isset($user[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection and query
        $userID = mysqli_real_escape_string($conn, $user['userID']);
        $cartQuery = "SELECT DISTINCT cart.productID FROM cart WHERE cart.userID = '$userID'";
        //Make connection
        $cartQuery_run = mysqli_query($conn, $cartQuery);

        if ($cartQuery_run) {

            if (mysqli_num_rows($cartQuery_run) > 0) {

                $cartRes = [];
                $num = 0;
                while ($row = mysqli_fetch_assoc($cartQuery_run)) {

                        $cartData = [
                            'productID' => $row['productID'],
                        ];

                        $cartRes[$num] = $cartData;
                        $num++;
                    };
                };
                //Response
                $data = [
                    'status' => 200,
                    'message' => 'DB Fetched Successfully',
                    "data" => $cartRes,
                    // var_dump($name) // Add this line for debugging
                ];
                header("HTTP/1.1 200 OK");
                return json_encode($data); 
        } else {
            throw new Exception('Failed to add item to wishlist', 500);
        }
    }

    function insertOrders($order)
    {

        global $conn;

        // Ensure all required fields are present in the data
        $requiredFields = ['branchID', 'userID', 'orderProductID', 'orderDate', 'quantity'];
        foreach ($requiredFields as $field) {
            if (!isset($order[$field])) {
                $response = [
                    'status' => 400,
                    'message' => 'Missing required field: ' . $field,
                ];
                header("HTTP/1.1 400 Bad Request");
                return json_encode($response);
            }
        }
        // Sanitize input data to prevent SQL injection
        $branchID = mysqli_real_escape_string($conn, $order['branchID']);
        $userID = mysqli_real_escape_string($conn, $order['userID']);
        $orderProductID = mysqli_real_escape_string($conn, $order['orderProductID']);
        $orderDate = mysqli_real_escape_string($conn, $order['orderDate']);
        $quantity = mysqli_real_escape_string($conn, $order['quantity']);

        // Construct and execute the SQL INSERT query
        $query = "INSERT INTO orders (branchID, userID, orderProductID, orderDate, quantity) 
              VALUES ('$branchID', '$userID', '$orderProductID', '$orderDate', '$quantity')";

        if (mysqli_query($conn, $query)) {
            $response = [
                'status' => 200,
                'message' => 'Order added',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($response);
        } else {
            $response = [
                'status' => 500,
                'message' => 'Failed to add order: ' . mysqli_error($conn),
            ];
            header("HTTP/1.1 500 Internal Server Error");
            return json_encode($response);
        }
    }

    function removeOrderItem($ID)
    {
        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['orderID'];
        foreach ($requiredFields as $field) {
            if (!isset($ID[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection and query
        $orderID = mysqli_real_escape_string($conn, $ID['orderID']);
        $orderQuery = "DELETE FROM orders WHERE orderID = '$orderID'";
        //Make connection
        $orderQuery_run = mysqli_query($conn, $orderQuery);

        if ($orderQuery_run) {
            //Response
            $data = [
                'status' => 200,
                'message' => 'Order removed',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($data);
        } else {
            throw new Exception('Failed to remove order', 500);
        }
    }

    function removeUserItem($user)
    {

        global $conn;

        // Ensure userID is provided
        if (!isset($user['userID'])) {
            $response = [
                'status' => 400,
                'message' => 'userID is required to remove user',
            ];
            header("HTTP/1.1 400 Bad Request");
            return json_encode($response);
        }
        // Sanitize input data to prevent SQL injection
        $userID = mysqli_real_escape_string($conn, $user['userID']);
        // Construct and execute the SQL UPDATE query
        $query = "DELETE FROM users WHERE userID = '$userID'";

        if (mysqli_query($conn, $query)) {
            $response = [
                'status' => 200,
                'message' => 'User Removed',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($response);
        } else {
            $response = [
                'status' => 500,
                'message' => 'Failed to remove user: ' . mysqli_error($conn),
            ];
            header("HTTP/1.1 500 Internal Server Error");
            return json_encode($response);
        }
    }

    function deleteUserCart($user)
    {

        global $conn;

        // Ensure userID is provided
        if (!isset($user['userID'])) {
            $response = [
                'status' => 400,
                'message' => 'userID is required to remove user',
            ];
            header("HTTP/1.1 400 Bad Request");
            return json_encode($response);
        }
        // Sanitize input data to prevent SQL injection
        $userID = mysqli_real_escape_string($conn, $user['userID']);
        // Construct and execute the SQL UPDATE query
        $query = "DELETE cart.* FROM cart WHERE cart.userID = '$userID'";

        if (mysqli_query($conn, $query)) {
            $response = [
                'status' => 200,
                'message' => 'Purchase Success',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($response);
        } else {
            $response = [
                'status' => 500,
                'message' => 'Failed Purchase: ' . mysqli_error($conn),
            ];
            header("HTTP/1.1 500 Internal Server Error");
            return json_encode($response);
        }
    }

    function editProductItem($product)
    {
        global $conn;
        $message = '';

        // Ensure userID is provided
        if (!isset($product['productID'])) {
            $response = [
                'status' => 400,
                'message' => 'productID is required to edit product',
            ];
            header("HTTP/1.1 400 Bad Request");
            return json_encode($response);
        }

        // Determine which column needs to be updated
        $updateColumn = '';
        if (isset($product['rating'])) {
            $updateColumn = 'rating';
            $message = 'Rating was updated';
        } elseif (isset($product['quantity'])) {
            $updateColumn = 'quantity';
            $message = 'Quantity was updated';
        } elseif (isset($product['price'])) {
            $updateColumn = 'price';
            $message = 'Price was updated';
        } elseif (isset($product['discount'])) {
            $updateColumn = 'discount';
            $message = 'Discount was updated';
        } else {
        }

        // Sanitize input data to prevent SQL injection
        $productID = mysqli_real_escape_string($conn, $product['productID']);
        $newValue = mysqli_real_escape_string($conn, $product[$updateColumn]);

        // Construct and execute the SQL UPDATE query
        $query = "UPDATE products SET $updateColumn = '$newValue' WHERE productID = '$productID'";

        if (mysqli_query($conn, $query)) {
            $response = [
                'status' => 200,
                'message' => $message,
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($response);
        } else {
            throw new Exception('Failed to update product', 500);
        }
    }

    function editUserItem($user)
    {
        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['userID', 'username', 'password', 'contact'];
        foreach ($requiredFields as $field) {
            if (!isset($user[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        //SQL injection
        $userID = mysqli_real_escape_string($conn, $user['userID']);
        $username = mysqli_real_escape_string($conn, $user['username']);
        $password = mysqli_real_escape_string($conn, $user['password']);
        $contact = mysqli_real_escape_string($conn, $user['contact']);

         //encrypting password
        $encryptP = password_hash($password, PASSWORD_DEFAULT);
        //SQL quary
        $userQuery = "UPDATE users SET username = '$username', password = '$encryptP', contact = '$contact' WHERE userID = '$userID'";
        //Make connection
        $userQuery_run = mysqli_query($conn, $userQuery);

        if ($userQuery_run) {
            //response
            $data = [
                'status' => 200,
                'message' => 'Account Edited',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($data);
        } else {
            throw new Exception('Failed to edit account', 500);
        }
    }

    function login($login)
    {

        $sec_key = 'theChamberOfSecrets';

        global $conn;
        // Ensure all required fields are present in the data
        $requiredFields = ['username', 'password'];
        foreach ($requiredFields as $field) {
            if (!isset($login[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }
        // Looking for username
        $username = mysqli_real_escape_string($conn, $login['username']);
        $checkQuery = "SELECT * FROM users WHERE username = '$username'";
        $checkResult = mysqli_query($conn, $checkQuery);
        $row = mysqli_fetch_assoc($checkResult);
        //If username is found
        if ($row) {
            if (password_verify($login['password'], $row['password'])) {
                //Password is correct
                //Creating token
                $tokenIssued = time();
                $tokenExp = $tokenIssued + 60;

                $payload = array(
                    'userID' => $row['userID'],
                    'username' => $row['username'],
                    'branchID' => $row['chosenBranch'],
                    'admin' => $row['admin'],
                    'issued' => $tokenIssued,
                    'exp' => $tokenExp
                );
                $encode = JWT::encode($payload, $sec_key, 'HS256');
                $response = [
                    'status' => 200,
                    'message' => 'Login Successful',
                    'token' => $encode,
                ];
                header("HTTP/1.1 200 Ok");
                return json_encode($response);
            } else {
                throw new Exception('Unauthorized', 401);
            }
        } else {
            //User not found
            throw new Exception('User not found', 400);
        }
    }

    function signUp($user)
    {

        global $conn;

        // Ensure all required fields are present in the data
        $requiredFields = ['username', 'password', 'contact', 'admin', 'chosenBranch'];
        foreach ($requiredFields as $field) {
            if (!isset($user[$field])) {
                throw new Exception('Missing required field: ' . $field, 400);
            }
        }

        // Check if the username already exists
        $username = mysqli_real_escape_string($conn, $user['username']);
        $checkQuery = "SELECT COUNT(*) AS count FROM users WHERE username = '$username'";
        $checkResult = mysqli_query($conn, $checkQuery);
        $row = mysqli_fetch_assoc($checkResult);
        if ($row['count'] > 0) {
            throw new Exception('User exists', 400);
        }
        //encrypting password
        $encryptP = password_hash($user['password'], PASSWORD_DEFAULT);
        // Sanitize input data to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $user['username']);
        $password = mysqli_real_escape_string($conn, $encryptP);
        $contact = mysqli_real_escape_string($conn, $user['contact']);
        $admin = mysqli_real_escape_string($conn, $user['admin']);
        $chosenBranch = mysqli_real_escape_string($conn, $user['chosenBranch']);

        // Construct and execute the SQL INSERT query
        $query = "INSERT INTO users (username, password, contact, admin, chosenBranch) 
              VALUES ('$username', '$password', '$contact', '$admin', '$chosenBranch')";

        if (mysqli_query($conn, $query)) {
            $response = [
                'status' => 200,
                'message' => 'Sign up successful, you can log in.',
            ];
            header("HTTP/1.1 200 OK");
            return json_encode($response);
        } else {
            throw new Exception('Failed to add user', 500);
        }
    }
    //======================================================
} catch (Exception $e) {
    $status = $e->getCode() ?: 500;
    $message = $e->getMessage() ?: 'Internal Server Error';

    header("HTTP/1.1 $status");
    echo json_encode(['status' => $status, 'message' => $message]);
}
