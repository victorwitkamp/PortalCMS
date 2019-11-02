<?php

namespace PortalCMS\Core\Email\Template;

use EmailTemplatePDOReader;
use PortalCMS\Core\Database\DB;

class EmailTemplatePDOWriter
{
    /**
     * Save a new EmailTemplate to the database and return the id.
     *
     * @param EmailTemplate $EmailTemplate
     *
     * @return int|bool
     */
    public function create(EmailTemplate $EmailTemplate) : int
    {
        $stmt = DB::conn()->prepare('INSERT INTO mail_templates(id, type, subject, body, status) VALUES (NULL,?,?,?,?)');
        $stmt->execute([$EmailTemplate->type, $EmailTemplate->emailMessage->subject, $EmailTemplate->emailMessage->body, $EmailTemplate->status]);
        if (!$stmt) {
            return false;
        }
        return EmailTemplatePDOReader::lastInsertedId();
    }

    public static function update(EmailTemplate $emailTemplate)
    {
        if (empty($emailTemplate->id) || ($emailTemplate->id) <= 0) {
            return false;
        }
        $stmt = DB::conn()->prepare(
            'UPDATE mail_templates
                SET type = ?, subject = ?, body = ?, status = ?
                    WHERE id = ?
                        LIMIT 1'
        );
        $stmt->execute([
            $emailTemplate->type,
            $emailTemplate->emailMessage->subject,
            $emailTemplate->emailMessage->body,
            $emailTemplate->status,
            $emailTemplate->id
        ]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return true;
    }
}
