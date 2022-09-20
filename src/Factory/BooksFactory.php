<?php

namespace App\Factory;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Books>
 *
 * @method static Books|Proxy createOne(array $attributes = [])
 * @method static Books[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Books|Proxy find(object|array|mixed $criteria)
 * @method static Books|Proxy findOrCreate(array $attributes)
 * @method static Books|Proxy first(string $sortedField = 'id')
 * @method static Books|Proxy last(string $sortedField = 'id')
 * @method static Books|Proxy random(array $attributes = [])
 * @method static Books|Proxy randomOrCreate(array $attributes = [])
 * @method static Books[]|Proxy[] all()
 * @method static Books[]|Proxy[] findBy(array $attributes)
 * @method static Books[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Books[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static BooksRepository|RepositoryProxy repository()
 * @method Books|Proxy create(array|callable $attributes = [])
 */
final class BooksFactory extends ModelFactory
{
    public function published(): self
    {
        return $this->addState(['isPublished' => true]);
    }

    public function withLongDescription(): self
    {
        return $this->addState([
            'description' => self::faker()->paragraphs(3, true)
        ]);
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'title' => 'Factory Title',
            'numberOfPages' => 7437057,
            'dateOfRelease' => null, // TODO add DATETIME ORM type manually
            'author' => UserFactory::new(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Books $books): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Books::class;
    }
}
