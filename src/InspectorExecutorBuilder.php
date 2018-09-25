<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Ganchudo;

use Pccomponentes\Ganchudo\Inspector\Implementation\IterativeInspector;
use Pccomponentes\Ganchudo\Inspector\Implementation\SimpleInspector;
use Pccomponentes\Ganchudo\Util\Path;
use Symfony\Component\Finder\Finder;

class InspectorExecutorBuilder
{
    private $workingDir;
    private $inspectors;

    public function __construct(string $workingDir)
    {
        $this->workingDir = $workingDir;
        $this->inspectors = [];
    }

    public function add(string $name, string $command, ?int $timeout): void
    {
        $this->inspectors[] = new SimpleInspector($this->workingDir, $name, $command, $timeout);
    }

    public function addIterator(
        string $name,
        string $command,
        ?int $timeout,
        array $in,
        string $file,
        array $exclude
    ): void {
        $iterator = (new Finder())
            ->in($this->absolutePaths($in))
            ->exclude($exclude)
            ->files()
            ->name($file);

        $this->inspectors[] = new IterativeInspector($this->workingDir, $name, $command, $timeout, $iterator);
    }

    public function build(): InspectorExecutor
    {
        return new InspectorExecutor($this->inspectors);
    }

    private function absolutePaths(array $relativePaths): array
    {
        return \array_map(
            function (string $dir) {
                return Path::fullPath($this->workingDir, $dir);
            },
            $relativePaths
        );
    }
}
