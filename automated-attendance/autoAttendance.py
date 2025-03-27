from deepface import DeepFace
import cv2
import numpy as np
import mysql.connector
import datetime as dt
import os

def load_student_images(path):
    print("Loading student database...")
    students = {}
    myList = os.listdir(path)
    for student_img in myList:
        name = os.path.splitext(student_img)[0]
        img_path = f'{path}/{student_img}'
        students[name] = img_path
    print("Database loaded successfully")
    return students

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
    
    cursor.execute("SELECT Id, classId FROM tblstudents WHERE name = %s", (student_name,))
    student_data = cursor.fetchone()
    
    if student_data:
        student_id = student_data[0]
        class_id = student_data[1]
        
        cursor.execute("""
            SELECT Id FROM tblattendance 
            WHERE studentId = %s 
            AND attendanceDate = %s
        """, (student_id, current_date))
        
        if not cursor.fetchone():
            query = """
                INSERT INTO tblattendance (studentId, attendanceDate, status) 
                VALUES (%s, %s, 'Present')
            """
            cursor.execute(query, (student_id, current_date))
            
            cursor.execute("""
                UPDATE atdetails 
                SET presentboys = presentboys + 1 
                WHERE className = (SELECT className FROM tblclass WHERE Id = %s)
                AND date = %s
            """, (class_id, current_date))
            
            print(f"Attendance marked for {student_name} on {current_date}")
        else:
            print(f"Attendance already marked for {student_name}")
            
    connection.commit()
    connection.close()

def recognize_faces(frame, students):
    detected_faces = DeepFace.extract_faces(frame, detector_backend='opencv')
    
    for face in detected_faces:
        face_img = face['face']
        max_similarity = 0
        matched_student = None
        
        for student_name, student_img_path in students.items():
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
                
        if matched_student and max_similarity > 0.6:  # Threshold for matching
            y, x, h, w = face['facial_area'].values()
            cv2.rectangle(frame, (x, y), (x+w, y+h), (0, 255, 0), 2)
            cv2.putText(frame, matched_student, (x, y-10), 
                       cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)
            markattendance(matched_student)
            
    return frame

# Main execution
students = load_student_images("images")
print("System Ready - Starting Camera Feed")

cap = cv2.VideoCapture(0)
cap.set(3, 640)  # Width
cap.set(4, 480)  # Height

while True:
    success, frame = cap.read()
    if success:
        frame_with_faces = recognize_faces(frame, students)
        cv2.imshow("SMART-SAMS SMART-School Attendance System", frame_with_faces)
        
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
