<?php
declare(strict_types=1);
/**
 * Copyright Victor Witkamp (c) 2020.
 */

namespace PortalCMS\Core\View;

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Session\Session;
use TCPDF;

class PDF extends TCPDF
{
    public static $defined = false;

    public function __construct()
    {
        if (self::$defined === false) {
            self::config();
        }
        parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor(PDF_AUTHOR);
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->setHeaderMargin(PDF_MARGIN_HEADER);
        $this->setFooterMargin(PDF_MARGIN_FOOTER);
        $this->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->setFontSubsetting(false);
        $this->AddPage();
    }

    public static function config()
    {
        define('K_TCPDF_EXTERNAL_CONFIG', true);
        define('K_PATH_MAIN', DIR_VENDOR . 'tecnickcom/tcpdf/');
        define('K_PATH_URL', DIR_VENDOR . 'tecnickcom/tcpdf/');
        define('K_PATH_FONTS', K_PATH_MAIN . 'fonts/');
        define('K_PATH_IMAGES', DIR_IMG);
        define('PDF_HEADER_LOGO', 'logo.jpg');
        define('PDF_HEADER_LOGO_WIDTH', 30);
        define('K_PATH_CACHE', DIR_TEMP);
        define('K_BLANK_IMAGE', '_blank.png');
        define('PDF_PAGE_FORMAT', 'A4');
        define('PDF_PAGE_ORIENTATION', 'P');
        define('PDF_CREATOR', SiteSetting::get('site_name'));
        define('PDF_AUTHOR', SiteSetting::get('site_name'));
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
        define('K_THAI_TOPCHARS', true);
        define('K_TCPDF_CALLS_IN_HTML', true);
        define('K_TCPDF_THROW_EXCEPTION_ERROR', false);
        self::$defined = true;
    }

    public function render(string $name): string
    {
        ob_end_clean();
        return $this->Output($name);
    }

    public function writeToFile(string $path): bool
    {
        if (file_exists($path)) {
            Session::add('feedback_negative', 'Bestand bestaat al.');
        } else {
            $this->Output($path, 'F');
            if (file_exists($path)) {
                return true;
            }
        }
        return false;
    }
}