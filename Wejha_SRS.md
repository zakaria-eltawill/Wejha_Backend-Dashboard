WEJHA DIGITAL PLATFORM 2026
Software Requirements Specification (SRS)
Version 1.0
1. Project Overview
1.1 Introduction

You are an Expert Software Architect, Senior Backend Engineer, and Laravel Consultant specializing in Enterprise Applications.

Your responsibility is to design and build the complete backend infrastructure, business logic, administration dashboard, database architecture, security layer, reporting engine, and API layer for the Wejha Digital Platform 2026.

This document serves as the single source of truth for the project.

Every generated component must follow this specification.

The resulting system must be:

Production Ready
Secure
Scalable
Maintainable
Modular
Fully documented
Enterprise Grade
1.2 About Wejha

Wejha is a youth educational guidance platform designed to help high-school students choose their future careers through:

Workshops
Seminars
Exhibitions
Assessments
Surveys
Guidance Sessions
Career Recommendations

The platform allows students to register for events, attend activities using QR Codes, complete assessments, and receive personalized guidance.

Administrators use a centralized dashboard to manage events, surveys, registrations, attendance, reports, analytics, and users.

1.3 Objectives

The system must achieve the following objectives:

Digitize youth guidance events.
Eliminate paper-based registration.
Support QR attendance.
Collect assessment data.
Generate analytics.
Produce PDF & Excel reports.
Support Arabic & English.
Provide an excellent administrator experience.
Be scalable for future national expansion.
2. Technology Stack

The project MUST use the following technologies.

Backend

Laravel 11

PHP 8.3

Strict Types

PSR-12

Composer

Database

PostgreSQL 16

Requirements:

Use UUIDs

Use JSONB

Use Composite Indexes

Use Partial Indexes

Use Foreign Keys

Use Transactions

Use Constraints

Use Check Constraints

Optimize Queries

Avoid N+1

Admin Dashboard

Filament PHP v5

Use latest Filament features including:

Resources
Widgets
Panels
Clusters
Infolists
Relation Managers
Custom Pages
Wizard Forms
Actions
Header Actions
Table Summaries
Global Search
Notifications
Tabs
Slide Overs
Frontend Communication

The frontend is developed separately using

React

Inertia.js

Laravel returns Inertia responses only.

No Blade UI except Filament.

Authentication

Laravel Authentication

Spatie Laravel Permission

Laravel Policies

Gates

Form Requests

Queue

Database Queue

Redis Ready

Notification Queue

Mail Queue

Export Queue

Report Queue

Storage

Laravel Filesystem

Support:

Local Storage

Amazon S3

Cloudflare R2

3. Architecture

The system MUST follow Clean Architecture.

Never place business logic inside controllers.

Use:

Controllers

↓

Services

↓

Repositories

↓

Models

↓

Database

Folder Structure

app/

Actions/

Services/

Repositories/

Policies/

Enums/

DTOs/

Traits/

Observers/

Events/

Listeners/

Notifications/

Exports/

Imports/

Reports/

Support/

Exceptions/

Helpers/

Queries/

Scopes/

4. Coding Standards

Every generated code must:

Use PHP Strict Types

Follow PSR-12

Be fully type hinted

Use dependency injection

Avoid duplicated code

Follow SOLID Principles

Follow DRY

Follow KISS

Follow Repository Pattern

Follow Service Pattern

Use DTO whenever appropriate

Use Enums instead of strings

Never hardcode values

Never duplicate validation

Never duplicate queries

Every class must have a single responsibility.

5. Localization

The platform must be fully bilingual.

Supported languages:

Arabic

English

Arabic is default.

Requirements:

All Filament Resources translated

All Forms translated

All Tables translated

All Notifications translated

Validation translated

Emails translated

PDF translated

Excel translated

Reports translated

Errors translated

Buttons translated

Menus translated

Widgets translated

Charts translated

Everything must use localization files.

Never hardcode any user-facing text.

RTL/LTR Support

Arabic

RTL

English

LTR

The direction changes automatically.

Dates

Numbers

Currency

Validation

Everything adapts automatically.

6. Appearance

The dashboard must support

Light Mode

Dark Mode

System Mode

Automatically detect operating system theme.

Store preference inside user settings.

All components must support both themes.

This includes

Dashboard

Widgets

Forms

Tables

Charts

Dialogs

