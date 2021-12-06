<?php declare(strict_types=1);

// An Entity in Shopware6 consists of
// Entity, Definition and Collection Classes

// We created the ShopFinderDefiniton php
/*
 * which extends EntityDefinition
 *
 * Then we created ShopFinderEntity.php
 * Then the ShopFinderCollection.php file
 *
 * It loads Definition, Entity and then Collection
 * Definition holds the field definition
 * Entity to set get fields
 * Collection makes the Shopware know what entity to serve
 *
 *
 * We make the definition with defining the Entity and Collection classes  with naming the Entity itself
 * Then we create the DB structure inside in the  Definition file
 *
 * In the FinderEntity, we craete the fields for what is
 * inside the DB and getters/setters
 *
 * getExpectedClass in Collection Class returns the Entity Class
 *
 * Then after finishing the work with classes, we need to register
 *
 *In the services xml, the service - tag entity is the entity name
 * Also, the name attribute is the same it always should be
 * shopware.entity.definition
 *
 * Before the migration, activate the plugin. Otherwise, plugin is invisible to the system.
 *
 * Then do the migration
 * ./psh.phar docker:start
 * ./psh.phar docker:ssh
 * while inside docker:ssh
 * bin/console database:create-migration -p SwagShopFinder
 *
 * Then it creates the migration file
 * Remove updateDestruction
 * Add CREATE TABLE sql to the update method
 *
 * In addition, ids are UUIDs in Shopware. Therefore, they are BINARY(16) in the SQL DB.
 *
 * Then MOVES TO CONTroLLEr
 */

namespace SwagShopFinder;

use Shopware\Core\Framework\Plugin;

class SwagShopFinder extends Plugin
{

}
