<?php
require_once 'config.php';

class TimetableGenerator {
    private $conn;
    private $timeSlots;
    private $days;

    public function __construct($conn, $timeSlots, $days) {
        $this->conn = $conn;
        $this->timeSlots = $timeSlots;
        $this->days = $days;
    }

    public function generateTimetable() {
        // Clear existing timetable
        $this->conn->query("TRUNCATE TABLE timetable");

        // Get all classes and their assigned lessons
        $classes = $this->getClassesWithLessons();
        $teachers = $this->getTeachersAndLessons();

        // Track teacher availability
        $teacherSchedule = [];

        foreach ($this->days as $day) {
            foreach ($this->timeSlots as $slot) {
                $availableTeachers = array_keys($teachers);
                
                if (isset($teacherSchedule[$day][$slot])) {
                    $availableTeachers = array_diff($availableTeachers, $teacherSchedule[$day][$slot]);
                }

                shuffle($classes); // Randomize class order to avoid bias

                foreach ($classes as $class) {
                    $classId = $class['Id'];
                    $assignedLessonIds = array_column($class['lessons'], 'lesson_id');

                    if (empty($assignedLessonIds)) continue; // Skip if no lessons assigned

                    // Filter teachers who teach lessons assigned to this class
                    $eligibleTeachers = array_filter($availableTeachers, function($teacherId) use ($teachers, $assignedLessonIds) {
                        return !empty(array_intersect($teachers[$teacherId]['lessons'], $assignedLessonIds));
                    });

                    if (empty($eligibleTeachers)) continue;

                    $teacherId = array_shift($eligibleTeachers);
                    $teacherLessons = array_intersect($teachers[$teacherId]['lessons'], $assignedLessonIds);
                    $lessonId = $teacherLessons[array_rand($teacherLessons)];

                    // Record teacher schedule
                    if (!isset($teacherSchedule[$day][$slot])) {
                        $teacherSchedule[$day][$slot] = [];
                    }
                    $teacherSchedule[$day][$slot][] = $teacherId;

                    // Insert into timetable
                    $stmt = $this->conn->prepare(
                        "INSERT INTO timetable (class_id, teacher_id, lesson_id, day, time_slot) 
                        VALUES (?, ?, ?, ?, ?)"
                    );
                    $stmt->bind_param("iiiss", $classId, $teacherId, $lessonId, $day, $slot);
                    $stmt->execute();

                    // Remove teacher from available pool for this slot
                    $availableTeachers = array_diff($availableTeachers, [$teacherId]);
                }
            }
        }
        return true;
    }

    public function getClassTimetable($classId) {
        $stmt = $this->conn->prepare(
            "SELECT t.*, tch.name AS teacher_name, l.lesson_title 
            FROM timetable t 
            JOIN tblteachers tch ON t.teacher_id = tch.id 
            JOIN lessons l ON t.lesson_id = l.id 
            WHERE t.class_id = ? 
            ORDER BY FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday'), 
            FIELD(time_slot, '08:00-09:00','09:00-10:00','10:00-11:00','11:00-12:00','13:00-14:00','14:00-15:00','15:00-16:00')"
        );
        $stmt->bind_param("i", $classId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getClassesWithLessons() {
        $result = $this->conn->query(
            "SELECT c.Id, c.className, cl.lesson_id, l.lesson_title 
            FROM tblclass c 
            LEFT JOIN class_lessons cl ON c.Id = cl.class_id 
            LEFT JOIN lessons l ON cl.lesson_id = l.id 
            WHERE c.school = 'ETS'"
        );
        
        $classes = [];
        while ($row = $result->fetch_assoc()) {
            if (!isset($classes[$row['Id']])) {
                $classes[$row['Id']] = [
                    'Id' => $row['Id'],
                    'className' => $row['className'],
                    'lessons' => []
                ];
            }
            if ($row['lesson_id']) {
                $classes[$row['Id']]['lessons'][] = [
                    'lesson_id' => $row['lesson_id'],
                    'lesson_title' => $row['lesson_title']
                ];
            }
        }
        return array_values($classes);
    }

    private function getTeachersAndLessons() {
        $result = $this->conn->query(
            "SELECT t.id, t.name, l.id AS lesson_id, l.lesson_title 
            FROM tblteachers t 
            LEFT JOIN lessons l ON t.id = l.teacher_id 
            WHERE t.school = 'ETS'"
        );
        
        $teachers = [];
        while ($row = $result->fetch_assoc()) {
            if (!isset($teachers[$row['id']])) {
                $teachers[$row['id']] = [
                    'name' => $row['name'],
                    'lessons' => []
                ];
            }
            if ($row['lesson_id']) {
                $teachers[$row['id']]['lessons'][] = $row['lesson_id'];
            }
        }
        return $teachers;
    }

    public function updateTimetableEntry($id, $newTimeSlot, $newDay) {
        $stmt = $this->conn->prepare(
            "SELECT teacher_id, class_id, lesson_id FROM timetable WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $entry = $stmt->get_result()->fetch_assoc();
        
        $teacherId = $entry['teacher_id'];
        $classId = $entry['class_id'];
        $lessonId = $entry['lesson_id'];

        // Verify lesson is still assigned to class
        $checkLesson = $this->conn->prepare(
            "SELECT COUNT(*) as count FROM class_lessons WHERE class_id = ? AND lesson_id = ?"
        );
        $checkLesson->bind_param("ii", $classId, $lessonId);
        $checkLesson->execute();
        if ($checkLesson->get_result()->fetch_assoc()['count'] == 0) {
            return false; // Lesson no longer assigned to class
        }

        // Check for teacher conflict
        $conflictCheck = $this->conn->prepare(
            "SELECT COUNT(*) as count FROM timetable 
            WHERE teacher_id = ? AND day = ? AND time_slot = ? AND id != ?"
        );
        $conflictCheck->bind_param("issi", $teacherId, $newDay, $newTimeSlot, $id);
        $conflictCheck->execute();
        $conflict = $conflictCheck->get_result()->fetch_assoc()['count'];

        if ($conflict == 0) {
            $stmt = $this->conn->prepare(
                "UPDATE timetable SET time_slot = ?, day = ? WHERE id = ?"
            );
            $stmt->bind_param("ssi", $newTimeSlot, $newDay, $id);
            return $stmt->execute();
        }
        return false; // Conflict detected
    }
}
?>