<?php
/**
 * Copyright Victor Witkamp (c) 2019.
 */

declare(strict_types=1);

namespace PortalCMS\Core\Email\Template;

use PortalCMS\Core\Session\Session;

class EmailTemplateManager
{
    /**
     * Instance of the MailTemplateMapper class
     *
     * @var EmailTemplate $emailTemplate
     */
    public $emailTemplate;

    /**
     * @var EmailTemplatePDOReader $EmailTemplatePDOReader
     */
    public $EmailTemplatePDOReader;

    /**
     * @var EmailTemplatePDOWriter $EmailTemplatePDOWriter
     */
    public $EmailTemplatePDOWriter;

    public function __construct()
    {
        $this->EmailTemplatePDOReader = new EmailTemplatePDOReader();
        $this->EmailTemplatePDOWriter = new EmailTemplatePDOWriter();
    }

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
     * @param int $id
     * @return EmailTemplate|null $emailTemplate
     */
    public function getExisting(int $id) : ?EmailTemplate
    {
        $existing = EmailTemplatePDOReader::getById($id);
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
     * Save the EmailTemplate to the database and return the id of the created record.
     * @return bool
     */
    public function store() : bool
    {
        if (empty($this->emailTemplate->type) || empty($this->emailTemplate->subject) || empty($this->emailTemplate->body)) {
            Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt.');
            return false;
        }
        $return = $this->EmailTemplatePDOWriter->create($this->emailTemplate);
        if (!empty($return)) {
            Session::add('feedback_positive', 'Template toegevoegd (ID = ' . $return . ')');
            return true;
        }
        Session::add('feedback_negative', 'Nieuwe template aanmaken mislukt.');
        return false;
    }

    /**
     * Save the EmailTemplate to the database and return the id of the created record.
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function update(EmailTemplate $emailTemplate) : bool
    {
        if (empty($emailTemplate->subject || empty($emailTemplate->body))) {
            Session::add('feedback_negative', 'Niet alle velden zijn ingevuld');
            return false;
        }
        $return = $this->EmailTemplatePDOWriter->update($emailTemplate);
        if (!empty($return)) {
            Session::add('feedback_positive', 'Template opgeslagen');
            return true;
        }
        Session::add('feedback_negative', 'Opslaan mislukt');
        return false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        if (!empty(EmailTemplatePDOReader::getById($id))) {
            if (EmailTemplatePDOWriter::delete($id)) {
                Session::add('feedback_positive', 'Template verwijderd.');
                return true;
            }
            Session::add('feedback_negative', 'Verwijderen van template mislukt.');
        } else {
            Session::add('feedback_negative', 'Verwijderen van template mislukt. Template bestaat niet.');
        }
        return false;
    }
}
