<?php
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Inspector\Implementation;

use Pccomponentes\Ganchudo\Inspector\Inspector;
use Pccomponentes\Ganchudo\Inspector\InspectorResult;
use Symfony\Component\Process\Process;

class SimpleInspector implements Inspector
{
    private $workingDir;
    private $name;
    private $command;
    private $timeout;

    public function __construct(string $workingDir, string $name, string $command, ?int $timeout)
    {
        $this->workingDir = $workingDir;
        $this->name = $name;
        $this->command = \explode(' ', $command);
        $this->timeout = $timeout;
    }

    public function execute(): InspectorResult
    {
        $process = new Process(
            $this->command,
            $this->workingDir,
            $_ENV,
            null,
            $this->timeout
        );

        $process->run();

        return new InspectorResult($process->isSuccessful(), $process->getOutput() . $process->getErrorOutput());
    }

    public function name(): string
    {
        return $this->name;
    }
}
