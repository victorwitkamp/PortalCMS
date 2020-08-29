<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;

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

    public function notFound() : ResponseInterface
    {
        return new HtmlResponse(
            $this->templates->render('Pages/Error/Error', [ 'title' => '404 - Not found', 'message' => 'The requested page cannot be found' ]),
            404
        );
    }

    public function permissionError() : ResponseInterface
    {
        return new HtmlResponse(
            $this->templates->render('Pages/Error/Error', [ 'title' => '403 - Forbidden', 'message' => 'You are not authorized perform this action.' ]),
            403
        );
    }
}
