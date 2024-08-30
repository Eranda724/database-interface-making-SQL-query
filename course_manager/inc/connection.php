<?php 
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'course_registration1';

    $connection = mysqli_connect('localhost','root','','course_manager');

    if(mysqli_connect_error()){
        die("not connected".mysqli_connect_errno());
    }

?>