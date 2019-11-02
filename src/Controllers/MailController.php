<?php

namespace PortalCMS\Controllers;

use PortalCMS\Core\HTTP\Request;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\Controllers\Controller;
use PortalCMS\Core\Email\Schedule\MailSchedule;

/**
 * MailController
 * Controls everything mail-related
 */
class MailController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['newScheduledMail'])) {
            MailSchedule::create();
        }
        if (isset($_POST['sendScheduledMailById'])) {
            MailSchedule::sendMailsById(Request::post('id'));
            Redirect::to('mail/messages.php');
        }
        if (isset($_POST['createMailWithTemplate'])) {
            MailSchedule::createWithTemplate(Request::post('templateid', true), Request::post('recipients'));
        }
        if (isset($_POST['deleteScheduledMailById'])) {
            MailSchedule::deleteById(Request::post('id'));
            Redirect::to('mail');
        }
        if (isset($_POST['sendBatchById'])) {
            MailBatch::sendById(Request::post('id'));
        }
        if (isset($_POST['deleteBatchById'])) {
            MailBatch::deleteById(Request::post('id'));
        }
    }
}
