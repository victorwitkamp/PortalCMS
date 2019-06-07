<?php
/**
 * Define the following constant to ignore the default configuration file.
 */
define('K_TCPDF_EXTERNAL_CONFIG', true);

/**
 * Installation path (/var/www/tcpdf/).
 * By default it is automatically calculated but you can also set it as a fixed string to improve performances.
 */
//define('K_PATH_MAIN', '');

/**
 * URL path to tcpdf installation folder (http://localhost/tcpdf/).
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
//define('K_PATH_URL', '');

/**
 * Path for PDF fonts.
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
//define('K_PATH_FONTS', K_PATH_MAIN.'fonts/');

/**
 * Default images directory.
 * By default it is automatically set but you can also set it as a fixed string to improve performances.
 */
define('K_PATH_IMAGES', dirname(__FILE__).'/../images/');


define('PDF_HEADER_LOGO', 'logo.jpg');
define('PDF_HEADER_LOGO_WIDTH', 30);

/**
 * Cache directory for temporary files (full path).
 */
define('K_PATH_CACHE', sys_get_temp_dir().'/');

/**
 * Generic name for a blank image.
 */
define('K_BLANK_IMAGE', '_blank.png');

/**
 * Page format.
 */
define('PDF_PAGE_FORMAT', 'A4');

/**
 * Page orientation (P=portrait, L=landscape).
 */
define('PDF_PAGE_ORIENTATION', 'P');

define('PDF_CREATOR', SiteSetting::getStaticSiteSetting('site_name'));
define('PDF_AUTHOR', SiteSetting::getStaticSiteSetting('site_name'));
define('PDF_HEADER_TITLE', 'Factuur');
define('PDF_HEADER_STRING', "Poppodium de Beuk\nbeukonline.nl");
define('PDF_UNIT', 'mm');
define('PDF_MARGIN_HEADER', 10);
define('PDF_MARGIN_FOOTER', 10);
define('PDF_MARGIN_TOP', 50);
define('PDF_MARGIN_BOTTOM', 25);
define('PDF_MARGIN_LEFT', 15);
define('PDF_MARGIN_RIGHT', 15);
define('PDF_FONT_NAME_MAIN', 'helvetica');
define('PDF_FONT_SIZE_MAIN', 10);
define('PDF_FONT_NAME_DATA', 'helvetica');
define('PDF_FONT_SIZE_DATA', 8);
define('PDF_FONT_MONOSPACED', 'courier');
define('PDF_IMAGE_SCALE_RATIO', 1.25);
define('HEAD_MAGNIFICATION', 1.1);
define('K_CELL_HEIGHT_RATIO', 1.25);
define('K_TITLE_MAGNIFICATION', 1.3);
define('K_SMALL_RATIO', 2 / 3);

/**
 * Set to true to enable the special procedure used to avoid the overlappind of symbols on Thai language.
 */
define('K_THAI_TOPCHARS', true);

/**
 * If true allows to call TCPDF methods using HTML syntax
 * IMPORTANT: For security reason, disable this feature if you are printing user HTML content.
 */
define('K_TCPDF_CALLS_IN_HTML', true);

/**
 * If true and PHP version is greater than 5, then the Error() method throw new exception instead of terminating the execution.
 */
define('K_TCPDF_THROW_EXCEPTION_ERROR', false);
