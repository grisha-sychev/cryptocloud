<?php

namespace CryptoCloud\Types;

class Dict
{
    private array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function get(string $key)
    {
        if (!array_key_exists($key, $this->data)) {
            throw new \InvalidArgumentException("Key '{$key}' not found.");
        }
        return $this->data[$key];
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

    public function keys(): array
    {
        return array_keys($this->data);
    }

    public function values(): array
    {
        return array_values($this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function __toString(): string
    {
        return json_encode($this->data);
    }
}
