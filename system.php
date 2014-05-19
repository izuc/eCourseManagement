<?php
require_once('Course.class.php');
if (isset($_REQUEST['action']) && is_numeric($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		// factory
		case 1:
			if (isset($_REQUEST['course_id'])) {
				$course = Course::factory($_REQUEST['course_id']);
				echo json_encode($course->toArray());
			}
			break;
		// listing
		case 2:
			$courses = Course::listing();
			$data = array();
			foreach ($courses as $course) {
				$data[] = $course->toArray();
			}
			echo json_encode($data);			
			break;
		// insert or update
		case 3:
			$data = json_decode($HTTP_RAW_POST_DATA);
			if (!empty($data->course_title) 
				&& !empty($data->course_description) 
				&& !empty($data->start_date) 
				&& !empty($data->end_date)
				&& !empty($data->price)) {
				
				$course_id = (!empty($data->course_id) ? $data->course_id : 0);
				$course = new Course($course_id, $data->course_title, $data->course_description, $data->start_date, $data->end_date, $data->price);
				
				if ($course_id == 0) {
					$course->insert();
				} else {
					$course->update();
				}
			}
			break;
		// delete
		case 4:
			$data = json_decode($HTTP_RAW_POST_DATA);
			if (!empty($data->course_id)) {
				Course::delete($data->course_id);
			}
			break;
	}
}
?>