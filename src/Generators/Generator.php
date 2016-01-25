<?php
/**
 * Created by naveedulhassan.
 * Date: 1/22/16
 * Time: 5:08 PM
 */

namespace Nvd\Crud\Generators;

use Nvd\Crud\Commands\Crud;

class Generator extends Crud
{
    public $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        return parent::__construct();
    }

}