Notifications

Login Page

Sidebar

Reports

Print Preview

Exports

Everything.

No color conflicts.

No unreadable text.

No accessibility issues.

WCAG AA compliant.

7. Official Brand Identity

The system design MUST strictly follow the official visual identity of the Wejha Project 2026. The design language should emphasize clarity, trust, modernity, flexibility, and scalability to create a youthful and professional digital experience.

Brand Vision

The visual identity has been redesigned to create a modern, scalable, and trustworthy platform that resonates with youth while maintaining simplicity and clarity in communication. The interface should consistently reflect these values across all modules.

Color Palette

Use the official color palette:

Primary Brand Color:
#001F8F

Secondary Brand Color:
#00389E

Primary Accent:
#FF4900

Secondary Accent:
#FF3324

Background:
#F1F2F2

These colors must be consistently applied across buttons, cards, charts, dashboards, forms, login pages, sidebars, reports, notifications, QR pages, and exported documents.

Typography

Primary Font:

DIN Next LT Arabic

Fallback:

Cairo

IBM Plex Sans Arabic

sans-serif

Follow the official typography hierarchy using bold for major headings, regular for section titles, and light weights for descriptive text.

Branding

Apply the official branding throughout the system:

Logo placement:

Login Screen
Dashboard
Sidebar
PDF Reports
Excel Exports
Email Templates
QR Tickets
Attendance Pages

Official slogans:

حسن اختيارك هو بداية مشوارك
خلي ديما عندك وجهة
#وجهتك-تبدأ-من-هنا

These should appear where appropriate, such as reports, welcome screens, and official communications.

8. Database Architecture
8.1 Database Philosophy

The Wejha Digital Platform database must be designed following enterprise-grade PostgreSQL best practices with an emphasis on:

High Performance
Scalability
Data Integrity
Security
Flexibility
Auditability
Future Expansion

The database must support future modules without requiring structural redesign. All schema decisions should favor maintainability and extensibility while minimizing redundancy and optimizing query performance.

8.2 Database Engine

The platform shall use:

PostgreSQL 16

Required PostgreSQL features include:

UUID Primary Keys
JSONB Columns
Foreign Key Constraints
Composite Indexes
Partial Indexes
CHECK Constraints
Transactions
Generated Columns (where beneficial)
Full Text Search (future-ready)
Row-Level Locking
Query Planning Optimization
8.3 UUID Strategy

The system must avoid auto-increment IDs for sensitive entities.

Use UUIDs for:

Users
Notifications
API Tokens
Public Identifiers
Future integrations

UUIDs should be generated using Laravel's native UUID support and stored efficiently using PostgreSQL UUID data types.

8.4 Naming Convention

Use singular Eloquent models.

Use plural table names.

Examples:

users

events

registrations

survey_questions

Foreign keys:

user_id

event_id

survey_template_id

Pivot tables:

Alphabetical naming order.

8.5 Timestamp Policy

Every table must contain:

created_at

updated_at

Where applicable:

deleted_at (Soft Deletes)

submitted_at

registered_at

scanned_at

published_at
8.6 Soft Deletes Policy

Soft Deletes should be enabled for:

Users
Events
Survey Templates
Notifications

Attendance records and survey responses should remain immutable to preserve historical integrity.

8.7 Database Constraints

The system must strictly enforce business rules at the database level.

Examples include:

One registration per user per event.
One attendance per registration.
One evaluation type (pre/post) per event.
One survey response per question per evaluation.
Capacity limits enforced in business logic.
Foreign key cascades where appropriate.

Never rely solely on application validation.

8.8 Database Indexing Strategy

Every frequently queried column should be indexed.

Mandatory indexes include:

email

school_name

event_date

user_id

event_id

survey_template_id

evaluation_type

created_at

registered_at

submitted_at

Composite indexes:

(user_id,event_id)

(event_id,evaluation_type)

(user_id,event_evaluation_id,question_id)

Partial indexes should be considered for:

Active events.
Published surveys.
Active notifications.
8.9 JSONB Usage

PostgreSQL JSONB must be used wherever flexible structured data is required.

Current usage:

Survey Question Options

[
    {
        "id":1,
        "label_ar":"نعم",
        "label_en":"Yes"
    }
]

Future modules may also leverage JSONB for:

Dynamic form configurations
Notification payloads
User preferences
Dashboard settings
Analytics filters

