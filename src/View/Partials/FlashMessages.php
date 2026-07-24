<?php

declare(strict_types=1);

/** @var array<string, list<mixed>> $flashMessages */
$flashMessages ??= [];
$allowedStyles = [ 'success', 'warning', 'danger', 'info' ];
?>
<?php foreach ($flashMessages as $type => $messages): ?>
    <?php $style = in_array($type, $allowedStyles, true) ? $type : 'info'; ?>
    <?php foreach ($messages as $message): ?>
        <?php
        $text = is_array($message) ? ($message['message'] ?? '') : $message;
        $link = is_array($message) && is_array($message['link'] ?? null)
            ? $message['link']
            : null;
        ?>
        <div class="alert alert-<?= $style ?> alert-dismissible fade show" role="alert">
            <?= $this->e((string) $text) ?>
            <?php if ($link !== null): ?>
                <a href="<?= $this->e((string) ($link['href'] ?? '')) ?>" class="alert-link">
                    <?= $this->e((string) ($link['label'] ?? '')) ?>
                </a>
            <?php endif ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endforeach ?>
<?php endforeach ?>
