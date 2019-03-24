### Command i Query ?
# Command:
Komendy zmieniają stan systemu jednak w event sorucingu nie bezpośrednio. Są one mediatorami miedzy warstwa UI a Domeny. 
```php
class CreateAuthorHandler implements CommandHandlerInterface
{
    /**
     * @var AuthorStore
     */
    private $authorStoreRepository;

    public function __construct(AuthorStore $authorStoreRepository)
    {
        $this->authorStoreRepository = $authorStoreRepository;
    }

    public function __invoke(CreateAuthorCommand $command): void
    {
        $author = Author::create(
            AggregateRootId::generate(),
            Name::fromString($command->getName())
        );
        $this->authorStoreRepository->save($author);
    }
}
```
Komenda wywołuje metodę tworzącą obiekt i wywołuje zapis z EventStoreRepository.
Ja personalnie używam własnej implementacji CommandBusa. Jak ona działa ? Wystarczy ze klasa będzie implementować interface CommandHandlerInterface i będzie w odpowiedniej konwencji nazewniczej np. jeżeli chcesz złapać TestCommand musisz stworzyć TestHandler.
#### Komenda powinna zmieniać stan nie powinna służyć do pobierania danych z readmodelu !!!!
# Query
Służa do pobierania danych np getAllBooksQuery działa to analogicznie jak komendy