<?php
session_start();

function getAllDepartments()
{
   global $db;
   $query = "select * from Department";    
   $statement = $db->prepare($query);    // compile
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

function getCoursesForDept($dept_code)
{
   global $db;
   $query = "select * from Course where dept_code='" . $dept_code . "';";
   $statement = $db->prepare($query);    // compile
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

function getNotesForCourse($course_id) {
   global $db;
   $query = "SELECT N.id AS note_id, N.date_uploaded, U.name AS author_name, N.computing_id, AVG(R.value) AS average_rating 
            FROM Note N JOIN User U ON N.computing_id = U.computing_id LEFT JOIN NoteRating NR ON N.id = NR.note_id LEFT JOIN Rating R ON NR.rating_id = R.id 
            WHERE N.course_id = " . $course_id . " 
            GROUP BY N.id, N.date_uploaded, U.name, N.computing_id;";
   $statement = $db->prepare($query);    // compile
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

function getUser($username, $computingId, $password){
   global $db;

   $query = "select * from User where computing_id='" . $computingId . "';";
   $statement = $db->prepare($query);    // compile
   $statement->execute();
   $result = $statement->fetchAll();     // fetch()
   $statement->closeCursor();

   return $result;
}

function insertUser($username, $computingId, $password)
{
   global $db;

   $query = "INSERT INTO User (computing_id, name, password) VALUES (:computingId, :username, :password)";
   $statement = $db->prepare($query);
   $statement->bindParam(':computingId', $computingId);
   $statement->bindParam(':username', $username);
   $statement->bindParam(':password', $password);
   
   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}

function updateUser($username)
{
   global $db;

   $computingId = $_SESSION['computingId'];
   $query = "UPDATE User SET name=:username WHERE computing_id=:computingId";

   $statement = $db->prepare($query);
   $statement->bindParam(':username', $username);
   $statement->bindParam(':computingId', $computingId);
   
   $success = $statement->execute();
   $statement->closeCursor();
      
   $_SESSION['name'] = $username;
   $_SESSION['profileError'] = '';

   return $success;
}

function uploadNote($course_id, $computing_id) 
{
   global $db;
   $query = "INSERT INTO Note (course_id, computing_id) VALUES (" . $course_id . ", '" . $computing_id . "')";
   $statement = $db->prepare($query);

   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}

function getNoteById($id) 
{
   global $db;

   $query = "SELECT * FROM Note WHERE id=:id";
   $statement = $db->prepare($query);
   $statement->bindParam(':id', $id);
   
   $success = $statement->execute();
   
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function deleteNoteById($id) 
{
   global $db;

   $query = "DELETE FROM Note WHERE id=:id";
   $statement = $db->prepare($query);
   $statement->bindParam(':id', $id);
   
   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}

function reuploadNote($id, $new_date) 
{
   global $db;
   
   $query = "UPDATE Note SET date_uploaded=:new_date WHERE id=:id";
   $statement = $db->prepare($query);
   $statement->bindParam(':id', $id);
   $statement->bindParam(':new_date', $new_date);

   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}

function addScheduleCourse($computingId, $courseId)
{
   global $db;

   $query = "INSERT INTO Schedule (computing_id, course_id) VALUES (:computingId, :courseId)";
   $statement = $db->prepare($query);
   $statement->bindParam(':computingId', $computingId);
   $statement->bindParam(':courseId', $courseId);
   
   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}

function getSchedule($computingId)
{
   global $db;

   $query = "SELECT DISTINCT Course.name, Course.dept_code, Course.professor_name, Course.id FROM Course JOIN Schedule ON Course.id = Schedule.course_id WHERE Schedule.computing_id=:computingId;";

   $statement = $db->prepare($query);
   $statement->bindParam(':computingId', $computingId);
   
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function deleteClassFromSchedule($computingId, $courseId)
{
   global $db;

   $query = "DELETE FROM Schedule WHERE computing_id=:computingId AND course_id=:courseId";

   $statement = $db->prepare($query);
   $statement->bindParam(':computingId', $computingId);
   $statement->bindParam(':courseId', $courseId);
   
   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}

function addFavorite($computingId, $noteId, $courseId)
{
   global $db;
   $query = "SELECT * FROM Favorite WHERE computing_id=:computingId AND note_id=:noteId AND course_id=:courseId";
   $statement = $db->prepare($query);
   $statement->bindParam(':computingId', $computingId);
   $statement->bindParam(':noteId', $noteId);
   $statement->bindParam(':courseId', $courseId);
   $statement->execute();
   $result = $statement->fetchAll();
   if (count($result) > 0){
      return false;
   }
   else{
      $query = "INSERT INTO Favorite (computing_id, note_id, course_id) VALUES (:computingId, :noteId, :courseId)";
      $statement = $db->prepare($query);
      $statement->bindParam(':computingId', $computingId);
      $statement->bindParam(':noteId', $noteId);
      $statement->bindParam(':courseId', $courseId);
      
      $success = $statement->execute();
      $statement->closeCursor();
   
      return $success;
   }
}

function deleteFavorite($computingId, $noteId, $courseId)
{
   global $db;

   $query = "DELETE FROM Favorite WHERE computing_id=:computingId AND note_id=:noteId AND course_id=:courseId";
   $statement = $db->prepare($query);
   $statement->bindParam(':computingId', $computingId);
   $statement->bindParam(':noteId', $noteId);
   $statement->bindParam(':courseId', $courseId);
   
   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}

function getFavorite($computingId, $courseId)
{
   global $db;

   $query = "SELECT Note.computing_id, Favorite.note_id FROM Note JOIN Favorite ON Note.id=Favorite.note_id WHERE Favorite.computing_id=:computingId AND Note.course_id=:courseId";
   $statement = $db->prepare($query);
   $statement->bindParam(':computingId', $computingId);
   $statement->bindParam(':courseId', $courseId);

   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function getFavoriteByNoteID($computingId, $noteId, $courseId)
{
   global $db;

   $query = "SELECT * FROM Favorite WHERE computing_id=:computingId AND note_id=:noteId AND course_id=:courseId";
   $statement = $db->prepare($query);
   $statement->bindParam(':computingId', $computingId);
   $statement->bindParam(':noteId', $noteId);
   $statement->bindParam(':courseId', $courseId);

   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function addRating($rating, $comment)
{

   global $db;

   $query = "INSERT INTO Rating (value, comment) VALUES (:value, :comment)";
   $statement = $db->prepare($query);
   $statement->bindParam(':value', $rating);
   $statement->bindParam(':comment', $comment);
   
   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}

function getRatingID()
{
   global $db;

   $query = "SELECT LAST_INSERT_ID() AS last_id;";
   $statement = $db->prepare($query);
   
   $statement->execute();
   $result = $statement->fetchAll();
   $statement->closeCursor();

   return $result;
}

function addNoteRating($ratingId, $noteId)
{
   global $db;

   $query = "INSERT INTO NoteRating (rating_id, note_id) VALUES (:ratingId, :noteId)";
   $statement = $db->prepare($query);
   $statement->bindParam(':ratingId', $ratingId);
   $statement->bindParam(':noteId', $noteId);
   
   $success = $statement->execute();
   $statement->closeCursor();

   return $success;
}
?>