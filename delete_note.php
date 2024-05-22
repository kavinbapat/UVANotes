<?php
session_start();
require('connect-db.php');
require('database-requests.php');
// var_dump($_SESSION);
if (!isset($_SESSION['computingId'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['note_id'])) {
    $note_id = $_GET['note_id'];
    $computing_id = $_SESSION['computingId'];

    $note = getNoteById($note_id)[0];
    if ($note && $note['computing_id'] === $computing_id) {
        $success = deleteNoteById($note_id);

        if ($success) {
            unlink("notes/" . $computing_id . ".pdf");
            header('Location: notes.php?course=' . $_GET['course_id'] . '&status=deleted');
        } else {
            header('Location: notes.php?course=' . $_GET['course_id'] . '&error=deletion_failed');
        }
    } else {
        header('Location: notes.php?course=' . $_GET['course_id'] . '&error=not_allowed');
    }
} else {
    header('Location: notes.php?error=missing_id');
}
exit;
?>
