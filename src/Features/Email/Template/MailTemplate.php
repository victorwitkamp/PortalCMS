<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Template;

use PortalCMS\Core\Config\Config;
use PortalCMS\Features\Email\Entity\MailTemplate as TemplateEntity;
use PortalCMS\Features\Email\Repository\MailTemplateRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class MailTemplate
{
    private const MAX_FILE_SIZE = 5_000_000;

    public function __construct(private readonly MailTemplateRepository $templates)
    {
    }

    public function system(string $name): ?TemplateEntity
    {
        return $this->templates->findSystem($name);
    }

    public function create(
        string $type,
        string $subject,
        string $body,
        ?int $createdBy = null,
    ): ?TemplateEntity {
        if ($type === '' || $subject === '' || $body === '') {
            return null;
        }

        $template = TemplateEntity::create($type, $subject, $body, $createdBy);
        $this->templates->save($template);
        $this->templates->flush();

        return $template;
    }

    public function update(TemplateEntity $template, string $subject, string $body): bool
    {
        if ($subject === '' || $body === '') {
            return false;
        }
        $template->changeSubject($subject);
        $template->changeBody($body);
        $this->templates->flush();

        return true;
    }

    public function delete(int $id): bool
    {
        $template = $this->templates->find($id);
        if (!$template instanceof TemplateEntity || !$this->templates->remove($template)) {
            return false;
        }
        $this->templates->flush();

        return true;
    }

    public function uploadAttachment(TemplateEntity $template, ?UploadedFile $file): bool
    {
        if (
            !$file instanceof UploadedFile
            || !$file->isValid()
            || $file->getSize() > self::MAX_FILE_SIZE
        ) {
            return false;
        }

        $path = (string) Config::get('PATH_ATTACHMENTS');
        $directory = DIR_ROOT . $path;
        if (!is_dir($directory) || !is_writable($directory)) {
            return false;
        }

        $originalName = $file->getClientOriginalName();
        $movedFile = $file->move($directory, $originalName);
        $template->addAttachment(
            $path,
            (string) pathinfo($originalName, PATHINFO_FILENAME),
            (string) pathinfo($originalName, PATHINFO_EXTENSION),
            'base64',
            $movedFile->getMimeType() ?? 'application/octet-stream',
        );
        $this->templates->flush();

        return true;
    }

    /**
     * @param int[] $attachmentIds
     * @return array{deleted: int, failed: int}
     */
    public function deleteAttachments(TemplateEntity $template, array $attachmentIds): array
    {
        $result = [ 'deleted' => 0, 'failed' => 0 ];
        foreach (array_unique(array_map('intval', $attachmentIds)) as $attachmentId) {
            $attachment = $this->templates->findAttachment($attachmentId);
            if ($attachment === null || $attachment->template !== $template) {
                ++$result['failed'];
                continue;
            }
            $template->removeAttachment($attachment);
            ++$result['deleted'];
        }
        if ($result['deleted'] > 0) {
            $this->templates->flush();
        }

        return $result;
    }
}
