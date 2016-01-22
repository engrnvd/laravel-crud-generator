<?php
/**
 * Created by naveedulhassan.
 * Date: 1/22/16
 * Time: 5:08 PM
 */

namespace Nvd\Crud\Generators;

use Nvd\Crud\Commands\Crud;

class Generator
{
    public $tableName;
    public $command;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->command = new Crud();
    }

    public function route()
    {
        return str_singular($this->tableName);
    }

    public function controller()
    {
        return studly_case(str_singular($this->tableName))."Controller";
    }
}