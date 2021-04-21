<?php
// +----------------------------------------------------------------------
// | 商城授权系统-Table
// +----------------------------------------------------------------------
// | Copyright (c) 2021 http://www.bajiukeji.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Date: 2021/04/17 17:23
// +----------------------------------------------------------------------
// | Author: bajiu <bajiu@bajiukeji.com>
// +--------------------------------------------------------------------

namespace mtmuo\think\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Facade\Db;

class Table extends Command
{
    protected function configure()
    {
        $this->setName('table')
            ->setDescription('MYSQL Table TO Markdown');
    }

    protected function execute(Input $input, Output $output)
    {
        $database = $this->app->db->connect()->getConfig('database');
        $tables = Db::query("select TABLE_NAME,TABLE_COMMENT from information_schema.tables where table_schema='{$database}'");
        $md = "";
        foreach ($tables as $table) {
            $md .= "### " . $table['TABLE_COMMENT'] . "(" . $table['TABLE_NAME'] . ")" . "\n";
            $md .= "| 字段 | 类型 | 备注 |\n";
            $md .= "| :--: | :--: | :--: |\n";
            $fields = Db::query("select COLUMN_NAME ,
IS_NULLABLE ,DATA_TYPE ,CHARACTER_SET_NAME,
COLUMN_TYPE ,COLUMN_KEY ,COLUMN_COMMENT
from information_schema.columns where table_schema = '{$database}' and table_name = '{$table['TABLE_NAME']}' ORDER BY ORDINAL_POSITION asc");
            foreach ($fields as $field) {
                $md .= "| {$field['COLUMN_NAME']} | {$field['COLUMN_TYPE']} | {$field['COLUMN_COMMENT']}" . ($field['COLUMN_KEY'] == 'YES' ? '' : '|主键') . " |\n";
            }
        }
        file_put_contents($this->app->getRootPath() . "TABLE.md", $md);
        $output->writeln('<info>Succeed!</info>');
    }
}
