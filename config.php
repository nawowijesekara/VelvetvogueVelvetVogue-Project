
<?php
$conn = mysqli_connect('localhost', 'root', '', 'velvet');

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>