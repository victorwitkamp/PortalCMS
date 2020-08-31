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
 * Class PageController
 * @package PortalCMS\Controllers
 */
class PageController
{
    protected $templates;

    public function __construct(Engine $templates)
    {
//        if (isset($_POST['updatePage'])) {
//            Page::updatePage((int)Request::post('id'), (string)Request::post('content'));
//        }
        $this->templates = $templates;
    }

    public function edit() : ResponseInterface
    {
        return new HtmlResponse($this->templates->render('Pages/Page/Edit'));
    }
}
