CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_title` varchar(55) NOT NULL,
  `course_description` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7;

INSERT INTO `courses` (`course_id`, `course_title`, `course_description`, `start_date`, `end_date`, `price`) VALUES
(1, 'Internet Communications', 'Introduces Internet communication and the fundamental concepts of Internet Architecture and how they support the massive growth and varied uses of the medium. ', '2014-05-20', '2014-05-20', '1500.00'),
(2, 'Foundations of Information Technology', 'This course introduces students to the principles and techniques involved in utilizing new information technologies.', '2014-05-20', '2014-06-30', '3000.00'),
(3, 'Introduction to Programming', 'Introduces the key skills of problem solving and computer programming, including the elementary programming concepts of documentation, data elements, sequence, selection, and iteration.', '2014-05-20', '2014-05-20', '4000.00'),
(4, 'Database Management Systems', 'Provides students with theoretical knowledge and practical skills in the use of databases and database management systems in information technology applications.', '2014-05-20', '2014-07-30', '2000.00'),
(5, 'Project Management', 'Professionals, whether they are working in the sciences, business, engineering, information technology, health or education, typically work in teams to complete projects.', '2014-05-20', '2014-05-20', '3000.00'),
(6, '	Contemporary Issues in Information Technology', 'This course investigates a number of contemporary issues in the rapidly changing information technology environment.', '2014-05-20', '2014-06-30', '2000.00');

