<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use League\Plates\Engine;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\Security\Authentication\Authentication;
use PortalCMS\Core\Security\Authorization\Authorization;
use PortalCMS\Modules\Contracts\ContractFactory;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ContractsController
 * @package PortalCMS\Controllers
 */
class ContractsController
{
    protected $templates;

    public function __construct(Engine $templates)
    {
        Authentication::checkAuthentication();
        $this->templates = $templates;
    }

    public function newContract() : ResponseInterface
    {
        ContractFactory::new();
        return new RedirectResponse('/Contracts');
    }

    public function updateContract() : ResponseInterface
    {
        ContractFactory::update();
        return new RedirectResponse('/Contracts');
    }

    public function deleteContract() : ResponseInterface
    {
        ContractFactory::delete((int) Request::post('id'));
        return new RedirectResponse('/Contracts');
    }

    public function index() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return new HtmlResponse($this->templates->render('Pages/Contracts/Index'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function new() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return new HtmlResponse($this->templates->render('Pages/Contracts/New'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function edit() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return new HtmlResponse($this->templates->render('Pages/Contracts/Edit'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function details() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return new HtmlResponse($this->templates->render('Pages/Contracts/Details'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }

    public function invoices() : ResponseInterface
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return new HtmlResponse($this->templates->render('Pages/Contracts/Invoices'));
        }
        return new RedirectResponse('/Error/PermissionError');
    }
}
