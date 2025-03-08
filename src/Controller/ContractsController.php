<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authorization\Authorization;
use App\Modules\Contracts\ContractModel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Contracts", name="contracts")
 */
class ContractsController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("", name="")
     */
    public function index() : Response
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return $this->render('Contracts.php');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/New", name="new")
     */
    public function new() : Response
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return $this->render('ContractsNew.php');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Edit", name="edit")
     */
    public function edit() : Response
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return $this->render('ContractsEdit.php');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Details", name="details")
     */
    public function details() : Response
    {
        if (Authorization::hasPermission('rental-contracts')) {
            return $this->render('ContractsDetails.php');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }
    //
    //    /**
    //     * @Route("/Invoices", name="invoices")
    //     */
    //    public function invoices() : Response
    //    {
    //        if (Authorization::hasPermission('rental-contracts')) {
    //            return $this->render('Contracts');
    //        }
    //        return $this->redirectToRoute('errorpermissionerror');
    //    }

    public function newContract(Request $request) : Response
    {
        ContractModel::new();
        return $this->redirectToRoute('contracts');
    }

    public function updateContract(Request $request) : Response
    {
        ContractModel::update();
        return $this->redirectToRoute('contracts');
    }

    public function deleteContract(Request $request) : Response
    {
        ContractModel::delete((int)$this->request->get('id'));
        return $this->redirectToRoute('contracts');
    }
}
