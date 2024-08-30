<?php
require_once('inc/connection.php');

//
$dropProcedureQuery = "DROP PROCEDURE IF EXISTS UpdateLectureHours";
mysqli_query($connection, $dropProcedureQuery);

$createProcedureQuery = "CREATE PROCEDURE UpdateLectureHours()
BEGIN
    UPDATE course
    SET `Lecture Hours` = CASE
        WHEN Credit = 2 THEN 30
        WHEN Credit = 3 THEN 45
        ELSE `Lecture Hours`
    END;
END;";

if(!mysqli_query($connection, $createProcedureQuery)){
    echo "ERROR STORED PROCEDURE: " . mysqli_error($connection);
}

$callProcedureQuery = "CALL UpdateLectureHours()";
$result = mysqli_query($connection, $callProcedureQuery);

if(!$result){
    echo "Error Updating: " . mysqli_error($connection);
}

//
$query = "SELECT DISTINCT `semester` FROM `manage` ORDER BY `semester` ASC";
$result = mysqli_query($connection, $query);


$options = '';
if(isset($_POST['semester'])){
   $semester=$_POST['semester'];
   $options = '<option value="">Semester '.$semester.'</option>';
}
while ($row = mysqli_fetch_assoc($result)) {
    $options .= '<option value="' . $row['semester'] . '">' .'Semester '.$row['semester'] . '</option>';
}

$newCourseCode = "";
$newCourseName = "";
$newCourseCredit = "";
$newCourseLectureHours = "";

