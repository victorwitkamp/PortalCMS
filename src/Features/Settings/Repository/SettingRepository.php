<?php

declare(strict_types=1);

namespace PortalCMS\Features\Settings\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PortalCMS\Features\Settings\Entity\Setting;

/**
 * @extends ServiceEntityRepository<Setting>
 */
final class SettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function findByName(string $name): ?Setting
    {
        $setting = $this->find($name);

        return $setting instanceof Setting ? $setting : null;
    }

    /**
     * @param string[] $names
     * @return array<string, Setting>
     */
    public function findByNames(array $names): array
    {
        if ($names === []) {
            return [];
        }

        $settings = $this->createQueryBuilder('setting')
            ->where('setting.name IN (:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();

        $indexedSettings = [];
        foreach ($settings as $setting) {
            if ($setting instanceof Setting) {
                $indexedSettings[$setting->name()] = $setting;
            }
        }

        return $indexedSettings;
    }

    /**
     * @param string[] $names
     * @return array<string, string|null>
     */
    public function findValues(array $names): array
    {
        if ($names === []) {
            return [];
        }

        $values = array_fill_keys($names, null);
        $rows = $this->createQueryBuilder('setting')
            ->select('setting.name', 'setting.value')
            ->where('setting.name IN (:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getArrayResult();

        foreach ($rows as $row) {
            $values[(string) $row['name']] = isset($row['value'])
                ? (string) $row['value']
                : null;
        }

        return $values;
    }
}
