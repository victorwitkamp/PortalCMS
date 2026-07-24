<?php

declare(strict_types=1);

namespace PortalCMS\Features\Pages\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Pages\Entity\Page;
use PortalCMS\Features\Pages\Input\PageInput;
use PortalCMS\Features\Pages\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PageController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly PageRepository $pages,
        private readonly RequestInputMapper $inputMapper,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Page/Edit', name: 'pages.edit', methods: [ 'GET' ])]
    #[Route('/page/edit', name: 'pages.edit_lowercase', methods: [ 'GET' ])]
    public function edit(Request $request): Response
    {
        $page = $this->pages->find((string) $request->query->getInt('id'));
        return $page instanceof Page
            ? $this->render('Pages::PageEditorPage', [ 'page' => $page ])
            : $this->notFoundResponse();
    }

    #[Route('/Page/Edit', name: 'pages.update', methods: [ 'POST' ])]
    #[Route('/page/edit', name: 'pages.update_lowercase', methods: [ 'POST' ])]
    public function update(Request $request): Response
    {
        $page = $this->pages->find((string) $request->request->getInt('id'));
        if (!$page instanceof Page) {
            return $this->notFoundResponse();
        }

        /** @var PageInput $input */
        $input = $this->inputMapper->map($request, PageInput::class);
        $page->changeContent($input->content);
        $this->pages->flush();
        $this->addFlash('success', 'Pagina opgeslagen.');

        return $this->redirectToRoute('pages.edit', [ 'id' => $page->id ]);
    }
}
