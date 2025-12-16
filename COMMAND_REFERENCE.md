# 🔧 COMMAND REFERENCE - Email Notifications

## Quick Commands Reference

### Setup & Initialization

```bash
# 1. Create queue jobs table
php artisan queue:table

# 2. Run all migrations
php artisan migrate

# 3. Clear configuration cache
php artisan config:clear

# 4. Clear all caches
php artisan cache:clear

# 5. All in one
php artisan queue:table && php artisan migrate && php artisan config:clear && php artisan cache:clear
```

---

## Queue Worker Commands

### Start Queue Worker

```bash
# Basic - process one job at a time
php artisan queue:work

# With verbose output
php artisan queue:work --verbose

# With custom settings
php artisan queue:work --sleep=3 --tries=3 --timeout=60

# Options:
# --sleep=N    : Sleep N seconds between jobs
# --tries=N    : Retry failed jobs N times
# --timeout=N  : Job timeout in seconds
# --memory=M   : Memory limit in MB
```

### Monitor Queue

```bash
# Show current queue status
php artisan queue:monitor

# Show failed jobs
php artisan queue:failed

# Show failed job details
php artisan queue:failed-show {id}

# Retry a specific failed job
php artisan queue:retry {id}

# Retry all failed jobs
php artisan queue:retry all

# Purge failed jobs older than 7 days
php artisan queue:prune-failed --hours=168

# Flush all failed jobs
php artisan queue:flush
```

### Test Email Sending

```bash
# Send test email
php artisan mail:test recipient@example.com

# Check mail config
php artisan config:show mail

# Check queue config
php artisan config:show queue
```

---

## Testing Notifications via Tinker

### Enter Tinker Console

```bash
php artisan tinker
```

### Test Each Notification

```php
# ===== REGISTRATION NOTIFICATION =====
use App\Models\User;
use App\Notifications\RegistrationNotification;

$user = User::find(1);
$user->notify(new RegistrationNotification());
echo "✓ Registration notification sent";

# ===== ACCOUNT APPROVED NOTIFICATION =====
use App\Notifications\AccountApprovedNotification;

$user = User::find(2);
$user->notify(new AccountApprovedNotification());
echo "✓ Account approved notification sent";

# ===== ACCOUNT REJECTED NOTIFICATION =====
use App\Notifications\AccountRejectedNotification;

$user = User::find(3);
$user->notify(new AccountRejectedNotification('Account tidak memenuhi kriteria'));
echo "✓ Account rejected notification sent";

# ===== APPLICATION SUBMITTED NOTIFICATION =====
use App\Models\Application;
use App\Notifications\ApplicationSubmittedNotification;

$application = Application::find(1);
$application->jobVacancy->company->user->notify(
    new ApplicationSubmittedNotification($application)
);
echo "✓ Application submitted notification sent";

# ===== APPLICATION STATUS NOTIFICATION =====
use App\Notifications\ApplicationStatusNotification;

$application = Application::find(1);
$application->student->user->notify(
    new ApplicationStatusNotification($application)
);
echo "✓ Application status notification sent";

# ===== VACANCY APPROVED NOTIFICATION =====
use App\Models\JobVacancy;
use App\Notifications\VacancyApprovedNotification;

$vacancy = JobVacancy::find(1);
$vacancy->company->user->notify(new VacancyApprovedNotification($vacancy));
echo "✓ Vacancy approved notification sent";

# ===== VACANCY REJECTED NOTIFICATION =====
use App\Notifications\VacancyRejectedNotification;

$vacancy = JobVacancy::find(2);
$vacancy->company->user->notify(new VacancyRejectedNotification($vacancy));
echo "✓ Vacancy rejected notification sent";

# ===== CONTACT FORM EMAIL =====
use App\Models\ContactRequest;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;

$contact = ContactRequest::find(1);
Mail::to('admin@example.com')->send(new ContactFormMail($contact));
echo "✓ Contact form email sent";
```

### Check Queue Status

