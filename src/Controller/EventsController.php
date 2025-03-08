<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Activity\Activity;
use App\Core\Config\SiteSetting;
use App\Core\Security\Authentication\Authentication;
use App\Core\Security\Authorization\Authorization;
use App\Core\Session\Session;
use App\Core\View\Text;
use App\Modules\Calendar\Event;
use App\Modules\Calendar\EventMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Events", name="events")
 */
class EventsController extends AbstractController
{
    public function __construct()
    {
        Authentication::checkAuthentication();
    }

    /**
     * @Route("", name="")
     */
    public function index(): Response
    {
        if (Authorization::hasPermission('events')) {
            return $this->render('Events/Events.html.twig', [
                'page_title' => 'Events',
                'site_name'  => SiteSetting::get('site_name'),
                'site_year'  => date('Y'),
                'user_name'  => Session::get('user_name')
            ]);
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Add", name="add")
     */
    public function add(): Response
    {
        if (Authorization::hasPermission('events')) {
            return $this->render('Events/EventsAdd.html.twig', [ 'pageName' => (string) Text::get('TITLE_EVENTS_ADD') ]);
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Edit/{id}", name="edit")
     */
    public function edit(int $id): Response
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById($id);
            if (!empty($event)) {
                return $this->render('Events/Events.html.twig', [
                    'event'    => $event,
                    'pageName' => 'Evenement ' . $event->title . ' bewerken'
                ]);
            }
            $this->addFlash('danger', 'Geen resultaten voor opgegeven event ID.');
            return $this->redirectToRoute('error');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    /**
     * @Route("/Details/{id}", name="details")
     */
    public function details(int $id): Response
    {
        if (Authorization::hasPermission('events')) {
            $event = EventMapper::getById($id);
            if (!empty($event)) {
                return $this->render('Events/EventsDetails.html.twig', [ 'event' => $event ]);
            }
            $this->addFlash('danger', 'Geen resultaten voor opgegeven event ID.');
            return $this->redirectToRoute('error');
        }
        return $this->redirectToRoute('errorpermissionerror');
    }

    public function deleteEvent(Request $request): Response
    {
        $id = (int) $request->get('id');
        if (empty(EventMapper::getById($id))) {
            $this->addFlash('danger', 'Verwijderen van evenement mislukt. Evenement bestaat niet.');
        } elseif (EventMapper::delete($id)) {
            $this->addFlash('success', 'Evenement verwijderd.');
            return $this->redirectToRoute('events');
        } else {
            $this->addFlash('danger', 'Verwijderen van evenement mislukt.');
        }
        return $this->redirectToRoute('error');

    }

    public function updateEvent(Request $request): Response
    {
        $event = new Event();
        $event->setId((int) $request->get('id'));
        $event->setTitle((string) $request->get('title'));
        $event->setStart((string) $request->get('start_event'));
        $event->setEnd((string) $request->get('end_event'));
        $event->setDescription((string) $request->get('description'));
        $event->setStatus((int) $request->get('status'));

        if (!EventMapper::exists($event->id)) {
            $this->addFlash('danger', 'Wijzigen van evenement mislukt. Evenement bestaat niet.');
        } elseif (EventMapper::update($event)) {
            Activity::add('UpdateEvent', Session::get('user_id'), 'ID: ' . $event->id, Session::get('user_name'));
            $this->addFlash('success', 'Evenement gewijzigd.');
            return $this->redirectToRoute('events');
        } else {
            $this->addFlash('danger', 'Wijzigen van evenement mislukt.');
        }
        return $this->redirectToRoute('error');
    }

    public function addEvent(Request $request): Response
    {
        $event = new Event();
        $event->setCreatedBy();
        $event->setTitle((string) $request->get('title'));
        $event->setStart((string) $request->get('start_event'));
        $event->setEnd((string) $request->get('end_event'));
        $event->setDescription((string) $request->get('description'));

        if (EventMapper::new($event)) {
            $this->addFlash('success', 'Evenement toegevoegd.');
            return $this->redirectToRoute('events');
        }
        $this->addFlash('danger', 'Toevoegen van evenement mislukt.');
        return $this->redirectToRoute('error');
    }

    /**
     * @Route("/loadCalendarEvents", name="loadCalendarEvents")
     */
    public function loadCalendarEvents(Request $request): Response
    {
        $eventsArray = [];
        $events = EventMapper::getByDate((string) $request->get('start'), (string) $request->get('end'));
        if (!empty($events)) {
            foreach ($events as $event) {
                if ($event->status === 1) {
                    $color = 'var(--success)';
                } elseif ($event->status === 2) {
                    $color = 'var(--danger)';
                } else {
                    $color = 'var(--info)';
                }
                $eventsArray[] = [
                    'id'              => $event->id,
                    'title'           => $event->title,
                    'start'           => $event->start_event,
                    'end'             => $event->end_event,
                    'backgroundColor' => $color
                ];
            }
        }
        return $this->json($eventsArray);
    }

    public function updateEventDate(Request $request): bool
    {
        return EventMapper::updateDate((int) $request->get('id'), (string) $request->get('start'), (string) $request->get('end'));
    }
}
