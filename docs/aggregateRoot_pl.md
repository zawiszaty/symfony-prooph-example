# AggregateRoot
### Czym to własciwie jest ? 
Aggregaty, czyli twój problem biznesowy, czyli jeżeli masz bloga twoimi agregatami będą Post, User, Comment itp. Jest to obiekt, który posiada stan i zachowanie. Najczęściej składa się on z ValueObjects, czyli obiektów wartości. ValueObject są poniekąd przechowalnia nadych.
```php
class AggregateRootId
{
    /**
     * @var string
     */
    private $id;

    /**
     * AggregateRootId constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function generate()
    {
        $id = new self(Uuid::uuid4()->toString());

        return $id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $id): self
    {
        Assertion::uuid($id);
        $id = new self($id);

        return $id;
    }
}
```
Jak widać posiadają też walidacje. Jeżeli ValueObject się utworzył znaczy to tyle że ma w sobie poprawną wartość. 

Właśnie po to tworzy się ValueObjects, aby wydzielić cześć walidacji z domeny
### Event Sourcing
```php
class Author extends AggregateRoot
{
    /**
     * @var AggregateRootId
     */
    protected $id;

    /**
     * @var Name
     */
    protected $name;

    public static function create(AggregateRootId $generate, Name $name): Author
    {
        $author = new self();
        $author->recordThat(AuthorWasCreated::createWithData($generate, $name));

        return $author;
    }

    public function changeName(string $string): void
    {
        $this->name->changeName($string);
        $this->recordThat(AuthorNameWasChanged::createWithData($this->id, $this->name));
    }

    public function delete()
    {
        $this->recordThat(AuthorWasDeleted::createWithData($this->id));
    }

    public function applyAuthorWasDeleted(AuthorWasDeleted $authorWasDeleted)
    {
    }

    protected function applyAuthorNameWasChanged(AuthorNameWasChanged $authorNameWasChanged)
    {
        $this->name = $authorNameWasChanged->getName();
    }

    protected function applyAuthorWasCreated(AuthorWasCreated $authorWasCreated): void
    {
        $this->id = $authorWasCreated->getId();
        $this->name = $authorWasCreated->getName();
    }

    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    /**
     * Apply given event.
     */
    protected function apply(AggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);
        if (!\method_exists($this, $handler)) {
            throw new \RuntimeException(\sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                \get_class($this)
            ));
        }
        $this->{$handler}($e);
    }

    protected function determineEventHandlerMethodFor(AggregateChanged $e): string
    {
        return 'apply'.\implode(\array_slice(\explode('\\', \get_class($e)), -1));
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
```
W Event Sorcingu nie ma miejsca na settery, każda metoda musi opisywać zachowanie obiektu. Każde zachowanie ma własną metodę oraz metodę zatwierdzająca ją.
```php
    public static function create(AggregateRootId $generate, Name $name): Author
    {
        $author = new self();
        $author->recordThat(AuthorWasCreated::createWithData($generate, $name));

        return $author;
    }
    protected function applyAuthorWasCreated(AuthorWasCreated $authorWasCreated): void
    {
        $this->id = $authorWasCreated->getId();
        $this->name = $authorWasCreated->getName();
    }
```
Każde zachowanie musi tworzyć event. AggregatRoot zapisuje w sobie event i zatwierdza go na sobie (później przez EventStoreRepository właśnie te eventy będą zapisane w EventStore)
```php
final class AuthorWasCreated extends AggregateChanged
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    public static function createWithData(AggregateRootId $id, Name $name): self
    {
        /** @var self $event */
        $event = self::occur($id->toString(), [
            'name' => $name->toString(),
        ]);

        $event->id = $id;
        $event->name = $name;

        return $event;
    }

    public function getId(): AggregateRootId
    {
        if (null === $this->id) {
            $this->id = AggregateRootId::fromString($this->aggregateId());
        }

        return $this->id;
    }

    public function getName(): Name
    {
        if (null === $this->name) {
            $this->name = Name::fromString($this->payload['name']);
        }

        return $this->name;
    }
}
```
Przykładowy event
###  Zapamiętaj
Zawsze jest metoda zatwierdzająca jednak różnią sie one sposobem zatwierdzania niektórzy. Ja dodaje do swojego aggregatu zawsze taki kod. Pozwala on mi na definiowanie metod w takiej konwencji. Jeżeli aggregat zanotował event AuthorWasCreated metoda zatwierdzająca to applyAuthorWasCreated(AuthorWasCreated $event)
```php
    /**
     * Apply given event.
     */
    protected function apply(AggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);
        if (!\method_exists($this, $handler)) {
            throw new \RuntimeException(\sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                \get_class($this)
            ));
        }
        $this->{$handler}($e);
    }

    protected function determineEventHandlerMethodFor(AggregateChanged $e): string
    {
        return 'apply'.\implode(\array_slice(\explode('\\', \get_class($e)), -1));
    }
```
Do tego każdy aggregat musi być zdefiniowany w konfiguracji
```yaml
                book:
                    repository_class: App\Infrastructure\Book\Repository\BookStoreRepository
                    aggregate_type: App\Domain\Book\Book
                    aggregate_translator: prooph_event_sourcing.aggregate_translator
```
Oraz posiadać StoreRepository
```php
class BookStoreRepository extends AggregateRepository implements BookStore
{
    /**
     * BookStoreRepository constructor.
     */
    public function __construct(MySqlEventStore $eventStore, AggregateTranslator $aggregateTranslator)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromString(Book::class),
            $aggregateTranslator
        );
    }

    public function save(Book $todo): void
    {
        $this->saveAggregateRoot($todo);
    }

    public function get(AggregateRootId $todoId): ?Book
    {
        /** @var Book $book */
        $book = $this->getAggregateRoot($todoId->toString());

        return $book;
    }
}
```