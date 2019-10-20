<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controller;
use PortalCMS\Models\Page;

class PageController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['updatePage'])) {
            Page::updatePage($_POST['id'], $_POST['content']);
        }
    }
}
