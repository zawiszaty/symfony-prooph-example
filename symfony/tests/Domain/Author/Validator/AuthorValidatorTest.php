<?php

declare(strict_types=1);

namespace Tests\Domain\Author\Validator;

use App\Domain\Author\Exception\AuthorNameFoundException;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Author\Query\AuthorRepository;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Author\Validator\AuthorValidator;
use Tests\TestCase;

const existUuid = '680a2529-7b10-41d8-9002-e7d68be03faa';
const notExistUuid = '680a2529-7b10-41d8-9002-e7d68be03fa2';

class TestAuthorRepository implements AuthorRepository
{
    public function add(AuthorView $authorView): void
    {
        // TODO: Implement add() method.
    }

    public function oneByUuid(AggregateRootId $id): AuthorView
    {
        // TODO: Implement oneByUuid() method.
    }

    public function find(string $id): ?AuthorView
    {
        if (existUuid === $id) {
            return new AuthorView(existUuid, 'test');
        }

        return null;
    }

    public function findOneBy(array $query): ?AuthorView
    {
        if ('test' === $query['name']) {
            return new AuthorView(existUuid, 'test');
        }

        return null;
    }

    public function delete(string $id): void
    {
        // TODO: Implement delete() method.
    }

    public function apply(): void
    {
        // TODO: Implement apply() method.
    }
}

class AuthorValidatorTest extends TestCase
{
    public function test_it_check_exist()
    {
        $validator = new AuthorValidator(new TestAuthorRepository());
        $this->assertNull($validator->exist(existUuid));
    }

    public function test_it_check_it_not_exist()
    {
        $this->expectException(AuthorNotFoundException::class);
        $validator = new AuthorValidator(new TestAuthorRepository());
        $validator->exist(notExistUuid);
    }

    public function test_it_check_it_name_exist()
    {
        $this->expectException(AuthorNameFoundException::class);
        $validator = new AuthorValidator(new TestAuthorRepository());
        $this->assertNull($validator->authorNameExist('test'));
    }

    public function test_it_check_it_name_not_exist()
    {
        $validator = new AuthorValidator(new TestAuthorRepository());
        $this->assertNull($validator->authorNameExist('test2'));
    }
}