```php
# Check pending jobs
DB::table('jobs')->count();

# View pending jobs
DB::table('jobs')->get();

# Check failed jobs
DB::table('failed_jobs')->count();

# View failed jobs
DB::table('failed_jobs')->get();

# Check notifications in database
DB::table('notifications')->count();

# View recent notifications
DB::table('notifications')->orderBy('created_at', 'desc')->limit(10)->get();

# Check specific user notifications
DB::table('notifications')
    ->where('notifiable_id', 1)
    ->orderBy('created_at', 'desc')
    ->get();
```

---

## Configuration Commands

### View Configuration

```bash
# View all mail config
php artisan config:show mail

# View all queue config
php artisan config:show queue

# View specific setting
php artisan config:show mail.from.address
```

### Clear Caches

```bash
# Clear config cache
php artisan config:clear

# Clear all caches
php artisan cache:clear

# Clear specific cache
php artisan cache:forget key_name

# Clear route cache (if using route:cache)
php artisan route:clear
```

---

## Database Commands

### Queue Tables

```bash
# Create queue:table migration
php artisan queue:table

# Create failed_jobs table
php artisan queue:failed-table

# Run all pending migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Migrate specific file
php artisan migrate --path=database/migrations/2024_01_01_000000_create_jobs_table.php
```

### Check Database Status

```bash
# In Tinker:

# List all tables
DB::connection()->getDoctrineSchemaManager()->listTableNames()

# Check if table exists
Schema::hasTable('jobs')

# Get table columns
Schema::getColumnListing('jobs')

# Check specific data
DB::table('jobs')->count()
DB::table('failed_jobs')->count()
DB::table('notifications')->count()
```

---

## Logging & Debugging

### View Logs

```bash
# View last 50 lines of log
tail -n 50 storage/logs/laravel.log

# Follow log in real-time
tail -f storage/logs/laravel.log

# Search for errors in logs
grep -i error storage/logs/laravel.log

# Search for specific notification
grep -i "RegistrationNotification" storage/logs/laravel.log

# View log size
du -h storage/logs/laravel.log
```

### Debug Mode

```bash
# Check if debug is enabled
grep APP_DEBUG .env

# Enable debug (if needed)
sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env

# Disable debug (for production)
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
```

---

## Production Deployment Commands

### Supervisor Setup (Linux)

```bash
# Install supervisor
sudo apt-get install supervisor

# Create config file
sudo nano /etc/supervisor/conf.d/bkk-queue.conf

# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update

# Start queue workers
sudo supervisorctl start bkk-queue:*

# Stop queue workers
sudo supervisorctl stop bkk-queue:*

# Restart queue workers
sudo supervisorctl restart bkk-queue:*

# Check status
sudo supervisorctl status

# Monitor in real-time
sudo supervisortail bkk-queue
```

### Systemd Setup (Linux)

```bash
# Create service file
sudo nano /etc/systemd/system/bkk-queue.service

# Reload daemon
sudo systemctl daemon-reload

# Enable service (start on boot)
sudo systemctl enable bkk-queue

# Start service
sudo systemctl start bkk-queue

# Stop service
sudo systemctl stop bkk-queue

# Restart service
sudo systemctl restart bkk-queue

# Check status
sudo systemctl status bkk-queue

# View logs
sudo journalctl -u bkk-queue -f
```

---

## Troubleshooting Commands

### Check SMTP Connection

```bash
# Test mail config
php artisan mail:test admin@example.com

# In Tinker - test SMTP directly
$config = config('mail.mailers.smtp');
echo $config['host'];
echo $config['port'];
echo $config['username'];
```

### Check Queue Connection

```bash
# In Tinker - verify database connection
DB::connection('default')->getPdo();

# Check queue driver
config('queue.default')

# Check queue connection
DB::table('jobs')->count()
```

### Fix Stuck Jobs

```bash
# Release stuck jobs (make them available again)
DB::table('jobs')->truncate();

# Retry all failed jobs
php artisan queue:retry all

# Delete specific failed job
php artisan queue:forget job_uuid
```

### Process Monitoring

```bash
# Find queue worker process
ps aux | grep "artisan queue:work"

# Kill queue worker process
kill -9 process_id

# Find all PHP processes
ps aux | grep php

# Monitor system resources while queue runs
top

# Check open ports
netstat -an | grep 587  # For SMTP port
netstat -an | grep 5432 # For PostgreSQL port
```

