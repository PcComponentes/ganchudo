<?php
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Tests\Util;

use Pccomponentes\Ganchudo\Util\Output;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    /**
     * @test
     */
    public function given_all_tags_when_ask_to_parse_ascii_colors_thwn_return_expected_string()
    {
        $this->assertEquals("\e[0m", Output::parse('<r>'), 'reset tag');
        $this->assertEquals("\e[1m", Output::parse('<b>'), 'bright tag');
        $this->assertEquals("\e[4m", Output::parse('<u>'), 'underline tag');
        $this->assertEquals("\e[5m", Output::parse('<f>'), 'flash tag');
        $this->assertEquals("\e[30m", Output::parse('<fg_black>'), 'foreground black color tag');
        $this->assertEquals("\e[31m", Output::parse('<fg_red>'), 'foreground red color tag');
        $this->assertEquals("\e[32m", Output::parse('<fg_green>'), 'foreground green color tag');
        $this->assertEquals("\e[33m", Output::parse('<fg_yellow>'), 'foreground yellow color tag');
        $this->assertEquals("\e[34m", Output::parse('<fg_blue>'), 'foreground blue color tag');
        $this->assertEquals("\e[35m", Output::parse('<fg_purple>'), 'foreground purple color tag');
        $this->assertEquals("\e[36m", Output::parse('<fg_cyan>'), 'foreground cyan color tag');
        $this->assertEquals("\e[37m", Output::parse('<fg_white>'), 'foreground white color tag');
        $this->assertEquals("\e[40m", Output::parse('<bg_black>'), 'background black color tag');
        $this->assertEquals("\e[41m", Output::parse('<bg_red>'), 'background red color tag');
        $this->assertEquals("\e[42m", Output::parse('<bg_green>'), 'background green color tag');
        $this->assertEquals("\e[43m", Output::parse('<bg_yellow>'), 'background yellow color tag');
        $this->assertEquals("\e[44m", Output::parse('<bg_blue>'), 'background blue color tag');
        $this->assertEquals("\e[45m", Output::parse('<bg_purple>'), 'background purple color tag');
        $this->assertEquals("\e[46m", Output::parse('<bg_cyan>'), 'background cyan color tag');
        $this->assertEquals("\e[47m", Output::parse('<bg_white>'), 'background white color tag');
        $this->assertEquals('', Output::parse(''), 'empty');
        $this->assertEquals(
            "<nothing>more text\e[31mthis is a description\e[0m",
            Output::parse('<nothing>more text<fg_red>this is a description<r>'),
            'full example'
        );

    }

}