GIN indexes should be created on JSONB columns to ensure fast querying.

8.10 Data Integrity

Every migration must define:

Foreign Keys
Cascading Rules
Unique Constraints
Check Constraints
Default Values
Nullable Rules

No orphaned records should be possible.

9. Entity Relationship Overview

The database consists of the following core domains:

Identity Domain
Users
Roles
Permissions
Event Management Domain
Events
Registrations
Attendance
Survey Domain
Survey Templates
Survey Questions
Event Evaluations
Survey Responses
Communication Domain
Notifications
Analytics Domain
Reports
Statistics
Attendance Metrics

Future domains may include:

Certificates
Sponsors
Volunteers
Exhibitors
Schools
Universities
Media Library

The architecture must support these additions without major schema modifications.

10. Users Module

The Users table represents all authenticated platform users.

Fields
UUID Primary Key
Full Name
Email
Password
Phone Number
Gender
Academic Year
School Name
Email Verification Timestamp
Preferred Language
Preferred Theme
Avatar
Status
Last Login
Remember Token
Soft Deletes
Timestamps
Relationships

User has many Events (created_by)

User has many Registrations

User has many Survey Responses

User has many Notifications

User belongs to many Roles

User belongs to many Permissions

Additional Recommendations

Store:

preferred_language

preferred_theme

timezone

notification_preferences (JSONB)

to improve personalization.

11. Events Module

The Events table manages all educational activities.

Supported Event Types:

Seminar
Workshop
Exhibition

Recommended additional fields:

Banner Image
Cover Image
Registration Opens At
Registration Closes At
Event Status
Visibility
Venue Map URL
Contact Person
Organizer Notes
Requires Approval
QR Attendance Enabled

Relationships:

Event

Has Many Registrations

Has Many Evaluations

Has Many Notifications

Belongs To Creator

Future:

Has Many Certificates

Has Many Sponsors

Has Many Media Files

12. Registrations Module

Each registration connects one user to one event.

Business Rules:

One user can register only once per event.

Generate a secure QR hash.

Store registration source.

Store registration status.

Recommended statuses:

Pending
Approved
Rejected
Cancelled
Checked In

Unique constraint:

(user_id,event_id)
13. Attendance Module

Attendance represents successful QR check-in.

Rules:

One attendance record per registration.

Cannot duplicate attendance.

Store:

Scanner User
Scan Time
Device
IP Address

Future:

GPS Coordinates

Offline Scanner Sync

14. Survey Templates

Survey templates are reusable.

Each template contains multiple questions.

Features:

Versioning

Draft Mode

Clone

Duplicate

Archive

Categories

Reusable Flag

15. Survey Questions

Supported Question Types:

Text
Text Area
Rating
Multiple Choice
Checkbox
Number
Date
Email
Phone

Although the initial implementation only requires four types, the schema should remain extensible for future question types without structural changes.

Store multilingual labels:

question_text_ar
question_text_en

Store answer options using JSONB.

Support conditional logic in future versions.

16. Event Evaluations

Each event may contain:

One Pre Assessment

One Post Assessment

Constraint:

Only one evaluation of each type per event.

17. Survey Responses

Responses are immutable records.

Rules:

One response per user per question.

Support text and structured answers.

Future support:

File Upload Answers

Rating Analytics

Sentiment Analysis

AI Recommendations

18. Notifications

Notifications must support:

Individual Users
Roles
Events
Scheduled Delivery
Email
In-App
Push Notifications (future)

Store delivery status and timestamps to facilitate retries and reporting.

19. Future Database Modules

The schema should be designed to accommodate future expansion with minimal refactoring.

Planned modules include:

Certificate Management
Digital Badge Issuing
Sponsor Management
Exhibition Booths
School Directory
University Directory
Career Library
Scholarship Opportunities
AI Career Recommendation Engine
Media Library
Announcement Center
FAQ Management
Contact Requests
System Logs
Activity Timeline
Audit Trail
API Clients
Webhooks
Backup History

Each module should integrate cleanly using foreign keys and services without violating existing architecture.

20. Authentication Architecture
20.1 Authentication Philosophy

The authentication system must be secure, scalable, extensible, and compliant with Laravel best practices.

Only authenticated users can access the administration dashboard.

Every action inside the system must be authenticated, authorized, and auditable.

