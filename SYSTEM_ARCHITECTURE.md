# 📊 EMAIL NOTIFICATIONS - SYSTEM ARCHITECTURE & DIAGRAMS

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                     USER INTERACTION LAYER                          │
│  (Registration, Application, Admin Actions, Contact Form)           │
└────────────────┬────────────────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│                  CONTROLLER LAYER                                   │
│  ├─ AuthController (Register)                                       │
│  ├─ UserManagementController (Approve/Reject)                       │
│  ├─ VacancyManagementController (Approve/Reject)                    │
│  ├─ CompanyDashboardController (Application Status)                 │
│  ├─ StudentDashboardController (Application Submit)                 │
│  └─ ContactController (Contact Form)                                │
└────────────────┬────────────────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│            NOTIFICATION DISPATCH (INSTANT)                          │
│  ├─ RegistrationNotification                                        │
│  ├─ AccountApprovedNotification                                     │
│  ├─ AccountRejectedNotification                                     │
│  ├─ ApplicationSubmittedNotification                                │
│  ├─ ApplicationStatusNotification                                   │
│  ├─ VacancyApprovedNotification                                     │
│  ├─ VacancyRejectedNotification                                     │
│  └─ ContactFormMail                                                 │
│  └─ All implement ShouldQueue                                       │
└────────────────┬────────────────────────────────────────────────────┘
                 │
                 ▼ (Non-blocking)
┌─────────────────────────────────────────────────────────────────────┐
│              QUEUE STORAGE (DATABASE)                               │
│  PostgreSQL `jobs` table                                            │
│  ├─ job_id (UUID)                                                   │
│  ├─ queue (default)                                                 │
│  ├─ payload (serialized notification)                               │
│  ├─ attempts (retry count)                                          │
│  └─ available_at (scheduled time)                                   │
└────────────────┬────────────────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│         QUEUE WORKER (php artisan queue:work)                       │
│  ├─ Continuous polling                                              │
│  ├─ Process 1 job at a time                                         │
│  ├─ Retry failed jobs (max 3 times)                                 │
│  └─ Log all errors                                                  │
└────────────────┬────────────────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────────────────┐
│                 DUAL CHANNEL DELIVERY                               │
│  ├─ EMAIL CHANNEL                 ├─ DATABASE CHANNEL              │
│  │  ├─ SMTP Config                │  ├─ notifications table         │
│  │  ├─ Gmail/Mailtrap/etc         │  ├─ Store notification record  │
│  │  ├─ Blade template             │  ├─ For in-app display        │
│  │  ├─ HTML email                 │  └─ Mark as read/unread       │
│  │  └─ Send via Mail::send()      │                               │
│  └─                               └─                              │
└────────────────┬────────────────────────────────────────────────────┘
                 │
     ┌───────────┴───────────┐
     ▼                       ▼
┌──────────────┐      ┌──────────────┐
│ Email Inbox  │      │ In-App Panel │
│ (User reads) │      │ (User reads) │
└──────────────┘      └──────────────┘
```

---

## Notification Flow Sequence Diagram

```
User                Controller           Notification          Queue           Worker           Email
  │                    │                     │                 │               │                │
  ├─ Register ─────────→│                     │                 │               │                │
  │                    │─ Create User ────────→│                 │               │                │
  │                    │                     │─ Dispatch Job───→│               │                │
  │ ← Success ─────────│ (Return Instantly)  │                 │               │                │
  │                    │                     │ Job queued      │               │                │
  │                    │                     │                 │               │                │
  │                    │                     │                 │ Poll ─────────→ Process Job   │
  │                    │                     │                 │                │ Build Email   │
  │                    │                     │                 │                │─ Send SMTP ──→ SENT ✅
  │                    │                     │                 │                │                │
```

### Timeline
```
t=0ms    : User submits form
t=10ms   : User sees "Success!" message (Controller returns)
t=50ms   : Job queued in database
t=100ms  : Queue worker picks up job
t=200ms  : SMTP connects to Gmail
t=300ms  : Email sent
t=310ms  : Total time (User waited only 10ms!)
```

---

## Email Notification Types

### 1️⃣ REGISTRATION NOTIFICATION
```
TRIGGER:  Student/Company registration completed
SENT TO:  Registered user
TIME:     Immediately after registration
PATH:     AuthController → notify() → RegistrationNotification
CONTENT:  
  ├─ Welcome message
  ├─ Account status (pending verification)
  ├─ Dashboard link
  └─ Next steps instructions
