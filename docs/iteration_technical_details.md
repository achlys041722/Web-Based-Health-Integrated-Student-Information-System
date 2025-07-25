# Technical Implementation Details for Iterative Development

## Code Structure Evolution Through Iterations

### File Organization Strategy

```
project_root/
├── docs/                          # Created in this documentation phase
│   ├── iterative_model_documentation.md
│   └── iteration_technical_details.md
├── src/                           # Business logic modules
│   ├── auth/                      # Iteration 1: Authentication
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── session_manager.php
│   │   └── security_functions.php
│   ├── common/                    # Shared utilities
│   │   ├── database.php
│   │   ├── config.php
│   │   └── helpers.php
│   ├── principal/                 # Iteration 2: Principal module
│   │   ├── dashboard.php
│   │   ├── teacher_approval.php
│   │   ├── school_management.php
│   │   └── nurse_requests.php
│   ├── teacher/                   # Iteration 3: Teacher module
│   │   ├── dashboard.php
│   │   ├── student_management.php
│   │   ├── class_admin.php
│   │   └── health_overview.php
│   └── nurse/                     # Iteration 4: Nurse module
│       ├── dashboard.php
│       ├── health_records.php
│       ├── school_assignments.php
│       └── analytics.php
├── templates/                     # Presentation layer
│   ├── login.php                  # Iteration 1
│   ├── register/                  # Iteration 1
│   ├── principal/                 # Iteration 2
│   ├── teacher/                   # Iteration 3
│   └── nurse/                     # Iteration 4
├── sql/                          # Database management
│   ├── initial_schema.sql         # Existing
│   ├── iteration_1_updates.sql    # Auth enhancements
│   ├── iteration_2_updates.sql    # Principal features
│   ├── iteration_3_updates.sql    # Teacher & student features
│   ├── iteration_4_updates.sql    # Health records
│   └── iteration_5_updates.sql    # Advanced features
├── assets/                       # Static resources
│   ├── css/
│   ├── js/
│   └── images/
├── tests/                        # Testing framework
│   ├── unit/
│   ├── integration/
│   └── acceptance/
└── config/                       # Configuration files
    ├── database.php
    ├── security.php
    └── app_config.php
```

## Iteration 1: Foundation & Authentication - Technical Details

