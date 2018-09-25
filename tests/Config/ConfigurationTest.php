<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Tests\Config;

use Pccomponentes\Ganchudo\Config\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

class ConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function given_simple_inspector_config_when_load_it_then_parse_it_successfully()
    {
        $str = <<<CONFIG
ganchudo:
    inspectors:
        -   name: 'Composer Validation'
            command: 'composer.phar validate --strict'
CONFIG;

        $parsed = (new Processor())->processConfiguration(
            new Configuration(),
            Yaml::parse($str)
        );

        $this->assertArrayHasKey('inspectors', $parsed);
        $this->assertCount(1, $parsed['inspectors']);
        $inspector = $parsed['inspectors'][0];
        $this->assertArrayHasKey('name', $inspector);
        $this->assertEquals('Composer Validation', $inspector['name']);
        $this->assertArrayHasKey('command', $inspector);
        $this->assertEquals('composer.phar validate --strict', $inspector['command']);
        $this->assertArrayHasKey('timeout', $inspector);
        $this->assertEquals(null, $inspector['timeout']);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function given_bad_command_simple_inspector_config_when_load_it_then_throw_exception()
    {
        $str = <<<CONFIG
ganchudo:
    inspectors:
        -   name: 'Composer Validation'
CONFIG;

        (new Processor())->processConfiguration(
            new Configuration(),
            Yaml::parse($str)
        );
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function given_bad_name_simple_inspector_config_when_load_it_then_throw_exception()
    {
        $str = <<<CONFIG
ganchudo:
    inspectors:
        -   command: 'composer.phar validate --strict'
CONFIG;

        (new Processor())->processConfiguration(
            new Configuration(),
            Yaml::parse($str)
        );
    }

    /**
     * @test
     */
    public function given_iterator_inspector_config_when_load_it_then_parse_it_successfully()
    {
        $str = <<<CONFIG
ganchudo:
    inspectors:
        -   name: 'Php Linter'
            command: 'php -l <iterator>'
            timeout: 3600
            iterator:
                in: ['src', 'tests']
                file: '*.php'
CONFIG;

        $parsed = (new Processor())->processConfiguration(
            new Configuration(),
            Yaml::parse($str)
        );

        $this->assertArrayHasKey('inspectors', $parsed);
        $this->assertCount(1, $parsed['inspectors']);
        $inspector = $parsed['inspectors'][0];
        $this->assertArrayHasKey('name', $inspector);
        $this->assertEquals('Php Linter', $inspector['name']);
        $this->assertArrayHasKey('command', $inspector);
        $this->assertEquals('php -l <iterator>', $inspector['command']);
        $this->assertArrayHasKey('timeout', $inspector);
        $this->assertEquals(3600, $inspector['timeout']);
        $iterator = $inspector['iterator'];
        $this->assertArrayHasKey('in', $iterator);
        $this->assertEquals(['src', 'tests'], $iterator['in']);
        $this->assertArrayHasKey('file', $iterator);
        $this->assertEquals('*.php', $iterator['file']);
        $this->assertArrayHasKey('exclude', $iterator);
        $this->assertEquals([], $iterator['exclude']);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function given_bad_in_iterator_inspector_config_when_load_it_then_throw_exception()
    {
        $str = <<<CONFIG
ganchudo:
    inspectors:
        -   name: 'Php Linter'
            command: 'php -l <iterator>'
            timeout: 3600
            iterator:
                file: '*.php'
CONFIG;

        (new Processor())->processConfiguration(
            new Configuration(),
            Yaml::parse($str)
        );
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function given_bad_file_iterator_inspector_config_when_load_it_then_throw_exception()
    {
        $str = <<<CONFIG
ganchudo:
    inspectors:
        -   name: 'Php Linter'
            command: 'php -l <iterator>'
            timeout: 3600
            iterator:
                in: ['src', 'tests']
CONFIG;

        (new Processor())->processConfiguration(
            new Configuration(),
            Yaml::parse($str)
        );
    }
}
