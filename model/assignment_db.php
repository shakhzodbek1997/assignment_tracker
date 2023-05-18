<?php
    // Retrieve assignments by course ID
    function get_assignments_by_course($course_id) {
      global $db;
      if($course_id){
        // If a specific course ID is provided, retrieve assignments for that course
        $query = '
          SELECT A.ID, A.Description, C.courseName FROM assignments
          A LEFT JOIN courses C ON A.courseID = C.courseID  
          WHERE A.courseID = :course_id ORDER BY ID
        ';
      }else {
        // If no course ID is provided, retrieve assignments for all courses
        $query = '
          SELECT A.ID, A.Description, C.courseName FROM assignments
          A LEFT JOIN courses C ON A.courseID = C.courseID  
          ORDER BY C.courseID 
        ';
      }

      $statement = $db-> prepare($query);
      if($course_id){
        $statement->bindValue(':course_id', $course_id);
      }
      $statement->execute();
      $assignments = $statement->fetchAll();
      $statement->closeCursor();
      return $assignments;
    }

    // Delete an assignment by assignment ID
    function delete_assignment($assignment_id){
      global $db;
      $query = '
        DELETE FROM assignments WHERE ID = :assign_id
      ';
      $statement = $db-> prepare($query);
      $statement->bindValue(':assign_id', $assignment_id);
      $statement->execute();
      $statement->closeCursor();
    }

    
     // Add a new assignment with the specified course ID and description
    function add_assignment($course_id, $description){
      global $db;
      $query = '
        INSERT INTO assignments (Description, courseID) 
        VALUES(:descr, :courseID)
      ';
      $statement = $db->prepare($query);
      $statement->bindValue(':descr', $description);
      $statement->bindValue(':courseID', $course_id);
      $statement->execute();
      $statement->closeCursor();
    }