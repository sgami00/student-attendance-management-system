//
// Example logic workflow for your scanner hardware/frontend script:
let scannedData = "1|482910"; // Data unpacked from the image matrix
let [classId, studentIdNumber] = scannedData.split('|');

// Fire payload payload directly over to routes/api.php
fetch('/api/attendance', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        school_class_id: classId,
        student_id_number: studentIdNumber,
        attendance_date: new Date().toISOString().split('T')[0], // YYYY-MM-DD
        status: 'present' // Or fallback default
    })
});