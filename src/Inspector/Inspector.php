<?php
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Inspector;

interface Inspector
{
    public function execute(): InspectorResult;
    public function name(): string;
}
