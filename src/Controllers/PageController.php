<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\View\Page;

class PageController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['updatePage'])) {
            Page::updatePage($_POST['id'], $_POST['content']);
        }
    }
}