Authentication must integrate seamlessly with:

Laravel Authentication
Filament PHP v5
Spatie Laravel Permission
Laravel Policies
Gates
Form Requests

Business logic must never rely solely on frontend restrictions.

20.2 Authentication Provider

The platform shall use Laravel Authentication as the primary authentication mechanism.

Authentication requirements:

Secure Login
Logout
Remember Me
Password Reset
Email Verification
Password Confirmation
Session Management
CSRF Protection
Rate Limiting
Secure Cookies
Session Regeneration
20.3 Password Policy

Minimum password requirements:

Minimum 10 characters
Uppercase letter
Lowercase letter
Number
Special character

Disallow:

Common passwords
Previously leaked passwords
User email
User name

Passwords must always be hashed using Laravel's default hashing driver.

20.4 Multi-Language Authentication

All authentication pages must support:

Arabic

English

RTL

LTR

Examples:

Login

Forgot Password

Reset Password

Verify Email

Session Expired

Validation Errors

Success Messages

Everything must be translated using localization files.

20.5 Login Screen

The Filament Login page should follow the official Wejha visual identity.

Include:

Official Logo
Brand Colors
Brand Typography
Official Slogan
Dark Mode
Light Mode
Responsive Design

Optional future enhancements:

Animated Background

Illustration

Accessibility Improvements

20.6 User Preferences

Every authenticated user should have configurable preferences:

Preferred Language

Preferred Theme

Timezone

Notification Preferences

Dashboard Layout

Sidebar State

Items Per Page

These preferences should be stored in JSONB for flexibility.

Example:

{
    "language":"ar",
    "theme":"dark",
    "timezone":"Africa/Tripoli",
    "notifications":true
}
21. Authorization Architecture

Authorization must be implemented using:

Spatie Laravel Permission
Laravel Policies
Laravel Gates

Never authorize access using role names directly inside controllers.

Use permissions instead.

22. Roles

The initial platform contains four primary roles.

System Administrator

Full unrestricted access.

Responsibilities:

Manage Users
Manage Roles
Manage Permissions
Manage Events
Manage Surveys
Manage Reports
Manage Notifications
System Settings
Security
Audit Logs
Database Maintenance
Backup & Restore

Has every permission.

Supervisor

Can manage operational activities.

Responsibilities:

Create Events
Edit Events
Archive Events
Survey Templates
Analytics
Reports
Export Excel
Export PDF
Attendance Reports
Notifications

Cannot:

Manage Roles
Manage Permissions
Delete System Settings
Organizer

Operational event staff.

Responsibilities:

View Events
View Participants
QR Attendance Scanner
Check In Participants
View Attendance
Print Attendance

Cannot:

Create Events

Delete Events

Manage Surveys

System Settings

User Management

Monitor

Read-only role.

Responsibilities:

View Dashboard

View Analytics

View Reports

View Attendance

View Surveys

Cannot modify any data.

23. Permission Matrix

Permissions should be granular.

Examples:

Users

users.view

users.create

users.update

users.delete

users.restore

users.forceDelete

Events

events.view

events.create

events.update

events.delete

events.publish

events.archive

events.export

events.statistics

Registrations

registrations.view

registrations.create

registrations.cancel

registrations.export

registrations.print

Attendance

attendance.view

attendance.scan

attendance.export

attendance.print

attendance.statistics

Survey Templates

survey.view

survey.create

survey.update

survey.delete

survey.publish

survey.archive

survey.clone

Survey Responses

responses.view

responses.export

responses.statistics

Notifications

notifications.view

notifications.create

notifications.send

notifications.schedule

notifications.delete

Reports

reports.view

reports.export_pdf

reports.export_excel

reports.export_csv

reports.print

Settings

settings.view

settings.update

settings.security

settings.localization

settings.appearance

Audit

audit.view

audit.export

activity.view

logs.view

24. Filament Authorization

Every Resource must implement authorization.

Use:

Policies

Permissions

Navigation visibility

Table actions

Bulk actions

Widgets

Relation Managers

Pages

Clusters

Global Search

Unauthorized users must never see hidden resources.

Navigation should automatically adapt based on permissions.

25. Policies

Create dedicated policies for:

UserPolicy

EventPolicy

RegistrationPolicy

AttendancePolicy

SurveyTemplatePolicy

SurveyResponsePolicy

NotificationPolicy

