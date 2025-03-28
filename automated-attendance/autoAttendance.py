from deepface import DeepFace
import cv2
import numpy as np
import mysql.connector
import datetime as dt
import os
import time

def load_student_images(path):
    print("Loading student database...")
    students = {}
    myList = os.listdir(path)
    for student_img in myList:
        name = os.path.splitext(student_img)[0]
        img_path = f'{path}/{student_img}'
        students[name] = img_path
    print(f"Database loaded successfully with {len(students)} students")
    return students

def get_active_session_term():
    connection = mysql.connector.connect(
        host='localhost',
        database='attendancemsystem',
        user='root',
        password=''
    )
    cursor = connection.cursor()
    
    cursor.execute("SELECT Id FROM tblsessionterm WHERE isActive = '1'")
    result = cursor.fetchone()
    
    connection.close()
    
    if result:
        return result[0]
    return None

def markattendance(student_name):
    connection = mysql.connector.connect(
        host='localhost',
        database='attendancemsystem',
        user='root',
        password=''
    )
    cursor = connection.cursor()
    
    now = dt.datetime.now()
    current_date = now.date()
    date_taken = current_date.strftime("%Y-%m-%d")
    
    # Get active session term
    session_term_id = get_active_session_term()
    if not session_term_id:
        print("No active session term found")
        connection.close()
        return
    
    # Get student details
    cursor.execute("""
        SELECT s.Id, s.admissionNumber, s.classId, ca.Id 
        FROM tblstudents s
        JOIN tblclassarms ca ON s.classArmId = ca.Id
        WHERE s.firstName = %s OR s.lastName = %s
    """, (student_name, student_name))
    
    student_data = cursor.fetchone()
    
    if student_data:
        student_id = student_data[0]
        admission_no = student_data[1]
        class_id = student_data[2]
        class_arm_id = student_data[3]
        
        # Check if attendance record exists for today
        cursor.execute("""
            SELECT * FROM tblattendance 
            WHERE admissionNo = %s 
            AND dateTimeTaken = %s
        """, (admission_no, date_taken))
        
        attendance_record = cursor.fetchone()
        
        if attendance_record:
            # Update existing record
            cursor.execute("""
                UPDATE tblattendance 
                SET status = '1' 
                WHERE admissionNo = %s 
                AND dateTimeTaken = %s
            """, (admission_no, date_taken))
            print(f"Updated attendance for {student_name} (Admission #: {admission_no})")
        else:
            # Create new attendance record if it doesn't exist
            cursor.execute("""
                INSERT INTO tblattendance (admissionNo, classId, classArmId, sessionTermId, status, dateTimeTaken) 
                VALUES (%s, %s, %s, %s, '1', %s)
            """, (admission_no, class_id, class_arm_id, session_term_id, date_taken))
            print(f"New attendance record created for {student_name} (Admission #: {admission_no})")
            
    else:
        print(f"Student {student_name} not found in database")
            
    connection.commit()
    connection.close()

def recognize_faces(frame, students, recognized_students, cooldown_time):
    current_time = time.time()
    
    try:
        detected_faces = DeepFace.extract_faces(frame, detector_backend='opencv')
        
        for face in detected_faces:
            face_img = face['face']
            max_similarity = 0
            matched_student = None
            
            for student_name, student_img_path in students.items():
                # Skip if student was recently recognized (cooldown)
                if student_name in recognized_students and current_time - recognized_students[student_name] < cooldown_time:
                    continue
                    
                try:
                    result = DeepFace.verify(
                        face_img,
                        student_img_path,
                        model_name="VGG-Face",
                        distance_metric="cosine",
                        detector_backend='skip'
                    )
                    
                    if result["verified"]:
                        similarity = 1 - result["distance"]
                        if similarity > max_similarity:
                            max_similarity = similarity
                            matched_student = student_name
                            
                except Exception as e:
                    continue
                    
            if matched_student and max_similarity > 0.7:  # Increased threshold for better accuracy
                y, x, h, w = face['facial_area'].values()
                cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
                cv2.putText(frame, f"{matched_student} ({max_similarity:.2f})", (x, y-10), 
                           cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)
                
                # Only mark attendance if not in cooldown period
                if matched_student not in recognized_students or current_time - recognized_students[matched_student] >= cooldown_time:
                    markattendance(matched_student)
                    recognized_students[matched_student] = current_time
                    
    except Exception as e:
        print(f"Error in face recognition: {e}")
            
    return frame

def initialize_system():
    print("Initializing Smart Attendance Management System...")
    
    # Check database connection
    try:
        connection = mysql.connector.connect(
            host='localhost',
            database='attendancemsystem',
            user='root',
            password=''
        )
        connection.close()
        print("Database connection successful")
    except Exception as e:
        print(f"Database connection failed: {e}")
        return False
    
    # Check if images directory exists
    if not os.path.exists("images"):
        print("Error: 'images' directory not found")
        print("Please create an 'images' directory with student photos")
        print("Format: firstname_lastname.jpg")
        return False
        
    # Check if DeepFace is installed
    try:
        import deepface
        print(f"DeepFace version: {deepface.__version__}")
    except:
        print("Error: DeepFace not installed")
        print("Please install DeepFace: pip install deepface")
        return False
        
    return True

# Main execution
def main():
    if not initialize_system():
        print("System initialization failed. Exiting...")
        return
        
    students = load_student_images("images")
    if not students:
        print("No student images found. Please add images to the 'images' directory.")
        return
        
    print("System Ready - Starting Camera Feed")
    print("Press 'q' to quit")

    # Dictionary to track when students were last recognized (for cooldown)
    recognized_students = {}
    cooldown_time = 60  # Seconds before the same student can be marked again
    
    cap = cv2.VideoCapture(0)
    cap.set(3, 640)  # Width
    cap.set(4, 480)  # Height

    while True:
        success, frame = cap.read()
        if success:
            # Add timestamp to frame
            timestamp = dt.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
            cv2.putText(frame, timestamp, (10, 30), 
                       cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 255), 2)
                       
            # Process every other frame to improve performance
            frame_with_faces = recognize_faces(frame, students, recognized_students, cooldown_time)
            
            # Display active student count
            active_count = sum(1 for t in recognized_students.values() if time.time() - t < cooldown_time)
            cv2.putText(frame, f"Active Students: {active_count}", (10, 60), 
                       cv2.FONT_HERSHEY_SIMPLEX, 0.7, (0, 0, 255), 2)
                       
            cv2.imshow("SMART-SAMS School Attendance System", frame_with_faces)
            
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    cap.release()
    cv2.destroyAllWindows()

if __name__ == "__main__":
    main()
