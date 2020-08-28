<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use League\Plates\Engine;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\User\UserMapper;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ProfileController
 * @package PortalCMS\Controllers
 */
class ProfileController
{
    protected $templates;

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public function index(): ResponseInterface
    {
        $user = UserMapper::getProfileById((int) Request::get('id'));
        if (!empty($user)) {
            return new HtmlResponse($this->templates->render('Pages/Profile/Index', (array) $user));
        } else {
            return new HtmlResponse(
                $this->templates->render(
                    'Pages/Error/Error',
                    ['title' => '404 - Not found', 'message' => 'The requested page cannot be found']
                ),
                404
            );
        }
    }
}
