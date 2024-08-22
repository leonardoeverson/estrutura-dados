<?php

class Node
{
    private ?string $key;

    private ?string $value;

    private ?self $next = null;
    private ?self $previous = null;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }


    public function setNext(?Node $node): void
    {
        $this->next = $node;
    }

    /** @return ?Node */
    public function getNext(): ?Node
    {
        return $this->next;
    }

    public function setPrevious(Node $node): void
    {
        $this->previous = $node;
    }

    /** @return ?Node */
    public function getPrevious(): ?Node
    {
        return $this->previous;
    }

    /** @return string|null */
    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setData(string $value): void
    {
        $this->value = $value;
    }

    /** @return string|null */
    public function getData(): ?string
    {
        return $this->value;
    }
}

class LRUCache
{
    private ?Node $head;

    private ?Node $tail;

    private array $hashMap = [];

    public function __construct(private readonly int $capacity = 1000)
    {
        $this->head = null;
        $this->tail = null;
    }

    public function set(string $key, string $value): bool
    {
        if ($this->capacity <= 0) {
            return false;
        }

        if (!empty($this->hashMap[$key])) {
            $node = $this->hashMap[$key];
            $this->detach($node);
            $this->attach($this->head);
            $node->setData($value);
            return false;
        }

        $node = new Node($key, $value);
        $this->hashMap[$key] = $node;
        $this->attach($node);
        $this->setTail();
        return true;
    }

    /**
     * @param string $key
     * @return Node|null
     */
    public function get(string $key): ?Node
    {
        if (empty($this->hashMap[$key])) {
            return null;
        }

        return $this->hashMap[$key];
    }

    public function remove(string $key): bool
    {
        if (empty($this->hashMap[$key])) {
            return false;
        }

        $node = $this->hashMap[$key];
        $this->detach($node);
        unset($this->hashMap[$node->getKey()]);
        return true;
    }

    private function attach(Node $node): void
    {
        if (!$this->head) {
            $this->head = $node;
            $this->tail = $node;
            return;
        }

        $node->setPrevious($this->head);
        $node->getPrevious()->setNext($node);
        $this->head = $node;
    }

    private function setTail(): void
    {
        $current = $this->head;

        while (true) {
            if (!$current->getPrevious()) {
                $this->tail = $current;
                break;
            }

            $current = $current->getPrevious();
        }

    }

    private function detach(Node $node): void
    {
        $node->getPrevious()->setNext($node->getNext());
        $node->getNext()->setPrevious($node->getPrevious());
    }

    public function frontToBack(): void
    {
        $currentItem = $this->head;

        while ($currentItem !== null) {
            echo $currentItem->getKey() . ' - ' . $currentItem->getData() . PHP_EOL;
            $currentItem = $currentItem->getPrevious();
        }
    }

    public function backToFront(): void
    {
        $currentItem = $this->tail;

        while ($currentItem !== null) {
            echo $currentItem->getKey() . ' - ' . $currentItem->getData() . PHP_EOL;
            $currentItem = $currentItem->getNext();
        }
    }
}

$lruCache = new LRUCache(10);
$lruCache->set('1', '1');
$lruCache->set('2', '12');
$lruCache->set('3', '123');
$lruCache->set('4', '1234');
$lruCache->set('5', '12345');
$lruCache->set('6', '123456');
$lruCache->set('7', '1234567');
$lruCache->set('8', '12345678');

$lruCache->remove('4');

$lruCache->frontToBack();
$lruCache->backToFront();