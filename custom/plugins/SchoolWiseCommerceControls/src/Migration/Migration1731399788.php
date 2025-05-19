<?php declare(strict_types=1);

namespace SchoolWiseCommerceControls\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1731399788 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1731399788;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('CREATE TABLE IF NOT EXISTS `school_licences_information` (
             `id` BINARY(16) NOT NULL,
             `customer_group_id` BINARY(16) NOT NULL,
             `category_id` BINARY(16) NOT NULL,
             `category_version_id` BINARY(16) NOT NULL,
             `product_id` BINARY(16) NOT NULL,
             `product_version_id` BINARY(16) NOT NULL,
             `created_at` DATETIME(3) NOT NULL,
             `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }
}