```

### 2️⃣ ACCOUNT APPROVED NOTIFICATION
```
TRIGGER:  Admin approves user account
SENT TO:  Approved user
TIME:     Immediately after approval
PATH:     UserManagementController → approve() → AccountApprovedNotification
CONTENT:
  ├─ Approval confirmation
  ├─ Dashboard access enabled
  ├─ Dashboard link
  └─ Welcome to platform message
```

### 3️⃣ ACCOUNT REJECTED NOTIFICATION
```
TRIGGER:  Admin rejects user account
SENT TO:  Rejected user
TIME:     Immediately after rejection
PATH:     UserManagementController → reject() → AccountRejectedNotification
CONTENT:
  ├─ Rejection notification
  ├─ Reason for rejection
  ├─ How to reapply
  └─ Support contact info
```

### 4️⃣ APPLICATION SUBMITTED NOTIFICATION
```
TRIGGER:  Student submits job application
SENT TO:  Company receiving application
TIME:     Immediately after submission
PATH:     StudentDashboardController → submitApplication() → ApplicationSubmittedNotification
CONTENT:
  ├─ New application alert
  ├─ Job title
  ├─ Applicant name & email
  ├─ View application link
  └─ Action needed notification
```

### 5️⃣ APPLICATION STATUS NOTIFICATION
```
TRIGGER:  Company updates application status
SENT TO:  Applying student
TIME:     Immediately after status update
PATH:     CompanyDashboardController → applicationUpdateStatus() → ApplicationStatusNotification
CONTENT:
  ├─ Status update (reviewed/accepted/rejected/interview/test)
  ├─ Job title
  ├─ Company notes (if any)
  ├─ Application detail link
  └─ Next steps
```

### 6️⃣ VACANCY APPROVED NOTIFICATION
```
TRIGGER:  Admin approves job vacancy
SENT TO:  Company that posted vacancy
TIME:     Immediately after approval
PATH:     VacancyManagementController → approve() → VacancyApprovedNotification
CONTENT:
  ├─ Vacancy approval confirmation
  ├─ Job title
  ├─ Vacancy type (internship/fulltime)
  ├─ Vacancy is now public
  ├─ View vacancy link
  └─ Share instruction
```

### 7️⃣ VACANCY REJECTED NOTIFICATION
```
TRIGGER:  Admin rejects job vacancy
SENT TO:  Company that posted vacancy
TIME:     Immediately after rejection
PATH:     VacancyManagementController → reject() → VacancyRejectedNotification
CONTENT:
  ├─ Vacancy rejection notification
  ├─ Job title
  ├─ Reason for rejection
  ├─ How to resubmit
  ├─ Edit vacancy link
  └─ Support contact
```

### 8️⃣ CONTACT FORM EMAIL
```
TRIGGER:  User submits contact form on website
SENT TO:  Admin email (from settings)
TIME:     Immediately after submission
PATH:     ContactController → store() → ContactFormMail
CONTENT:
  ├─ Sender name
  ├─ Sender email (reply-to)
  ├─ Message subject
  ├─ Full message
  ├─ Timestamp
  └─ Reply button
```

---

## Database Tables Involved

```
┌─────────────────┐
│   users         │
├─────────────────┤
│ id              │
│ email           │◄─── Email recipient
│ role            │
│ status          │
│ created_at      │
└────────┬────────┘
         │
         │ 1:N
         ▼
┌─────────────────────┐
│   notifications     │──── Database channel storage
├─────────────────────┤
│ id                  │
│ notifiable_id       │
│ notifiable_type     │
│ type                │
│ data (JSON)         │
│ read_at             │
│ created_at          │
└─────────────────────┘

┌─────────────────────┐
│   jobs              │──── Queue storage
├─────────────────────┤
│ id                  │
│ queue               │
│ payload             │
│ attempts            │
│ reserved_at         │
│ available_at        │
│ created_at          │
└─────────────────────┘

