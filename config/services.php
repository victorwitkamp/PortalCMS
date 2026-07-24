<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Doctrine\ORM\EntityManagerInterface;
use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Database\DoctrineConfiguration;
use PortalCMS\Features\Activity\Entity\Activity as ActivityEntity;
use PortalCMS\Features\Activity\Repository\ActivityRepository;
use PortalCMS\Features\Contracts\Entity\Contract;
use PortalCMS\Features\Contracts\Repository\ContractRepository;
use PortalCMS\Features\Email\Entity\MailBatch;
use PortalCMS\Features\Email\Entity\MailSchedule;
use PortalCMS\Features\Email\Entity\MailTemplate;
use PortalCMS\Features\Email\Repository\MailBatchRepository;
use PortalCMS\Features\Email\Repository\MailScheduleRepository;
use PortalCMS\Features\Email\Repository\MailTemplateRepository;
use PortalCMS\Features\Email\SMTP\SMTPTransport;
use PortalCMS\Features\Email\Transport\MailTransport;
use PortalCMS\Features\Events\Entity\Event;
use PortalCMS\Features\Events\Repository\EventRepository;
use PortalCMS\Features\Invoices\Entity\Invoice;
use PortalCMS\Features\Invoices\Repository\InvoiceRepository;
use PortalCMS\Features\Members\Entity\Member;
use PortalCMS\Features\Members\Repository\MemberRepository;
use PortalCMS\Features\Pages\Entity\Page;
use PortalCMS\Features\Pages\Repository\PageRepository;
use PortalCMS\Features\Products\Entity\Product;
use PortalCMS\Features\Products\Repository\ProductRepository;
use PortalCMS\Features\Settings\Entity\SiteSetting as SiteSettingEntity;
use PortalCMS\Features\Settings\Repository\SiteSettingRepository;
use PortalCMS\Features\Users\Entity\Permission;
use PortalCMS\Features\Users\Entity\Role;
use PortalCMS\Features\Users\Entity\User;
use PortalCMS\Features\Users\Repository\PermissionRepository;
use PortalCMS\Features\Users\Repository\RoleRepository;
use PortalCMS\Features\Users\Repository\UserRepository;

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

    $services->set(EntityManagerInterface::class)
        ->factory([ DoctrineConfiguration::class, 'createEntityManager' ]);

    $repositories = [
        ActivityRepository::class => ActivityEntity::class,
        ContractRepository::class => Contract::class,
        EventRepository::class => Event::class,
        InvoiceRepository::class => Invoice::class,
        MailBatchRepository::class => MailBatch::class,
        MailScheduleRepository::class => MailSchedule::class,
        MailTemplateRepository::class => MailTemplate::class,
        MemberRepository::class => Member::class,
        PageRepository::class => Page::class,
        ProductRepository::class => Product::class,
        SiteSettingRepository::class => SiteSettingEntity::class,
        PermissionRepository::class => Permission::class,
        RoleRepository::class => Role::class,
        UserRepository::class => User::class,
    ];

    foreach ($repositories as $repository => $entity) {
        $services->set($repository)
            ->factory([ service(EntityManagerInterface::class), 'getRepository' ])
            ->args([ $entity ]);
    }

    $services->alias(MailTransport::class, SMTPTransport::class);
};