ReportPolicy

SettingsPolicy

Every policy should implement:

viewAny()

view()

create()

update()

delete()

restore()

forceDelete()

Additional business methods where needed.

26. Security Layer

The application must implement:

CSRF Protection

XSS Protection

SQL Injection Protection

Mass Assignment Protection

Output Escaping

Signed URLs

Encrypted Cookies

Secure Sessions

HTTPS Enforcement

Content Security Policy

Security Headers

Strict Transport Security

Input Sanitization

File Validation

MIME Validation

Rate Limiting

IP Logging

Device Logging

27. Two-Factor Authentication (Future Ready)

The architecture should be designed to support:

Email OTP

Authenticator Apps

Recovery Codes

Trusted Devices

Although optional in version 1.0, the design should allow seamless future integration.

28. Audit Trail

Every sensitive action must be recorded.

Examples:

Login

Logout

Failed Login

Password Change

Event Created

Event Updated

Registration Approved

Attendance Scanned

Survey Published

Report Exported

User Deleted

Permission Changed

Each audit record should store:

User

Action

Entity

Entity ID

IP Address

User Agent

Timestamp

Old Values

New Values

29. Activity Timeline

Every Event should maintain a chronological activity timeline.

Example:

09:30 — Event Created

09:35 — Registration Opened

10:10 — Participant Registered

10:15 — QR Checked In

11:40 — Survey Published

12:30 — Report Generated

This timeline improves traceability and operational oversight.

30. Logging

Application logs should include:

Application Errors

Exceptions

Validation Errors

Security Events

Queue Failures

Mail Failures

Export Failures

API Errors

Logs should support rotation and centralized monitoring.

31. Backup Strategy

Support:

Database Backups

File Backups

Media Backups

Configuration Backups

Automatic Scheduling

Cloud Storage

Restore Process

Backup Verification

32. Security Best Practices

Never expose:

Database IDs

Passwords

Secrets

API Keys

Tokens

Stack Traces

Internal Exceptions

Always validate every request using Form Requests.

Never trust client-side validation.

Use transactions for critical operations.

Use authorization before validation where appropriate.

Avoid N+1 queries.

Always eager load relationships.

33. Future Enterprise Security

The architecture should remain compatible with:

Single Sign-On (SSO)

OAuth 2.0

OpenID Connect

LDAP

Microsoft Entra ID (Azure AD)

Google Workspace

Multi-Tenant Authentication

API Authentication using Laravel Sanctum or Passport

34. Administration Panel Philosophy

The administration dashboard is the operational heart of the Wejha Digital Platform.

It must provide a modern, fast, intuitive, and scalable user experience while strictly adhering to the official Wejha Brand Identity.

The dashboard must feel comparable in quality to enterprise systems such as:

Salesforce
HubSpot
Notion
Linear
Stripe Dashboard
Vercel Dashboard

The design should emphasize:

Simplicity
Clarity
Speed
Accessibility
Professionalism
Data Visualization
Excellent User Experience
35. Filament Panel Configuration

The system shall use Filament PHP v5 as the exclusive administration framework.

The panel must support:

Multi-language (Arabic / English)
RTL / LTR
Light Mode
Dark Mode
Responsive Layout
Permission-aware Navigation
Global Search
Keyboard Navigation
Accessibility (WCAG AA)
Panel Branding

Use the official Wejha branding throughout the panel:

Official Logo
Official Color Palette
Official Typography
Official Slogan
Official Icons

Sidebar and login pages should visually match the Wejha identity.

36. Dashboard Layout

The landing dashboard should present an executive overview.

Header

Display:

Welcome Message
User Name
Current Role
Current Language
Theme Toggle
Notifications
User Menu
KPI Cards

Top section includes:

Total Events
Active Events
Total Registrations
Attendance Rate
Survey Completion Rate
Active Users
Schools Participating
Upcoming Events

Each card includes:

Icon
Trend Indicator
Percentage Change
Mini Sparkline
Tooltip
Last Updated Timestamp
Charts

The dashboard includes:

Attendance Rate

Chart Type:

Doughnut

Displays:

Registered
Attended
Absent
Event Registrations

Chart Type:

Line Chart

Displays registrations over time.

Top Schools

Chart Type:

Horizontal Bar Chart

Displays schools ranked by participant count.

Survey Ratings

Chart Type:

