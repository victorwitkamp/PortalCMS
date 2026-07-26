<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Features\Email\SMTP\SMTPTransport;
use PortalCMS\Features\Email\Transport\MailTransport;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $services->instanceof(AbstractController::class)
        ->tag('controller.service_arguments');

    $services->load('PortalCMS\\', '../src/')
        ->exclude([
            '../src/Core/Database/Migrations/',
            '../src/Core/Kernel.php',
            '../src/Core/Security/Encryption.php',
            '../src/Features/*/Entity/',
            '../src/Features/*/Input/',
            '../src/Features/*/View/Templates/',
            '../src/Features/Email/Message/EmailMessage.php',
            '../src/Features/Email/Recipient/EmailRecipient.php',
            '../src/View/',
        ]);

    $services->alias(MailTransport::class, SMTPTransport::class);
};