// Check if form submitted for adding a new course
if(isset($_POST['addCourse'])){
    // Retrieve new course data from form
    $newCourseCode = $_POST['newCourseCode'];
    $newCourseName = $_POST['newCourseName'];
    $newCourseCredit = $_POST['newCourseCredit'];
    $newCourseLectureHours = $_POST['newCourseLectureHours'];
    $semester=$_POST['semester'];

    $query = "INSERT IGNORE INTO `course` (`Code`, `Cname`, `Credit`, `Lecture Hours`) 
              VALUES ('$newCourseCode', '$newCourseName', '$newCourseCredit', '$newCourseLectureHours')";

    $record = mysqli_query($connection, $query);
    if($record){
        $query="SELECT `MAid` FROM `manage` WHERE `Semester`='$semester'";
        $record=mysqli_query($connection,$query);
        if($result=mysqli_fetch_assoc($record)){
            $MAid=$result['MAid'];
            $query="INSERT IGNORE INTO `manage` (`Ccode`,`MAid`,`Semester`) VALUES ('$newCourseCode','$MAid','$semester')";
            $record=mysqli_query($connection,$query);
            if($record){
                echo "Added!";
            }
        }
        else{
            echo "Error in Manage table!";
        }
    }
    else{
        if(mysqli_errno($connection) == 1062) {
            echo "Error: Duplicate key didididid. Course with code '$newCourseCode' already exists.";
        } else {
            echo "Error in adding: " . mysqli_error($connection);
        }
    }
}
?>
<?php 
    if(isset($_POST['update'])){
    // Retrieve new course data from form
    $oldCourseCode = $_POST['oldCourseCode'];
    $newCourseCode = $_POST['newCourseCode'];
    $newCourseName = $_POST['newCourseName'];
    $newCourseCredit = $_POST['newCourseCredit'];
    $newCourseLectureHours = $_POST['newCourseLectureHours'];
    $semester=$_POST['semester'];

    $query = "UPDATE `course` SET `Code`='$newCourseCode', `Cname`='$newCourseName', `Credit`='$newCourseCredit', `Lecture Hours`='$newCourseLectureHours' WHERE `Code`='$oldCourseCode'";


    $record = mysqli_query($connection, $query);
    if($record){
        $query="SELECT `MAid` FROM `manage` WHERE `Semester`='$semester'";
        $record=mysqli_query($connection,$query);
        if($result=mysqli_fetch_assoc($record)){
            $MAid=$result['MAid'];
            $query="INSERT IGNORE INTO `manage` (`Ccode`,`MAid`,`Semester`) VALUES ('$newCourseCode','$MAid','$semester')";
            $record=mysqli_query($connection,$query);
        }
        else{
            echo "Error in Manage table!";
        }
    }
    else{
        if(mysqli_errno($connection) == 1062) {
            echo "Error: Duplicate key didididid. Course with code '$newCourseCode' already exists.";
        } else {
            echo "Error in adding: " . mysqli_error($connection);
        }
    }
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management System</title>
    <style>
        body {
            background-color: #9DBFC0;
            font-family: Arial, Helvetica, sans-serif;
        }
        .top {
            border: 1px solid black;
            margin: 0;
        }
        .header {
            background-color: rgb(90, 93, 96);
            text-align: center;
            height: 20px;
            margin: 0;
        }
        img {
            width: 20px;
            vertical-align: middle;
            margin-inline-end: 10px;
            margin-left: 100px;
            display: inline-block;
        }
        .uni {
            margin-top: 0;
            color: white;
            font-size: 10px;
            font-weight: lighter;
            line-height: 20px;
            display: inline-block;
        }
        .fac {
            font-size: 20px;
            color: rgb(27, 183, 241);
            margin-top: 10px;
        }
        .uni2 {
            font-size: 25px;
            color: rgb(71, 86, 108);
        }
        .header2 p {
            margin-top: 10px;
            margin: 5px;
        }
        .courselist {
            font-size: 20px;
            color: black;
            text-align: center;
            margin-top: 0px;
        }
        .sem-container {
            width: 300px;
            margin: 0 auto;
        }
        form {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
        }
        label {
            display: block;
            margin-right: 10px;
        }
        select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            outline: none;
            display: block;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 8px 20px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-left: 20px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        td, th {
            border: 1px solid black;
            background-color: rgb(240, 243, 250);
            padding: 10px;
        }
        table {
            margin: 10px auto;
            border-collapse: collapse;
            text-align: center;
        }
        .edit-btn, .delete-btn {
            border: solid;
            color: white;
            padding: 5px;
            text-align: center;
            font-size: 16px;
            margin: 10px;
        }
        .edit-btn {
            background-color: blue;
        }
        .edit-btn:hover {
            background-color: darkblue;
        }
        .delete-btn {
            background-color: red;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
        .addCourse {
            padding: 8px 20px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-left: 20px;
        }
        .addCourse:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="top">
        
    <legend></legend>
    <div class="header">
        <img src="img/jaffna.jpg">
        <p class="uni">University Of Jaffna</p>
    </div>
    <div class="header2">
        <h1 class="fac">FACULTY OF ENGINEERING</h1>
        <h2 class="uni2">UNIVERSITY OF JAFFNA</h2>
    </div>
    </div>
    <div class="courselist">
        <p>Course List</p><hr>
    </div>
    <label for="semesters">Select Semester</label>
    <div class="sem-container">
    <form id="semesterForm" action="index.php" method="post">
        <select id="semesterSelect" name="semester">
            <?php echo $options; ?>
        </select>
    </form>
    </div>

    <div id="output"></div>

    <script>
        document.getElementById('semesterSelect').addEventListener('change', function() {
            document.getElementById('semesterForm').submit();
        });
    </script>

<?php 
    if(isset($_POST['semester'])){
        $semester=$_POST['semester'];
        if(isset($_POST['Delete'])){
        $Ccode=$_POST['courseCode'];
        $delete_query="DELETE FROM `course` WHERE `Code`='$Ccode'";
        $delete_result = mysqli_query($connection, $delete_query);
        }
        $query="SELECT `Ccode` from `manage` where `semester`='$semester'";
        $result = mysqli_query($connection, $query);
        echo "<table>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Credit</th>
                    <th>Lecture Hours</th>
                    <th>Action</th>
                </tr>";
        while($row = mysqli_fetch_assoc($result)){
            $code=$row['Ccode'];
            $query2="SELECT * from `course` where `Code`='$code'";
            $result2 = mysqli_query($connection, $query2);
            while($row2 = mysqli_fetch_assoc($result2)){
                echo "<tr>";
                echo "<td>" . $row2['Code'] . "</td>";
                echo "<td>" . $row2['Cname'] . "</td>";
                echo "<td>" . $row2['Credit'] . "</td>";
                echo "<td>" . $row2['Lecture Hours'] . "</td>";
                echo "<td>
                    <div class='button-container'>
                        <form action='index.php' method='post' style='display: inline-block;'>
                            <input type='hidden' name='courseCode' value='" . $row2['Code'] . "'>
                            <input type='hidden' name='courseName' value='" . $row2['Cname'] . "'>
                            <input type='hidden' name='credit' value='" . $row2['Credit'] . "'>
                            <input type='hidden' name='lecture_hours' value='" . $row2['Lecture Hours'] . "'>
                            
                            <input type='hidden' name='semester' value='" . $semester. "'>
                            <input type='submit' name='Edit' value='Edit' class='edit-button' style='background-color: #0d6efd; color: white; padding: 5px 10px; border: none; cursor: pointer;border-radius: 5px;'>
                        </form>
                        <form  action='index.php' method='post' style='display: inline-block;'>
                            <input type='hidden' name='courseCode' value='" . $row2['Code'] . "'>
                            <input type='hidden' name='semester' value='" . $semester. "'>
                            <input type='submit' name='Delete' value='Delete' class='delete-button' style='background-color: 
#dc3545; color: white; padding: 5px 10px; border: none; cursor: pointer;border-radius: 5px;'>
                        </form>
                    </div>
                      </td>";
                echo "</tr>";
            }
        }


    if(isset($_POST['add'])){
        echo "<tr>";
        echo "<form action='index.php' method='post'>";

        echo "<td><input type='text' name='newCourseCode' value='$newCourseCode'></td>";
        echo "<td><input type='text' name='newCourseName' value='$newCourseName'></td>";
        echo "<td><input type='text' name='newCourseCredit' value='$newCourseCredit'></td>";
        echo "<td><input type='text' name='newCourseLectureHours' value='$newCourseLectureHours'></td>";
        echo "<input type='hidden' name='semester' value='". $semester."'>";
        echo "<td style='text-align: center;'>";
        echo "<div style='display: flex; justify-content: center;'>"; // Start button container
        echo "<input type='submit' name='addCourse' value='Save' style='background-color: blue; color: white; padding: 5px 10px; border: none; cursor: pointer;border-radius: 5px;'>"; // Save button
        echo "<input type='submit' name='cancel' value='Cancel' style='background-color: blue; color: white; padding: 5px 10px; border: none; cursor: pointer;border-radius: 5px;margin-left:5px;'>"; // Cancel button
        echo "</div>"; 
        echo "</td>";
        echo "</form>";
        echo "</tr>";
    }

    if(isset($_POST['Edit'])){
        echo "<tr>";
        echo "<form action='index.php' method='post'>";
        echo "<input type='hidden' name='oldCourseCode' value='" . $_POST['courseCode'] . "'>";
        echo "<td><input type='text' name='newCourseCode' value='" . $_POST['courseCode'] . "'></td>";
        echo "<td><input type='text' name='newCourseName' value='" . $_POST['courseName'] . "'></td>";
        echo "<td><input type='text' name='newCourseCredit' value='" . $_POST['credit'] . "'></td>";
        echo "<td><input type='text' name='newCourseLectureHours' value='" . $_POST['lecture_hours'] . "'></td>";
        echo "<input type='hidden' name='semester' value='". $semester."'>";
        echo "<td style='text-align: center;'>";
        echo "<div style='display: flex; justify-content: center;'>"; // Start button container
        echo "<input type='submit' name='update' value='Update' style='background-color: blue; color: white; padding: 5px 10px; border: none; cursor: pointer;border-radius: 5px;'>"; // Save button
        echo "<input type='submit' name='cancel' value='Cancel' style='background-color: blue; color: white; padding: 5px 10px; border: none; cursor: pointer;border-radius: 5px;margin-left:5px;'>"; // Cancel button
        echo "</div>"; 
        echo "</td>";
        echo "</form>";
        echo "</tr>";
    }

        echo "</table>";
    }
    ?>

    <br>

    <?php   
        if(isset($_POST['semester'])){
            echo "<form action='index.php' method='post'>";
            echo "<div style='display: flex; justify-content: center;'><input id='add-button' type='submit' name='add' value='Add New Course' style='background-color: #0d6efd; color: white; padding: 5px 10px; border: none; cursor: pointer;border-radius: 5px;'></div>";
            echo "<input type='hidden' name='semester' value='".$semester."'>";
            echo "</form>";
        }
     ?>



</body>
</html>

