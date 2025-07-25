# System Architecture Design
## Web-Based Health Monitoring System for Elementary Schools

### Table of Contents
1. [System Overview](#system-overview)
2. [Architectural Patterns](#architectural-patterns)
3. [System Architecture Layers](#system-architecture-layers)
4. [Component Architecture](#component-architecture)
5. [Database Architecture](#database-architecture)
6. [Security Architecture](#security-architecture)
7. [Deployment Architecture](#deployment-architecture)
8. [Integration Architecture](#integration-architecture)
9. [Scalability and Performance](#scalability-and-performance)
10. [Technology Stack](#technology-stack)

---

## System Overview

### Definition
The **Web-Based Health Monitoring System for Elementary Schools** is a comprehensive digital platform designed to facilitate the collection, management, and analysis of student health data in elementary educational institutions. The system enables healthcare professionals, educators, and administrators to collaboratively monitor student health metrics, track growth patterns, and ensure timely medical interventions.

### Purpose and Scope
- **Primary Purpose**: Digitize and streamline health record management for elementary school students
- **Scope**: Complete health data lifecycle management from data collection to reporting and analytics
- **Target Users**: School nurses, principals, teachers, and healthcare administrators
- **Coverage**: Elementary schools (Kindergarten through Grade 6)

### Key Objectives
1. **Centralized Health Data Management**: Single source of truth for student health records
2. **Real-time Health Monitoring**: Immediate alerts and notifications for health concerns
3. **Collaborative Care**: Enable seamless communication between healthcare and educational staff
4. **Data-Driven Insights**: Comprehensive analytics for health trend analysis
5. **Compliance and Security**: Ensure HIPAA compliance and data protection standards

---

## Architectural Patterns

### Primary Architectural Pattern: Model-View-Controller (MVC)

```
┌─────────────────────────────────────────────────────────────────┐
│                        MVC ARCHITECTURE                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                         VIEW LAYER                          │ │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐          │ │
│  │  │Principal│ │Teacher  │ │ Nurse   │ │ Login   │          │ │
│  │  │Interface│ │Interface│ │Interface│ │Interface│          │ │
│  │  └─────────┘ └─────────┘ └─────────┘ └─────────┘          │ │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐          │ │
│  │  │Dashboard│ │Reports  │ │Analytics│ │Settings │          │ │
│  │  │Templates│ │Templates│ │Templates│ │Templates│          │ │
│  │  └─────────┘ └─────────┘ └─────────┘ └─────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↕                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                     CONTROLLER LAYER                       │ │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐          │ │
│  │  │Auth     │ │Principal│ │Teacher  │ │ Nurse   │          │ │
│  │  │Controller│ │Controller│ │Controller│ │Controller│          │ │
│  │  └─────────┘ └─────────┘ └─────────┘ └─────────┘          │ │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐          │ │
│  │  │Student  │ │Health   │ │Report   │ │Alert    │          │ │
│  │  │Controller│ │Controller│ │Controller│ │Controller│          │ │
│  │  └─────────┘ └─────────┘ └─────────┘ └─────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↕                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                       MODEL LAYER                          │ │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐          │ │
│  │  │User     │ │Student  │ │Health   │ │School   │          │ │
│  │  │Model    │ │Model    │ │Model    │ │Model    │          │ │
│  │  └─────────┘ └─────────┘ └─────────┘ └─────────┘          │ │
│  │  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐          │ │
│  │  │Class    │ │Alert    │ │Report   │ │Audit    │          │ │
│  │  │Model    │ │Model    │ │Model    │ │Model    │          │ │
│  │  └─────────┘ └─────────┘ └─────────┘ └─────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

### Supporting Patterns
- **Repository Pattern**: Data access abstraction
- **Factory Pattern**: Object creation and dependency injection
- **Observer Pattern**: Event-driven notifications and alerts
- **Strategy Pattern**: Different authentication and reporting strategies

---

## System Architecture Layers

### Complete System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    SYSTEM ARCHITECTURE LAYERS                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                  PRESENTATION LAYER                         │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │              WEB BROWSER INTERFACE                      │ │ │
│  │  │  • HTML5/CSS3 User Interfaces                          │ │ │
│  │  │  • JavaScript/jQuery Interactive Elements              │ │ │
│  │  │  • Bootstrap Responsive Framework                      │ │ │
│  │  │  • Chart.js Data Visualization                         │ │ │
│  │  │  • AJAX Asynchronous Communications                    │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │               TEMPLATE ENGINE                           │ │ │
│  │  │  • PHP Template Processing                             │ │ │
│  │  │  • Dynamic Content Generation                          │ │ │
│  │  │  • Role-based View Rendering                           │ │ │
│  │  │  • Form Validation and Sanitization                   │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↕                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                  APPLICATION LAYER                          │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │              WEB SERVER (Apache/Nginx)                 │ │ │
│  │  │  • HTTP Request/Response Handling                      │ │ │
│  │  │  • SSL/TLS Encryption                                  │ │ │
│  │  │  • Load Balancing                                      │ │ │
│  │  │  • Static Asset Serving                                │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │              PHP APPLICATION ENGINE                     │ │ │
│  │  │  • Session Management                                  │ │ │
│  │  │  • Authentication & Authorization                      │ │ │
│  │  │  • Input Validation & Sanitization                    │ │ │
│  │  │  • Error Handling & Logging                           │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↕                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                   BUSINESS LOGIC LAYER                     │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │               CORE BUSINESS SERVICES                    │ │ │
│  │  │  • User Management Service                             │ │ │
│  │  │  • Student Management Service                          │ │ │
│  │  │  • Health Records Service                              │ │ │
│  │  │  • School Administration Service                       │ │ │
│  │  │  • Notification Service                                │ │ │
│  │  │  • Reporting & Analytics Service                       │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │               HEALTH MONITORING ENGINE                  │ │ │
│  │  │  • BMI Calculation Engine                              │ │ │
│  │  │  • Health Alert System                                 │ │ │
│  │  │  • Growth Tracking Algorithm                           │ │ │
│  │  │  • Nutritional Status Analysis                         │ │ │
│  │  │  • Health Trend Analytics                              │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↕                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    DATA ACCESS LAYER                       │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │               DATABASE ABSTRACTION                      │ │ │
│  │  │  • PDO Database Interface                              │ │ │
│  │  │  • Connection Pool Management                          │ │ │
│  │  │  • Transaction Management                              │ │ │
│  │  │  • Query Optimization                                  │ │ │
│  │  │  • Database Migration Tools                            │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │               CACHING LAYER                             │ │ │
│  │  │  • Redis/Memcached Integration                         │ │ │
│  │  │  • Session Storage                                     │ │ │
│  │  │  • Query Result Caching                                │ │ │
│  │  │  • Application Data Caching                            │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↕                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                     DATA LAYER                              │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │               PRIMARY DATABASE                          │ │ │
│  │  │            MySQL 8.0+ (InnoDB Engine)                  │ │ │
│  │  │  • Student Information Storage                         │ │ │
│  │  │  • Health Records Repository                           │ │ │
│  │  │  • User Account Management                             │ │ │
│  │  │  • System Configuration Data                           │ │ │
│  │  │  • Audit Logs and System Logs                         │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  │                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────┐ │ │
│  │  │               FILE STORAGE SYSTEM                       │ │ │
│  │  │  • Document Storage (Reports, Images)                  │ │ │
│  │  │  • System Backups                                      │ │ │
│  │  │  • Log Files                                           │ │ │
│  │  │  • Configuration Files                                 │ │ │
│  │  └─────────────────────────────────────────────────────────┘ │ │
│  └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

### Layer Definitions

#### 1. Presentation Layer
- **Purpose**: User interface and user experience management
- **Components**: HTML templates, CSS styling, JavaScript interactions
- **Responsibilities**: 
  - Render dynamic content based on user roles
  - Handle user input validation
  - Provide responsive design for multiple devices
  - Implement accessibility standards

#### 2. Application Layer
- **Purpose**: Web server and application runtime environment
- **Components**: Apache/Nginx web server, PHP runtime
- **Responsibilities**:
  - Process HTTP requests and responses
  - Manage SSL/TLS encryption
  - Handle session management
  - Provide application security

#### 3. Business Logic Layer
- **Purpose**: Core application functionality and business rules
- **Components**: Service classes, business rule engines
- **Responsibilities**:
  - Implement health monitoring algorithms
  - Manage user workflows
  - Enforce business rules and policies
  - Handle complex calculations and analytics

#### 4. Data Access Layer
- **Purpose**: Data persistence and retrieval abstraction
- **Components**: Database interfaces, caching mechanisms
- **Responsibilities**:
  - Abstract database operations
  - Manage database connections
  - Implement caching strategies
  - Handle data validation and sanitization

#### 5. Data Layer
- **Purpose**: Physical data storage and management
- **Components**: MySQL database, file storage systems
- **Responsibilities**:
  - Store and retrieve persistent data
  - Maintain data integrity and consistency
  - Provide backup and recovery capabilities
  - Ensure data security and compliance

---

## Component Architecture

### Core System Components

```
┌─────────────────────────────────────────────────────────────────┐
│                    COMPONENT ARCHITECTURE                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                 AUTHENTICATION MODULE                       │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   Login     │ │Registration │ │   Session   │          │ │
│  │  │ Controller  │ │ Controller  │ │  Manager    │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │          │              │              │                  │ │
│  │          └──────────────┼──────────────┘                  │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │  Security   │       │       │    Role     │          │ │
│  │  │  Manager    │←──────┼──────→│  Manager    │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │   Password  │       │       │    Token    │          │ │
│  │  │  Validator  │←──────┼──────→│  Generator  │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  └─────────────────────────┼─────────────────────────────────┘ │
│                            │                                   │
│  ┌─────────────────────────┼─────────────────────────────────┐ │
│  │                 USER MANAGEMENT MODULE                   │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │  Principal  │       │       │   Teacher   │          │ │
│  │  │  Manager    │←──────┼──────→│   Manager   │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  │          │              │              │                  │ │
│  │          └──────────────┼──────────────┘                  │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │    Nurse    │       │       │    User     │          │ │
│  │  │   Manager   │←──────┼──────→│ Permission  │          │ │
│  │  └─────────────┘       │       │   Manager   │          │ │
│  │                         │       └─────────────┘          │ │
│  └─────────────────────────┼─────────────────────────────────┘ │
│                            │                                   │
│  ┌─────────────────────────┼─────────────────────────────────┐ │
│  │               STUDENT MANAGEMENT MODULE                  │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │   Student   │       │       │    Class    │          │ │
│  │  │ Registration│←──────┼──────→│  Manager    │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  │          │              │              │                  │ │
│  │          └──────────────┼──────────────┘                  │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │  Enrollment │       │       │ Attendance  │          │ │
│  │  │   Manager   │←──────┼──────→│   Manager   │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │   Guardian  │       │       │   Student   │          │ │
│  │  │   Manager   │←──────┼──────→│  Profile    │          │ │
│  │  └─────────────┘       │       │   Manager   │          │ │
│  │                         │       └─────────────┘          │ │
│  └─────────────────────────┼─────────────────────────────────┘ │
│                            │                                   │
│  ┌─────────────────────────┼─────────────────────────────────┐ │
│  │              HEALTH MONITORING MODULE                    │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │   Health    │       │       │     BMI     │          │ │
│  │  │   Record    │←──────┼──────→│ Calculator  │          │ │
│  │  │   Manager   │       │       └─────────────┘          │ │
│  │  └─────────────┘       │              │                  │ │
│  │          │              │              │                  │ │
│  │          └──────────────┼──────────────┘                  │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │   Vital     │       │       │   Growth    │          │ │
│  │  │   Signs     │←──────┼──────→│  Tracking   │          │ │
│  │  │  Recorder   │       │       │   Engine    │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │   Health    │       │       │ Nutritional │          │ │
│  │  │   Alert     │←──────┼──────→│   Status    │          │ │
│  │  │   System    │       │       │  Analyzer   │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  └─────────────────────────┼─────────────────────────────────┘ │
│                            │                                   │
│  ┌─────────────────────────┼─────────────────────────────────┐ │
│  │              REPORTING & ANALYTICS MODULE                │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │   Report    │       │       │ Data Export │          │ │
│  │  │  Generator  │←──────┼──────→│   Manager   │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  │          │              │              │                  │ │
│  │          └──────────────┼──────────────┘                  │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │ Statistical │       │       │   Chart     │          │ │
│  │  │  Analytics  │←──────┼──────→│  Generator  │          │ │
│  │  │   Engine    │       │       └─────────────┘          │ │
│  │  └─────────────┘       │              │                  │ │
│  │          │              │              │                  │ │
│  │          └──────────────┼──────────────┘                  │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │   Trend     │       │       │ Dashboard   │          │ │
│  │  │  Analysis   │←──────┼──────→│   Widget    │          │ │
│  │  │   Engine    │       │       │   Manager   │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  └─────────────────────────┼─────────────────────────────────┘ │
│                            │                                   │
│  ┌─────────────────────────┼─────────────────────────────────┐ │
│  │             NOTIFICATION & COMMUNICATION MODULE          │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │    Alert    │       │       │    Email    │          │ │
│  │  │   Manager   │←──────┼──────→│   Service   │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  │          │              │              │                  │ │
│  │          └──────────────┼──────────────┘                  │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │ Notification│       │       │ Message     │          │ │
│  │  │   Queue     │←──────┼──────→│ Template    │          │ │
│  │  │   Manager   │       │       │   Manager   │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  │                         │                                 │ │
│  │  ┌─────────────┐       │       ┌─────────────┐          │ │
│  │  │ Event-Driven│       │       │   SMS/Push  │          │ │
│  │  │Notification │←──────┼──────→│Notification │          │ │
│  │  │   System    │       │       │   Service   │          │ │
│  │  └─────────────┘       │       └─────────────┘          │ │
│  └─────────────────────────┼─────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

### Component Definitions

#### Authentication Module
- **Login Controller**: Manages user authentication processes
- **Registration Controller**: Handles new user account creation
- **Session Manager**: Maintains user session state and security
- **Security Manager**: Implements security policies and encryption
- **Role Manager**: Manages role-based access control
- **Password Validator**: Enforces password strength policies
- **Token Generator**: Creates secure session and authentication tokens

#### User Management Module
- **Principal Manager**: Handles principal account administration
- **Teacher Manager**: Manages teacher account lifecycle
- **Nurse Manager**: Oversees nurse account and assignment management
- **User Permission Manager**: Controls access rights and permissions

#### Student Management Module
- **Student Registration**: Processes new student enrollments
- **Class Manager**: Organizes students into classes and grade levels
- **Enrollment Manager**: Handles student enrollment and transfers
- **Attendance Manager**: Tracks student attendance records
- **Guardian Manager**: Manages parent/guardian information
- **Student Profile Manager**: Maintains comprehensive student profiles

#### Health Monitoring Module
- **Health Record Manager**: Central health data management
- **BMI Calculator**: Automated BMI calculation and categorization
- **Vital Signs Recorder**: Captures and validates vital sign measurements
- **Growth Tracking Engine**: Monitors student growth patterns
- **Health Alert System**: Automated health concern detection
- **Nutritional Status Analyzer**: Evaluates nutritional health indicators

#### Reporting & Analytics Module
- **Report Generator**: Creates customizable health reports
- **Data Export Manager**: Handles data export in various formats
- **Statistical Analytics Engine**: Performs complex data analysis
- **Chart Generator**: Creates visual data representations
- **Trend Analysis Engine**: Identifies health trends and patterns
- **Dashboard Widget Manager**: Manages dashboard components

#### Notification & Communication Module
- **Alert Manager**: Coordinates system alerts and notifications
- **Email Service**: Handles email communications
- **Notification Queue Manager**: Manages notification delivery queues
- **Message Template Manager**: Maintains communication templates
- **Event-Driven Notification System**: Triggers contextual notifications
- **SMS/Push Notification Service**: Mobile and instant messaging

---

## Database Architecture

### Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    DATABASE ARCHITECTURE                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    CORE ENTITIES                            │ │
│  │                                                             │ │
│  │  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐   │ │
│  │  │   SCHOOLS   │────→│ PRINCIPALS  │←────│   NURSES    │   │ │
│  │  │             │     │             │     │             │   │ │
│  │  │ • id (PK)   │     │ • id (PK)   │     │ • id (PK)   │   │ │
│  │  │ • name      │     │ • email     │     │ • email     │   │ │
│  │  │ • lrn       │     │ • full_name │     │ • username  │   │ │
│  │  │ • address   │     │ • school    │     │ • password  │   │ │
│  │  │ • contact   │     │ • contact   │     │ • full_name │   │ │
│  │  │ • principal │     │ • status    │     │ • status    │   │ │
│  │  │ • status    │     │ • created_at│     │ • created_at│   │ │
│  │  └─────────────┘     └─────────────┘     └─────────────┘   │ │
│  │         │                    │                   │         │ │
│  │         │                    │                   │         │ │
│  │         ↓                    ↓                   ↓         │ │
│  │  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐   │ │
│  │  │   CLASSES   │────→│  TEACHERS   │←────│NURSE_SCHOOLS│   │ │
│  │  │             │     │             │     │             │   │ │
│  │  │ • id (PK)   │     │ • id (PK)   │     │ • id (PK)   │   │ │
│  │  │ • school_id │     │ • email     │     │ • nurse_id  │   │ │
│  │  │ • grade_lvl │     │ • full_name │     │ • school_id │   │ │
│  │  │ • teacher_id│     │ • grade_lvl │     │ • role      │   │ │
│  │  │ • created_at│     │ • address   │     │ • status    │   │ │
│  │  └─────────────┘     │ • contact   │     │ • assigned  │   │ │
│  │         │             │ • principal │     └─────────────┘   │ │
│  │         │             │ • status    │                       │ │
│  │         │             │ • created_at│                       │ │
│  │         │             └─────────────┘                       │ │
│  │         ↓                                                   │ │
│  │  ┌─────────────┐                                           │ │
│  │  │   STUDENTS  │                                           │ │
│  │  │             │                                           │ │
│  │  │ • id (PK)   │                                           │ │
│  │  │ • class_id  │                                           │ │
│  │  │ • first_name│                                           │ │
│  │  │ • last_name │                                           │ │
│  │  │ • lrn       │                                           │ │
│  │  │ • birthdate │                                           │ │
│  │  │ • gender    │                                           │ │
│  │  │ • address   │                                           │ │
│  │  │ • guardian  │                                           │ │
│  │  │ • status    │                                           │ │
│  │  │ • created_at│                                           │ │
│  │  └─────────────┘                                           │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                            │                                     │
│                            ↓                                     │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                   HEALTH ENTITIES                           │ │
│  │                                                             │ │
│  │  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐   │ │
│  │  │HEALTH_RECORDS│    │HEALTH_ALERTS│     │HEALTH_TRENDS│   │ │
│  │  │             │     │             │     │             │   │ │
│  │  │ • id (PK)   │     │ • id (PK)   │     │ • id (PK)   │   │ │
│  │  │ • student_id│────→│ • student_id│     │ • student_id│   │ │
│  │  │ • height_cm │     │ • alert_type│     │ • metric    │   │ │
│  │  │ • weight_kg │     │ • severity  │     │ • value     │   │ │
│  │  │ • bmi       │     │ • message   │     │ • date      │   │ │
│  │  │ • temp      │     │ • created_by│     │ • trend     │   │ │
│  │  │ • bp        │     │ • status    │     │ • period    │   │ │
│  │  │ • pulse     │     │ • created_at│     │ • created_at│   │ │
│  │  │ • notes     │     │ • resolved  │     └─────────────┘   │ │
│  │  │ • date      │     └─────────────┘                       │ │
│  │  │ • nurse_id  │                                           │ │
│  │  │ • status    │                                           │ │
│  │  │ • created_at│                                           │ │
│  │  └─────────────┘                                           │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                   SYSTEM ENTITIES                           │ │
│  │                                                             │ │
│  │  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐   │ │
│  │  │USER_SESSIONS│     │NOTIFICATIONS│     │ SYSTEM_LOGS │   │ │
│  │  │             │     │             │     │             │   │ │
│  │  │ • session_id│     │ • id (PK)   │     │ • id (PK)   │   │ │
│  │  │ • user_id   │     │ • recipient │     │ • level     │   │ │
│  │  │ • user_type │     │ • type      │     │ • category  │   │ │
│  │  │ • created_at│     │ • title     │     │ • message   │   │ │
│  │  │ • expires_at│     │ • message   │     │ • context   │   │ │
│  │  │ • ip_addr   │     │ • is_read   │     │ • user_id   │   │ │
│  │  │ • user_agent│     │ • action_url│     │ • user_type │   │ │
│  │  │ • is_active │     │ • created_at│     │ • ip_addr   │   │ │
│  │  └─────────────┘     │ • read_at   │     │ • created_at│   │ │
│  │                      └─────────────┘     └─────────────┘   │ │
│  │                                                             │ │
│  │  ┌─────────────┐     ┌─────────────┐     ┌─────────────┐   │ │
│  │  │AUDIT_TRAILS │     │REPORT_TEMPS │     │PERF_METRICS │   │ │
│  │  │             │     │             │     │             │   │ │
│  │  │ • id (PK)   │     │ • id (PK)   │     │ • id (PK)   │   │ │
│  │  │ • table_name│     │ • name      │     │ • endpoint  │   │ │
│  │  │ • record_id │     │ • type      │     │ • method    │   │ │
│  │  │ • action    │     │ • user_types│     │ • resp_time │   │ │
│  │  │ • old_values│     │ • sql_query │     │ • memory_mb │   │ │
│  │  │ • new_values│     │ • chart_cfg │     │ • query_cnt │   │ │
│  │  │ • user_id   │     │ • created_at│     │ • user_id   │   │ │
│  │  │ • user_type │     └─────────────┘     │ • created_at│   │ │
│  │  │ • ip_addr   │                         │ • user_id   │   │ │
│  │  │ • created_at│                         └─────────────┘   │ │
│  │  └─────────────┘                                           │ │
│  └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

### Database Design Principles

#### 1. Normalization
- **Third Normal Form (3NF)**: Eliminates data redundancy and update anomalies
- **Referential Integrity**: Foreign key constraints ensure data consistency
- **Atomic Data**: Each field contains indivisible data elements

#### 2. Indexing Strategy
```sql
-- Primary Performance Indexes
CREATE INDEX idx_students_class_status ON students(class_id, status);
CREATE INDEX idx_health_records_student_date ON health_records(student_id, record_date);
CREATE INDEX idx_health_alerts_status_severity ON health_alerts(status, severity);
CREATE INDEX idx_notifications_recipient ON notifications(recipient_id, recipient_type, is_read);
CREATE INDEX idx_sessions_active ON user_sessions(is_active, expires_at);

-- Composite Indexes for Complex Queries
CREATE INDEX idx_health_records_nurse_date ON health_records(nurse_id, record_date);
CREATE INDEX idx_students_grade_status ON students(class_id, status, created_at);
CREATE INDEX idx_alerts_student_created ON health_alerts(student_id, created_at);
```

#### 3. Data Integrity Constraints
```sql
-- Check Constraints for Data Validation
ALTER TABLE health_records ADD CONSTRAINT chk_bmi_range 
    CHECK (bmi IS NULL OR (bmi >= 10 AND bmi <= 50));

ALTER TABLE health_records ADD CONSTRAINT chk_height_range 
    CHECK (height_cm IS NULL OR (height_cm >= 50 AND height_cm <= 250));

ALTER TABLE health_records ADD CONSTRAINT chk_weight_range 
    CHECK (weight_kg IS NULL OR (weight_kg >= 5 AND weight_kg <= 200));

-- Unique Constraints
ALTER TABLE students ADD CONSTRAINT uk_student_lrn UNIQUE (lrn);
ALTER TABLE teachers ADD CONSTRAINT uk_teacher_email UNIQUE (email);
ALTER TABLE nurses ADD CONSTRAINT uk_nurse_email UNIQUE (email);
```

#### 4. Audit Trail Implementation
```sql
-- Audit Trail Trigger Example
DELIMITER $$
CREATE TRIGGER audit_health_records_update
AFTER UPDATE ON health_records
FOR EACH ROW
BEGIN
    INSERT INTO audit_trails (
        table_name, record_id, action, old_values, new_values, 
        user_id, user_type, ip_address, created_at
    ) VALUES (
        'health_records', NEW.id, 'UPDATE',
        JSON_OBJECT('height_cm', OLD.height_cm, 'weight_kg', OLD.weight_kg, 'bmi', OLD.bmi),
        JSON_OBJECT('height_cm', NEW.height_cm, 'weight_kg', NEW.weight_kg, 'bmi', NEW.bmi),
        NEW.nurse_id, 'nurse', @user_ip, NOW()
    );
END$$
DELIMITER ;
```

---

## Security Architecture

### Multi-Layer Security Model

```
┌─────────────────────────────────────────────────────────────────┐
│                      SECURITY ARCHITECTURE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    PERIMETER SECURITY                       │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   Firewall  │ │     WAF     │ │    DDoS     │          │ │
│  │  │ Protection  │ │ (Web App    │ │ Protection  │          │ │
│  │  │             │ │ Firewall)   │ │             │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   SSL/TLS   │ │   Rate      │ │    IP       │          │ │
│  │  │ Encryption  │ │ Limiting    │ │ Filtering   │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                 APPLICATION SECURITY                        │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    Input    │ │   Output    │ │   Session   │          │ │
│  │  │ Validation  │ │ Encoding    │ │ Management  │          │ │
│  │  │ & Sanitize  │ │ & Escaping  │ │             │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    CSRF     │ │     XSS     │ │    SQL      │          │ │
│  │  │ Protection  │ │ Prevention  │ │ Injection   │          │ │
│  │  │             │ │             │ │ Prevention  │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   Password  │ │    Multi    │ │  Role-Based │          │ │
│  │  │  Security   │ │   Factor    │ │   Access    │          │ │
│  │  │  Policies   │ │    Auth     │ │   Control   │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    DATA SECURITY                            │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │  Database   │ │    Field    │ │   Backup    │          │ │
│  │  │ Encryption  │ │ Encryption  │ │ Encryption  │          │ │
│  │  │   at Rest   │ │             │ │             │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    Data     │ │    Data     │ │   Privacy   │          │ │
│  │  │ Masking &   │ │ Retention   │ │   Controls  │          │ │
│  │  │ Anonymize   │ │ Policies    │ │   (HIPAA)   │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   Access    │ │    Audit    │ │ Data Loss   │          │ │
│  │  │   Logging   │ │   Trails    │ │ Prevention  │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                 MONITORING & COMPLIANCE                     │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   Security  │ │   Incident  │ │ Compliance  │          │ │
│  │  │ Monitoring  │ │   Response  │ │  Reporting  │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │ Penetration │ │    Risk     │ │ Vulnerability│          │ │
│  │  │   Testing   │ │ Assessment  │ │  Scanning   │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

### Security Implementation Details

#### 1. Authentication Security
```php
// Multi-layer Authentication Implementation
class SecureAuthentication {
    // Password Security
    public function hashPassword($password) {
        // Use bcrypt with cost factor 12
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    // Session Security
    public function createSecureSession($userId, $userType) {
        // Regenerate session ID
        session_regenerate_id(true);
        
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 1);
        ini_set('session.cookie_samesite', 'Strict');
        
        // Create session with token
        $sessionToken = bin2hex(random_bytes(32));
        $_SESSION['token'] = $sessionToken;
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_type'] = $userType;
        $_SESSION['last_activity'] = time();
        
        return $sessionToken;
    }
    
    // CSRF Protection
    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
```

#### 2. Input Validation & Sanitization
```php
class InputSecurity {
    public function validateHealthRecord($data) {
        $errors = [];
        
        // Height validation
        if (isset($data['height_cm'])) {
            $height = filter_var($data['height_cm'], FILTER_VALIDATE_FLOAT);
            if ($height === false || $height < 50 || $height > 250) {
                $errors[] = 'Invalid height measurement';
            }
        }
        
        // Weight validation
        if (isset($data['weight_kg'])) {
            $weight = filter_var($data['weight_kg'], FILTER_VALIDATE_FLOAT);
            if ($weight === false || $weight < 5 || $weight > 200) {
                $errors[] = 'Invalid weight measurement';
            }
        }
        
        // Temperature validation
        if (isset($data['temperature'])) {
            $temp = filter_var($data['temperature'], FILTER_VALIDATE_FLOAT);
            if ($temp === false || $temp < 30 || $temp > 45) {
                $errors[] = 'Invalid temperature reading';
            }
        }
        
        return $errors;
    }
    
    public function sanitizeInput($input, $type = 'string') {
        switch ($type) {
            case 'email':
                return filter_var(trim($input), FILTER_SANITIZE_EMAIL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            default:
                return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
    }
}
```

#### 3. Role-Based Access Control
```php
class AccessControl {
    private $permissions = [
        'nurse' => [
            'health_records.create',
            'health_records.read',
            'health_records.update',
            'students.read',
            'alerts.create',
            'reports.health'
        ],
        'teacher' => [
            'students.create',
            'students.read',
            'students.update',
            'health_records.read',
            'classes.manage',
            'reports.class'
        ],
        'principal' => [
            'teachers.approve',
            'schools.manage',
            'nurses.assign',
            'reports.school',
            'analytics.view'
        ]
    ];
    
    public function hasPermission($userType, $permission) {
        return isset($this->permissions[$userType]) && 
               in_array($permission, $this->permissions[$userType]);
    }
    
    public function enforcePermission($userType, $permission) {
        if (!$this->hasPermission($userType, $permission)) {
            throw new SecurityException('Access denied: Insufficient permissions');
        }
    }
}
```

#### 4. Data Encryption
```php
class DataEncryption {
    private $encryptionKey;
    
    public function __construct() {
        $this->encryptionKey = $_ENV['ENCRYPTION_KEY'] ?? '';
    }
    
    public function encryptSensitiveData($data) {
        $cipher = 'AES-256-GCM';
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        
        $ciphertext = openssl_encrypt($data, $cipher, $this->encryptionKey, 
                                    OPENSSL_RAW_DATA, $iv, $tag);
        
        return base64_encode($iv . $tag . $ciphertext);
    }
    
    public function decryptSensitiveData($encryptedData) {
        $data = base64_decode($encryptedData);
        $cipher = 'AES-256-GCM';
        $ivlen = openssl_cipher_iv_length($cipher);
        
        $iv = substr($data, 0, $ivlen);
        $tag = substr($data, $ivlen, 16);
        $ciphertext = substr($data, $ivlen + 16);
        
        return openssl_decrypt($ciphertext, $cipher, $this->encryptionKey, 
                             OPENSSL_RAW_DATA, $iv, $tag);
    }
}
```

---

## Deployment Architecture

### Production Deployment Model

```
┌─────────────────────────────────────────────────────────────────┐
│                    DEPLOYMENT ARCHITECTURE                      │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                      LOAD BALANCER                          │ │
│  │                    (Nginx/HAProxy)                          │ │
│  │  • SSL Termination                                          │ │
│  │  • Request Distribution                                     │ │
│  │  • Health Checks                                            │ │
│  │  • Rate Limiting                                            │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                   WEB SERVER CLUSTER                        │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │  Web Server │ │  Web Server │ │  Web Server │          │ │
│  │  │     #1      │ │     #2      │ │     #3      │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • Apache    │ │ • Apache    │ │ • Apache    │          │ │
│  │  │ • PHP-FPM   │ │ • PHP-FPM   │ │ • PHP-FPM   │          │ │
│  │  │ • App Code  │ │ • App Code  │ │ • App Code  │          │ │
│  │  │ • Sessions  │ │ • Sessions  │ │ • Sessions  │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    DATABASE CLUSTER                         │ │
│  │                                                             │ │
│  │  ┌─────────────┐           ┌─────────────┐                  │ │
│  │  │   Master    │──────────→│   Slave     │                  │ │
│  │  │  Database   │           │  Database   │                  │ │
│  │  │             │           │             │                  │ │
│  │  │ • MySQL 8.0 │           │ • MySQL 8.0 │                  │ │
│  │  │ • InnoDB    │           │ • InnoDB    │                  │ │
│  │  │ • Read/Write│           │ • Read Only │                  │ │
│  │  │ • Replication│          │ • Backup    │                  │ │
│  │  └─────────────┘           └─────────────┘                  │ │
│  │                                   │                         │ │
│  │  ┌─────────────┐                  │                         │ │
│  │  │   Backup    │←─────────────────┘                         │ │
│  │  │  Database   │                                            │ │
│  │  │             │                                            │ │
│  │  │ • MySQL 8.0 │                                            │ │
│  │  │ • Daily     │                                            │ │
│  │  │ • Archives  │                                            │ │
│  │  │ • Recovery  │                                            │ │
│  │  └─────────────┘                                            │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    CACHING LAYER                            │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    Redis    │ │  Memcached  │ │   File      │          │ │
│  │  │   Cluster   │ │   Cluster   │ │   Cache     │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • Sessions  │ │ • Query     │ │ • Static    │          │ │
│  │  │ • User Data │ │ • Results   │ │ • Assets    │          │ │
│  │  │ • Temp Data │ │ • App Data  │ │ • Images    │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                   MONITORING & LOGGING                      │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   System    │ │ Application │ │   Security  │          │ │
│  │  │ Monitoring  │ │  Monitoring │ │  Monitoring │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • CPU/RAM   │ │ • Response  │ │ • Access    │          │ │
│  │  │ • Disk I/O  │ │ • Errors    │ │ • Failed    │          │ │
│  │  │ • Network   │ │ • Performance│ │ • Attacks   │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    Log      │ │   Alert     │ │   Backup    │          │ │
│  │  │ Aggregation │ │ Management  │ │ Management  │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • ELK Stack │ │ • Email     │ │ • Automated │          │ │
│  │  │ • Kibana    │ │ • SMS       │ │ • Scheduled │          │ │
│  │  │ • Analytics │ │ • Dashboard │ │ • Verified  │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

### Infrastructure Specifications

#### Production Environment
```yaml
# Infrastructure Requirements
Load Balancer:
  - Type: Hardware/Software (Nginx/HAProxy)
  - SSL: TLS 1.3 with automatic certificate renewal
  - Rate Limiting: 1000 requests/minute per IP
  - Health Checks: Every 30 seconds

Web Servers (3 nodes):
  - OS: Ubuntu 20.04 LTS
  - CPU: 4 cores, 2.4GHz minimum
  - RAM: 8GB minimum
  - Storage: 100GB SSD
  - PHP: 8.1+ with OPcache enabled
  - Apache: 2.4+ with mod_rewrite

Database Cluster:
  Master:
    - CPU: 8 cores, 3.0GHz
    - RAM: 32GB minimum
    - Storage: 500GB SSD (RAID 10)
    - MySQL: 8.0+ with InnoDB
  
  Slave(s):
    - CPU: 4 cores, 2.4GHz
    - RAM: 16GB minimum
    - Storage: 500GB SSD
    - Replication: Async/Semi-sync

Caching Layer:
  Redis:
    - RAM: 8GB dedicated
    - Persistence: RDB + AOF
    - Clustering: 3 nodes
  
  Memcached:
    - RAM: 4GB dedicated
    - Distribution: Consistent hashing
    - Nodes: 2 instances

Backup Strategy:
  - Database: Daily full backups, hourly incremental
  - Application: Weekly full backups
  - Files: Daily differential backups
  - Retention: 30 days online, 1 year archive
```

---

## Technology Stack

### Complete Technology Stack Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                      TECHNOLOGY STACK                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                    FRONTEND TECHNOLOGIES                    │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    HTML5    │ │    CSS3     │ │ JavaScript  │          │ │
│  │  │             │ │             │ │   ES2020    │          │ │
│  │  │ • Semantic  │ │ • Flexbox   │ │ • Async/    │          │ │
│  │  │ • Forms     │ │ • Grid      │ │   Await     │          │ │
│  │  │ • Canvas    │ │ • Variables │ │ • Modules   │          │ │
│  │  │ • Storage   │ │ • Animation │ │ • Classes   │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │  Bootstrap  │ │   jQuery    │ │  Chart.js   │          │ │
│  │  │     5.3     │ │    3.6      │ │    4.4      │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • Grid      │ │ • DOM       │ │ • Line      │          │ │
│  │  │ • Components│ │ • AJAX      │ │ • Bar       │          │ │
│  │  │ • Utilities │ │ • Events    │ │ • Pie       │          │ │
│  │  │ • Responsive│ │ • Animation │ │ • Doughnut  │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                   BACKEND TECHNOLOGIES                      │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    PHP      │ │   Apache    │ │    Nginx    │          │ │
│  │  │    8.1+     │ │    2.4+     │ │    1.20+    │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • OOP       │ │ • mod_php   │ │ • Reverse   │          │ │
│  │  │ • Namespaces│ │ • mod_ssl   │ │   Proxy     │          │ │
│  │  │ • Composer  │ │ • mod_rewrite│ │ • Load      │          │ │
│  │  │ • PSR-4     │ │ • Virtual   │ │   Hosts     │ │ • SSL Term  │          │ │
│  │  │ • OPcache   │ │   Hosts     │ │ • SSL Term  │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   MySQL     │ │    Redis    │ │ Memcached   │          │ │
│  │  │    8.0+     │ │    7.0+     │ │    1.6+     │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • InnoDB    │ │ • Caching   │ │ • Memory    │          │ │
│  │  │ • JSON      │ │ • Sessions  │ │   Cache     │          │ │
│  │  │ • Full-Text │ │ • Pub/Sub   │ │ • Distributed│          │ │
│  │  │ • Replication│ │ • Clustering│ │ • LRU       │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                  DEVELOPMENT TOOLS                          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │     Git     │ │   Composer  │ │   PHPUnit   │          │ │
│  │  │    2.40+    │ │    2.5+     │ │    10.0+    │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • Version   │ │ • Dependency│ │ • Unit      │          │ │
│  │  │   Control   │ │   Manager   │ │   Testing   │          │ │
│  │  │ • Branching │ │ • Autoload  │ │ • Mocking   │          │ │
│  │  │ • Merging   │ │ • Scripts   │ │ • Coverage  │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │   VS Code   │ │    Docker   │ │   Postman   │          │ │
│  │  │   Latest    │ │   20.10+    │ │   Latest    │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • Extensions│ │ • Container │ │ • API       │          │ │
│  │  │ • Debugging │ │ • Images    │ │   Testing   │          │ │
│  │  │ • IntelliSense│ • Compose   │ │ • Collection│          │ │
│  │  │ • Git       │ │ • Networks  │ │ • Environ   │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
│                                ↓                                 │
│  ┌─────────────────────────────────────────────────────────────┐ │
│  │                 MONITORING & SECURITY                       │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    SSL      │ │  Fail2ban   │ │   ModSec    │          │ │
│  │  │ Let's Encrypt│ │    0.11+    │ │    3.0+     │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • Auto      │ │ • Intrusion │ │ • Web App   │          │ │
│  │  │   Renewal   │ │   Detection │ │   Firewall  │          │ │
│  │  │ • Multi     │ │ • IP        │ │ • OWASP     │          │ │
│  │  │   Domain    │ │   Blocking  │ │   Rules     │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  │                                                             │ │
│  │  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │ │
│  │  │    ELK      │ │   Grafana   │ │ Prometheus  │          │ │
│  │  │   Stack     │ │    9.0+     │ │    2.40+    │          │ │
│  │  │             │ │             │ │             │          │ │
│  │  │ • Elastic   │ │ • Dashboard │ │ • Metrics   │          │ │
│  │  │ • Logstash  │ │ • Alerts    │ │ • Time      │          │ │
│  │  │ • Kibana    │ │ • Panels    │ │   Series    │          │ │
│  │  │ • Beats     │ │ • Plugins   │ │ • Alerting  │          │ │
│  │  └─────────────┘ └─────────────┘ └─────────────┘          │ │
│  └─────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

### Technology Justification

#### Frontend Technologies
- **HTML5**: Modern semantic markup for accessibility and SEO
- **CSS3**: Advanced styling with responsive design capabilities
- **JavaScript ES2020**: Modern language features for clean, maintainable code
- **Bootstrap 5.3**: Rapid UI development with mobile-first approach
- **jQuery 3.6**: Simplified DOM manipulation and AJAX operations
- **Chart.js 4.4**: Interactive data visualization for health analytics

#### Backend Technologies
- **PHP 8.1+**: Mature, secure server-side language with modern features
- **Apache 2.4+**: Reliable, configurable web server with extensive module support
- **MySQL 8.0+**: Robust, ACID-compliant database with JSON support
- **Redis 7.0+**: High-performance caching and session storage
- **Memcached 1.6+**: Distributed memory object caching system

#### Development & Operations
- **Git**: Distributed version control for collaborative development
- **Composer**: PHP dependency management and autoloading
- **PHPUnit**: Comprehensive testing framework for quality assurance
- **Docker**: Containerization for consistent development environments
- **VS Code**: Feature-rich IDE with extensive PHP/web development support

#### Security & Monitoring
- **SSL/TLS**: Industry-standard encryption for data in transit
- **ModSecurity**: Web application firewall for attack prevention
- **Fail2ban**: Intrusion prevention through IP blocking
- **ELK Stack**: Centralized logging and analysis platform
- **Grafana/Prometheus**: Real-time monitoring and alerting system

This comprehensive architectural design provides a solid foundation for developing a scalable, secure, and maintainable Web-Based Health Monitoring System for Elementary Schools, ensuring all stakeholders can effectively collaborate in monitoring and improving student health outcomes.