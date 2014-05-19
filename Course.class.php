<?php
// Modify with your own Database Settings
define('DB_HOST_NAME', 'localhost');
define('DB_USER_NAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'ecourse');

class DB extends mysqli {
	public function DB() {
		parent::__construct(DB_HOST_NAME, DB_USER_NAME, DB_PASSWORD, DB_NAME);
	}
}

define('SQL_COURSE_FETCH', 'SELECT * FROM courses WHERE course_id = ? LIMIT 1');
define('SQL_COURSE_LIST', 'SELECT * FROM courses ORDER BY course_title ASC');
define('SQL_COURSE_UPDATE', 'UPDATE courses SET course_title = ?, course_description = ?, start_date = ?, end_date = ?, price = ? WHERE course_id = ?');
define('SQL_COURSE_INSERT', 'INSERT INTO courses (course_title, course_description, start_date, end_date, price) VALUES(?, ?, ?, ?, ?)');
define('SQL_COURSE_DELETE', 'DELETE FROM courses WHERE course_id = ?');

class Course {
	private $_course_id;
	private $_course_title;
	private $_course_description;
	private $_start_date;
	private $_end_date;
	private $_price;
	
	public function Course($course_id, $course_title, $course_description, $start_date, $end_date, $price) {
		$this->setCourseID($course_id);
		$this->setCourseTitle($course_title);
		$this->setCourseDescription($course_description);
		$this->setStartDate($start_date);
		$this->setEndDate($end_date);
		$this->setPrice($price);
	}
	
	public function getCourseID() {
		return $this->_course_id;
	}
	
	public function setCourseID($course_id) {
		$this->_course_id = $course_id;
	}
	
	public function getCourseTitle() {
		return $this->_course_title;
	}
	
	public function setCourseTitle($course_title) {
		$this->_course_title = $course_title;
	}
	
	public function getCourseDescription() {
		return $this->_course_description;
	}
	
	public function setCourseDescription($course_description) {
		$this->_course_description = $course_description;
	}
	
	public function getStartDate() {
		return $this->_start_date;
	}
	
	public function setStartDate($start_date) {
		$this->_start_date = $start_date;
	}
	
	public function getEndDate() {
		return $this->_end_date;
	}
	
	public function setEndDate($end_date) {
		$this->_end_date = $end_date;
	}
	
	public function getPrice($price) {
		return $this->_price;
	}
	
	public function setPrice($price) {
		$this->_price = $price;
	}
	
	public function insert() {
		$db = new DB();
		$statement = $db->prepare(SQL_COURSE_INSERT);
		
		$start_date = date('Y-m-d', strtotime($this->getStartDate()));
		$end_date = date('Y-m-d', strtotime($this->getEndDate()));
		
		$statement->bind_param('sssss', $this->getCourseTitle(), $this->getCourseDescription(), $start_date, $end_date, $this->getPrice());
		$statement->execute();
		return $db->insert_id;
	}

	public function update(){
		$db = new DB();
		$statement = $db->prepare(SQL_COURSE_UPDATE);
		
		$start_date = date('Y-m-d', strtotime($this->getStartDate()));
		$end_date = date('Y-m-d', strtotime($this->getEndDate()));
		
		$statement->bind_param('sssssi', $this->getCourseTitle(), $this->getCourseDescription(), $start_date, $end_date, $this->getPrice(), $this->getCourseID());
		$statement->execute();
		$db->close();
		return true;
	}
	
	public function toArray() {
		$start_date = date('d-m-Y', strtotime($this->getStartDate()));
		$end_date = date('d-m-Y', strtotime($this->getEndDate()));
	
		return array('course_id' => $this->getCourseID(),
							  'course_title' => $this->getCourseTitle(),
							  'course_description' => $this->getCourseDescription(),
							  'start_date' => $start_date,
							  'end_date' => $end_date,
							  'price' => $this->getPrice());
	}
	
	public static function listing() {
		$db = new DB();
		$courses = array();
		$statement = $db->prepare(SQL_COURSE_LIST);
		$statement->execute();
		$statement->bind_result($course_id, $course_title, $course_description, $start_date, $end_date, $price);
		while($statement->fetch()) {
			$courses[] = new Course($course_id, $course_title, $course_description, $start_date, $end_date, $price);
		}
		$db->close();
		return $courses;
	}
	
	public static function factory($course_id) {
		$db = new DB();
		$statement = $db->prepare(SQL_COURSE_FETCH);
		$statement->bind_param("i", $course_id);
		$statement->execute();
		$statement->bind_result($course_id, $course_title, $course_description, $start_date, $end_date, $price);
		$statement->fetch();
		$db->close();
		return new Course($course_id, $course_title, $course_description, $start_date, $end_date, $price);
	}
	
	public static function delete($course_id) {
		$db = new DB();
		$statement = $db->prepare(SQL_COURSE_DELETE);
		$statement->bind_param("i", $course_id);
		$statement->execute();
		$db->close();
		return true;
	} 
}
?>