-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2023 at 11:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendancesystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `emp_info`
--

CREATE TABLE `emp_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` enum('HR','Marketing','Accounting','IT','Admin') NOT NULL,
  `position` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `work_days` varchar(255) NOT NULL,
  `work_hrs` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_info`
--

INSERT INTO `emp_info` (`id`, `name`, `department`, `position`, `start_date`, `work_days`, `work_hrs`) VALUES
(1, 'Ichiji Seika', 'Admin', 'Overlord', '2023-03-16', 'Monday to Friday', '8am - 5pm'),
(2, 'Otogi Aruto', 'HR', 'HR Supervisor', '2023-03-20', 'Monday to Friday', '8am to 5pm'),
(3, 'Saito Miyako', 'HR', 'Talent Acquisition', '2023-04-12', 'MWF', '8am - 5pm');

-- --------------------------------------------------------

--
-- Table structure for table `int_info`
--

CREATE TABLE `int_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` enum('HR','Marketing','Accounting','IT','Admin') NOT NULL,
  `position` varchar(255) NOT NULL DEFAULT 'Intern',
  `start_date` date NOT NULL,
  `work_days` varchar(255) NOT NULL,
  `work_hrs` varchar(255) NOT NULL,
  `hr_req` int(3) NOT NULL,
  `hr_ren` int(3) NOT NULL DEFAULT 0,
  `hr_left` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `int_info`
--

INSERT INTO `int_info` (`id`, `name`, `department`, `position`, `start_date`, `work_days`, `work_hrs`, `hr_req`, `hr_ren`, `hr_left`) VALUES
(1, 'Gotou Hitori', 'IT', 'Intern', '2023-04-24', 'Monday to Friday', '8am - 5pm', 486, 231, 255),
(2, 'Ichiji Nijika', 'HR', 'Intern', '2023-04-24', 'Monday to Friday', '8am - 5pm', 500, 37, 463),
(3, 'Yamada Ryo', 'Accounting', 'Intern', '2023-04-24', 'Friday', '8am - 8:30am', 600, 36, 564),
(4, 'Kita Ikuyo', 'Marketing', 'Intern', '2023-04-26', 'Monday to Saturday', '8am - 5pm', 360, 31, 329),
(5, 'Hiroi Kikuri', 'Admin', 'Intern', '2023-04-26', 'MWF', '8am - 5pm', 700, 1, 699),
(6, 'Arima Kana', 'Marketing', 'Intern', '2023-05-04', 'Monday to Friday', '8am - 5pm', 300, 50, 250);

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `leave_type` enum('Planned Leave','School Initiated Leave','Emergency Leave','Sick Leave','Birthday Leave','Vacation Leave') NOT NULL,
  `date_req` datetime NOT NULL,
  `date_app` datetime NOT NULL,
  `status` enum('Approved','Rejected') NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `name`, `leave_type`, `date_req`, `date_app`, `status`, `remarks`) VALUES
(6, 'Ichiji Seika', 'Birthday Leave', '2023-05-03 09:44:00', '2023-05-17 17:42:00', 'Rejected', 'no'),
(7, 'Gotou Hitori', 'School Initiated Leave', '2023-05-16 10:46:00', '2023-05-17 17:42:00', 'Rejected', 'why'),
(8, 'Ichiji Nijika', 'Emergency Leave', '2023-05-16 09:54:00', '2023-05-18 15:52:00', 'Approved', 'ok');

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE `notices` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Late','Absent without Leave','Reminder','Late - No time in','Unidentified') NOT NULL,
  `date` date NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `name`, `type`, `date`, `remarks`) VALUES
(1, 'Ichiji Nijika', 'Late', '2023-05-18', 'why'),
(2, 'Gotou Hitori', 'Absent without Leave', '2023-05-18', 'ok'),
(3, 'Yamada Ryo', 'Late - No time in', '2023-05-23', 'yo');

-- --------------------------------------------------------

--
-- Table structure for table `tasks_activities`
--

CREATE TABLE `tasks_activities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_date_assigned` datetime NOT NULL,
  `task_deadline` datetime NOT NULL,
  `task_from` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks_activities`
--

INSERT INTO `tasks_activities` (`id`, `name`, `department`, `task_name`, `task_date_assigned`, `task_deadline`, `task_from`) VALUES
(1, 'Gotou Hitori', 'IT', 'AMS Development', '2023-07-12 00:00:00', '2023-07-17 11:24:51', 'Admin Department'),
(2, 'Ichiji Nijika', 'HR', 'Monitor IT Department', '2023-07-01 14:23:48', '2023-07-07 14:23:48', 'HR Department'),
(3, '', 'All Departments', 'Attend Weekly Meeting', '2023-07-01 14:26:54', '2023-07-14 14:26:54', 'Admin Department'),
(4, '', 'HR', 'List down Intern Names', '2023-07-13 10:24:48', '2023-07-13 10:24:48', 'HR Department'),
(5, 'Ichiji Nijika', 'HR', 'Test', '2023-05-01 16:37:02', '2023-07-13 10:37:02', ''),
(6, 'Ryo Yamada ', 'Accounting', 'Check Payroll', '2023-07-13 11:15:42', '2023-07-13 11:15:42', '');

