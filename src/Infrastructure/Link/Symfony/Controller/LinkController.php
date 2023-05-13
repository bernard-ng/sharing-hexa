<?php

declare(strict_types=1);

namespace Infrastructure\Link\Symfony\Controller;

use Domain\Link\Entity\Link;
use Domain\Link\Repository\LinkRepositoryInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * class LinkController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[Route('/link', name: 'app_link_')]
final class LinkController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(
        LinkRepositoryInterface $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        return $this->render(
            view: 'domain/link/index.html.twig',
            parameters: [
                'data' => $paginator->paginate(
                    target: $repository->findAll(),
                    page: $request->query->getInt('page', 1),
                    limit: 50
                )
            ]
        );
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return $this->render('domain/link/new.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => Requirement::UUID], methods: ['GET'])]
    public function show(Link $link): Response
    {
        return $this->render('domain/link/show.html.twig');
    }

    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => Requirement::UUID], methods: ['GET', 'POST'])]
    public function edit(Link $link, Request $request): Response
    {
        return $this->render('domain/link/edit.html.twig');
    }

    #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::UUID], methods: ['DELETE'])]
    public function delete(Link $link, Request $request): Response
    {
        return $this->redirectToRoute('app_link_index');
    }
}
