<?php
    session_start();
    require_once "config/db.php";
    if (isset($_POST['submit'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $classyear = $_POST['classyear'];
        $birthday = $_POST['birthday'];
        $img = $_FILES['img'];

        $allow = array('jpg','jpeg','png');
        $extension = explode(".",$img['name']);
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = "uploads/".$fileNew;
    
        if (in_array($fileActExt, $allow)){
            if ($img['size'] > 0 && $img['error'] == 0) {
                if (move_uploaded_file($img['tmp_name'], $filePath))
                    $sql = $conn->prepare("INSERT INTO user(firstname, lastname, classyear, birthday, img) VALUES(:firstname, :lastname, :classyear, :birthday, :img)");
                    $sql->bindParam(":firstname",$firstname);
                    $sql->bindParam(":lastname",$lastname);
                    $sql->bindParam(":classyear",$classyear);
                    $sql->bindParam(":birthday",$birthday);
                    $sql->bindParam(":img",$fileNew);
                    $sql->execute();

                    if ($sql) {
                        $_SESSION['success'] = "Data has been inserted Succesfully";
                        header("location: index.php");
                    } else {
                        $_SESSION['error'] = "Data has not been inserted Succesfully";
                        header("location: index.php");
                    }
            }
        }
    }

?>