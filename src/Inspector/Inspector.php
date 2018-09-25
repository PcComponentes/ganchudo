<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Ganchudo\Inspector;

interface Inspector
{
    public function execute(): InspectorResult;
    public function name(): string;
}
