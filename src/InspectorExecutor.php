<?php
declare(strict_types=1);
namespace Pccomponentes\Ganchudo;

use Pccomponentes\Ganchudo\Inspector\Inspector;
use Pccomponentes\Ganchudo\Inspector\InspectorResult;
use Pccomponentes\Ganchudo\Util\Output;

class InspectorExecutor
{
    private $inspectors;

    public function __construct(array $inspectors)
    {
        $this->inspectors = $inspectors;
    }

    public function execute(): int
    {
        $successfully = true;
        \array_walk(
            $this->inspectors,
            function (Inspector $inspector) use (&$successfully) {
                echo $this->title($inspector->name());
                $result = $this->executeOne($inspector);
                if ($result->successful()) {
                    echo $this->okMessage();
                } else {
                    $successfully = false;
                    echo $result->output() . PHP_EOL;
                    echo $this->failMessage($inspector->name());
                }
            }
        );

        return $successfully ? $this->happyEnd() : $this->badEnd();
    }

    private function executeOne(Inspector $inspector): InspectorResult
    {
        try {
            return $inspector->execute();
        } catch (\Throwable $e) {
            return new InspectorResult(false, $e->getMessage());
        }
    }

    private function title(string $inspector): string
    {
        $style = '<b><bg_green><fg_white>';
        $str = $style . \str_pad('', \strlen($inspector) + 4, '=') . '<r>' . PHP_EOL;
        $str .= $style . "= {$inspector} =" . '<r>' . PHP_EOL;
        $str .= $style . \str_pad('', \strlen($inspector) + 4, '=') . '<r>' . PHP_EOL;

        return Output::parse($str);
    }

    private function okMessage(): string
    {
        return Output::parse('<fg_green>OK!<r>' . PHP_EOL);
    }

    private function failMessage(string $inspector): string
    {
        return Output::parse("<b><fg_white><bg_red>{$inspector} fails. Resolve the conflict first.<r>" . PHP_EOL);
    }

    private function happyEnd(): int
    {
        $str = <<<PAINT
<b><fg_green>-----------------------------------------------------
  <f><fg_cyan>The team celebrates your work<r><b><fg_green>
   _                             .-.
  / )  .-.    ___          __   (   )
 ( (  (   ) .'___)        (__'-._) (
  \ '._) (,'.'               '.     '-.
   '.      /  "\               '    -. '.
     )    /   \ \   .-.   ,'.   )  (  ',_)    _
   .'    (     \ \ (   \ . .' .'    )    .-. ( \
  (  .''. '.    \ \|  .' .' ,',--, /    (   ) ) )
   \ \   ', :    \    .-'  ( (  ( (     _) (,' /
    \ \   : :    )  / _     ' .  \ \  ,'      /
  ,' ,'   : ;   /  /,' '.   /.'  / / ( (\    (
  '.'      "   (    .-'. \       ''   \_)\    \
                \  |    \ \__             )    )
              ___\ |     \___;           /  , /
             /  ___)                    (  ( (
             '.'                         ) ;) ;
                                        (_/(_/
----------------------------------------------------<r>
PAINT;
        echo Output::parse($str . PHP_EOL);
        return 0;
    }

    private function badEnd(): int
    {
        $str = <<<PAINT
<b><fg_red>~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
          <f><fg_cyan>"Dear developer, FIX THIS FUCKING SHIT NOW.<r><b><fg_red>
         ___       <f><fg_cyan>Thanks for your understanding.<r><b><fg_red>
        /_ _\           <f><fg_cyan>Have a nice day."<r><b><fg_red>
       ( - - )
      __\ _ /__                   \
     /   \_/   \                   \  __
  __/___________\__                  //\\\
  |                |\    ____       (//\\\)
  |      <fg_white>CODE<fg_red>      ||   / ___)      _\__/_
  |    <fg_white>INSPECTOR<fg_red>   ||   (.~O=O     /      \
  |                ||   _\_-/     / /    \ \
  |                ||  /  \|/\    \_\     |/
  |                ||_/_|____|\__  (/_____|
  |                |            |\  | _ _ |
  |                |    <fg_white>YOU<fg_red>     ||__|  |  |________
  |                |            ||  |  |  |
  |________________|____________|/  |__|__|
                                   (__/ \__)

[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[[ <fg_white>TEAM LEADER<fg_red> [[[[
]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]]
___________________________________________________
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<r>
PAINT;
        echo Output::parse($str . PHP_EOL);
        return 1;
    }
}
