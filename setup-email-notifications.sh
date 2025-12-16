#!/bin/bash
# Setup Email Notifications untuk BKK SMKN 1 Purwosari

echo "=========================================="
echo "BKK Email Notifications Setup Script"
echo "=========================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    echo "Please copy .env.example to .env first"
    exit 1
fi

echo "📧 Step 1: Creating queue jobs table..."
php artisan queue:table
echo "✅ Queue table migration created"
echo ""

echo "🗄️  Step 2: Running migrations..."
php artisan migrate
echo "✅ Database migrations completed"
echo ""

echo "🔄 Step 3: Clearing cache..."
php artisan config:clear
php artisan cache:clear
echo "✅ Cache cleared"
echo ""

echo "📝 Step 4: Email Configuration"
echo "Current MAIL_MAILER: $(grep MAIL_MAILER .env | cut -d '=' -f2)"
echo ""
echo "Available options:"
echo "  1) Gmail SMTP"
echo "  2) Mailtrap (Testing)"
echo "  3) Custom SMTP"
echo "  4) Skip (use current config)"
echo ""
read -p "Choose option (1-4): " option

case $option in
    1)
        echo ""
        echo "📧 Gmail Configuration"
        read -p "Enter Gmail email: " gmail_email
        read -s -p "Enter Gmail App Password: " gmail_password
        echo ""
        
        # Update .env
        sed -i "s/^MAIL_MAILER=.*/MAIL_MAILER=smtp/" .env
        sed -i "s/^MAIL_HOST=.*/MAIL_HOST=smtp.gmail.com/" .env
        sed -i "s/^MAIL_PORT=.*/MAIL_PORT=587/" .env
        sed -i "s/^MAIL_USERNAME=.*/MAIL_USERNAME=$gmail_email/" .env
        sed -i "s/^MAIL_PASSWORD=.*/MAIL_PASSWORD=$gmail_password/" .env
        sed -i "s/^MAIL_ENCRYPTION=.*/MAIL_ENCRYPTION=tls/" .env
        sed -i "s/^MAIL_FROM_ADDRESS=.*/MAIL_FROM_ADDRESS=$gmail_email/" .env
        
        echo "✅ Gmail configuration saved"
        ;;
    2)
        echo ""
        echo "📨 Mailtrap Configuration"
        read -p "Enter Mailtrap username: " mailtrap_user
        read -s -p "Enter Mailtrap password: " mailtrap_pass
        echo ""
        
        # Update .env
        sed -i "s/^MAIL_MAILER=.*/MAIL_MAILER=smtp/" .env
        sed -i "s/^MAIL_HOST=.*/MAIL_HOST=smtp.mailtrap.io/" .env
        sed -i "s/^MAIL_PORT=.*/MAIL_PORT=465/" .env
        sed -i "s/^MAIL_USERNAME=.*/MAIL_USERNAME=$mailtrap_user/" .env
        sed -i "s/^MAIL_PASSWORD=.*/MAIL_PASSWORD=$mailtrap_pass/" .env
        sed -i "s/^MAIL_ENCRYPTION=.*/MAIL_ENCRYPTION=ssl/" .env
        
        echo "✅ Mailtrap configuration saved"
        ;;
    3)
        echo ""
        echo "🔧 Custom SMTP Configuration"
        read -p "Enter MAIL_HOST: " mail_host
        read -p "Enter MAIL_PORT: " mail_port
        read -p "Enter MAIL_USERNAME: " mail_user
        read -s -p "Enter MAIL_PASSWORD: " mail_pass
        echo ""
        
        # Update .env
        sed -i "s/^MAIL_MAILER=.*/MAIL_MAILER=smtp/" .env
        sed -i "s/^MAIL_HOST=.*/MAIL_HOST=$mail_host/" .env
        sed -i "s/^MAIL_PORT=.*/MAIL_PORT=$mail_port/" .env
        sed -i "s/^MAIL_USERNAME=.*/MAIL_USERNAME=$mail_user/" .env
        sed -i "s/^MAIL_PASSWORD=.*/MAIL_PASSWORD=$mail_pass/" .env
        
        echo "✅ Custom SMTP configuration saved"
        ;;
    *)
        echo "⏭️  Skipping email configuration"
        ;;
esac

echo ""
echo "🔄 Clearing cache again..."
php artisan config:clear
php artisan cache:clear
echo "✅ Cache cleared"
echo ""

echo "=========================================="
echo "✅ Setup Complete!"
echo "=========================================="
echo ""
echo "📝 Next steps:"
echo "1. Verify SMTP credentials in .env file"
echo "2. Run: php artisan queue:work"
echo "   (Keep this running in a separate terminal)"
echo "3. Test email notifications using tinker:"
echo "   php artisan tinker"
echo ""
echo "For production, use supervisor or systemd"
echo "See QUEUE_EMAIL_SETUP.md for more details"
echo ""