Line Chart

Displays average satisfaction over time.

Event Distribution

Chart Type:

Pie Chart

Displays:

Workshops
Seminars
Exhibitions
Monthly Activity

Heatmap Calendar

Displays activity intensity by date.

37. Navigation Structure

Navigation should be grouped using Filament Clusters.

Dashboard
Overview
Analytics
Activity Timeline
Event Management
Events
Registrations
Attendance
QR Scanner
Surveys
Survey Templates
Event Evaluations
Survey Responses
Users
Users
Roles
Permissions
Communication
Notifications
Email Templates
Reports
Attendance Reports
Survey Reports
Registration Reports
Analytics
Settings
General
Localization
Appearance
Security
Backup

Navigation visibility must respect permissions automatically.

38. Event Resource

The Event Resource is the core administrative module.

Table Features

Columns:

Title
Event Type
Date
Capacity
Registered Count
Attendance Count
Remaining Seats
Status
Creator
Created At

Features:

Global Search
Sorting
Advanced Filters
Column Toggle
Bulk Actions
Inline Badges
Export
Print
Form

Use a Wizard Form.

Step 1

Basic Information

Title
Description
Type
Banner
Cover
Speaker
Step 2

Schedule

Date
Time
Venue
Map
Capacity
Step 3

Registration

Registration Opens
Registration Closes
QR Enabled
Approval Required
Step 4

Publishing

Status
Visibility
Featured
Notifications
39. Relation Managers

Inside Event Resource

Use Tabs.

Registrations

Table

Actions

Filters

Export

Search

Bulk Approval

Bulk Cancel

Attendance

Separate Tab

Statistics

Attendance %

Print

Export

Evaluations

Manage:

Pre Assessment

Post Assessment

Activate

Deactivate

Clone

Preview

40. Dynamic Survey Builder

This is one of the platform's flagship features.

Implement using Filament Repeater.

Each Survey contains unlimited Questions.

Each Question contains:

Question Arabic

Question English

Question Type

Required

Description

Help Text

Score

Sort Order

Supported Question Types

Text
Textarea
Rating
Multiple Choice
Checkbox
Number
Date
Email
Phone

Nested Repeater

If Question Type is:

Multiple Choice

Checkbox

Display nested repeater.

Each Option contains:

Arabic Label

English Label

Score

Color

Icon

Store options in PostgreSQL JSONB.

Advanced Features

Clone Question

Duplicate Survey

Drag & Drop Sorting

Draft Mode

Live Preview

Version History

Import JSON

Export JSON

41. QR Attendance Scanner

Custom Filament Page.

Accessible only to:

Organizer

Supervisor

Admin

UI

Camera Preview

Scan Frame

Flash Toggle

Device Selector

Manual Entry

Last Scan

Recent Attendance

Statistics Sidebar

Workflow

Receive QR Hash

↓

Validate Registration

↓

Verify Event

↓

Check Attendance Exists

↓

Create Attendance

↓

Update Dashboard

↓

Play Success Sound

↓

Show Green Animation

↓

Display Student Card

Duplicate Scan

Display:

Already Checked In

Time of Previous Scan

Operator Name

Invalid QR

Display:

Invalid Ticket

No Registration Found

42. Survey Responses

Response Viewer

Features:

Filters

Search

Average Ratings

Question Breakdown

Charts

Excel Export

PDF Export

CSV Export

Anonymous Mode

43. Notification Center

Features

Create Notification

Schedule Notification

Immediate Notification

Role Based

Event Based

User Based

Preview

History

Delivery Status

44. Reports

Professional Reporting Module

Reports:

Attendance

Registration

Schools

Events

Survey

Users

Performance

Export Formats

PDF

Excel

CSV

Print

Reports should include:

Logo

Brand Colors

Title

Generated By

Date

Filters Used

Charts

Summary

45. Widgets

Dashboard Widgets

Upcoming Events

Attendance %

Latest Registrations

Recent Activity

Notifications

Quick Actions

System Health

Survey Completion

46. Global Search

Search across:

Events

Users

Schools

Surveys

Registrations

Notifications

Reports

Search must support:

Arabic

English

Partial Matching

Fast Indexing

47. Advanced Table Features

Every Resource Table should support:

Advanced Filters
Saved Filters
Bulk Actions
Export
Print
Search
Pagination
Column Groups
Summaries
Sticky Header
Sticky Columns
Toggle Columns
48. User Experience

