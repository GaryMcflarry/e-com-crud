<?php
$con = mysqli_connect("localhost", "root", "", "meatworld");
$response = array();

if ($con) {
    $sql = "select * from users";
    $result = mysqli_query($con, $sql);
    if ($result) {
        header("Content-Type: JSON");
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            
            $response[$i]['userID'] = $row['userID'];
            $response[$i]['username'] = $row['username'];
            $response[$i]['password'] = $row['password'];
            $response[$i]['contact'] = $row['contact'];
            $response[$i]['admin'] = $row['admin'];
            $response[$i]['chosenBranch'] = $row['chosenBranch'];
            $response[$i]['icon'] = $row['icon'];
            $i++;
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo "DB Connection Failed";
    }
}
