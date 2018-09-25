<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Ganchudo;

use Pccomponentes\Ganchudo\Config\Configuration;
use Pccomponentes\Ganchudo\Util\Output;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class Runner
{
    public function run(string $configFile): int
    {
        try {
            $config = $this->load($configFile);
        } catch (\Throwable $e) {
            echo Output::parse(PHP_EOL . '<b><bg_red><fg_white>' . $e->getMessage(). '<r>' . PHP_EOL);
            exit(1);
        }

        $builder = new InspectorExecutorBuilder(\dirname($configFile));
        $inspectors = $config['inspectors'];

        \array_walk(
            $inspectors,
            function (array $inspector) use ($builder) {
                if (1 === \preg_match('/<iterator>/i', $inspector['command'])) {
                    if (false === \array_key_exists('iterator', $inspector)) {
                        throw new \InvalidArgumentException(
                            sprintf('Iterator fields needed in %s inspector', $inspector['name'])
                        );
                    }
                    $builder->addIterator(
                        $inspector['name'],
                        $inspector['command'],
                        $inspector['timeout'],
                        $inspector['iterator']['in'],
                        $inspector['iterator']['file'],
                        $inspector['iterator']['exclude']
                    );
                } else {
                    $builder->add(
                        $inspector['name'],
                        $inspector['command'],
                        $inspector['timeout']
                    );
                }
            }
        );

        return $builder->build()->execute();
    }

    private function load(string $configFile): array
    {
        if (true !== @\is_readable($configFile)) {
            throw new \InvalidArgumentException(sprintf('%s is not readable.', $configFile));
        }

        return (new Processor())->processConfiguration(
            new Configuration(),
            Yaml::parseFile($configFile)
        );
    }
}
