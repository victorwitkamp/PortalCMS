<?php

namespace PortalCMS\Core\Email\Template;

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
        $stmt = DB::conn()->prepare(
            'INSERT INTO mail_templates(
                id, type, subject, body, status
                ) VALUES (
                    NULL,?,?,?,?)'
        );
        $stmt->execute([$EmailTemplate->type, $EmailTemplate->subject, $EmailTemplate->body, $EmailTemplate->status]);
        if (!$stmt) {
            return null;
        }
        return EmailTemplatePDOReader::lastInsertedId();
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function update(EmailTemplate $emailTemplate) : bool
    {
        if (empty($emailTemplate->id) || ($emailTemplate->id <= 0)) {
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
            $emailTemplate->subject,
            $emailTemplate->body,
            $emailTemplate->status,
            $emailTemplate->id
        ]);
        if ($stmt->rowCount() === 0) {
            return false;
        }
        return true;
    }
}