-- --------------------------------------------------------

--
-- Table structure for table `time_in`
--

CREATE TABLE `time_in` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `photo_loc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_in`
--

INSERT INTO `time_in` (`id`, `name`, `datetime`, `location`, `photo_loc`) VALUES
(1, 'Kita Ikuyo', '2023-05-05 18:03:33', 'WFH', ''),
(2, 'Ichiji Nijika', '2023-05-05 18:17:16', 'Imus', ''),
(7, 'Gotou Hitori', '2023-05-06 12:31:40', 'WFH', ''),
(8, 'Ichiji Nijika', '2023-05-06 12:31:54', 'Evia', ''),
(9, 'Yamada Ryo', '2023-05-06 12:32:05', 'Imus', ''),
(10, 'Kita Ikuyo', '2023-05-06 12:32:16', 'WFH', ''),
(11, 'Gotou Hitori', '2023-05-07 13:11:34', 'WFH', ''),
(12, 'Ichiji Nijika', '2023-05-07 13:11:46', 'Imus', ''),
(13, 'Yamada Ryo', '2023-05-07 13:11:58', 'Evia', ''),
(14, 'Kita Ikuyo', '2023-05-07 13:12:11', 'Imus', ''),
(15, 'Gotou Hitori', '2023-05-08 15:20:45', 'Imus', ''),
(16, 'Yamada Ryo', '2023-05-08 15:23:20', 'WFH', ''),
(17, 'Ichiji Nijika', '2023-05-08 15:23:33', 'WFH', ''),
(18, 'Kita Ikuyo', '2023-05-08 15:25:08', 'WFH', ''),
(19, 'Otogi Aruto', '2023-05-08 17:04:36', 'WFH', ''),
(20, 'Gotou Hitori', '2023-05-14 13:47:35', 'WFH', ''),
(21, 'Ichiji Nijika', '2023-05-14 13:48:02', 'Imus', ''),
(22, 'Yamada Ryo', '2023-05-14 13:48:14', 'Evia', ''),
(23, 'Kita Ikuyo', '2023-05-14 13:48:27', 'WFH', ''),
(24, 'Otogi Aruto', '2023-05-14 13:48:38', 'WFH', ''),
(25, 'Otogi Aruto', '2023-05-14 13:50:22', 'WFH', ''),
(26, 'Hiroi Kikuri', '2023-05-14 14:44:50', 'WFH', ''),
(27, 'Arima Kana', '2023-05-18 16:12:14', 'WFH', ''),
(29, 'Ichiji Nijika', '2023-05-23 18:48:56', 'WFH', ''),
(30, 'Gotou Hitori', '2023-05-25 16:44:41', 'WFH', ''),
(32, 'Ichiji Nijika', '2023-05-25 08:00:00', 'Evia', ''),
(33, 'Kita Ikuyo', '2023-06-01 01:55:11', 'WFH', ''),
(34, 'Gotou Hitori', '2023-06-01 13:14:45', 'WFH', ''),
(35, 'Gotou Hitori', '2023-06-01 13:55:12', 'WFH', '/timein_photos/647832c01fe9a_Screenshot_20230105_222056.png'),
(36, 'Gotou Hitori', '2023-06-01 14:03:48', 'Imus', 'C:xampphtdocsAttendance-Management-System	imein_photos647834c408af2_Screenshot_20230105_222118.png'),
(37, 'Gotou Hitori', '2023-06-01 14:06:08', 'WFH', 'timein_photos/64783550755b1_FB_IMG_1672429769387.jpg'),
(38, 'Gotou Hitori', '2023-06-01 14:11:54', 'WFH', 'timein_photos/347292536_819865018982443_8719438044339798454_n.jpg'),
(39, 'Gotou Hitori', '2023-06-01 14:15:03', 'Evia', 'timein_photos/347445819_1407741353394391_2613097734876580622_n.jpg'),
(40, 'Ichiji Nijika', '2023-06-01 14:16:01', 'WFH', 'timein_photos/104730590_p0.png');

-- --------------------------------------------------------

--
-- Table structure for table `time_out`
--

