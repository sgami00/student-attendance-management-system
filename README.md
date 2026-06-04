# Student Attendance Management System

A web-based attendance management system built with Laravel 13, designed for teachers to manage classes, students, and attendance records. Includes a REST API for integration with other systems.

---

## Features

- Teacher authentication (login/logout)
- Student portal (view personal attendance records)
- Class management (create, view, update, delete)
- Student enrollment per class
- Attendance recording with status: `present`, `absent`, `late`
- Attendance analytics and logs
- REST API with full CRUD for classes and attendance
- QR code support

---

## Tech Stack

- **Backend:** Laravel 13 (PHP 8.4)
- **Frontend:** Blade + Tailwind CSS
- **Database:** MySQL
- **API:** RESTful JSON API
- **Auth:** Laravel session-based auth + Sanctum

---

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL

---

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/sgami00/student-attendance-management-system.git
cd student-attendance-management-system

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_db
DB_USERNAME=root
DB_PASSWORD=

# 7. Run migrations and seeders
php artisan migrate --seed

# 8. Build frontend assets
npm run build

# 9. Start the server
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

---

## REST API

Base URL: `http://127.0.0.1:8000/api`

All API requests must include these headers:
```
Accept: application/json
Content-Type: application/json
```

### Classes

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/classes` | Get all classes |
| POST | `/api/classes` | Create a class |
| GET | `/api/classes/{id}` | Get a single class |
| PUT | `/api/classes/{id}` | Update a class |
| DELETE | `/api/classes/{id}` | Delete a class |
| POST | `/api/classes/{id}/students` | Add a student to a class |
| GET | `/api/classes/{id}/students` | Get all students in a class |

**Create a class:**
```json
POST /api/classes
{
  "name": "Math 101",
  "code": "MATH101",
  "teacher_id": 1
}
```

**Add a student to a class:**
```json
POST /api/classes/{id}/students
{
  "student_id_number": "2024-0001",
  "name": "Juan Dela Cruz",
  "email": "juan@example.com"
}
```

### Attendance

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/attendance` | Get all attendance records |
| POST | `/api/attendance` | Record attendance (auto-creates student if new) |
| PUT | `/api/attendance/{id}` | Update attendance status |
| DELETE | `/api/attendance/{id}` | Delete an attendance record |
| GET | `/api/attendance/students` | Get all students in the system |
| GET | `/api/attendance/students?class_id=1` | Get students in a specific class |

**Record attendance (auto-creates and enrolls new student):**
```json
POST /api/attendance
{
  "school_class_id": 1,
  "student_id_number": "2024-0001",
  "name": "Juan Dela Cruz",
  "email": "juan@example.com",
  "attendance_date": "2026-06-04",
  "status": "present"
}
```

Status options: `present`, `absent`, `late`

**Optional filters for GET /api/attendance:**
```
?school_class_id=1
?attendance_date=2026-06-04
?status=present
?student_name=Juan
?student_id_number=2024-0001
```

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AttendanceApiController.php
│   │   │   └── SchoolClassApiController.php
│   │   ├── AuthController.php
│   │   ├── AttendanceController.php
│   │   ├── SchoolClassController.php
│   │   └── StudentAuthController.php
│   └── Models/
│       ├── Attendance.php
│       ├── SchoolClass.php
│       ├── Student.php
│       └── User.php
routes/
├── api.php
└── web.php
```

---

## License

This project is open-sourced for academic purposes.