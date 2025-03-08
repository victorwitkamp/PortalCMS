<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Config\SiteSetting;
use App\Core\Session\Session;
use App\Core\View\Page;

use App\Core\View\PageMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/PageEditor", name="page")
 */
class PageEditorController extends AbstractController
{
    /**
     * @Route("/{id}", name="edit")
     */
    final public function edit(int $id, Request $request) : Response
    {
        $pageMapper = new PageMapper();
        if ($request->isMethod('POST')) {
            $pageMapper->updatePage((int) $request->get('id'), (string) $request->get('content'));
        }
        $page = $pageMapper->getPage($id);
        $pageName = 'Pagina ' . $page['name'] . ' bewerken';
        return $this->render('PageEditor.html.twig', [
            'page_title'   => $pageName,
            'site_logo'    => SiteSetting::get('site_logo'),
            'site_name'    => SiteSetting::get('site_name'),
            'site_year'    => date('Y'),
            'user_name'    => Session::get('user_name'),
            'page_id'      => $page['id'],
            'page_content' => $page['content']
        ]);
    }
}
