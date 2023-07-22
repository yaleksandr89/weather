<?php

namespace Ya\Weather\Models;

class Result
{
    private int|float $fahrenheit;
    private int|float $celsius;

    public function getFahrenheit(): float|int
    {
        return $this->fahrenheit;
    }

    public function setFahrenheit(float|int $fahrenheit): static
    {
        $this->fahrenheit = $fahrenheit;
        return $this;

    }

    public function getCelsius(): float|int
    {
        return $this->celsius;
    }

    public function setCelsius(float|int $celsius): static
    {
        $this->celsius = $celsius;
        return $this;
    }
}
