<?php 
require("connect-db.php");
require("database-requests.php");

if (!isset($_SESSION['computingId'])) {
    header('Location: login.php');
    exit;
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        if ($fileExtension == 'pdf') {
            if($_POST['action']=='update') {
                $dest_path = './notes/' . $_SESSION['computingId'] . '.pdf';
                $new_date = date('Y-m-d');
                // instead of move_uploaded_file, might need to use a file storage server
                unlink($dest_path);
                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    $success = reuploadNote($_POST['note_id'], $new_date);
                    if($success) {
                        $redirectURL = 'notes.php?course=' . $_POST['course_id']; 
                        header("Location: $redirectURL");
                        echo 'File is successfully uploaded.';
                    }
                } else {
                    echo 'There was some error moving the file to upload directory.';
                }
            } else if ($_POST['action']=='upload') {
                $dest_path = './notes/' . $_SESSION['computingId'] . '.pdf';
                // instead of move_uploaded_file, might need to use a file storage server
                if(move_uploaded_file($fileTmpPath, $dest_path)) {
                    $success = uploadNote($_POST['course_id'], $_SESSION['computingId']);
                    if($success) {
                        $redirectURL = 'notes.php?course=' . $_POST['course_id']; 
                        header("Location: $redirectURL");
                        echo 'File is successfully uploaded.';
                    }
                } else {
                    echo 'There was some error moving the file to upload directory.';
                }
            }
            
        } else {
            echo 'Upload failed. Allowed file types: PDF.';
        }
    } else {
        echo 'Error in uploading file. Error code: ' . $_FILES['file']['error'];
    }
}
?>
