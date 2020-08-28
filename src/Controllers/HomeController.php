<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use League\Plates\Engine;
use PortalCMS\Core\Security\Authentication\Authentication;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HomeController
 * @package PortalCMS\Controllers
 */
class HomeController
{
    protected $templates;

    public function __construct(Engine $templates)
    {
//        $this->templates = new Engine(DIR_VIEW);
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public function index() : ResponseInterface
    {
        return new HtmlResponse($this->templates->render('Pages/Home/Index'));
    }
}
