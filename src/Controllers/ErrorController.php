<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;

/**
 * Class ErrorController
 * @package PortalCMS\Controllers
 */
class ErrorController
{
    protected $templates;

    public function __construct(Engine $templates)
    {
        $this->templates = $templates;
    }
    /**
     * Use this when something is not found. Gives back a proper 404 header response plus a normal page (where you could
     * show a well-designed error message or something more useful for your users).
     * You can see this in action in action in /core/Application.php -> __construct
     */
    public function notFound()
    {
        header('HTTP/1.0 404 Not Found', true, 404);
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Error/Error', [ 'title' => '404 - Not found', 'message' => 'The requested page cannot be found' ]);
    }

    public function permissionError()
    {
        header('HTTP/1.0 403 Not Found', true, 403);
        $templates = new Engine(DIR_VIEW);
        echo $templates->render('Pages/Error/Error', [ 'title' => '403 - Forbidden', 'message' => 'You are not authorized perform this action.' ]);
    }
}
