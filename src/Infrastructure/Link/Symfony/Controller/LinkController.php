<?php

declare(strict_types=1);

namespace Infrastructure\Link\Symfony\Controller;

use Application\Link\Command\CreateLinkCommand;
use Domain\Link\Entity\Link;
use Domain\Link\Exception\NonUniqueSlugException;
use Domain\Link\Repository\LinkRepositoryInterface;
use Infrastructure\Link\Symfony\Form\CreateLinkForm;
use Infrastructure\Shared\Symfony\Controller\FlashMessageTrait;
use Infrastructure\Shared\Symfony\Messenger\CommandBusAwareDispatchTrait;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * class LinkController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[Route('/link', name: 'app_link_')]
class LinkController extends AbstractController
{
    use CommandBusAwareDispatchTrait;
    use FlashMessageTrait;

    public function __construct(
        protected readonly MessageBusInterface $commandBus,
        protected readonly LoggerInterface $logger,
        protected readonly TranslatorInterface $translator
    ) {
    }

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
                    target: $repository->findBy([], ['created_at' => 'DESC']),
                    page: $request->query->getInt('page', 1),
                    limit: 50
                )
            ]
        );
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $command = new CreateLinkCommand();
        $form = $this->createForm(CreateLinkForm::class, $command)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
               $this->dispatchSync($command);
               return $this->redirectToRoute('app_link_index');
            } catch (\Throwable $e) {
                $this->addSafeMessageExceptionFlash($e);
            }
        }

        return $this->render(
            view: 'domain/link/new.html.twig',
            parameters: [
                'form' => $form
            ],
            response: new Response(status: 422)
        );
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