---

## Automation & Cron Commands

### Setup Cron Job (if needed)

```bash
# Edit crontab
crontab -e

# Add this line to run queue worker restart daily at midnight
0 0 * * * cd /path/to/bkk && php artisan queue:work &

# Or every 5 minutes check and restart if not running
*/5 * * * * pgrep -f "artisan queue:work" || cd /path/to/bkk && php artisan queue:work &
```

### Scheduled Cleanup

```bash
# In tinker - cleanup old jobs
DB::table('jobs')->where('created_at', '<', now()->subDays(7))->delete();

# Cleanup old failed jobs
DB::table('failed_jobs')->where('created_at', '<', now()->subDays(30))->delete();

# Cleanup old notifications
DB::table('notifications')->where('created_at', '<', now()->subMonths(3))->delete();
```

---

## Common Issues & Solutions

### Issue: "No binding found for [Dispatcher]"

```bash
# Solution: Clear cache
php artisan config:clear
php artisan cache:clear
```

### Issue: Queue jobs not processing

```bash
# Check queue worker is running
ps aux | grep "artisan queue:work"

# If not running, start it
php artisan queue:work --verbose

# Check logs
tail -f storage/logs/laravel.log
```

### Issue: Email not sent

```bash
# Check SMTP config
php artisan config:show mail

# Test email sending
php artisan mail:test your-email@gmail.com

# Check failed jobs
php artisan queue:failed

# Retry
php artisan queue:retry all
```

### Issue: Database connection error

```bash
# Check database config
php artisan config:show database

# Test database connection
php artisan tinker
DB::connection()->getPdo();

# Verify PostgreSQL is running
sudo systemctl status postgresql
```

---

## Useful One-Liners

```bash
# Get queue stats
php artisan tinker --execute="echo 'Pending: ' . DB::table('jobs')->count() . ', Failed: ' . DB::table('failed_jobs')->count();"

# Restart queue worker
pkill -f "artisan queue:work" && php artisan queue:work &

# Monitor queue continuously
while true; do php artisan queue:monitor; sleep 5; clear; done

# Cleanup old logs
find storage/logs -name "*.log" -mtime +30 -delete

# Backup database
pg_dump bkk > bkk_backup_$(date +%Y%m%d_%H%M%S).sql

# Watch queue status
watch -n 5 'php artisan queue:monitor'
```

---

## Environment Variables Reference

```bash
# Email Configuration
MAIL_MAILER=smtp                    # smtp, log, mailgun, postmark, ses, sendmail
MAIL_HOST=smtp.gmail.com            # SMTP server hostname
MAIL_PORT=587                       # 587 for TLS, 465 for SSL
MAIL_USERNAME=email@gmail.com       # SMTP username
MAIL_PASSWORD=app_password          # SMTP password
MAIL_ENCRYPTION=tls                 # tls or ssl
MAIL_FROM_ADDRESS=email@gmail.com   # Sender email
MAIL_FROM_NAME="BKK SMKN 1"         # Sender name

# Queue Configuration
QUEUE_CONNECTION=database           # database, redis, sync, sqs, etc
QUEUE_DRIVER=database               # Alternative name

# Database
DB_CONNECTION=pgsql                 # pgsql, mysql, sqlite
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bkk
DB_USERNAME=postgres
DB_PASSWORD=password
```

---

## Quick Debug Template

```bash
#!/bin/bash
# Quick debug script

echo "=== Email Notifications Debug ==="
echo ""
echo "1. Checking config..."
php artisan config:show mail | head -10

echo ""
echo "2. Queue status..."
php artisan queue:monitor

echo ""
echo "3. Failed jobs..."
php artisan queue:failed

echo ""
echo "4. Queue worker status..."
ps aux | grep "artisan queue:work"

echo ""
echo "5. Database status..."
php artisan tinker --execute="echo DB::table('jobs')->count() . ' pending jobs';"

echo ""
echo "=== End Debug ==="
```

Save as `debug-emails.sh` and run: `bash debug-emails.sh`

---

**Reference Created:** December 16, 2025
**Version:** 1.0
**Status:** Complete ✅
