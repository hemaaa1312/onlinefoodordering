<?php
// Set environment early
define('ENVIRONMENT', 'development'); 

    if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
        // Use dev mailer
    } else {
        // Use real mailer
    }
   define('MAIL_LOG_PATH', __DIR__.'/logs/mail.log');

function dev_mail($to, $subject, $message, $headers) {
    $log = date('[Y-m-d H:i:s]')." To: $to\nSubject: $subject\n\n$message\n\n";
    if (!is_dir(dirname(MAIL_LOG_PATH))) {
        mkdir(dirname(MAIL_LOG_PATH), 0755, true);
    }
    return file_put_contents(MAIL_LOG_PATH, $log, FILE_APPEND) !== false;
}