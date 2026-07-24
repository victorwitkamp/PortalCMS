<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Users\Authentication\Authentication;
use PortalCMS\Features\Users\PasswordReset;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class LoginController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly Authentication $authentication,
        private readonly PasswordReset $passwordReset,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/Login', name: 'login.index', methods: [ 'GET' ])]
    #[Route('/Login/', name: 'login.index_slash', methods: [ 'GET' ])]
    #[Route('/Login/Index', name: 'login.index_legacy', methods: [ 'GET' ])]
    public function index(Request $request): Response
    {
        $destination = $this->destination($request->query->getString('redirect'));
        if ($this->authentication->isLoggedIn()) {
            $this->addFlash('success', 'You are already logged in.');
            return $this->redirectToLocalPath($destination);
        }

        $rememberMe = $request->cookies->getString('remember_me');
        if ($rememberMe !== '' && $this->authentication->loginFromRememberMeCookie($rememberMe)) {
            return $this->redirectToLocalPath($destination);
        }

        $response = $this->render('Users::Authentication/LoginPage', [
            'redirect' => $request->query->getString('redirect'),
            'csrfToken' => $this->csrfTokenManager->getToken('login')->getValue(),
        ]);
        $this->applyCookie($response, $this->authentication->takeResponseCookie());

        return $response;
    }

    #[Route('/Login', name: 'login.submit', methods: [ 'POST' ])]
    #[Route('/Login/', name: 'login.submit_slash', methods: [ 'POST' ])]
    public function login(Request $request): Response
    {
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken(
            'login',
            $request->request->getString('csrf_token'),
        ))) {
            $this->addFlash('danger', 'Invalid CSRF token.');
            return $this->redirectToRoute('login.index');
        }

        $loggedIn = $this->authentication->login(
            trim($request->request->getString('user_name')),
            $request->request->getString('user_password'),
            $request->request->getString('set_remember_me_cookie') === 'on',
        );
        $response = $loggedIn
            ? $this->redirectToLocalPath($this->destination($request->request->getString('redirect')))
            : $this->redirectToRoute('login.index');
        $this->applyCookie($response, $this->authentication->takeResponseCookie());

        return $response;
    }

    #[Route('/Login/RequestPasswordReset', name: 'login.password_request', methods: [ 'GET' ])]
    public function requestPasswordReset(): Response
    {
        return $this->render('Users::Authentication/RequestPasswordResetPage');
    }

    #[Route('/Login/RequestPasswordReset', name: 'login.password_request_submit', methods: [ 'POST' ])]
    public function submitPasswordResetRequest(Request $request): Response
    {
        $sent = $this->passwordReset->request(trim($request->request->getString('user_name_or_email')));
        $this->addFlash(
            $sent ? 'success' : 'danger',
            $sent
                ? (string) Text::get('FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL')
                : ($this->passwordReset->error() ?? (string) Text::get('FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR')),
        );

        return $this->redirectToRoute($sent ? 'login.index' : 'login.password_request');
    }

    #[Route('/Login/PasswordReset', name: 'login.password_reset', methods: [ 'GET' ])]
    #[Route('/Login/PasswordReset.php', name: 'login.password_reset_legacy', methods: [ 'GET' ])]
    public function passwordReset(Request $request): Response
    {
        $username = $request->query->getString('username');
        $token = $request->query->getString('password_reset_hash');
        if (!$this->passwordReset->verify($username, $token)) {
            return $this->render(
                'View::Error/ErrorPage',
                [ 'title' => '401 - Unauthorized', 'message' => 'Invalid or expired token.' ],
                Response::HTTP_UNAUTHORIZED,
            );
        }

        return $this->render('Users::Authentication/ResetPasswordPage', compact('username', 'token'));
    }

    #[Route('/Login/PasswordReset', name: 'login.password_reset_submit', methods: [ 'POST' ])]
    #[Route('/Login/PasswordReset.php', name: 'login.password_reset_submit_legacy', methods: [ 'POST' ])]
    public function resetPassword(Request $request): Response
    {
        $reset = $this->passwordReset->reset(
            $request->request->getString('username'),
            $request->request->getString('password_reset_hash'),
            $request->request->getString('password'),
            $request->request->getString('confirm_password'),
        );
        $this->addFlash(
            $reset ? 'success' : 'danger',
            $reset
                ? (string) Text::get('FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL')
                : ($this->passwordReset->error() ?? (string) Text::get('FEEDBACK_PASSWORD_CHANGE_FAILED')),
        );

        return $this->redirectToRoute($reset ? 'login.index' : 'login.password_request');
    }

    #[Route('/Login/Activate', name: 'login.activate', methods: [ 'GET', 'POST' ])]
    public function activate(): Response
    {
        return $this->render('Users::Authentication/ActivateAccountPage');
    }

    private function destination(string $redirect): string
    {
        if ($redirect === '') {
            return 'Home';
        }
        $decoded = urldecode($redirect);
        if (parse_url($decoded, PHP_URL_HOST) !== null) {
            return 'Home';
        }

        return ltrim($decoded, '/');
    }

    private function applyCookie(Response $response, ?Cookie $cookie): void
    {
        if ($cookie !== null) {
            $response->headers->setCookie($cookie);
        }
    }
}
