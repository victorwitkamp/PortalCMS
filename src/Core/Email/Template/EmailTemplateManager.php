<?php
/**
 * Copyright Victor Witkamp (c) 2020.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\HTTP\Session;

/**
 * Class EmailTemplateManager
 * @package PortalCMS\Core\Email\Template
 */
class EmailTemplateManager
{
    /**
     * Instance of the MailTemplateMapper class
     * @var EmailTemplate $emailTemplate
     */
    public $emailTemplate;

    /**
     * @var EmailTemplateMapper $EmailTemplateMapper
     */
    public $EmailTemplateMapper;

    public function __construct()
    {
        $this->EmailTemplateMapper = new EmailTemplateMapper();
    }

    /**
     */
    public static function delete(int $id): bool
    {
        if (!empty(EmailTemplateMapper::getById($id))) {
            if (EmailTemplateMapper::delete($id)) {
                Session::add('feedback_positive', 'Template verwijderd.');
                return true;
            }
            Session::add('feedback_negative', 'Verwijderen van template mislukt.');
        } else {
            Session::add('feedback_negative', 'Verwijderen van template mislukt. Template bestaat niet.');
        }
        return false;
    }

    /**
     */
    public function create(string $type, string $subject, string $body): bool
    {
        if (empty($type) || empty($subject) || empty($body)) {
            return false;
        }
        $this->emailTemplate = new EmailTemplate();
        $this->emailTemplate->type = $type;
        $this->emailTemplate->subject = $subject;
        $this->emailTemplate->body = $body;
        $this->emailTemplate->status = 1;
        $this->emailTemplate->CreatedBy = (int) Session::get('user_id');
        return true;
    }

    /**
     */
    public function getExisting(int $id): ?EmailTemplate
    {
        $existing = EmailTemplateMapper::getById($id);
        if (!empty($existing)) {
            $emailTemplate = new EmailTemplate();
            $emailTemplate->id = $existing->id;
            $emailTemplate->type = $existing->type;
            $emailTemplate->name = $existing->name;
            $emailTemplate->subject = $existing->subject;
            $emailTemplate->body = $existing->body;
            $emailTemplate->status = $existing->status;
            $emailTemplate->CreatedBy = $existing->CreatedBy;
            return $emailTemplate;
        }
        return null;
    }

    /**
     */
    public function store(): bool
    {
        if (empty($this->emailTemplate->type) || empty($this->emailTemplate->subject) || empty($this->emailTemplate->body)) {
            Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt.');
        } else {
            $return = $this->EmailTemplateMapper->create($this->emailTemplate);
            if ($return === null) {
                Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt.');
            } else {
                Session::add('feedback_positive', 'Template toegevoegd (ID = ' . $return . ')');
                return true;
            }
        }
        return false;
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    /**
     */
    public function update(EmailTemplate $emailTemplate): bool
    {
        if (empty($emailTemplate->subject) || empty($emailTemplate->body)) {
            Session::add('feedback_negative', 'Niet alle velden zijn ingevuld');
        } elseif ($this->EmailTemplateMapper->update($emailTemplate)) {
            Session::add('feedback_positive', 'Template opgeslagen');
            return true;
        } else {
            Session::add('feedback_negative', 'Opslaan mislukt');
        }
        return false;
    }
}