┌─────────────────────┐
│   failed_jobs       │──── Failed job tracking
├─────────────────────┤
│ id                  │
│ uuid                │
│ connection          │
│ queue               │
│ payload             │
│ exception           │
│ failed_at           │
└─────────────────────┘
```

---

## Configuration Files Map

```
BKK Application
├── .env (EMAIL & QUEUE CONFIG)
│   ├── MAIL_MAILER=smtp
│   ├── MAIL_HOST=smtp.gmail.com
│   ├── MAIL_PORT=587
│   ├── MAIL_USERNAME=email@gmail.com
│   ├── MAIL_PASSWORD=app_password
│   ├── MAIL_ENCRYPTION=tls
│   ├── MAIL_FROM_ADDRESS=email@gmail.com
│   ├── MAIL_FROM_NAME="BKK SMKN 1"
│   └── QUEUE_CONNECTION=database
│
├── config/
│   ├── mail.php (MAILER SETUP)
│   │   ├── default: failover (SMTP → Log)
│   │   ├── mailers.smtp
│   │   ├── mailers.log
│   │   └── mailers.failover
│   └── queue.php (QUEUE CONFIG)
│       └── default: database
│
├── app/
│   ├── Notifications/ (8 NOTIFICATION CLASSES)
│   │   ├── RegistrationNotification.php
│   │   ├── AccountApprovedNotification.php
│   │   ├── AccountRejectedNotification.php
│   │   ├── ApplicationSubmittedNotification.php
│   │   ├── ApplicationStatusNotification.php
│   │   ├── VacancyApprovedNotification.php
│   │   ├── VacancyRejectedNotification.php
│   │   └── (Plus ContactFormMail in Mail/)
│   │
│   ├── Http/Controllers/
│   │   ├── AuthController.php (Triggers: Register)
│   │   ├── Admin/UserManagementController.php (Triggers: Approve/Reject)
│   │   ├── Admin/VacancyManagementController.php (Triggers: Approve/Reject)
│   │   ├── Company/CompanyDashboardController.php (Triggers: Status)
│   │   ├── Student/StudentDashboardController.php (Triggers: Submit)
│   │   └── ContactController.php (Triggers: Contact)
│   │
│   └── Mail/
│       └── ContactFormMail.php
│
├── resources/views/
│   ├── emails/
│   │   └── contact-form.blade.php (Contact email template)
│   └── vendor/notifications/ (Default notification templates)
│
└── database/migrations/
    ├── ...jobs table...
    ├── ...failed_jobs table...
    └── ...notifications table...
```

---

## Performance Characteristics

### Email Delivery Timeline

```
SYNCHRONOUS (Without Queue):
┌─────────────────────────────────────┐
│ Register User                       │
│   ├─ Create user (1ms)             │
│   ├─ Send email (2000ms) ⏳ WAIT    │
│   ├─ Return success (50ms)         │
│   └─ Total: ~2 seconds             │
└─────────────────────────────────────┘
User waits 2 seconds ❌

ASYNCHRONOUS (With Queue):
┌─────────────────────────────────────┐
│ Register User                       │
│   ├─ Create user (1ms)             │
│   ├─ Queue notification (10ms)     │
│   ├─ Return success (50ms)         │
│   └─ Total: ~60ms                  │
└─────────────────────────────────────┘
User sees success instantly ✅

Background Processing:
┌─────────────────────────────────────┐
│ Queue Worker                        │
│   ├─ Get job (10ms)               │
│   ├─ Process notification (50ms)   │
│   ├─ Send email (1000ms)           │
│   └─ Mark complete (10ms)          │
└─────────────────────────────────────┘
Email sent while user continues...
```

### Throughput Comparison

```
SYNC DELIVERY:
┌─────────────┐
│ Email 1: 2s │
│ Email 2: 2s │
│ Email 3: 2s │
└─────────────┘
Total: 6 seconds for 3 emails
= 0.5 emails/second

QUEUE DELIVERY (Single Worker):
┌─────────┐ ┌─────────┐ ┌─────────┐
│ Email 1 │ │ Email 2 │ │ Email 3 │
│  1 sec  │ │  1 sec  │ │  1 sec  │
└─────────┘ └─────────┘ └─────────┘
Total: 3 seconds for 3 emails
= 1 email/second