### Database Schema Updates
```sql
-- iteration_1_updates.sql
-- Enhance existing tables with authentication fields

-- Add authentication fields to existing tables
ALTER TABLE principals ADD COLUMN password_hash VARCHAR(255) AFTER email;
ALTER TABLE principals ADD COLUMN salt VARCHAR(255) AFTER password_hash;
ALTER TABLE principals ADD COLUMN last_login TIMESTAMP NULL AFTER created_at;
ALTER TABLE principals ADD COLUMN status ENUM('active', 'inactive', 'suspended') DEFAULT 'active';

ALTER TABLE teachers ADD COLUMN password_hash VARCHAR(255) AFTER email;
ALTER TABLE teachers ADD COLUMN salt VARCHAR(255) AFTER password_hash;
ALTER TABLE teachers ADD COLUMN last_login TIMESTAMP NULL AFTER created_at;

-- Nurses table already has password field, but enhance it
ALTER TABLE nurses MODIFY COLUMN password VARCHAR(255) NOT NULL;
ALTER TABLE nurses ADD COLUMN salt VARCHAR(255) AFTER password;
ALTER TABLE nurses ADD COLUMN last_login TIMESTAMP NULL AFTER created_at;
ALTER TABLE nurses ADD COLUMN status ENUM('active', 'inactive', 'suspended') DEFAULT 'active';

-- Create sessions table for session management
CREATE TABLE user_sessions (
    session_id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    user_type ENUM('principal', 'teacher', 'nurse') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    is_active BOOLEAN DEFAULT TRUE
);

-- Create activity log table
CREATE TABLE user_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_type ENUM('principal', 'teacher', 'nurse') NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Core Authentication Classes

```php
// src/common/database.php
<?php
class DatabaseManager {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $config = require_once '../config/database.php';
        $this->connection = new PDO(
            "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

// src/auth/security_functions.php
<?php
class SecurityManager {
    public static function hashPassword($password) {
        $salt = bin2hex(random_bytes(32));
        $hash = hash('sha256', $password . $salt);
        return ['hash' => $hash, 'salt' => $salt];
    }
    
    public static function verifyPassword($password, $hash, $salt) {
        return hash_equals($hash, hash('sha256', $password . $salt));
    }
    
    public static function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public static function generateSessionToken() {
        return bin2hex(random_bytes(64));
    }
}

// src/auth/session_manager.php
<?php
class SessionManager {
    private $db;
    
    public function __construct() {
        $this->db = DatabaseManager::getInstance()->getConnection();
    }
    
    public function createSession($userId, $userType) {
        $sessionId = SecurityManager::generateSessionToken();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $stmt = $this->db->prepare("
            INSERT INTO user_sessions (session_id, user_id, user_type, expires_at, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $sessionId,
            $userId,
            $userType,
            $expiresAt,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
        
        $_SESSION['session_id'] = $sessionId;
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_type'] = $userType;
        
        return $sessionId;
    }
    
    public function validateSession($sessionId) {
        $stmt = $this->db->prepare("
            SELECT user_id, user_type FROM user_sessions 
            WHERE session_id = ? AND expires_at > NOW() AND is_active = TRUE
        ");
        
        $stmt->execute([$sessionId]);
        return $stmt->fetch();
    }
    
    public function destroySession($sessionId) {
        $stmt = $this->db->prepare("
            UPDATE user_sessions SET is_active = FALSE WHERE session_id = ?
        ");
        $stmt->execute([$sessionId]);
        
        session_unset();
        session_destroy();
    }
}
```

## Iteration 2: Principal Module - Technical Implementation

### Database Schema Updates
```sql
-- iteration_2_updates.sql

-- Enhance schools table
ALTER TABLE schools ADD COLUMN address VARCHAR(255) AFTER name;
ALTER TABLE schools ADD COLUMN contact_phone VARCHAR(20) AFTER address;
ALTER TABLE schools ADD COLUMN contact_email VARCHAR(100) AFTER contact_phone;
ALTER TABLE schools ADD COLUMN established_year YEAR AFTER contact_email;
ALTER TABLE schools ADD COLUMN student_capacity INT AFTER established_year;
ALTER TABLE schools ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active';

-- Teacher approval workflow enhancements
ALTER TABLE teachers ADD COLUMN approval_date TIMESTAMP NULL AFTER status;
ALTER TABLE teachers ADD COLUMN approved_by INT NULL AFTER approval_date;
ALTER TABLE teachers ADD COLUMN rejection_reason TEXT NULL AFTER approved_by;

-- Add foreign key constraints
ALTER TABLE teachers ADD CONSTRAINT fk_teacher_principal 
    FOREIGN KEY (approved_by) REFERENCES principals(id);

-- School-principal relationship
ALTER TABLE schools ADD CONSTRAINT fk_school_principal 
    FOREIGN KEY (principal_id) REFERENCES principals(id);

-- Nurse request enhancements
ALTER TABLE nurse_requests ADD COLUMN message TEXT AFTER nurse_email;
ALTER TABLE nurse_requests ADD COLUMN priority ENUM('low', 'medium', 'high') DEFAULT 'medium';
```

### Principal Dashboard Implementation
```php
// src/principal/dashboard.php
<?php
class PrincipalDashboard {
    private $db;
    private $principalId;
    
    public function __construct($principalId) {
        $this->db = DatabaseManager::getInstance()->getConnection();
        $this->principalId = $principalId;
    }
    
    public function getDashboardData() {
        return [
            'school_info' => $this->getSchoolInfo(),
            'teacher_stats' => $this->getTeacherStats(),
            'student_stats' => $this->getStudentStats(),
            'pending_approvals' => $this->getPendingApprovals(),
            'nurse_requests' => $this->getNurseRequests()
        ];
    }
    
    private function getSchoolInfo() {
        $stmt = $this->db->prepare("
            SELECT s.*, p.full_name as principal_name 
            FROM schools s 
            JOIN principals p ON s.principal_id = p.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$this->principalId]);
        return $stmt->fetch();
    }
    
    private function getTeacherStats() {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_teachers,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
            FROM teachers t 
            JOIN schools s ON t.principal_email = (
                SELECT email FROM principals WHERE id = ?
            )
        ");
        $stmt->execute([$this->principalId]);
        return $stmt->fetch();
    }
    
    private function getPendingApprovals() {
        $stmt = $this->db->prepare("
            SELECT t.*, s.name as school_name 
            FROM teachers t 
            JOIN schools s ON s.principal_id = ?
            WHERE t.status = 'pending' 
            ORDER BY t.created_at ASC
        ");
        $stmt->execute([$this->principalId]);
        return $stmt->fetchAll();
    }
}

// src/principal/teacher_approval.php
class TeacherApprovalManager {
    private $db;
    
    public function __construct() {
        $this->db = DatabaseManager::getInstance()->getConnection();
    }
    
    public function approveTeacher($teacherId, $principalId) {
        $stmt = $this->db->prepare("
            UPDATE teachers 
            SET status = 'approved', 
                approval_date = NOW(), 
                approved_by = ? 
            WHERE id = ?
        ");
        
        return $stmt->execute([$principalId, $teacherId]);
    }
    
    public function rejectTeacher($teacherId, $principalId, $reason = null) {
        $stmt = $this->db->prepare("
            UPDATE teachers 
            SET status = 'rejected', 
                approval_date = NOW(), 
                approved_by = ?, 
                rejection_reason = ? 
            WHERE id = ?
        ");
        
        return $stmt->execute([$principalId, $reason, $teacherId]);
    }
}
```

## Iteration 3: Teacher Module - Technical Implementation

### Database Schema Updates
```sql
-- iteration_3_updates.sql

-- Students table enhancements (already exists but needs relationships)
ALTER TABLE students ADD COLUMN lrn VARCHAR(20) UNIQUE AFTER last_name;
ALTER TABLE students ADD COLUMN address VARCHAR(255) AFTER gender;
ALTER TABLE students ADD COLUMN guardian_name VARCHAR(100) AFTER address;
ALTER TABLE students ADD COLUMN guardian_contact VARCHAR(100) AFTER guardian_name;
ALTER TABLE students ADD COLUMN guardian_email VARCHAR(100) AFTER guardian_contact;
ALTER TABLE students ADD COLUMN enrollment_date DATE AFTER guardian_email;
ALTER TABLE students ADD COLUMN status ENUM('active', 'inactive', 'transferred') DEFAULT 'active';

-- Class-teacher assignments
ALTER TABLE classes ADD CONSTRAINT fk_class_teacher 
    FOREIGN KEY (teacher_id) REFERENCES teachers(id);

ALTER TABLE classes ADD CONSTRAINT fk_class_school 
    FOREIGN KEY (school_id) REFERENCES schools(id);

-- Student-class relationships (already exists via class_id in students)
ALTER TABLE students ADD CONSTRAINT fk_student_class 
    FOREIGN KEY (class_id) REFERENCES classes(id);

-- Student attendance tracking
CREATE TABLE student_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,
    notes TEXT,
    recorded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (recorded_by) REFERENCES teachers(id),
    UNIQUE KEY unique_student_date (student_id, attendance_date)
);
```

### Teacher Module Implementation
```php
// src/teacher/student_management.php
<?php
class StudentManager {
    private $db;
    private $teacherId;
    
    public function __construct($teacherId) {
        $this->db = DatabaseManager::getInstance()->getConnection();
        $this->teacherId = $teacherId;
    }
    
    public function getMyStudents() {
        $stmt = $this->db->prepare("
            SELECT s.*, c.grade_level, 
                   hr.height_cm, hr.weight_kg, hr.bmi, hr.record_date
            FROM students s 
            JOIN classes c ON s.class_id = c.id 
            LEFT JOIN health_records hr ON s.id = hr.student_id 
                AND hr.record_date = (
                    SELECT MAX(record_date) FROM health_records WHERE student_id = s.id
                )
            WHERE c.teacher_id = ? AND s.status = 'active'
            ORDER BY s.last_name, s.first_name
        ");
        
        $stmt->execute([$this->teacherId]);
        return $stmt->fetchAll();
    }
    
    public function addStudent($studentData) {
        $this->db->beginTransaction();
        
        try {
            // Get teacher's class
            $classStmt = $this->db->prepare("
                SELECT id FROM classes WHERE teacher_id = ? LIMIT 1
            ");
            $classStmt->execute([$this->teacherId]);
            $class = $classStmt->fetch();
            
            if (!$class) {
                throw new Exception("Teacher not assigned to any class");
            }
            
            $stmt = $this->db->prepare("
                INSERT INTO students (
                    class_id, first_name, last_name, lrn, birthdate, gender, 
                    address, guardian_name, guardian_contact, guardian_email, 
                    enrollment_date, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
            ");
            
            $stmt->execute([
                $class['id'],
                $studentData['first_name'],
                $studentData['last_name'],
                $studentData['lrn'],
                $studentData['birthdate'],
                $studentData['gender'],
                $studentData['address'],
                $studentData['guardian_name'],
                $studentData['guardian_contact'],
                $studentData['guardian_email'],
                $studentData['enrollment_date']
            ]);
            
            $this->db->commit();
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function updateStudent($studentId, $studentData) {
        // Verify teacher owns this student
        if (!$this->verifyStudentOwnership($studentId)) {
            throw new Exception("Unauthorized: Student not in your class");
        }
        
        $stmt = $this->db->prepare("
            UPDATE students 
            SET first_name = ?, last_name = ?, lrn = ?, birthdate = ?, 
                gender = ?, address = ?, guardian_name = ?, guardian_contact = ?, 
                guardian_email = ? 
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $studentData['first_name'],
            $studentData['last_name'],
            $studentData['lrn'],
            $studentData['birthdate'],
            $studentData['gender'],
            $studentData['address'],
            $studentData['guardian_name'],
            $studentData['guardian_contact'],
            $studentData['guardian_email'],
            $studentId
        ]);
    }
    
    private function verifyStudentOwnership($studentId) {
        $stmt = $this->db->prepare("
            SELECT s.id 
            FROM students s 
            JOIN classes c ON s.class_id = c.id 
            WHERE s.id = ? AND c.teacher_id = ?
        ");
        $stmt->execute([$studentId, $this->teacherId]);
        return $stmt->fetch() !== false;
    }
}

// src/teacher/class_admin.php
class ClassAdministration {
    private $db;
    private $teacherId;
    
    public function __construct($teacherId) {
        $this->db = DatabaseManager::getInstance()->getConnection();
        $this->teacherId = $teacherId;
    }
    
    public function getClassInfo() {
        $stmt = $this->db->prepare("
            SELECT c.*, s.name as school_name, t.full_name as teacher_name,
                   COUNT(st.id) as student_count
            FROM classes c 
            JOIN schools s ON c.school_id = s.id 
            JOIN teachers t ON c.teacher_id = t.id 
            LEFT JOIN students st ON c.id = st.class_id AND st.status = 'active'
            WHERE c.teacher_id = ?
            GROUP BY c.id
        ");
        
        $stmt->execute([$this->teacherId]);
        return $stmt->fetch();
    }
    
    public function getClassStatistics() {
        $classInfo = $this->getClassInfo();
        
        if (!$classInfo) {
            return null;
        }
        
        // Get health statistics
        $healthStmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_with_records,
                AVG(hr.bmi) as avg_bmi,
                COUNT(CASE WHEN hr.bmi < 18.5 THEN 1 END) as underweight,
                COUNT(CASE WHEN hr.bmi BETWEEN 18.5 AND 24.9 THEN 1 END) as normal,
                COUNT(CASE WHEN hr.bmi BETWEEN 25 AND 29.9 THEN 1 END) as overweight,
                COUNT(CASE WHEN hr.bmi >= 30 THEN 1 END) as obese
            FROM students s 
            JOIN classes c ON s.class_id = c.id 
            LEFT JOIN health_records hr ON s.id = hr.student_id 
                AND hr.record_date = (
                    SELECT MAX(record_date) FROM health_records WHERE student_id = s.id
                )
            WHERE c.teacher_id = ? AND s.status = 'active'
        ");
        
        $healthStmt->execute([$this->teacherId]);
        $healthStats = $healthStmt->fetch();
        
        return array_merge($classInfo, $healthStats);
    }
}
```

## Iteration 4: Nurse Module & Health Records - Technical Implementation

### Database Schema Updates
```sql
-- iteration_4_updates.sql

-- Health records enhancements
ALTER TABLE health_records ADD COLUMN temperature DECIMAL(4,2) AFTER bmi;
ALTER TABLE health_records ADD COLUMN blood_pressure VARCHAR(20) AFTER temperature;
ALTER TABLE health_records ADD COLUMN pulse_rate INT AFTER blood_pressure;
ALTER TABLE health_records ADD COLUMN notes TEXT AFTER pulse_rate;
ALTER TABLE health_records ADD COLUMN status ENUM('normal', 'needs_attention', 'follow_up') DEFAULT 'normal';

-- Nurse-schools relationship enhancements
ALTER TABLE nurse_schools ADD COLUMN role ENUM('primary', 'backup') DEFAULT 'primary';
ALTER TABLE nurse_schools ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active';

-- Health alerts system
CREATE TABLE health_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    alert_type ENUM('bmi_concern', 'temperature_high', 'blood_pressure', 'follow_up_needed') NOT NULL,
    severity ENUM('low', 'medium', 'high', 'critical') NOT NULL,
    message TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    resolved_by INT NULL,
    status ENUM('active', 'resolved', 'dismissed') DEFAULT 'active',
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (created_by) REFERENCES nurses(id),
    FOREIGN KEY (resolved_by) REFERENCES nurses(id)
);

-- Health record templates for different checkup types
CREATE TABLE health_checkup_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    required_fields JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default templates
INSERT INTO health_checkup_templates (name, description, required_fields) VALUES
('Basic Health Check', 'Standard height, weight, and vital signs', 
 '["height_cm", "weight_kg", "temperature", "blood_pressure", "pulse_rate"]'),
('BMI Monitoring', 'Focus on BMI tracking and nutritional assessment', 
 '["height_cm", "weight_kg", "notes"]'),
('Vital Signs Only', 'Quick vital signs check', 
 '["temperature", "blood_pressure", "pulse_rate"]');
```

### Nurse Module Implementation
```php
// src/nurse/health_records.php
<?php
class HealthRecordsManager {
    private $db;
    private $nurseId;
    
    public function __construct($nurseId) {
        $this->db = DatabaseManager::getInstance()->getConnection();
        $this->nurseId = $nurseId;
    }
    
    public function addHealthRecord($studentId, $recordData) {
        $this->db->beginTransaction();
        
        try {
            // Verify nurse has access to student's school
            if (!$this->verifySchoolAccess($studentId)) {
                throw new Exception("Unauthorized: No access to this student's school");
            }
            
            // Calculate BMI
            $bmi = null;
            if (!empty($recordData['height_cm']) && !empty($recordData['weight_kg'])) {
                $heightM = $recordData['height_cm'] / 100;
                $bmi = round($recordData['weight_kg'] / ($heightM * $heightM), 2);
            }
            
            $stmt = $this->db->prepare("
                INSERT INTO health_records (
                    student_id, height_cm, weight_kg, bmi, temperature, 
                    blood_pressure, pulse_rate, notes, record_date, nurse_id, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $status = $this->determineHealthStatus($recordData, $bmi);
            
            $stmt->execute([
                $studentId,
                $recordData['height_cm'] ?? null,
                $recordData['weight_kg'] ?? null,
                $bmi,
                $recordData['temperature'] ?? null,
                $recordData['blood_pressure'] ?? null,
                $recordData['pulse_rate'] ?? null,
                $recordData['notes'] ?? null,
                $recordData['record_date'] ?? date('Y-m-d'),
                $this->nurseId,
                $status
            ]);
            
            $recordId = $this->db->lastInsertId();
            
            // Check for health alerts
            $this->checkAndCreateAlerts($studentId, $recordData, $bmi);
            
            $this->db->commit();
            return $recordId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function determineHealthStatus($recordData, $bmi) {
        // Temperature check
        if (!empty($recordData['temperature'])) {
            $temp = (float)$recordData['temperature'];
            if ($temp >= 38.0) { // Fever
                return 'needs_attention';
            }
        }
        
        // BMI check
        if ($bmi !== null) {
            if ($bmi < 16 || $bmi > 35) {
                return 'needs_attention';
            } elseif ($bmi < 18.5 || $bmi > 25) {
                return 'follow_up';
            }
        }
        
        return 'normal';
    }
    
    private function checkAndCreateAlerts($studentId, $recordData, $bmi) {
        $alerts = [];
        
        // BMI alerts
        if ($bmi !== null) {
            if ($bmi < 16) {
                $alerts[] = [
                    'type' => 'bmi_concern',
                    'severity' => 'high',
                    'message' => "Severely underweight BMI: {$bmi}"
                ];
            } elseif ($bmi > 35) {
                $alerts[] = [
                    'type' => 'bmi_concern',
                    'severity' => 'high',
                    'message' => "Severely overweight BMI: {$bmi}"
                ];
            } elseif ($bmi < 18.5 || $bmi > 30) {
                $alerts[] = [
                    'type' => 'bmi_concern',
                    'severity' => 'medium',
                    'message' => "BMI outside normal range: {$bmi}"
                ];
            }
        }
        
        // Temperature alerts
        if (!empty($recordData['temperature'])) {
            $temp = (float)$recordData['temperature'];
            if ($temp >= 39.0) {
                $alerts[] = [
                    'type' => 'temperature_high',
                    'severity' => 'critical',
                    'message' => "High fever: {$temp}°C"
                ];
            } elseif ($temp >= 38.0) {
                $alerts[] = [
                    'type' => 'temperature_high',
                    'severity' => 'medium',
                    'message' => "Fever detected: {$temp}°C"
                ];
            }
        }
        
        // Insert alerts
        foreach ($alerts as $alert) {
            $stmt = $this->db->prepare("
                INSERT INTO health_alerts (
                    student_id, alert_type, severity, message, created_by
                ) VALUES (?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $studentId,
                $alert['type'],
                $alert['severity'],
                $alert['message'],
                $this->nurseId
            ]);
        }
    }
    
    private function verifySchoolAccess($studentId) {
        $stmt = $this->db->prepare("
            SELECT s.id 
            FROM students st
            JOIN classes c ON st.class_id = c.id
            JOIN schools s ON c.school_id = s.id
            JOIN nurse_schools ns ON s.id = ns.school_id
            WHERE st.id = ? AND ns.nurse_id = ? AND ns.status = 'active'
        ");
        
        $stmt->execute([$studentId, $this->nurseId]);
        return $stmt->fetch() !== false;
    }
    
    public function getStudentHealthHistory($studentId) {
        if (!$this->verifySchoolAccess($studentId)) {
            throw new Exception("Unauthorized access to student records");
        }
        
        $stmt = $this->db->prepare("
            SELECT hr.*, n.full_name as nurse_name,
                   s.first_name, s.last_name, s.birthdate
            FROM health_records hr
            JOIN nurses n ON hr.nurse_id = n.id
            JOIN students s ON hr.student_id = s.id
            WHERE hr.student_id = ?
            ORDER BY hr.record_date DESC, hr.created_at DESC
        ");
        
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }
}

// src/nurse/analytics.php
class HealthAnalytics {
    private $db;
    private $nurseId;
    
    public function __construct($nurseId) {
        $this->db = DatabaseManager::getInstance()->getConnection();
        $this->nurseId = $nurseId;
    }
    
    public function getSchoolHealthOverview($schoolId) {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(DISTINCT s.id) as total_students,
                COUNT(DISTINCT hr.id) as total_records,
                AVG(hr.bmi) as avg_bmi,
                COUNT(CASE WHEN hr.bmi < 18.5 THEN 1 END) as underweight_count,
                COUNT(CASE WHEN hr.bmi BETWEEN 18.5 AND 24.9 THEN 1 END) as normal_weight_count,
                COUNT(CASE WHEN hr.bmi BETWEEN 25 AND 29.9 THEN 1 END) as overweight_count,
                COUNT(CASE WHEN hr.bmi >= 30 THEN 1 END) as obese_count,
                COUNT(CASE WHEN ha.severity = 'critical' THEN 1 END) as critical_alerts,
                COUNT(CASE WHEN ha.severity = 'high' THEN 1 END) as high_alerts
            FROM students s
            JOIN classes c ON s.class_id = c.id
            LEFT JOIN health_records hr ON s.id = hr.student_id 
                AND hr.record_date = (
                    SELECT MAX(record_date) FROM health_records WHERE student_id = s.id
                )
            LEFT JOIN health_alerts ha ON s.id = ha.student_id AND ha.status = 'active'
            WHERE c.school_id = ? AND s.status = 'active'
        ");
        
        $stmt->execute([$schoolId]);
        return $stmt->fetch();
    }
    
    public function getBMIDistribution($schoolId = null, $gradeLevel = null) {
        $whereClause = "WHERE s.status = 'active'";
        $params = [];
        
        if ($schoolId) {
            $whereClause .= " AND c.school_id = ?";
            $params[] = $schoolId;
        }
        
        if ($gradeLevel) {
            $whereClause .= " AND c.grade_level = ?";
            $params[] = $gradeLevel;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                c.grade_level,
                COUNT(*) as total_students,
                AVG(hr.bmi) as avg_bmi,
                ROUND(COUNT(CASE WHEN hr.bmi < 18.5 THEN 1 END) * 100.0 / COUNT(*), 2) as underweight_percent,
                ROUND(COUNT(CASE WHEN hr.bmi BETWEEN 18.5 AND 24.9 THEN 1 END) * 100.0 / COUNT(*), 2) as normal_percent,
                ROUND(COUNT(CASE WHEN hr.bmi BETWEEN 25 AND 29.9 THEN 1 END) * 100.0 / COUNT(*), 2) as overweight_percent,
                ROUND(COUNT(CASE WHEN hr.bmi >= 30 THEN 1 END) * 100.0 / COUNT(*), 2) as obese_percent
            FROM students s
            JOIN classes c ON s.class_id = c.id
            JOIN health_records hr ON s.id = hr.student_id 
                AND hr.record_date = (
                    SELECT MAX(record_date) FROM health_records WHERE student_id = s.id
                )
            {$whereClause}
            GROUP BY c.grade_level
            ORDER BY c.grade_level
        ");
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getActiveAlerts($schoolId = null) {
        $whereClause = "WHERE ha.status = 'active'";
        $params = [];
        
        if ($schoolId) {
            $whereClause .= " AND c.school_id = ?";
            $params[] = $schoolId;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                ha.*,
                s.first_name,
                s.last_name,
                c.grade_level,
                sch.name as school_name
            FROM health_alerts ha
            JOIN students s ON ha.student_id = s.id
            JOIN classes c ON s.class_id = c.id
            JOIN schools sch ON c.school_id = sch.id
            {$whereClause}
            ORDER BY 
                CASE ha.severity 
                    WHEN 'critical' THEN 1 
                    WHEN 'high' THEN 2 
                    WHEN 'medium' THEN 3 
                    ELSE 4 
                END,
                ha.created_at DESC
        ");
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
```

## Iteration 5: Advanced Features - Technical Implementation

### Database Schema Updates
```sql
-- iteration_5_updates.sql

-- Notifications system
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_id INT NOT NULL,
    recipient_type ENUM('principal', 'teacher', 'nurse') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'alert', 'success') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL
);

-- Report templates
CREATE TABLE report_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    template_type ENUM('health_summary', 'bmi_analysis', 'student_profile', 'school_overview') NOT NULL,
    user_types JSON,
    sql_query TEXT,
    chart_config JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- System logs
CREATE TABLE system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_level ENUM('debug', 'info', 'warning', 'error', 'critical') NOT NULL,
    category VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    context JSON,
    user_id INT,
    user_type ENUM('principal', 'teacher', 'nurse'),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Performance monitoring
CREATE TABLE performance_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint VARCHAR(255) NOT NULL,
    method ENUM('GET', 'POST', 'PUT', 'DELETE') NOT NULL,
    response_time_ms INT NOT NULL,
    memory_usage_mb DECIMAL(8,2),
    query_count INT,
    cache_hits INT DEFAULT 0,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add indexes for better performance
CREATE INDEX idx_health_records_student_date ON health_records(student_id, record_date);
CREATE INDEX idx_health_alerts_status_severity ON health_alerts(status, severity);
CREATE INDEX idx_notifications_recipient ON notifications(recipient_id, recipient_type, is_read);
CREATE INDEX idx_user_sessions_active ON user_sessions(is_active, expires_at);
CREATE INDEX idx_system_logs_level_category ON system_logs(log_level, category, created_at);
```

### Advanced Features Implementation
```php
// src/common/notification_manager.php
<?php
class NotificationManager {
    private $db;
    
    public function __construct() {
        $this->db = DatabaseManager::getInstance()->getConnection();
    }
    
    public function createNotification($recipientId, $recipientType, $title, $message, $type = 'info', $actionUrl = null) {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (recipient_id, recipient_type, title, message, type, action_url)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([$recipientId, $recipientType, $title, $message, $type, $actionUrl]);
    }
    
    public function getUnreadNotifications($userId, $userType) {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE recipient_id = ? AND recipient_type = ? AND is_read = FALSE
            ORDER BY created_at DESC
        ");
        
        $stmt->execute([$userId, $userType]);
        return $stmt->fetchAll();
    }
    
    public function markAsRead($notificationId, $userId) {
        $stmt = $this->db->prepare("
            UPDATE notifications 
            SET is_read = TRUE, read_at = NOW() 
            WHERE id = ? AND recipient_id = ?
        ");
        
        return $stmt->execute([$notificationId, $userId]);
    }
    
    public function sendHealthAlert($studentId, $alertType, $severity, $message) {
        // Get all relevant users for this student
        $stmt = $this->db->prepare("
            SELECT DISTINCT 
                t.id as teacher_id, 'teacher' as type, t.email, t.full_name
            FROM students s
            JOIN classes c ON s.class_id = c.id
            JOIN teachers t ON c.teacher_id = t.id
            WHERE s.id = ? AND t.status = 'approved'
            
            UNION
            
            SELECT DISTINCT 
                p.id as principal_id, 'principal' as type, p.email, p.full_name
            FROM students s
            JOIN classes c ON s.class_id = c.id
            JOIN schools sch ON c.school_id = sch.id
            JOIN principals p ON sch.principal_id = p.id
            WHERE s.id = ?
            
            UNION
            
            SELECT DISTINCT 
                n.id as nurse_id, 'nurse' as type, n.email, n.full_name
            FROM students s
            JOIN classes c ON s.class_id = c.id
            JOIN nurse_schools ns ON c.school_id = ns.school_id
            JOIN nurses n ON ns.nurse_id = n.id
            WHERE s.id = ? AND ns.status = 'active'
        ");
        
        $stmt->execute([$studentId, $studentId, $studentId]);
        $recipients = $stmt->fetchAll();
        
        foreach ($recipients as $recipient) {
            $this->createNotification(
                $recipient['teacher_id'] ?? $recipient['principal_id'] ?? $recipient['nurse_id'],
                $recipient['type'],
                "Health Alert: {$alertType}",
                $message,
                $severity === 'critical' ? 'alert' : 'warning',
                "/student/{$studentId}/health"
            );
        }
    }
}

// src/common/report_generator.php
class ReportGenerator {
    private $db;
    
    public function __construct() {
        $this->db = DatabaseManager::getInstance()->getConnection();
    }
    
    public function generateHealthSummaryReport($schoolId, $startDate, $endDate) {
        $data = $this->getHealthSummaryData($schoolId, $startDate, $endDate);
        return $this->formatReport($data, 'health_summary');
    }
    
    private function getHealthSummaryData($schoolId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT 
                s.first_name,
                s.last_name,
                s.birthdate,
                c.grade_level,
                hr.height_cm,
                hr.weight_kg,
                hr.bmi,
                hr.temperature,
                hr.blood_pressure,
                hr.pulse_rate,
                hr.record_date,
                hr.status,
                CASE 
                    WHEN hr.bmi < 18.5 THEN 'Underweight'
                    WHEN hr.bmi BETWEEN 18.5 AND 24.9 THEN 'Normal'
                    WHEN hr.bmi BETWEEN 25 AND 29.9 THEN 'Overweight'
                    WHEN hr.bmi >= 30 THEN 'Obese'
                    ELSE 'No Data'
                END as bmi_category,
                TIMESTAMPDIFF(YEAR, s.birthdate, CURDATE()) as age
            FROM students s
            JOIN classes c ON s.class_id = c.id
            LEFT JOIN health_records hr ON s.id = hr.student_id
                AND hr.record_date BETWEEN ? AND ?
            WHERE c.school_id = ? AND s.status = 'active'
            ORDER BY c.grade_level, s.last_name, s.first_name
        ");
        
        $stmt->execute([$startDate, $endDate, $schoolId]);
        return $stmt->fetchAll();
    }
    
    public function generateBMIAnalysisReport($schoolId = null, $gradeLevel = null) {
        $whereClause = "WHERE s.status = 'active'";
        $params = [];
        
        if ($schoolId) {
            $whereClause .= " AND c.school_id = ?";
            $params[] = $schoolId;
        }
        
        if ($gradeLevel) {
            $whereClause .= " AND c.grade_level = ?";
            $params[] = $gradeLevel;
        }
        
        $stmt = $this->db->prepare("
            SELECT 
                c.grade_level,
                COUNT(*) as total_students,
                AVG(hr.bmi) as avg_bmi,
                MIN(hr.bmi) as min_bmi,
                MAX(hr.bmi) as max_bmi,
                STDDEV(hr.bmi) as bmi_stddev,
                COUNT(CASE WHEN hr.bmi < 18.5 THEN 1 END) as underweight,
                COUNT(CASE WHEN hr.bmi BETWEEN 18.5 AND 24.9 THEN 1 END) as normal,
                COUNT(CASE WHEN hr.bmi BETWEEN 25 AND 29.9 THEN 1 END) as overweight,
                COUNT(CASE WHEN hr.bmi >= 30 THEN 1 END) as obese
            FROM students s
            JOIN classes c ON s.class_id = c.id
            JOIN health_records hr ON s.id = hr.student_id 
                AND hr.record_date = (
                    SELECT MAX(record_date) FROM health_records WHERE student_id = s.id
                )
            {$whereClause}
            GROUP BY c.grade_level
            ORDER BY c.grade_level
        ");
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    private function formatReport($data, $type) {
        return [
            'report_type' => $type,
            'generated_at' => date('Y-m-d H:i:s'),
            'data' => $data,
            'summary' => $this->generateSummaryStats($data, $type)
        ];
    }
    
    private function generateSummaryStats($data, $type) {
        if (empty($data)) {
            return ['total_records' => 0];
        }
        
        switch ($type) {
            case 'health_summary':
                return [
                    'total_records' => count($data),
                    'with_health_data' => count(array_filter($data, fn($record) => !empty($record['bmi']))),
                    'avg_age' => round(array_sum(array_column($data, 'age')) / count($data), 1)
                ];
                
            case 'bmi_analysis':
                return [
                    'total_students' => array_sum(array_column($data, 'total_students')),
                    'overall_avg_bmi' => round(array_sum(array_column($data, 'avg_bmi')) / count($data), 2),
                    'total_underweight' => array_sum(array_column($data, 'underweight')),
                    'total_normal' => array_sum(array_column($data, 'normal')),
                    'total_overweight' => array_sum(array_column($data, 'overweight')),
                    'total_obese' => array_sum(array_column($data, 'obese'))
                ];
                
            default:
                return ['total_records' => count($data)];
        }
    }
}

// src/common/performance_monitor.php
class PerformanceMonitor {
    private static $startTime;
    private static $startMemory;
    private static $queryCount = 0;
    
    public static function start() {
        self::$startTime = microtime(true);
        self::$startMemory = memory_get_usage(true);
        self::$queryCount = 0;
    }
    
    public static function incrementQueryCount() {
        self::$queryCount++;
    }
    
    public static function end($endpoint, $method, $userId = null) {
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $responseTime = round(($endTime - self::$startTime) * 1000);
        $memoryUsage = round(($endMemory - self::$startMemory) / 1024 / 1024, 2);
        
        $db = DatabaseManager::getInstance()->getConnection();
        $stmt = $db->prepare("
            INSERT INTO performance_metrics 
            (endpoint, method, response_time_ms, memory_usage_mb, query_count, user_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $endpoint,
            $method,
            $responseTime,
            $memoryUsage,
            self::$queryCount,
            $userId
        ]);
    }
    
    public static function getPerformanceStats($days = 7) {
        $db = DatabaseManager::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT 
                endpoint,
                COUNT(*) as request_count,
                AVG(response_time_ms) as avg_response_time,
                MAX(response_time_ms) as max_response_time,
                AVG(memory_usage_mb) as avg_memory_usage,
                AVG(query_count) as avg_query_count
            FROM performance_metrics 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY endpoint
            ORDER BY avg_response_time DESC
        ");
        
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }
}
```

This comprehensive technical documentation provides detailed implementation guidance for each iteration of your Web-Based Health-Integrated Student Information System. Each iteration builds upon the previous one, ensuring a systematic and well-structured development process that maintains data integrity and system security throughout the development lifecycle.