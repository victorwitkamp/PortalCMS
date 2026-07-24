<?php

declare(strict_types=1);

namespace PortalCMS\Features\Events\Controller;

use DateTimeImmutable;
use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Activity\Activity;
use PortalCMS\Features\Events\Entity\Event;
use PortalCMS\Features\Events\Input\EventInput;
use PortalCMS\Features\Events\Repository\EventRepository;
use PortalCMS\Features\Users\Authorization\Authorization;
use PortalCMS\Features\Users\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class EventsController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly EventRepository $events,
        private readonly UserRepository $users,
        private readonly RequestInputMapper $inputMapper,
        private readonly Authorization $authorization,
        private readonly Activity $activity,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Events', name: 'events.index', methods: [ 'GET' ])]
    #[Route('/Events/', name: 'events.index_slash', methods: [ 'GET' ])]
    #[Route('/events/', name: 'events.index_lowercase', methods: [ 'GET' ])]
    #[Route('/Events/Index', name: 'events.index_legacy', methods: [ 'GET' ])]
    public function index(): Response
    {
        return $this->allowed()
            ? $this->render('Events::EventListPage')
            : $this->forbiddenResponse();
    }

    #[Route('/Events/Add', name: 'events.add', methods: [ 'GET' ])]
    public function add(): Response
    {
        return $this->allowed()
            ? $this->render('Events::CreateEventPage', [ 'pageName' => (string) Text::get('TITLE_EVENTS_ADD') ])
            : $this->forbiddenResponse();
    }

    #[Route('/Events/Add', name: 'events.create', methods: [ 'POST' ])]
    public function create(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        /** @var EventInput $input */
        $input = $this->inputMapper->map($request, EventInput::class);
        $event = Event::create(
            (int) $this->session()->get('user_id'),
            $input->title,
            $input->start_event,
            $input->end_event,
            $input->description,
        );
        $this->events->save($event);
        $this->events->flush();
        $this->activity->add('NewEvent', (int) $this->session()->get('user_id'), 'ID: ' . $event->id);
        $this->addFlash('success', 'Evenement toegevoegd.');

        return $this->redirectToRoute('events.index');
    }

    #[Route('/Events/Edit', name: 'events.edit', methods: [ 'GET' ])]
    public function edit(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $event = $this->events->find($request->query->getInt('id'));
        if (!$event instanceof Event) {
            $this->addFlash('danger', 'Geen resultaten voor opgegeven event ID.');
            return $this->notFoundResponse();
        }

        return $this->render('Events::EditEventPage', [
            'event' => $event,
            'pageName' => 'Evenement ' . $event->title . ' bewerken',
        ]);
    }

    #[Route('/Events/Edit', name: 'events.update', methods: [ 'POST' ])]
    public function update(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $event = $this->events->find($request->request->getInt('id'));
        if (!$event instanceof Event) {
            $this->addFlash('danger', 'Wijzigen van evenement mislukt. Evenement bestaat niet.');
            return $this->notFoundResponse();
        }

        /** @var EventInput $input */
        $input = $this->inputMapper->map($request, EventInput::class);
        $event->update(
            $input->title,
            $input->start_event,
            $input->end_event,
            $input->description,
            $input->status,
        );
        $this->events->flush();
        $this->activity->add('UpdateEvent', (int) $this->session()->get('user_id'), 'ID: ' . $event->id);
        $this->addFlash('success', 'Evenement gewijzigd.');

        return $this->redirectToRoute('events.index');
    }

    #[Route('/Events/Delete', name: 'events.delete', methods: [ 'POST' ])]
    public function delete(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $event = $this->events->find($request->request->getInt('id'));
        if (!$event instanceof Event) {
            $this->addFlash('danger', 'Verwijderen van evenement mislukt. Evenement bestaat niet.');
            return $this->notFoundResponse();
        }

        $eventId = $event->id;
        $this->events->remove($event);
        $this->events->flush();
        $this->activity->add('DeleteEvent', (int) $this->session()->get('user_id'), 'ID: ' . $eventId);
        $this->addFlash('success', 'Evenement verwijderd.');

        return $this->redirectToRoute('events.index');
    }

    #[Route('/Events/Details', name: 'events.details', methods: [ 'GET' ])]
    public function details(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $event = $this->events->find($request->query->getInt('id'));
        if (!$event instanceof Event) {
            return $this->notFoundResponse('Geen resultaten voor opgegeven event ID.');
        }

        return $this->render('Events::EventDetailsPage', [
            'event' => $event,
            'creator' => $this->users->findProfileById($event->CreatedBy),
        ]);
    }

    #[Route('/Events/loadCalendarEvents', name: 'events.calendar', methods: [ 'GET' ])]
    public function loadCalendarEvents(Request $request): JsonResponse
    {
        $start = new DateTimeImmutable($request->query->getString('start'));
        $end = new DateTimeImmutable($request->query->getString('end'));

        return new JsonResponse(array_map(
            static function (Event $event): array {
                $color = match ($event->status) {
                    1 => 'var(--success)',
                    2 => 'var(--danger)',
                    default => 'var(--info)',
                };

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_event->format('Y-m-d H:i:s'),
                    'end' => $event->end_event->format('Y-m-d H:i:s'),
                    'backgroundColor' => $color,
                ];
            },
            $this->events->findBetween($start, $end),
        ));
    }

    #[Route('/Events/updateEventDate', name: 'events.reschedule', methods: [ 'POST' ])]
    public function reschedule(Request $request): JsonResponse
    {
        if (!$this->allowed()) {
            return new JsonResponse([ 'error' => 'Forbidden' ], Response::HTTP_FORBIDDEN);
        }

        $event = $this->events->find($request->request->getInt('id'));
        if (!$event instanceof Event) {
            return new JsonResponse([ 'error' => 'Event not found' ], Response::HTTP_NOT_FOUND);
        }

        $event->reschedule(
            new DateTimeImmutable($request->request->getString('start')),
            new DateTimeImmutable($request->request->getString('end')),
        );
        $this->events->flush();

        return new JsonResponse([ 'updated' => true ]);
    }

    private function allowed(): bool
    {
        return $this->authorization->hasPermission('events');
    }
}