QUEUE DELIVERY (3 Workers):
┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐
│ E1   │ │ E2   │ │ E3   │ │ E4   │
└──────┘ └──────┘ └──────┘ └──────┘
Total: 1.3 seconds for 4 emails
= 3 emails/second
```

---

## Error Handling Flow

```
Notification Triggered
        │
        ▼
    Try Send
        │
    ┌───┴───┐
    │       │
  FAIL    SUCCESS
    │       │
    ▼       ▼
  Retry   ✅ Email Sent
  Count?
    │
  ┌─┴─┐
  │   │
 <3  ≥3
 │    │
 ▼    ▼
Retry ❌ Failed
  │       │
  │       ▼
  │    failed_jobs table
  │       │
  │       ▼
  │    Can manually retry
  │    php artisan queue:retry all
  │
  ▼
Try Again
```

---

## Deployment Architecture

```
LOCAL DEVELOPMENT:
┌─────────────────────────────────────┐
│ Laravel Application                 │
│  ├─ Queue: database (sync for dev)  │
│  ├─ Mail: log (to file)             │
│  └─ Manual: php artisan queue:work  │
└─────────────────────────────────────┘

PRODUCTION:
┌────────────────────────────────────────────────────┐
│ Load Balancer / Nginx                              │
│   ├─ Multiple Laravel Application Servers         │
│   │   ├─ Handle user requests                     │
│   │   └─ Dispatch jobs to queue                   │
│   │                                               │
│   ├─ PostgreSQL Database                          │
│   │   ├─ jobs table (queue storage)              │
│   │   ├─ notifications table                      │
│   │   └─ failed_jobs table                        │
│   │                                               │
│   ├─ Queue Workers (via Supervisor)               │
│   │   ├─ Worker 1 (1 job at a time)              │
│   │   ├─ Worker 2                                 │
│   │   └─ Worker 3                                 │
│   │       └─ All send via SMTP (Gmail/SendGrid)   │
│   │                                               │
│   └─ Monitoring & Logging                         │
│       ├─ Queue status monitoring                  │
│       ├─ Error logging                            │
│       └─ Email delivery tracking                  │
└────────────────────────────────────────────────────┘
```

---

## Monitoring Dashboard

```
QUEUE STATUS DASHBOARD
═══════════════════════════════════════

📊 Current Stats:
  ├─ Pending Jobs:      42
  ├─ Failed Jobs:       2
  ├─ Processing:        3
  └─ Processed Today:   1,234

📈 Performance:
  ├─ Avg Process Time:  1.2 seconds
  ├─ Success Rate:      99.8%
  ├─ Emails/Second:     12.5
  └─ Workers Running:   3/3

⚠️  Alerts:
  ├─ 1 job failing repeatedly
  └─ Worker 2 idle for 5 min

🔍 Recent Jobs:
  1. RegistrationNotification (success)
  2. ApplicationStatusNotification (success)
  3. ContactFormEmail (success)
  4. VacancyApprovedNotification (failed - retry)

📝 Logs:
  [2024-12-16 14:32:10] Processed job #123
  [2024-12-16 14:32:11] Email sent to user@example.com
  [2024-12-16 14:32:12] Job #124 completed
```

---

## Queue Worker Health Check

```
✅ HEALTHY STATE:
  ├─ Workers running:    3/3 ✅
  ├─ Pending jobs:       < 100 ✅
  ├─ Failed jobs:        < 5 ✅
  ├─ Process time:       < 5s ✅
  ├─ Success rate:       > 98% ✅
  └─ CPU usage:          < 50% ✅

⚠️ WARNING STATE:
  ├─ Workers running:    < 3 ⚠️
  ├─ Pending jobs:       100-500 ⚠️
  ├─ Failed jobs:        5-20 ⚠️
  ├─ Process time:       5-10s ⚠️
  ├─ Success rate:       90-98% ⚠️
  └─ CPU usage:          50-80% ⚠️

❌ CRITICAL STATE:
  ├─ Workers running:    0 ❌
  ├─ Pending jobs:       > 500 ❌
  ├─ Failed jobs:        > 20 ❌
  ├─ Process time:       > 10s ❌
  ├─ Success rate:       < 90% ❌
  └─ CPU usage:          > 80% ❌
```

---

**Architecture Documentation:** Complete ✅
**Last Updated:** December 16, 2025
