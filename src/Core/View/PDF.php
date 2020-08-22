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
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', true);

        if (self::$defined === false) {
            $this->SetCreator(SiteSetting::get('site_name'));
            $this->SetAuthor(SiteSetting::get('site_name'));
            $this->SetHeaderData('logo.jpg', 30, 'Factuur', "Poppodium de Beuk\nbeukonline.nl", [ 0, 64, 255 ], [
                0,
                64,
                128
            ]);
            $this->setFooterData([ 0, 64, 0 ], [ 0, 64, 128 ]);
            $this->setPrintHeader(false);
            $this->setPrintFooter(false);
            $this->SetDefaultMonospacedFont('courier');
            $this->SetMargins(15, 50, 15);
            $this->setHeaderMargin(10);
            $this->setFooterMargin(10);
            $this->SetAutoPageBreak(true, 25);
            $this->setImageScale(1.25);
            $this->setFontSubsetting(false);
            $this->AddPage();
            self::$defined = true;
        }
    }

//    public function config()
//    {

    //        define('K_TCPDF_EXTERNAL_CONFIG', true);
    //        define('K_PATH_MAIN', DIR_VENDOR . 'tecnickcom/tcpdf/');
    //        define('K_PATH_URL', DIR_VENDOR . 'tecnickcom/tcpdf/');
    //        define('K_PATH_FONTS', K_PATH_MAIN . 'fonts/');
    //        define('K_PATH_IMAGES', DIR_IMG);
    //        define('K_PATH_CACHE', DIR_TEMP);
    //        define('K_BLANK_IMAGE', '_blank.png');
    //        define('PDF_FONT_NAME_MAIN', 'helvetica');
    //        define('PDF_FONT_SIZE_MAIN', 10);
    //        define('PDF_FONT_NAME_DATA', 'helvetica');
    //        define('PDF_FONT_SIZE_DATA', 8);
    //        define('HEAD_MAGNIFICATION', 1.1);
    //        define('K_CELL_HEIGHT_RATIO', 1.25);
    //        define('K_TITLE_MAGNIFICATION', 1.3);
    //        define('K_SMALL_RATIO', 2 / 3);
    //        define('K_THAI_TOPCHARS', true);
    //        define('K_TCPDF_CALLS_IN_HTML', true);
    //        define('K_TCPDF_THROW_EXCEPTION_ERROR', false);
//    }

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
