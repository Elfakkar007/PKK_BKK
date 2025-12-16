#!/bin/bash
# Queue & Email Monitoring Script untuk BKK SMKN 1 Purwosari

echo "=========================================="
echo "Queue & Email Monitoring Dashboard"
echo "=========================================="
echo ""

# Function untuk refresh data
refresh() {
    clear
    echo "=========================================="
    echo "Queue & Email Monitoring Dashboard"
    echo "$(date '+%Y-%m-%d %H:%M:%S')"
    echo "=========================================="
    echo ""
    
    # Check job queue status
    echo "📊 Job Queue Status:"
    echo "-------------------------------------------"
    php artisan tinker --execute="
    \$pending = DB::table('jobs')->count();
    \$failed = DB::table('failed_jobs')->count();
    echo 'Pending Jobs: ' . \$pending . PHP_EOL;
    echo 'Failed Jobs: ' . \$failed . PHP_EOL;
    "
    echo ""
    
    # Check recent email activity
    echo "📧 Recent Notifications (Database):"
    echo "-------------------------------------------"
    php artisan tinker --execute="
    \$notifications = DB::table('notifications')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    if (\$notifications->isEmpty()) {
        echo 'No notifications yet' . PHP_EOL;
    } else {
        foreach (\$notifications as \$notif) {
            \$type = json_decode(\$notif->data)->notification ?? 'Unknown';
            echo '[' . \$notif->created_at . '] ' . \$notif->notifiable_type . ' - ' . \$type . PHP_EOL;
        }
    }
    "
    echo ""
    
    # Queue Worker Status
    echo "🔄 Queue Worker Status:"
    echo "-------------------------------------------"
    if pgrep -f "artisan queue:work" > /dev/null; then
        echo "✅ Queue worker is RUNNING"
    else
        echo "❌ Queue worker is NOT running"
        echo "Start it with: php artisan queue:work"
    fi
    echo ""
    
    # Mail Configuration
    echo "⚙️  Mail Configuration:"
    echo "-------------------------------------------"
    echo "MAIL_MAILER: $(grep MAIL_MAILER .env | cut -d '=' -f2)"
    echo "MAIL_HOST: $(grep MAIL_HOST .env | cut -d '=' -f2)"
    echo "MAIL_PORT: $(grep MAIL_PORT .env | cut -d '=' -f2)"
    echo "MAIL_FROM_ADDRESS: $(grep MAIL_FROM_ADDRESS .env | cut -d '=' -f2)"
    echo ""
    
    echo "Press Ctrl+C to exit or wait for next refresh..."
    sleep 10
}

# Main loop
while true; do
    refresh
done
