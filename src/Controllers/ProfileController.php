<?php

/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use League\Plates\Engine;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\User\UserMapper;

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

    public function index() : void
    {
        $templates = new Engine(DIR_VIEW);
        $user = UserMapper::getProfileById((int)Request::get('id'));
        if (!empty($user)) {
            echo $templates->render('Pages/Profile/Index', (array)$user);
        } else {
            header('HTTP/1.0 404 Not Found', true, 404);
            echo $templates->render('Pages/Error/Error', [ 'title' => '404 - Not found', 'message' => 'The requested page cannot be found' ]);
        }
    }
}
