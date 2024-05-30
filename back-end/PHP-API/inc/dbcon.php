<?php
//SQL connection
//locahost (testing)
// $conn = mysqli_connect("localhost", "root", "", "meatworld");
//connection to hosting database
$conn = mysqli_connect("localhost", "id22238633_gary_mcflarry", "Skyrim_123", "id22238633_mw");
//If SQL connection has failed
if(!$conn) {
    die("Connection Failed: ".mysqli_connect_error());
}

?>