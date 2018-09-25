<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Inspector\Implementation;

use Pccomponentes\Ganchudo\Inspector\Inspector;
use Pccomponentes\Ganchudo\Inspector\InspectorResult;
use Symfony\Component\Process\Process;

class IterativeInspector implements Inspector
{
    private $workingDir;
    private $name;
    private $command;
    private $timeout;
    private $iterator;

    public function __construct(
        string $workingDir,
        string $name,
        string $command,
        ?int $timeout,
        \IteratorAggregate $iterator
    ) {
        $this->workingDir = $workingDir;
        $this->name = $name;
        $this->command = \explode(' ', $command);
        $this->timeout = $timeout;
        $this->iterator = $iterator;
    }

    public function execute(): InspectorResult
    {
        $successfully = true;
        $output = '';
        foreach ($this->iterator as $file) {
            $process = new Process(
                \preg_replace('/<iterator>/i', $file->getRealPath(), $this->command),
                $this->workingDir,
                $_ENV,
                null,
                $this->timeout
            );

            $process->run();

            if (false === $process->isSuccessful()) {
                $successfully = false;
                $output .= $process->getOutput() . $process->getErrorOutput();
            }
        }

        return new InspectorResult($successfully, $output);
    }

    public function name(): string
    {
        return $this->name;
    }
}
