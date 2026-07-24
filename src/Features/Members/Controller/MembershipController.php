<?php

declare(strict_types=1);

namespace PortalCMS\Features\Members\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Activity\Activity;
use PortalCMS\Features\Members\Entity\Member;
use PortalCMS\Features\Members\Factory\MemberFactory;
use PortalCMS\Features\Members\Input\MemberInput;
use PortalCMS\Features\Members\Repository\MemberRepository;
use PortalCMS\Features\Users\Authorization\Authorization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class MembershipController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly MemberRepository $members,
        private readonly MemberFactory $factory,
        private readonly RequestInputMapper $inputMapper,
        private readonly Authorization $authorization,
        private readonly Activity $activity,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Membership', name: 'members.index', methods: [ 'GET' ])]
    #[Route('/Membership/', name: 'members.index_slash', methods: [ 'GET' ])]
    #[Route('/membership', name: 'members.index_lowercase', methods: [ 'GET' ])]
    #[Route('/membership/', name: 'members.index_lowercase_slash', methods: [ 'GET' ])]
    public function index(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $year = $request->query->getInt('year', (int) date('Y'));
        return $this->render('Members::MemberListPage', [
            'year' => $year,
            'years' => $this->members->findYears(),
            'yearCounts' => $this->yearCounts(),
            'members' => $this->members->findRows($year),
        ]);
    }

    #[Route('/Membership/New', name: 'members.new', methods: [ 'GET' ])]
    public function new(): Response
    {
        return $this->allowed()
            ? $this->render('Members::CreateMemberPage')
            : $this->forbiddenResponse();
    }

    #[Route('/Membership/New', name: 'members.create', methods: [ 'POST' ])]
    public function create(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        /** @var MemberInput $input */
        $input = $this->inputMapper->map($request, MemberInput::class);
        if ($input->emailadres !== null
            && $this->members->emailExistsForYear($input->jaarlidmaatschap, $input->emailadres)
        ) {
            $this->addFlash('danger', 'Emailadres wordt dit jaar al gebruikt door een ander lid.');
            return $this->redirectToRoute('members.index');
        }

        $member = $this->factory->create($input);
        $this->members->save($member);
        $this->members->flush();
        $this->activity->add('NewMember', (int) $this->session()->get('user_id'), 'ID: ' . $member->id);
        $this->addFlash('success', 'Lid toegevoegd.');

        return $this->redirectToRoute('members.index');
    }

    #[Route('/Membership/Edit', name: 'members.edit', methods: [ 'GET' ])]
    public function edit(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $member = $this->members->find($this->queryId($request));
        return $member instanceof Member
            ? $this->render('Members::EditMemberPage', [ 'member' => $member ])
            : $this->notFoundResponse();
    }

    #[Route('/Membership/Edit', name: 'members.update', methods: [ 'POST' ])]
    public function update(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $member = $this->members->find($request->request->getInt('id'));
        if (!$member instanceof Member) {
            return $this->notFoundResponse();
        }

        /** @var MemberInput $input */
        $input = $this->inputMapper->map($request, MemberInput::class);
        $this->factory->update($member, $input);
        $this->members->flush();
        $this->activity->add('UpdateMember', (int) $this->session()->get('user_id'), 'ID: ' . $member->id);
        $this->addFlash('success', 'Lid opgeslagen.');

        return $this->redirectToRoute('members.index');
    }

    #[Route('/Membership/Delete', name: 'members.delete', methods: [ 'POST' ])]
    public function delete(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        foreach ($this->requestIds($request) as $id) {
            $member = $this->members->find($id);
            if ($member instanceof Member) {
                $this->members->remove($member);
                $this->activity->add(
                    'DeleteMember',
                    (int) $this->session()->get('user_id'),
                    'ID: ' . $id,
                    flush: false,
                );
            }
        }
        $this->members->flush();
        $this->addFlash('success', 'Geselecteerde leden verwijderd.');

        return $this->redirectToRoute('members.index');
    }

    #[Route('/Membership/Status', name: 'members.status', methods: [ 'POST' ])]
    public function status(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $status = $request->request->getInt('status');
        foreach ($this->requestIds($request) as $id) {
            $member = $this->members->find($id);
            if ($member instanceof Member) {
                $member->status = $status;
            }
        }
        $this->members->flush();
        $this->addFlash('success', 'Status bijgewerkt.');

        return $this->redirectToRoute('members.index');
    }

    #[Route('/Membership/NewFromExisting', name: 'members.copy_form', methods: [ 'GET' ])]
    public function newFromExisting(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $selectedYear = $request->query->getInt('Year', (int) date('Y'));
        $selectedPaymentType = $request->query->getString('PaymentType', 'incasso');

        return $this->render('Members::CopyMembersPage', [
            'selectedYear' => $selectedYear,
            'selectedPaymentType' => $selectedPaymentType,
            'years' => $this->members->findYears(),
            'yearCounts' => $this->yearCounts(),
            'paymentTypes' => $this->members->findPaymentTypes(),
            'members' => $this->members->findRows($selectedYear, $selectedPaymentType),
        ]);
    }

    #[Route('/Membership/NewFromExisting', name: 'members.copy', methods: [ 'POST' ])]
    public function copy(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $targetYear = $request->request->getInt('targetYear');
        foreach ($this->requestIds($request) as $id) {
            $member = $this->members->find($id);
            if (!$member instanceof Member || $member->jaarlidmaatschap === $targetYear) {
                continue;
            }
            if ($member->emailadres !== null
                && $this->members->emailExistsForYear($targetYear, $member->emailadres)
            ) {
                continue;
            }
            $this->members->save($this->factory->copyForYear($member, $targetYear));
        }
        $this->members->flush();
        $this->addFlash('success', 'Geselecteerde leden gekopieerd.');

        return $this->redirectToRoute('members.index', [ 'year' => $targetYear ]);
    }

    #[Route('/Membership/Profile', name: 'members.profile', methods: [ 'GET' ])]
    public function profile(Request $request): Response
    {
        if (!$this->allowed()) {
            return $this->forbiddenResponse();
        }

        $member = $this->members->find($this->queryId($request));
        return $member instanceof Member
            ? $this->render('Members::MemberProfilePage', [ 'member' => $member ])
            : $this->notFoundResponse();
    }

    /**
     * @return array<int, int>
     */
    private function yearCounts(): array
    {
        $counts = [];
        foreach ($this->members->findYears() as $year) {
            $counts[$year] = $this->members->countByYear($year);
        }
        return $counts;
    }

    /**
     * @return int[]
     */
    private function requestIds(Request $request): array
    {
        $ids = $request->request->all()['id'] ?? [];
        $ids = is_array($ids) ? $ids : [ $ids ];
        return array_values(array_filter(
            array_map(static fn (mixed $id): int => (int) $id, $ids),
            static fn (int $id): bool => $id > 0,
        ));
    }

    private function queryId(Request $request): int
    {
        return $request->query->getInt('Id', $request->query->getInt('id'));
    }

    private function allowed(): bool
    {
        return $this->authorization->hasPermission('membership');
    }
}
