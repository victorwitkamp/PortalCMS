<?php

/**
 * MailController
 * Controls everything mail-related
 */
class MailController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['testeventmail'])) {
            MailSchedule::sendEventMail($_POST['testeventmail_recipientemail']);
        }
        if (isset($_POST['newScheduledMail'])) {
            MailSchedule::new();
        }
        if (isset($_POST['sendScheduledMailById'])) {
            MailSchedule::sendbyid(Request::post('id'));
        }
        if (isset($_POST['createMailWithTemplate'])) {
            MailSchedule::newWithTemplate();
        }
        if (isset($_POST['deleteScheduledMailById'])) {
            MailSchedule::deleteById();
        }
        if (isset($_POST['sendBatchById'])) {
            MailBatch::sendById(Request::post('id'));
        }
        if (isset($_POST['deleteBatchById'])) {
            MailBatch::deleteById(Request::post('id'));
        }
    }

}