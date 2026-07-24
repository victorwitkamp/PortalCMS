<?php

declare(strict_types=1);

namespace PortalCMS\Features\Contracts\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Activity\Activity;
use PortalCMS\Features\Contracts\Entity\Contract;
use PortalCMS\Features\Contracts\Factory\ContractFactory;
use PortalCMS\Features\Contracts\Input\ContractInput;
use PortalCMS\Features\Contracts\Repository\ContractRepository;
use PortalCMS\Features\Invoices\Repository\InvoiceRepository;
use PortalCMS\Features\Users\Authorization\Authorization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ContractsController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly ContractRepository $contracts,
        private readonly InvoiceRepository $invoices,
        private readonly ContractFactory $factory,
        private readonly RequestInputMapper $inputMapper,
        private readonly Authorization $authorization,
        private readonly Activity $activity,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Contracts', name: 'contracts.index', methods: [ 'GET' ])]
    #[Route('/Contracts/', name: 'contracts.index_slash', methods: [ 'GET' ])]
    public function index(): Response
    {
        return $this->allowed()
            ? $this->render('Contracts::ContractListPage', [ 'contracts' => $this->contracts->findAllOrdered() ])
            : $this->forbiddenResponse();
    }

    #[Route('/Contracts/New', name: 'contracts.new', methods: [ 'GET' ])]
    public function new(): Response
    {
        return $this->allowed()
            ? $this->render('Contracts::CreateContractPage')
            : $this->forbiddenResponse();
    }

    #[Route('/Contracts/New', name: 'contracts.create', methods: [ 'POST' ])]
    public function create(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        /** @var ContractInput $input */
        $input = $this->inputMapper->map($request, ContractInput::class);
        $contract = $this->factory->create($input);
        $this->contracts->save($contract);
        $this->contracts->flush();
        $this->activity->add('NewContract', (int) $this->session()->get('user_id'), 'ID: ' . $contract->id);
        $this->addFlash('success', 'Contract toegevoegd.');

        return $this->redirectToRoute('contracts.index');
    }

    #[Route('/Contracts/Edit', name: 'contracts.edit', methods: [ 'GET' ])]
    public function edit(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $contract = $this->contracts->find($request->query->getInt('id'));
        return $contract instanceof Contract
            ? $this->render('Contracts::EditContractPage', [ 'contract' => $contract ])
            : $this->notFoundResponse();
    }

    #[Route('/Contracts/Edit', name: 'contracts.update', methods: [ 'POST' ])]
    public function update(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $contract = $this->contracts->find($request->request->getInt('id'));
        if (!$contract instanceof Contract) {
            return $this->notFoundResponse();
        }

        /** @var ContractInput $input */
        $input = $this->inputMapper->map($request, ContractInput::class);
        $this->factory->update($contract, $input);
        $this->contracts->flush();
        $this->activity->add('UpdateContract', (int) $this->session()->get('user_id'), 'ID: ' . $contract->id);
        $this->addFlash('success', 'Contract gewijzigd.');

        return $this->redirectToRoute('contracts.index');
    }

    #[Route('/Contracts/Delete', name: 'contracts.delete', methods: [ 'POST' ])]
    public function delete(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $contract = $this->contracts->find($request->request->getInt('id'));
        if (!$contract instanceof Contract) {
            return $this->notFoundResponse();
        }
        if ($this->invoices->findByContractId($contract->id) !== []) {
            $this->addFlash('danger', 'Dit contract heeft al facturen.');
            return $this->redirectToRoute('contracts.details', [ 'id' => $contract->id ]);
        }

        $contractId = $contract->id;
        $this->contracts->remove($contract);
        $this->contracts->flush();
        $this->activity->add('DeleteContract', (int) $this->session()->get('user_id'), 'ID: ' . $contractId);
        $this->addFlash('success', 'Contract verwijderd.');

        return $this->redirectToRoute('contracts.index');
    }

    #[Route('/Contracts/Details', name: 'contracts.details', methods: [ 'GET' ])]
    #[Route('/Contracts/View', name: 'contracts.view_legacy', methods: [ 'GET' ])]
    public function details(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $contract = $this->contracts->find($request->query->getInt('id'));
        return $contract instanceof Contract
            ? $this->render('Contracts::ContractDetailsPage', [ 'contract' => $contract ])
            : $this->notFoundResponse();
    }

    private function allowed(): bool
    {
        return $this->authorization->hasPermission('rental-contracts');
    }
}
