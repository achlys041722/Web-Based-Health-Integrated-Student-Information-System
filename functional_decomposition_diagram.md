# Functional Decomposition Diagram
## Web-Based Health-Integrated Student Information System

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                    WEB-BASED HEALTH-INTEGRATED STUDENT                                 │
│                           INFORMATION SYSTEM                                           │
└─────────────────────────────────────────────────────────────────────────────────────┘
                                           │
                ┌──────────────────────────┼──────────────────────────┐
                │                          │                          │
        ┌───────▼───────┐         ┌───────▼───────┐         ┌───────▼───────┐
        │ AUTHENTICATION│         │ USER MANAGEMENT│         │ DATA MANAGEMENT│
        │    SYSTEM     │         │    SYSTEM      │         │    SYSTEM      │
        └───────────────┘         └────────────────┘         └────────────────┘
                │                          │                          │
    ┌───────────┴───────────┐             │              ┌───────────┴───────────┐
    │                       │             │              │                       │
┌───▼───┐              ┌───▼───┐         │          ┌───▼───┐              ┌───▼───┐
│ LOGIN │              │LOGOUT │         │          │DATABASE│              │HEALTH │
│       │              │       │         │          │SCHEMA │              │RECORDS│
└───────┘              └───────┘         │          └───────┘              └───────┘
                                         │
        ┌────────────────────────────────┴────────────────────────────────┐
        │                                                                  │
┌───────▼───────┐         ┌───────▼───────┐         ┌───────▼───────┐
│   PRINCIPAL    │         │    TEACHER     │         │    NURSE       │
│   SUBSYSTEM    │         │   SUBSYSTEM    │         │   SUBSYSTEM    │
└────────────────┘         └────────────────┘         └────────────────┘
        │                          │                          │
        │                          │                          │
┌───────┴───────┐         ┌───────┴───────┐         ┌───────┴───────┐
│               │         │               │         │               │
▼               ▼         ▼               ▼         ▼               ▼

