<?php
 /**
  * Configuration for DEVELOPMENT environment
  *
  * Returns the full configuration.
  * This is used by the core/Config class.
  */
 return [
    'URL' => 'https://portal.beukonline.nl/',

    'PATH_LOGO' =>  DIR_ROOT . 'content/logo/',
    'PATH_LOGO_PUBLIC' => DIR_ROOT . 'content/logo/',
    'PATH_ATTACHMENTS' =>  DIR_ROOT . 'content/attachments/',
    'PATH_ATTACHMENTS_PUBLIC' => 'content/attachments/',

    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'silent-field.49425portal.dbinf.buildingtogether.io',
    'DB_NAME' => '49425portal',
    'DB_USER' => '49425portal',
    'DB_PASS' => 'Zh@_@x@T_reQ9#pQ',
    'DB_PORT' => '3306',
    'DB_CHARSET' => 'utf8mb4',
    'DB_COLLATE' => 'utf8mb4_general_ci',

    'COOKIE_RUNTIME' => 1209600,
    'COOKIE_PATH' => '/',
    'COOKIE_DOMAIN' => '',
    'COOKIE_SECURE' => true,
    'COOKIE_HTTP' => true,
    'SESSION_RUNTIME' => 604800,

    'FB_APP_ID' => '1991997357725439',
    'FB_APP_SECRET' => 'a033dce887f749e53907fb710ae253bc',
    'FB_LOGIN_URL' => 'https://portal.beukonline.nl/Login/ext/fb/fb-callback-login.php',
    'FB_ASSIGN_URL' => 'https://portal.beukonline.nl/Login/ext/fb/fb-callback.php',

   'AVATAR_SIZE' => 44,
   'AVATAR_JPEG_QUALITY' => 85,

    'ENCRYPTION_KEY' => '6#x0gÃƒÅ ÃƒÂ¬f^25cL1f$08&',
    'HMAC_SALT' => '8qk9c^4L6d#15tM8z7n0%',

    'EMAIL_PASSWORD_RESET_URL' => 'Login/resetPassword.php',
    'EMAIL_PASSWORD_RESET_SUBJECT' => 'Password reset',
    'EMAIL_VERIFICATION_URL' => 'register/verify',
    'EMAIL_VERIFICATION_FROM_EMAIL' => 'vrijwilligers@beukonline.nl',
    'EMAIL_VERIFICATION_FROM_NAME' => 'My Project',
    'EMAIL_VERIFICATION_SUBJECT' => 'Account activation',
    'EMAIL_VERIFICATION_CONTENT' => 'Please click on this link to activate your account: '
 ];
