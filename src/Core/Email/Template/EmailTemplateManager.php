<?php


declare(strict_types=1);

namespace App\Core\Email\Template;

use App\Core\Session\Session;

class EmailTemplateManager
{
    public EmailTemplate $emailTemplate;
    public EmailTemplateMapper $EmailTemplateMapper;

    public function __construct()
    {
        $this->EmailTemplateMapper = new EmailTemplateMapper();
    }

    public function delete(int $id): bool
    {
        if (!empty(EmailTemplateMapper::getById($id))) {
            if (EmailTemplateMapper::delete($id)) {
                $this->addFlash('success','Template verwijderd.');
                return true;
            }
            $this->addFlash('danger','Verwijderen van template mislukt.');
        } else {
            $this->addFlash('danger','Verwijderen van template mislukt. Template bestaat niet.');
        }
        return false;
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
        $this->emailTemplate->CreatedBy = (int)Session::get('user_id');
        return true;
    }

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

    public function store(): bool
    {
        if (empty($this->emailTemplate->type) || empty($this->emailTemplate->subject) || empty($this->emailTemplate->body)) {
            $this->addFlash('danger','Nieuwe template aanmaken mislukt.');
        } else {
            $return = $this->EmailTemplateMapper->create($this->emailTemplate);
            if ($return === null) {
                $this->addFlash('danger','Nieuwe template aanmaken mislukt.');
            } else {
                $this->addFlash('success','Template toegevoegd (ID = ' . $return . ')');
                return true;
            }
        }
        return false;
    }

    public function update(EmailTemplate $emailTemplate): bool
    {
        if (empty($emailTemplate->subject) || empty($emailTemplate->body)) {
            $this->addFlash('danger','Niet alle velden zijn ingevuld');
        } elseif ($this->EmailTemplateMapper->update($emailTemplate)) {
            $this->addFlash('success','Template opgeslagen');
            return true;
        } else {
            $this->addFlash('danger','Opslaan mislukt');
        }
        return false;
    }
}
