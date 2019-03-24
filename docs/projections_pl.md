### Projekcje 
W prooph po wystąpieniu eventu szukana jest projekcja a mianowicie szukany jest metoda która "złapie" event. Tak wygląda przykładowy projektor.
```php
/**
 * @method readModel()
 */
class AuthorProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                AuthorWasCreated::class => function ($state, AuthorWasCreated $event) {
                    /** @var AuthorReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('insert', [
                        'id' => $event->getId()->toString(),
                        'name' => $event->getName()->toString(),
                    ]);
                },
                AuthorNameWasChanged::class => function ($state, AuthorNameWasChanged $event) {
                    /** @var AuthorReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('changeName', [
                        'id' => $event->getId()->toString(),
                        'name' => $event->getName()->toString(),
                    ]);
                },
                AuthorWasDeleted::class => function ($state, AuthorWasDeleted $event) {
                    /** @var AuthorReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('deleteAuthor', [
                        'id' => $event->getId()->toString(),
                    ]);
                },
            ]);

        return $projector;
    }
}
```
Kiedy wystąpi Event ma go złapać i odpalić odpowiednią metode ReadModelu
```php
class AuthorReadModel extends AbstractReadModel
{
    /**
     * @var MysqlAuthorRepository
     */
    private $authorRepository;
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var \Doctrine\DBAL\Schema\Schema
     */
    private $schema;

    public function __construct(MysqlAuthorRepository $authorRepository, Connection $connection)
    {
        $this->authorRepository = $authorRepository;
        $this->connection = $connection;
        $this->schema = $connection->getSchemaManager()->createSchema();
    }

    public function init(): void
    {
        $this->schema->createTable('author');
    }

    public function isInitialized(): bool
    {
        return $this->schema->hasTable('author');
    }

    public function reset(): void
    {
        $this->schema->dropTable('author');
        $this->schema->createTable('author');
    }

    public function delete(): void
    {
        $this->schema->dropTable('author');
    }

    public function insert(array $data)
    {
        $author = new AuthorView(
            $data['id'],
            $data['name']
        );
        $this->authorRepository->add($author);
    }

    public function changeName(array $data)
    {
        $author = $this->authorRepository->find($data['id']);
        $author->changeName($data['name']);
        $this->authorRepository->apply();
    }

    public function deleteAuthor(array $data)
    {
        $this->authorRepository->delete($data['id']);
    }
}
```
Tak wygląda przykładowy ReadModel. 
Ja używam Doctrine wiec wykosztuje jego encje i repozytoria. Niektórzy powiedzą, że nie powinno się tego robić w EventSorcingu. Ma to swoje wady, jak i zalety. Decyzje pozostawiam wam. Demo proopha pokazuje alternatywe z czystym sqlem https://github.com/prooph/proophessor-do-symfony
### Zapamiętaj
Każda projekcja musi być zdefiniowana w pliku konfiguracyjnym 
```yaml
                author_projection:
                    read_model: App\Infrastructure\Author\Projection\AuthorReadModel
                    projection: App\Infrastructure\Author\Projection\AuthorProjection
```
Inaczej wasz projektor nie bedzie "łapał" Eventów
Do tego każda projekcje trzeba uruchomić z automatu sa async i działaja przez workera.
Ja używam do tego kontenera dockera, który automatycznie po uruchomieniu odpala projekcje
```yaml
  worker_2:
    image: zawiszaty/tutorials-tank-php:3.0.3
    working_dir: /var/www/project
    volumes:
      - ./symfony:/var/www/project
    command: ['php', 'bin/console', 'event-store:projection:run', 'author_projection']
    restart: always
    links:
      - php
```