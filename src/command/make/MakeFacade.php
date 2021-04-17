<?php
// +----------------------------------------------------------------------
// | Facade
// +----------------------------------------------------------------------
// | Date: 2021/04/16 08:36
// +----------------------------------------------------------------------
// | Author: zt <21723614@qq.com>
// +--------------------------------------------------------------------

namespace mtmuo\think\command\make;

use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;


class MakeFacade extends Make
{
    protected function configure()
    {
        $this->setName('facade:make')
            ->addArgument('name', Argument::OPTIONAL, "your facade name")
            ->addOption('city', null, Option::VALUE_REQUIRED, 'city name')
            ->setDescription('Create a new facade class and Service class');
    }

    protected function execute(Input $input, Output $output)
    {
        $name = trim($input->getArgument('name'));
        $classname = $this->getClassName($name, "service", 'Service');
        $s_stub = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'service.stub';
        $pathname = $this->getPathName($classname);
        if (is_file($pathname)) {
            $output->writeln('<error>' . $classname . ' already exists!</error>');
            //return false;
        }
        file_put_contents($pathname, $this->buildClass($classname, $s_stub));
        $output->writeln('<info>' . $classname . ' created successfully.</info>');
        // 创建Facade
        $classFacade = $this->getClassName($name, "service/facade", 'Service');
        $s_stub = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'facade.stub';
        $pathname = $this->getPathName($classFacade);

        file_put_contents($pathname, $this->buildClass($classFacade, $s_stub,[
            'serviceClass' => $classname
        ]));
        $output->writeln('<info>' . $classFacade . ' created successfully.</info>');
    }
}
