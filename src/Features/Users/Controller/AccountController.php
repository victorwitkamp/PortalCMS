<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Users\Authentication\Authentication;
use PortalCMS\Features\Users\Entity\User;
use PortalCMS\Features\Users\Password;
use PortalCMS\Features\Users\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class AccountController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly UserRepository $users,
        private readonly Authentication $authentication,
        private readonly Password $password,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Account', name: 'account.index', methods: [ 'GET' ])]
    #[Route('/Account/', name: 'account.index_slash', methods: [ 'GET' ])]
    public function index(): Response
    {
        $user = $this->currentUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login.index', [ 'redirect' => 'Account' ]);
        }

        return $this->render('Users::Account/AccountPage', [
            'user' => $user,
            'roles' => $this->users->findRoles($user->user_id),
            'changeUsernameCsrfToken' => $this->csrfTokenManager
                ->getToken('account.change_username')
                ->getValue(),
            'changePasswordCsrfToken' => $this->csrfTokenManager
                ->getToken('account.change_password')
                ->getValue(),
        ]);
    }

    #[Route('/Account/Username', name: 'account.username', methods: [ 'POST' ])]
    public function changeUsername(Request $request): Response
    {
        $user = $this->currentUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login.index', [ 'redirect' => 'Account' ]);
        }
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken(
            'account.change_username',
            $request->request->getString('csrf_token'),
        ))) {
            $this->addFlash('danger', 'Invalid CSRF token.');
            return $this->redirectToRoute('account.index');
        }

        $username = trim($request->request->getString('user_name'));
        if ($username === $user->user_name) {
            $this->addFlash('danger', Text::get('FEEDBACK_USERNAME_SAME_AS_OLD_ONE'));
        } elseif (preg_match('/^[a-zA-Z0-9]{2,64}$/', $username) !== 1) {
            $this->addFlash('danger', Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
        } elseif ($this->users->usernameExists($username, $user->user_id)) {
            $this->addFlash('danger', Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN'));
        } else {
            $user->changeUsername($username);
            $this->users->flush();
            $this->session()->set('user_name', $username);
            $this->addFlash('success', Text::get('FEEDBACK_USERNAME_CHANGE_SUCCESSFUL'));
        }

        return $this->redirectToRoute('account.index');
    }

    #[Route('/Account/Password', name: 'account.password', methods: [ 'POST' ])]
    public function changePassword(Request $request): Response
    {
        $user = $this->currentUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login.index', [ 'redirect' => 'Account' ]);
        }
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken(
            'account.change_password',
            $request->request->getString('csrf_token'),
        ))) {
            $this->addFlash('danger', 'Invalid CSRF token.');
            return $this->redirectToRoute('account.index');
        }

        $error = $this->password->change(
            $user,
            $request->request->getString('currentpassword'),
            $request->request->getString('newpassword'),
            $request->request->getString('newconfirmpassword'),
        );
        $this->addFlash(
            $error === null ? 'success' : 'danger',
            $error ?? Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL'),
        );

        return $this->redirectToRoute('account.index');
    }

    #[Route('/Account/Facebook/Delete', name: 'account.facebook_delete', methods: [ 'POST' ])]
    public function clearFacebook(): Response
    {
        $user = $this->currentUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login.index', [ 'redirect' => 'Account' ]);
        }
        $user->connectFacebook(null);
        $this->users->flush();
        $this->session()->set('user_fbid', null);
        $this->addFlash('success', Text::get('FEEDBACK_REMOVE_FACEBOOK_ACCOUNT_SUCCESS'));

        return $this->redirectToRoute('account.index');
    }

    private function currentUser(): ?User
    {
        if (!$this->authentication->isLoggedIn()) {
            return null;
        }
        $user = $this->users->find($this->authentication->userId());

        return $user instanceof User ? $user : null;
    }
}