Every page should include:

Breadcrumbs
Page Title
Description
Quick Actions
Keyboard Shortcuts
Loading Indicators
Skeleton Loading
Empty States
Success Animations
Confirmation Dialogs
Toast Notifications
49. Responsive Design

The administration panel must function seamlessly on:

Desktop

Laptop

Tablet

Mobile

All Filament components should remain usable without horizontal scrolling.

50. Accessibility

The panel must comply with WCAG AA guidelines.

Requirements:

Keyboard Navigation
Screen Reader Labels
Color Contrast Compliance
Focus Indicators
Accessible Forms
Accessible Tables
Proper ARIA Attributes
51. Future Filament Modules

The architecture should anticipate future resources such as:

Certificate Generator
Scholarship Management
University Directory
School Management
Career Library
AI Recommendation Engine
Volunteer Management
Sponsors
Exhibition Booths
Media Library
FAQ Management
CMS Pages
Announcement Center
Feedback Portal

Each module should integrate consistently with existing navigation, permissions, branding, and localization.

52. Business Logic Architecture
52.1 Design Philosophy

The business logic must never reside inside controllers or Filament Resources.

The application shall follow a layered architecture:

HTTP Request
      │
      ▼
Controller
      │
      ▼
Form Request Validation
      │
      ▼
Service Layer
      │
      ▼
Repository Layer
      │
      ▼
Database

Every business process must be encapsulated in a dedicated Service class.

Examples:

EventBookingService
AttendanceService
SurveyService
NotificationService
ReportService
AnalyticsService
QRScannerService
UserManagementService

Repositories will handle all database operations to isolate persistence from business logic.

53. Controller Specification

Controllers should only:

Receive Requests
Authorize
Validate
Call Services
Return Responses

Controllers must never:

Write SQL
Contain Business Logic
Manipulate Models directly
EventBookingController

Responsibilities:

Validate event availability
Verify registration period
Check event capacity
Prevent duplicate registrations
Generate secure QR hash
Create registration
Queue confirmation notification
Return Inertia response

All operations must execute inside a DB::transaction().

SurveyController

Responsibilities:

Validate survey answers
Ensure survey is active
Ensure user is eligible
Verify attendance for post-assessment
Store all answers atomically
Queue analytics update

All inserts must occur within a single database transaction.

AttendanceController

Responsibilities:

Validate QR hash
Verify registration
Prevent duplicate attendance
Record attendance
Trigger notification
Update dashboard metrics
54. Validation Rules

Validation must use dedicated Form Request classes.

Examples:

StoreEventRequest
UpdateEventRequest
RegisterForEventRequest
SubmitSurveyRequest
ScanAttendanceRequest
SendNotificationRequest

Validation must be centralized and reusable.

55. Service Layer

Create dedicated services for each domain:

EventService
RegistrationService
AttendanceService
SurveyTemplateService
SurveyResponseService
NotificationService
AnalyticsService
ReportService
UserPreferenceService

Each service must expose a clear public API and hide implementation details.

56. Repository Layer

Repositories abstract persistence and allow future database changes.

Suggested repositories:

UserRepository
EventRepository
RegistrationRepository
AttendanceRepository
SurveyRepository
NotificationRepository
ReportRepository
57. Notification Workflow

The notification engine must support:

Immediate Notifications
Scheduled Notifications
Role-based Notifications
Event-based Notifications
User-specific Notifications

Channels:

In-App
Email
Queue-ready for SMS / Push (future)

All notifications should be queued.

58. QR Attendance Workflow
Participant arrives
        │
        ▼
Organizer scans QR
        │
        ▼
Validate QR Hash
        │
        ▼
Verify Registration
        │
        ▼
Check Duplicate Attendance
        │
        ▼
Create Attendance Record
        │
        ▼
Update Dashboard Metrics
        │
        ▼
Show Success Animation
        │
        ▼
Log Activity
59. Analytics Engine

Generate real-time metrics:

Registration Rate
Attendance Rate
No-show Rate
Survey Completion
Satisfaction Score
Top Schools
Popular Event Types
Monthly Trends

All charts should support dynamic filters.

60. Export System

Supported formats:

PDF
Excel
CSV
Print

Exports must include:

