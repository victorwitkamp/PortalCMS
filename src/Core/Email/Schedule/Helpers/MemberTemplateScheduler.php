<?php

namespace PortalCMS\Core\Email\Schedule\Helpers;

use PortalCMS\Core\Config\SiteSetting;
use PortalCMS\Core\Email\Batch\MailBatch;
use PortalCMS\Core\Email\Message\Attachment\EmailAttachmentMapper;
use PortalCMS\Core\Email\Recipient\EmailRecipientMapper;
use PortalCMS\Core\Email\Schedule\MailScheduleMapper;
use PortalCMS\Core\HTTP\Redirect;
use PortalCMS\Core\Session\Session;
use PortalCMS\Modules\Members\MemberModel;

class MemberTemplateScheduler
{
    public function scheduleMails($template, $recipientIds)
    {
        $success = 0;
        $failed = 0;

        if (!empty($recipientIds)) {
            MailBatch::create($template['id']);
            foreach ($recipientIds as $memberId) {
                $member = MemberModel::getMemberById($memberId);
                $return = MailScheduleMapper::create(MailBatch::lastInsertedId(), $memberId, $template['subject'], self::replaceholdersMember($memberId, $template['body']));
                if (!$return) {
                    ++$failed;
                } else {
                    ++$success;
                    $mailid = MailScheduleMapper::lastInsertedId();
                    $memberFullname = $member['voornaam'] . ' ' . $member['achternaam'];
                    EmailRecipientMapper::createRecipient($mailid, $member['emailadres'], $memberFullname);
                    $templateAttachments = EmailAttachmentMapper::getByTemplateId($template['id']);
                    if (!empty($templateAttachments)) {
                        foreach ($templateAttachments as $templateAttachment) {
                            EmailAttachmentMapper::create($mailid, $templateAttachment['path'], $templateAttachment['name'], $templateAttachment['extension'], $templateAttachment['encoding'], $templateAttachment['type']);
                        }
                    }
                }
            }
            if ($failed === 0) {
                Session::add('feedback_positive', 'Totaal aantal berichten aangemaakt:' . $success);
            } else {
                Session::add('feedback_warning', 'Totaal aantal berichten aangemaakt: ' . $success . '. Berichten met fout: ' . $failed);
            }
            Redirect::to('mail');
        }
    }

    public static function replaceholdersMember($memberid, $templatebody)
    {
        $member = MemberModel::getMemberById($memberid);
        $variables = [
            'voornaam' => $member['voornaam'],
            'achternaam' => $member['achternaam'],
            'iban' => $member['iban'],
            'afzender' => SiteSetting::getStaticSiteSetting('site_name')
        ];
        foreach ($variables as $key => $value) {
            $templatebody = str_replace('{' . strtoupper($key) . '}', $value, $templatebody);
        }
        return $templatebody;
    }
}