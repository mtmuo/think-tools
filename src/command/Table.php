<?php
// +----------------------------------------------------------------------
// | think-utils-Table
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://www.bajiukeji.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Date: 2021/04/17 17:10
// +----------------------------------------------------------------------
// | Author: bajiu <bajiu@bajiukeji.com>
// +--------------------------------------------------------------------

namespace mtmuo\think\command;


use think\console\Command;
use think\console\Input;
use think\console\Output;

class Table extends Command
{
    protected function configure()
    {
        $this->setName('table')
            ->setDescription('Build database schema cache.');
    }

    protected function execute(Input $input, Output $output)
    {

    }
}