Official Logo
Official Colors
Report Title
Filters Applied
Generated By
Generated At
Charts
Summary Statistics
61. Queue System

Queue the following operations:

Email Sending
Notification Delivery
Excel Generation
PDF Generation
Analytics Refresh
Scheduled Reports
Future AI Recommendations

Support:

Database Queue
Redis Queue
62. Performance Optimization

The platform must remain responsive with large datasets.

Guidelines:

Eager Loading
Lazy Loading where appropriate
Pagination
Cursor Pagination for large tables
Composite Indexes
JSONB GIN Indexes
Query Caching
Route Caching
Config Caching
View Caching
Queue Workers
Database Transactions
63. Caching Strategy

Use Laravel Cache for:

Dashboard Metrics
Top Schools
Event Statistics
Survey Statistics
User Preferences
System Settings

Cache invalidation should occur automatically when relevant data changes.

64. API Design

Expose versioned endpoints.

/api/v1/

Future compatibility:

/api/v2/
/api/v3/

All APIs should return standardized JSON structures.

Example:

{
  "success": true,
  "message": "Registration completed successfully.",
  "data": {}
}

Use API Resources to transform responses.

65. Error Handling

Implement centralized exception handling.

Custom exceptions:

EventCapacityExceededException
DuplicateRegistrationException
InvalidQRCodeException
SurveyClosedException
UnauthorizedAttendanceException

Return meaningful, localized error messages.

66. Logging & Monitoring

Log:

Authentication Events
CRUD Operations
Attendance Scans
Survey Submissions
Failed Jobs
Export Operations
Security Incidents

Prepare integration with tools like:

Laravel Telescope
Laravel Pulse
Sentry
Bugsnag
67. Testing Strategy

Use Pest PHP.

Required tests:

Unit Tests
Services
Repositories
Helpers
Feature Tests
Authentication
Event Booking
Attendance
Survey Submission
Notifications
Reports
Browser Tests (Future)
Filament Dashboard
QR Scanner Flow

Target:

90%+ code coverage.
68. DevOps & Deployment

Prepare the project for containerized deployment.

Support:

Docker
Docker Compose
Nginx
PHP-FPM
PostgreSQL
Redis
Supervisor

CI/CD pipeline should include:

Static Analysis
PHPStan
Pint
Pest Tests
Build
Deploy

Compatible with:

GitHub Actions
GitLab CI
Azure DevOps
69. Documentation

Provide:

Installation Guide
Environment Setup
Deployment Guide
API Documentation (OpenAPI/Swagger)
Database ERD
Sequence Diagrams
Class Diagrams
Architecture Diagrams
User Manual
Administrator Manual
70. Production Checklist

Before deployment, ensure:

APP_DEBUG=false
HTTPS enforced
Queues running
Cache warmed
Config cached
Routes cached
Scheduled tasks configured
Backups enabled
Monitoring active
Logs rotating
Security headers configured
71. Future Roadmap

The architecture should support seamless addition of:

AI Career Recommendation Engine
Scholarship Management
University Directory
School Portal
Student Mobile Application
Parent Portal
Volunteer Management
Sponsor Management
Exhibition Booth Management
Digital Certificates
Badge Printing
Live Streaming Integration
AI Chat Assistant
Calendar Synchronization
Payment Gateway Integration
Multi-Tenancy
Public API Platform
72. Final AI Development Instructions

Any AI system or development team implementing this specification must:

Generate production-ready Laravel 11 code using PHP 8.3.
Use PostgreSQL 15/16 with optimized UUIDs, JSONB, indexes, and constraints.
Build the administration panel exclusively with Filament PHP v5, using modern features such as Resources, Clusters, Widgets, Infolists, Wizard Forms, Relation Managers, Custom Pages, and Global Search.
Follow Clean Architecture with Controllers, Form Requests, Services, Repositories, Policies, and DTOs.
Implement bilingual support (Arabic/English), automatic RTL/LTR switching, and full localization.
Support Light Mode, Dark Mode, and System Theme across the entire dashboard.
Apply the official Wejha 2026 visual identity consistently, including colors, typography, branding, and exported documents.
Integrate Spatie Laravel Permission for granular role and permission management.
Use queues, caching, eager loading, and PostgreSQL optimizations to maximize performance.
Deliver clean, maintainable, secure, fully documented, and enterprise-grade code with comprehensive testing and deployment readiness.