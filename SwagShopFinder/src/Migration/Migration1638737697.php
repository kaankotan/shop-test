<?php declare(strict_types=1);

namespace SwagShopFinder\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1638737697 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1638737697;
    }

    public function update(Connection $connection): void
    {
        $connection->exec("CREATE TABLE IF NOT EXISTS `swag_shop_finder` (
            `id` BINARY(16) NOT NULL,
            `active` TINYINT(1) NULL DEFAULT '0',
            `name` VARCHAR(255) NOT NULL,
            `street` VARCHAR(255) NOT NULL,
            `post_code` VARCHAR(255) NOT NULL,
            `city` VARCHAR(255) NOT NULL,
            `url` VARCHAR(255) NULL,
            `telephone` VARCHAR(255) NULL,
            `open_times` LONGTEXT NULL,
            `country_id` BINARY(16) NULL,
            `created_ad` DATETImE(3),
            `updated_at` DATETImE(3),
            PRIMARY_KEY (`id`),
            KEY `fk.swag.shop_finder.country_id` (`country_id`),
            CONSTRAINT `fk.swag.shop_finder.country_id` FOREIGN KEY (`country_id`)
            REFERENCES `country` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4 unicode_ci;");
    }
}
