<?php
// Copy to config/local.php. This file is ignored by Git; do not publish real credentials.
return [
    'DB_DRIVER' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_NAME' => 'shreejigas',
    'DB_USER' => 'your_database_user',
    'DB_PASS' => 'your_database_password',
    'APP_URL' => 'https://your-domain.example',
    'APP_BASE_PATH' => '',
    'MAIL_ENABLED' => '0',
    'SMTP_ENABLED' => '0',
    'SMTP_HOST' => 'smtp.gmail.com',
    'SMTP_PORT' => 587,
    'SMTP_USER' => 'your-gmail-address@example.com',
    'SMTP_PASS' => 'your-16-character-google-app-password',
    'SMTP_FROM_EMAIL' => 'your-gmail-address@example.com',
    'SMTP_FROM_NAME' => 'Shree Ji Gas Industries',
    'OTP_EXPIRY_MINUTES' => 10,
];
