<?php

declare(strict_types=1);

namespace App\Core\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AlertService
{
    private $requestStack;

    public function __construct(RequestStack $requestStack) {
        $this->requestStack = $requestStack;
    }

    public function renderFeedbackMessages(RequestStack $requestStack) : bool
    {
        $session = $this->requestStack->getSession();

        $warningMessages = $session->getFlashBag()->get('warning', []);
        $errorMessages = $session->getFlashBag()->get('error', []);
        $successMessages = $session->getFlashBag()->get('success', []);
        if (empty($warningMessages) && empty($errorMessages) && empty($successMessages)) {
            return false;
        }
        foreach ($warningMessages as $message) {
            $this->renderAlert($message, 'warning');
        }
        foreach ($errorMessages as $message) {
            $this->renderAlert($message, 'danger');
        }
        foreach ($successMessages as $message) {
            $this->renderAlert($message, 'success');
        }
        return true;
    }

    public function renderAlert(string $feedback, string $style) : string
    {
        if (!empty($feedback) && !empty($style)) {
            echo('');
            ?>
            <div class="alert alert-<?= $style ?> alert-dismissible fade show" role="alert"><?= $feedback ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div><?php
        }
    }
}