CREATE TABLE `time_out` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `overtime` int(2) NOT NULL,
  `hours` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_out`
--

INSERT INTO `time_out` (`id`, `name`, `datetime`, `overtime`, `hours`) VALUES
(3, 'Kita Ikuyo', '2023-05-05 17:57:24', 0, 0),
(4, 'Kita Ikuyo', '2023-05-05 17:57:53', 3, 0),
(5, 'Kita Ikuyo', '2023-05-05 17:59:32', 3, 0),
(6, 'Kita Ikuyo', '2023-05-05 18:07:37', 0, 0),
(7, 'Ichiji Nijika', '2023-05-05 18:17:23', 0, 0),
(8, 'Ichiji Nijika', '2023-05-05 18:34:44', 3, 0),
(9, 'Ichiji Nijika', '2023-05-05 18:35:19', 4, 4),
(10, 'Ichiji Nijika', '2023-05-05 18:57:28', 0, 1),
(11, 'Gotou Hitori', '2023-05-07 00:21:11', 0, 12),
(12, 'Ichiji Nijika', '2023-05-07 00:22:46', 0, 11),
(13, 'Yamada Ryo', '2023-05-07 00:23:17', 4, 15),
(14, 'Kita Ikuyo', '2023-05-07 00:23:40', 2, 13),
(15, 'Gotou Hitori', '2023-05-08 15:18:25', 0, 25),
(16, 'Yamada Ryo', '2023-05-08 15:21:14', 4, 29),
(17, 'Ichiji Nijika', '2023-05-08 15:23:31', 2, 27),
(18, 'Kita Ikuyo', '2023-05-08 15:25:06', 1, 26),
(19, 'Gotou Hitori', '2023-05-08 17:45:51', 1, 2),
(20, 'Ichiji Nijika', '2023-05-08 17:46:26', 0, 1),
(21, 'Yamada Ryo', '2023-05-08 17:46:47', 0, 1),
(22, 'Kita Ikuyo', '2023-05-08 17:47:06', 4, 5),
(23, 'Otogi Aruto', '2023-05-08 17:47:22', 4, 4),
(24, 'Otogi Aruto', '2023-05-14 13:50:05', 0, -1),
(25, 'Gotou Hitori', '2023-05-14 17:12:05', 0, 2),
(26, 'Ichiji Nijika', '2023-05-14 17:13:33', 0, 2),
(27, 'Yamada Ryo', '2023-05-14 17:14:00', 4, 6),
(28, 'Kita Ikuyo', '2023-05-14 17:14:15', 1, 3),
(29, 'Hiroi Kikuri', '2023-05-14 17:14:29', 0, 1),
(31, 'Ichiji Nijika', '2023-05-23 18:49:04', 0, -1),
(36, 'Gotou Hitori', '2023-05-25 17:00:00', 0, -1),
(37, 'Ichiji Nijika', '2023-05-25 17:00:00', 0, 8),
(38, 'Kita Ikuyo', '2023-06-01 01:55:16', 0, -1),
(39, 'Kita Ikuyo', '2023-06-01 02:07:12', 0, -1),
(40, 'Kita Ikuyo', '2023-06-01 02:08:38', 0, -1),
(41, 'Gotou Hitori', '2023-06-01 13:14:54', 0, -1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` enum('admin','regular') NOT NULL DEFAULT 'regular',
  `position` enum('intern','employee') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `role`, `position`) VALUES
(1, 'hr@csk.com', 'hradmin', 'Ichiji Seika', 'admin', 'employee'),
(2, 'bocchi@csk.com', 'guitarhero', 'Gotou Hitori', 'regular', 'intern'),
(3, 'nijika@csk.com', 'dorito', 'Ichiji Nijika', 'regular', 'intern'),
(4, 'ryo@csk.com', 'bassman', 'Yamada Ryo', 'regular', 'intern'),
(5, 'ikuyo@csk.com', 'kitakita', 'Kita Ikuyo', 'regular', 'intern'),
(6, 'pa_san@csk.com', 'konnichiwa', 'Otogi Aruto', 'regular', 'employee'),
(7, 'kikuri@csk.com', 'yamahahaha', 'Hiroi Kikuri', 'regular', 'intern'),
(8, 'arimakana@csk.com', 'bakingsoda', 'Arima Kana', 'regular', 'intern'),
(9, 'ichigopro@csk.com', 'strawberry', 'Saito Miyako', 'admin', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emp_info`
--
ALTER TABLE `emp_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `int_info`
--
ALTER TABLE `int_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notices`
--
ALTER TABLE `notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks_activities`
--
ALTER TABLE `tasks_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_in`
--
ALTER TABLE `time_in`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_out`
--
ALTER TABLE `time_out`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emp_info`
--
ALTER TABLE `emp_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `int_info`
--
ALTER TABLE `int_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notices`
--
ALTER TABLE `notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks_activities`
--
ALTER TABLE `tasks_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `time_in`
--
ALTER TABLE `time_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `time_out`
--
ALTER TABLE `time_out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