┌─────────────────────────────────────────────────────────────────────────────────────┐
│                            PRINCIPAL SUBSYSTEM                                       │
├─────────────────────────────────────────────────────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐     │
│ │ PRINCIPAL   │ │   SCHOOL    │ │   TEACHER   │ │   NURSE     │ │  PRINCIPAL  │     │
│ │REGISTRATION │ │REGISTRATION │ │  APPROVAL   │ │ INVITATION  │ │  DASHBOARD  │     │
│ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘     │
│                                                                                     │
│ Functions:                                                                          │
│ • Register principal account                                                        │
│ • Register school with LRN                                                         │
│ • Approve/reject teacher applications                                               │
│ • Invite nurses to school                                                          │
│ • View teacher status and applications                                             │
│ • Manage school information                                                        │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────┐
│                             TEACHER SUBSYSTEM                                        │
├─────────────────────────────────────────────────────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐                     │
│ │   TEACHER   │ │  TEACHER    │ │   STUDENT   │ │   TEACHER   │                     │
│ │REGISTRATION │ │  DASHBOARD  │ │   ACCESS    │ │   STATUS    │                     │
│ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘                     │
│                                                                                     │
│ Functions:                                                                          │
│ • Register teacher account                                                          │
│ • Complete profile information                                                      │
│ • Request assignment to principal/school                                            │
│ • Access student information (when approved)                                       │
│ • View approval status                                                             │
│ • Manage grade level assignments                                                   │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────┐
│                              NURSE SUBSYSTEM                                         │
├─────────────────────────────────────────────────────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐     │
│ │    NURSE    │ │    NURSE    │ │   NURSE     │ │   HEALTH    │ │   SCHOOL    │     │
│ │REGISTRATION │ │  DASHBOARD  │ │ REQUESTS    │ │  RECORDS    │ │ ASSIGNMENT  │     │
│ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘     │
│                                                                                     │
│ Functions:                                                                          │
│ • Register nurse account                                                           │
│ • Complete profile information                                                     │
│ • Accept/reject principal invitations                                              │
│ • Manage health records for students                                               │
│ • Record height, weight, BMI data                                                  │
│ • View assigned schools                                                            │
│ • Process nurse requests from principals                                           │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────┐
│                           DATA MANAGEMENT SUBSYSTEM                                  │
├─────────────────────────────────────────────────────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐     │
│ │  DATABASE   │ │   STUDENT   │ │   HEALTH    │ │   SCHOOL    │ │   USER      │     │
│ │ CONNECTION  │ │    DATA     │ │   RECORDS   │ │    DATA     │ │ MANAGEMENT  │     │
│ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘     │
│                                                                                     │
│ Database Tables:                                                                    │
│ • principals - Principal user accounts                                             │
│ • teachers - Teacher user accounts and status                                      │
│ • nurses - Nurse user accounts                                                     │
│ • schools - School information and LRN                                             │
│ • classes - Grade levels and teacher assignments                                   │
│ • students - Student demographic information                                       │
│ • health_records - Student health measurements                                     │
│ • nurse_requests - Principal-nurse collaboration requests                          │
│ • nurse_schools - Many-to-many nurse-school assignments                           │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────┐
│                          AUTHENTICATION SUBSYSTEM                                    │
├─────────────────────────────────────────────────────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐                     │
│ │   SESSION   │ │   ROLE      │ │  PASSWORD   │ │   ACCESS    │                     │
│ │ MANAGEMENT  │ │VERIFICATION │ │VERIFICATION │ │  CONTROL    │                     │
│ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘                     │
│                                                                                     │
│ Functions:                                                                          │
│ • Multi-role login (Principal, Teacher, Nurse)                                     │
│ • Session management and security                                                  │
│ • Role-based access control                                                        │
│ • Password hashing and verification                                                │
│ • Profile completion validation                                                    │
│ • Approval status checking for teachers                                            │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────┐
│                           REGISTRATION SUBSYSTEM                                     │
├─────────────────────────────────────────────────────────────────────────────────────┤
│ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐                     │
│ │   ACCOUNT   │ │    ROLE     │ │   PROFILE   │ │ VALIDATION  │                     │
│ │  CREATION   │ │ SELECTION   │ │ COMPLETION  │ │    SYSTEM   │                     │
│ └─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘                     │
│                                                                                     │
│ Functions:                                                                          │
│ • New user account registration                                                     │
│ • Role-specific registration forms                                                  │
│ • Email validation and uniqueness checking                                         │
│ • Password creation and hashing                                                    │
│ • Profile information collection                                                   │
└─────────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────┐
│                            SYSTEM ARCHITECTURE                                       │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                     │
│ PRESENTATION LAYER:                                                                 │
│ • Bootstrap-based responsive UI                                                     │
│ • Role-specific dashboards and forms                                               │
│ • PHP-generated dynamic content                                                    │
│                                                                                     │
│ APPLICATION LAYER:                                                                  │
│ • PHP backend processing                                                           │
│ • Session management                                                               │
│ • Business logic implementation                                                    │
│                                                                                     │
│ DATA LAYER:                                                                         │
│ • MySQL database with normalized schema                                            │
│ • Prepared statements for security                                                 │
│ • Relational data integrity                                                        │
│                                                                                     │
│ SECURITY FEATURES:                                                                  │
│ • Password hashing (password_verify)                                               │
│ • Session-based authentication                                                     │
│ • Role-based access control                                                        │
│ • SQL injection prevention                                                         │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

## Key System Workflows

### 1. User Registration & Authentication Flow
```
User Access → Registration Form → Role Selection → Account Creation → 
Profile Completion → Login → Role Verification → Dashboard Access
```

### 2. Principal-Teacher Workflow
```
Principal Registration → School Registration → Teacher Registration → 
Teacher Profile Completion → Principal Approval → Teacher Dashboard Access
```

### 3. Principal-Nurse Collaboration Workflow
```
Principal Nurse Invitation → Nurse Request Creation → Nurse Response → 
School Assignment → Health Records Management
```

### 4. Health Records Management Workflow
```
Student Enrollment → Nurse Assignment → Health Data Collection → 
BMI Calculation → Record Storage → Data Access by Authorized Users
```

## System Components Summary

- **Entry Point**: `index.php` redirects to login system
- **Authentication**: Multi-role login with session management
- **User Roles**: Principal, Teacher, Nurse with distinct capabilities
- **Data Management**: MySQL database with 9 normalized tables
- **UI Framework**: Bootstrap 5 for responsive design
- **Security**: Password hashing, prepared statements, role-based access
- **Core Features**: School management, teacher approval, health records, nurse collaboration