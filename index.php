<?php 
// The require statements are used to include external PHP files in the index.php file.
  require('model/database.php');
  require('model/assignment_db.php');
  require('model/course_db.php');

  // Retrieve input values from POST and sanitize them
  $assignment_id = filter_input(INPUT_POST, 'assignment_id', FILTER_VALIDATE_INT);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
  $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);

   // Retrieve input value for course_id from POST or GET and sanitize it
  $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
  if(!$course_id){
    $course_id = filter_input(INPUT_GET, 'course_id', FILTER_VALIDATE_INT);
  }

  // Retrieve input value for action from POST or GET and sanitize it
  $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
  if(!$action){
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if(!$action) {
      $action = 'list_assignments';       
    }
  }

    // Perform different actions based on the value of $action
  switch($action){
    case "list_courses":
      // Get the list of courses
      $courses = get_courses();
      // Include the course_list.php file for displaying the list of courses
      include('view/course_list.php');
      break;
    case "add_course":
      // Adding a new course
      add_course($course_name);
      // Redirect to the list of courses
      header("Location: .?action=list_courses");
      break;
    case "add_assignment":
      // Add a new assignment for a specific course
      if($course_id && $description){
        add_assignment($course_id, $description);
        // Redirect to the page displaying assignments for the selected course
        header("Location: .?course_id=$course_id");
      }else{
         // If invalid assignment data is provided, display an error message
        $error = "Invalid assignment data. Check All Fields and try again.";
        include('view/error.php');
        exit();
      }
      break;  
    case "delete_course":
      // Delete a course and its associated assignments
      if($course_id){
        try {
          delete_course($course_id);
        }catch(PDOException $e){
          // If assignments exist for the course, display an error message
          $error = "You Cannot delete a course if assignments exist in the course.";
          include('view/error.php');
          exit();
        }
        header("Location: .?action=list_courses");
      }
      break;
    // finally we have delete assignment
    case "delete_assignment":
      if($assignment_id){
        delete_assignment($assignment_id);
        header("Location: .?course_id=$course_id");
      } else{
        // If missing or incorrect assignment ID is provided, display an error message
        $error = "Missing or incorrect assignment id";
        include('view/error.php');
      }
      break;
    default:
      // If no specific action is specified, display the list of assignments for a course
      $course_name = get_course_name($course_id);
      $courses = get_courses();
      $assignments = get_assignments_by_course($course_id);
      include('view/assignment_list.php');
  }