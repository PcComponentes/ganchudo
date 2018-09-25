<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Tests\Util;

use Pccomponentes\Ganchudo\Util\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    /**
     * @test
     */
    public function given_dir_and_relative_file_path_when_ask_for_absolute_file_path_then_return_it()
    {
        $this->assertEquals('/var/example/subdir/file.txt', Path::fullPath('/var/example', 'subdir/file.txt'));
    }

    /**
     * @test
     */
    public function given_dir_with_end_bar_and_relative_file_path_when_ask_for_absolute_file_path_then_return_it()
    {
        $this->assertEquals('/var/example/subdir/file.txt', Path::fullPath('/var/example/', 'subdir/file.txt'));
    }

    /**
     * @test
     */
    public function given_dir_and_absolute_file_path_when_ask_for_absolute_file_path_then_return_it()
    {
        $this->assertEquals('/subdir/file.txt', Path::fullPath('/var/example', '/subdir/file.txt'));
    }
}
