<?php

declare(strict_types=1);

namespace PortalCMS\Features\Email\Batch;

use PortalCMS\Features\Email\Entity\MailBatch as BatchEntity;
use PortalCMS\Features\Email\Repository\MailBatchRepository;
use PortalCMS\Features\Email\Repository\MailScheduleRepository;
use PortalCMS\Features\Email\Schedule\MailSchedule;

final class MailBatch
{
    public function __construct(
        private readonly MailBatchRepository $batches,
        private readonly MailScheduleRepository $mails,
        private readonly MailSchedule $schedule,
    ) {
    }

    public function create(?int $templateId = null, ?int $createdBy = null): BatchEntity
    {
        $batch = BatchEntity::create($templateId, $createdBy);
        $this->batches->save($batch);
        $this->batches->flush();

        return $batch;
    }

    /**
     * @param int[] $batchIds
     * @return array{sent: int, failed: int, alreadySent: int}
     */
    public function send(array $batchIds): array
    {
        $scheduled = [];
        foreach (array_unique(array_map('intval', $batchIds)) as $batchId) {
            array_push($scheduled, ...$this->mails->findScheduledByBatchId($batchId));
        }

        return $this->schedule->sendScheduled($scheduled);
    }

    /**
     * @param int[] $batchIds
     * @return array{batches: int, messages: int, failed: int}
     */
    public function delete(array $batchIds): array
    {
        $result = [ 'batches' => 0, 'messages' => 0, 'failed' => 0 ];
        foreach (array_unique(array_map('intval', $batchIds)) as $batchId) {
            $batch = $this->batches->find($batchId);
            if (!$batch instanceof BatchEntity) {
                ++$result['failed'];
                continue;
            }

            foreach ($this->mails->findByBatchId($batchId) as $mail) {
                $this->mails->remove($mail);
                ++$result['messages'];
            }
            $this->batches->remove($batch);
            ++$result['batches'];
        }
        if ($result['batches'] > 0) {
            $this->batches->flush();
        }

        return $result;
    }
}
