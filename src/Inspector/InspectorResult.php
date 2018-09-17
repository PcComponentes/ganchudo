<?php
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Inspector;

class InspectorResult
{
    private $successful;
    private $output;

    public function __construct(bool $successful, string $output)
    {
        $this->successful = $successful;
        $this->output = $output;
    }

    public function successful(): bool
    {
        return $this->successful;
    }

    public function output(): string
    {
        return $this->output;
    }
}
