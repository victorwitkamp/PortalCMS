<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Authentication;

use DateTimeImmutable;
use PortalCMS\Core\Config\Config;
use PortalCMS\Core\Security\Encryption;
use PortalCMS\Core\View\Text;
use PortalCMS\Features\Activity\Activity;
use PortalCMS\Features\Users\Entity\User;
use PortalCMS\Features\Users\Password;
use PortalCMS\Features\Users\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Throwable;

final class Authentication
{
    private ?Cookie $responseCookie = null;

    public function __construct(
        private readonly UserRepository $users,
        private readonly Activity $activity,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function isLoggedIn(): bool
    {
        return $this->session()->get('user_logged_in') === true;
    }

    public function userId(): int
    {
        return (int) $this->session()->get('user_id');
    }

    public function login(string $login, string $password, bool $rememberMe = false): bool
    {
        if ($login === '' || $password === '') {
            $this->addFlash('danger', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY'));
            return false;
        }
        if ($this->sessionLoginBlocked()) {
            $this->addFlash('danger', Text::get('FEEDBACK_LOGIN_FAILED_3_TIMES'));
            return false;
        }

        $user = $this->users->findByLogin($login);
        if (!$user instanceof User) {
            $this->recordUnknownUserFailure();
            $this->addFlash('danger', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }
        if ($user->isLoginBlocked()) {
            $this->addFlash('danger', Text::get('FEEDBACK_PASSWORD_WRONG_3_TIMES'));
            return false;
        }
        if (!$user->user_active || $user->user_deleted) {
            $this->addFlash('danger', Text::get('FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET'));
            return false;
        }
        if (!Password::verify($user, $password)) {
            $user->recordFailedLogin();
            $this->users->flush();
            $this->addFlash('danger', Text::get('FEEDBACK_USERNAME_OR_PASSWORD_WRONG'));
            return false;
        }

        $this->clearUnknownUserFailures();
        $user->resetFailedLogins();
        if ($rememberMe) {
            $this->responseCookie = $this->createRememberMeCookie($user);
        }
        $this->startUserSession($user);
        $this->users->flush();
        $this->activity->add('LoginWithPassword', $user->user_id);

        return true;
    }

    public function loginFromRememberMeCookie(string $value): bool
    {
        $validated = $this->validateRememberMeCookie($value);
        if ($validated === null) {
            $this->responseCookie = $this->expiredRememberMeCookie();
            return false;
        }

        $user = $this->users->findByRememberToken($validated['userId'], $validated['token']);
        if (!$user instanceof User || !$user->user_active || $user->user_deleted) {
            $this->responseCookie = $this->expiredRememberMeCookie();
            return false;
        }

        $this->startUserSession($user);
        $this->users->flush();
        $this->activity->add('LoginWithCookie', $user->user_id);
        $this->addFlash('success', Text::get('FEEDBACK_COOKIE_LOGIN_SUCCESSFUL'));

        return true;
    }

    public function loginWithFacebook(string $facebookId): bool
    {
        $user = $this->users->findByFacebookId($facebookId);
        if (!$user instanceof User || !$user->user_active || $user->user_deleted) {
            $this->addFlash('danger', Text::get('FEEDBACK_FACEBOOK_LOGIN_FAILED'));
            return false;
        }

        $this->startUserSession($user);
        $this->users->flush();
        $this->activity->add('LoginWithFacebook', $user->user_id);
        $this->addFlash('success', Text::get('FEEDBACK_SUCCESSFUL_FACEBOOK_LOGIN'));

        return true;
    }

    public function logout(): void
    {
        $session = $this->session();
        $user = $this->users->find((int) $session->get('user_id'));
        if ($user instanceof User) {
            $user->setRememberMeToken(null);
            $user->setSessionId(null);
            $this->users->flush();
        }

        $session->invalidate();
        $session->getFlashBag()->add('success', Text::get('FEEDBACK_LOGOUT_SUCCESSFUL'));
        $this->responseCookie = $this->expiredRememberMeCookie();
    }

    public function takeResponseCookie(): ?Cookie
    {
        $cookie = $this->responseCookie;
        $this->responseCookie = null;

        return $cookie;
    }

    private function startUserSession(User $user): void
    {
        $session = $this->session();
        $session->migrate(true);
        $session->set('user_id', $user->user_id);
        $session->set('user_name', $user->user_name);
        $session->set('user_email', $user->user_email);
        $session->set('user_fbid', $user->user_fbid);
        $session->set('user_logged_in', true);
        $user->markLoggedIn($session->getId());
    }

    private function createRememberMeCookie(User $user): Cookie
    {
        $token = bin2hex(random_bytes(32));
        $user->setRememberMeToken($token);
        $encryptedId = base64_encode(Encryption::encrypt((string) $user->user_id));
        $hash = hash('sha256', $user->user_id . ':' . $token);

        return $this->cookie($encryptedId . ':' . $token . ':' . $hash)
            ->withExpires(new DateTimeImmutable('+' . (int) Config::get('COOKIE_RUNTIME') . ' seconds'));
    }

    /**
     * @return array{userId: int, token: string}|null
     */
    private function validateRememberMeCookie(string $value): ?array
    {
        $parts = explode(':', $value);
        if (count($parts) !== 3) {
            return null;
        }
        [ $encryptedId, $token, $hash ] = $parts;

        try {
            $decoded = base64_decode($encryptedId, true);
            $userId = (int) Encryption::decrypt($decoded !== false ? $decoded : $encryptedId);
        } catch (Throwable) {
            return null;
        }
        if (
            $userId <= 0
            || $token === ''
            || !hash_equals(hash('sha256', $userId . ':' . $token), $hash)
        ) {
            return null;
        }

        return [ 'userId' => $userId, 'token' => $token ];
    }

    private function expiredRememberMeCookie(): Cookie
    {
        return $this->cookie('')->withExpires(new DateTimeImmutable('-1 year'));
    }

    private function cookie(string $value): Cookie
    {
        $cookie = Cookie::create('remember_me')
            ->withValue($value)
            ->withPath((string) Config::get('COOKIE_PATH'))
            ->withSecure((bool) Config::get('COOKIE_SECURE'))
            ->withHttpOnly((bool) Config::get('COOKIE_HTTP'))
            ->withSameSite(Cookie::SAMESITE_LAX);
        $domain = (string) Config::get('COOKIE_DOMAIN');

        return $domain !== '' ? $cookie->withDomain($domain) : $cookie;
    }

    private function sessionLoginBlocked(): bool
    {
        $session = $this->session();

        return (int) $session->get('failed-login-count') >= 3
            && (int) $session->get('last-failed-login') > time() - 30;
    }

    private function recordUnknownUserFailure(): void
    {
        $session = $this->session();
        $session->set('failed-login-count', (int) $session->get('failed-login-count') + 1);
        $session->set('last-failed-login', time());
    }

    private function clearUnknownUserFailures(): void
    {
        $session = $this->session();
        $session->set('failed-login-count', 0);
        $session->set('last-failed-login', 0);
    }

    private function session(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    private function addFlash(string $type, mixed $message): void
    {
        $this->session()->getFlashBag()->add($type, $message);
    }
}
