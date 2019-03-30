<?php

declare(strict_types=1);

namespace Tests\Domain\Category\Validator;

use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Exception\CategoryNameExistException;
use App\Domain\Category\Exception\CategoryNotExistException;
use App\Domain\Category\Validator\CategoryValidator;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Tests\TestCase;

const existUuid = '680a2529-7b10-41d8-9002-e7d68be03faa';
const notExistUuid = '680a2529-7b10-41d8-9002-e7d68be03fa2';

class TestCategoryRepository implements CategoryRepository
{
    public function add(CategoryView $postView): void
    {
        // TODO: Implement add() method.
    }

    public function oneByUuid(AggregateRootId $id): CategoryView
    {
        // TODO: Implement oneByUuid() method.
    }

    public function delete(string $id): void
    {
        // TODO: Implement delete() method.
    }

    public function apply(): void
    {
        // TODO: Implement apply() method.
    }

    public function find(string $id): ?CategoryView
    {
        if (existUuid === $id) {
            return new CategoryView(existUuid, 'test');
        }

        return null;
    }

    public function findOneBy(array $query): ?CategoryView
    {
        if ('test' === $query['name']) {
            return new CategoryView(existUuid, 'test');
        }

        return null;
    }
}

class CategoryValidatorTest extends TestCase
{
    public function test_it_check_it_correctly()
    {
        $validator = new CategoryValidator(new TestCategoryRepository());
        $this->assertNull($validator->exist(existUuid));
        $this->expectException(CategoryNotExistException::class);
        $validator->exist(notExistUuid);
    }

    /**
     * @throws CategoryNotExistException
     */
    public function test_it_check_it_name_exist_correctly()
    {
        $validator = new CategoryValidator(new TestCategoryRepository());
        $this->assertNull($validator->categoryNameExist('not Found Name'));
        $this->expectException(CategoryNameExistException::class);
        $validator->categoryNameExist('test');
    }
}
