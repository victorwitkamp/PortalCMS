<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\View\Page;

/**
 * Class PageController
 * @package PortalCMS\Controllers
 */
class PageController
{
    protected $templates;

    public function __construct(Engine $templates)
    {
        if (isset($_POST['updatePage'])) {
            Page::updatePage((int)Request::post('id'), (string)Request::post('content'));
        }
        $this->templates = $templates;
    }

    public function edit() : void
    {
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Page/Edit');
    }
}
