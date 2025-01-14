<?php

namespace CryptoCloud\Types;

class Decimal
{
    private string $value;

    public function __construct(string $value)
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("Value must be numeric.");
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function add(Decimal $other): Decimal
    {
        return new Decimal(bcadd($this->value, $other->getValue(), 10)); // 10 знаков после запятой
    }

    public function subtract(Decimal $other): Decimal
    {
        return new Decimal(bcsub($this->value, $other->getValue(), 10));
    }

    public function multiply(Decimal $other): Decimal
    {
        return new Decimal(bcmul($this->value, $other->getValue(), 10));
    }

    public function divide(Decimal $other): Decimal
    {
        if ($other->getValue() == '0') {
            throw new \InvalidArgumentException("Division by zero.");
        }
        return new Decimal(bcdiv($this->value, $other->getValue(), 10));
    }

    public function __toString(): string
    {
        return $this->value;
    }
}