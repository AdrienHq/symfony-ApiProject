<?php

namespace App\Factory;

use App\Entity\BooksNotification;
use App\Repository\BooksNotificationRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<BooksNotification>
 *
 * @method static BooksNotification|Proxy createOne(array $attributes = [])
 * @method static BooksNotification[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static BooksNotification|Proxy find(object|array|mixed $criteria)
 * @method static BooksNotification|Proxy findOrCreate(array $attributes)
 * @method static BooksNotification|Proxy first(string $sortedField = 'id')
 * @method static BooksNotification|Proxy last(string $sortedField = 'id')
 * @method static BooksNotification|Proxy random(array $attributes = [])
 * @method static BooksNotification|Proxy randomOrCreate(array $attributes = [])
 * @method static BooksNotification[]|Proxy[] all()
 * @method static BooksNotification[]|Proxy[] findBy(array $attributes)
 * @method static BooksNotification[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static BooksNotification[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static BooksNotificationRepository|RepositoryProxy repository()
 * @method BooksNotification|Proxy create(array|callable $attributes = [])
 */
final class BooksNotificationFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(BooksNotification $booksNotification): void {})
        ;
    }

    protected static function getClass(): string
    {
        return BooksNotification::class;
    }
}
