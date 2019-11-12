<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Controllers;

use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\Email\Schedule\MailSchedule;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Router;

/**
 * MailController
 */
class MailController extends Controller
{
    /**
     * The requests that this controller will handle
     * @var array $requests
     */
    private $requests = [
        'sendScheduledMailById' => 'POST',
        'createMailWithTemplate' => 'POST',
        'deleteScheduledMailById' => 'POST',
        'sendBatchById' => 'POST',
        'deleteBatchById' => 'POST'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        Router::processRequests($this->requests, __CLASS__);
    }

    public static function sendScheduledMailById() : void
    {
        MailSchedule::sendMailsById((array) Request::post('id'));
        Redirect::to('mail/messages.php');
    }

    public static function createMailWithTemplate() : void
    {
        $templateId = filter_input(INPUT_POST, 'templateid', FILTER_VALIDATE_INT);
        $recipients = (array) Request::post('recipients');
        MailSchedule::createWithTemplate(
            $templateId,
            $recipients
        );
        Redirect::to('mail');
    }

    public static function deleteScheduledMailById() : void
    {
        MailSchedule::deleteById((array) Request::post('id'));
        Redirect::to('mail');
    }

    public static function sendBatchById() : void
    {
        MailBatch::sendById((array) Request::post('id'));
    }

    public static function deleteBatchById() : void
    {
        MailBatch::deleteById((array) Request::post('id'));
    }
}
