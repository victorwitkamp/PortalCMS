<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Page;

class PageController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['updatePage'])) {
            Page::updatePage(
                (int) Request::post('id'),
                (string) Request::post('content')
            );
        }
    }

    public static function edit()
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Page/Edit');
    }
}
