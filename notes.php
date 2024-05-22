<?php 
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();
require("connect-db.php"); 
require("database-requests.php");
?>

<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_GET['course'])){
        $course= $_GET['course'];
    }
    if (isset($_POST['course_id'])){
        $success = addScheduleCourse($_SESSION['computingId'], $course);
    }
    if (isset($_POST['note_id'])){
        $noteId = $_POST['note_id'];
        $success = addFavorite($_SESSION['computingId'], $noteId, $course);
    }
    if (isset($_POST['rating']) && isset($_POST['comment'])){
        $noteId = $_POST['note_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $success = addRating($rating, $comment);
        $ratingArray = getRatingID();
        $ratingId=$ratingArray[0]['last_id'];
        $success2 = addNoteRating($ratingId, $noteId);
    }
}

$schedule = getSchedule($_SESSION['computingId']);

if (isset($_GET['course'])){
    $course= $_GET['course'];
}
$list_of_notes = getNotesForCourse($course);
$userHasUploaded = false;

if (isset($_SESSION['computingId'])) {
    foreach ($list_of_notes as $note) {
        if ($note['computing_id'] == $_SESSION['computingId']) {
            $userHasUploaded = true;
            $uploadedNoteId = $note['note_id'];
            break;
        }
    }
}
$courseAdded = true;
foreach ($schedule as $eachCourse){
    if (in_array($course, $eachCourse)){
        $courseAdded = false;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
  
  <title>Notes</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="styles.css">  
</head>

<body>  
<div class="nav" style="padding: 16px;">
    <a href="departments.php" style="text-decoration: none;">Back to Departments</a>
</div>
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Notes for course</h2>
    </div>  
  </div>
</div>
<div class="container">
    <h3>Notes</h3>
    <?php if ($userHasUploaded): ?>
        <button class="btn btn-danger" onclick="showConfirmModal()">Delete Note</button>

        <button class="btn btn-primary" onclick="openReuploadModal()">Re-upload Note</button>

        <div id="confirmModal" class="modal" style="display:none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="close" onclick="closeConfirmModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this note?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deleteNote()">Delete</button>
                        <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Re-upload Modal (similar to Add Note modal) -->
        <div id="reuploadModal" class="modal" style="display:none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Re-upload a Note</h5>
                        <button type="button" class="close" onclick="closeReuploadModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="note_id" value="<?php echo $uploadedNoteId; ?>">
                            <input type="hidden" name="course_id" value=<?php echo htmlspecialchars($course); ?>>
                            <input type="file" class="form-control" id="file" name="file" accept=".pdf" required>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-success">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif (isset($_SESSION['computingId'])): ?>
        <button class="btn btn-primary my-3" onclick="openModal()">Add Note</button>
    <?php endif; ?>
    <?php if (isset($_SESSION['computingId']) && $courseAdded): ?>
        <form action="notes.php?course=<?php echo $course ?>" method="POST">
            <input type="hidden" name="course_id" value="<?= $course ?>">
            <button type="submit" class="btn btn-secondary">Add course to schedule</button>
        </form>
    <?php endif; ?>
    <!-- <div class="search-container">
        <input type="text" id="searchCourse" onkeyup="searchCourses()" placeholder="Search for Courses">
    </div> -->
    <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
        <thead>
        <tr style="background-color:#B0B0B0">
            <th><b>Author</b></th>
            <th><b>Date Uploaded (YYYY-MM-DD)</b></th>        
            <th><b>Rating</b></th> 
            <th><b>Link</b></th>
            <?php if (isset($_SESSION['computingId'])): ?>
            <th><b>Favorite Note</b></th>
            <th><b>Add Rating (1-5)</b></th>
            <?php endif; ?>
        </tr>
        </thead>
        <!-- iterate array of results, display the existing requests -->
        <?php foreach ($list_of_notes as $note): ?>
        <tr>
            <td><?php echo $note['computing_id']; ?></td>
            <td><?php echo $note['date_uploaded']; ?></td>        
            <td><?php echo $note['average_rating']; ?>/5</td>    
            <?php
            $checkPath = "notes/" . $note['computing_id'] . ".pdf";
            if (file_exists($checkPath)) {
                $filePath = "../notes/" . $note['computing_id'] . ".pdf";
            } else {
                $filePath = "../notes/example.pdf";
            }
            ?>
            <td><a href="<?php echo htmlspecialchars($filePath); ?>">View Note</a></td>
            
            <?php if(isset($_SESSION['computingId']) && count(getFavoriteByNoteID($_SESSION['computingId'], $note['note_id'], $course)) < 1): ?>
            <td>
                <form action="notes.php?course=<?php echo $course ?>" method="POST">
                    <input type="hidden" name="note_id" value="<?= $note['note_id'] ?>">
                    <button type="submit" class="btn btn-primary">Favorite Note</button>
                </form>
            </td>
            <?php endif; ?>
            <?php if(isset($_SESSION['computingId']) && count(getFavoriteByNoteID($_SESSION['computingId'], $note['note_id'], $course)) > 0): ?>
            <td>
                <p>Note is already favorited!</p>
            </td>
            <?php endif; ?>
            <?php if(isset($_SESSION['computingId'])): ?>
            <td>
                <form action="notes.php?course=<?php echo $course ?>" method="POST">
                    <input type="hidden" name="note_id" value="<?= $note['note_id'] ?>">
                    <input type="number" name="rating" placeholder="3.5" style="width:50px;">
                    <input type="text" name="comment" placeholder="Good visuals!" style="width:150px;">
                    <button type="submit" class="btn btn-primary" style="width:100px; height:40px;">Rate Note</button>
                </form>
            </td>
            <?php endif; ?>

        </tr>
        <?php endforeach; ?>  

    </table>
</div>   
<br/><br/>
<?php include('footer.html') ?>
<!-- <script src='courses.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<div id="uploadModal" class="modal" style="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload a Note</h5>
        <button type="button" class="close" onclick="closeModal()">&times;</button>
      </div>
      <div class="modal-body">
        <form action="upload.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="file">File:</label>
            <input type="hidden" name="action" value="upload">
            <input type="hidden" name="course_id" value=<?php echo htmlspecialchars($course); ?>>
            <input type="file" class="form-control" id="file" name="file" accept=".pdf" required>
          </div>
          <div class="form-group mt-2">
            <button type="submit" class="btn btn-success">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function openModal() {
    document.getElementById('uploadModal').style.display = 'block';
}
function closeModal() {
    document.getElementById('uploadModal').style.display = 'none';
}
function showConfirmModal() {
    document.getElementById('confirmModal').style.display = 'block';
}
function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
}
function openReuploadModal() {
    document.getElementById('reuploadModal').style.display = 'block';
}
function closeReuploadModal() {
    document.getElementById('reuploadModal').style.display = 'none';
}
function deleteNote() {
    window.location.href = 'delete_note.php?note_id=<?php echo $uploadedNoteId; ?>&course_id=<?php echo $course; ?>';
}
</script>

</body>

</html>