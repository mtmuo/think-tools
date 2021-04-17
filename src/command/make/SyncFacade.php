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
use think\console\Output;

class SyncFacade extends Make
{
    protected function configure()
    {
        $this->setName('facade:sync')
            ->setDescription('Sync Service class to Facade class');
    }

    protected function execute(Input $input, Output $output)
    {

        $this->sync("");
    }

    public function sync($path)
    {
        $root = app_path("service/" . $path);
        if (!is_dir($root)) {
            return false;
        }
        $data = scandir($root);
        foreach ($data as $item) {
            if ($item == '.' || $item == '..' || $item == 'facade') {
                continue;
            }
            if (is_file($root . $item)) {
                $this->create((empty($path) ? '' : $path . "/") . $item);

            } else {
                $this->sync($item);
            }
        }
    }

    public function create($file_path)
    {
        $info = pathinfo($file_path);
        $serverClassName = "app\\service\\" . ($info['dirname'] == '.' ? '' : $info['dirname'] . '\\') . $info['filename'];
        if (!class_exists($serverClassName)) {
            return;
        }
        $class = new \ReflectionClass($serverClassName);
        $file = file_get_contents($class->getFileName());

        $annotation = "";
        preg_match_all('/public\s+function\s+([\w\s+]+)([\w($)=,\'\'">"\[\]\s]+)/', $file, $result);
        foreach ($result[1] as $key => $value) {
            $annotation .= "\r\n * @method static ";
            // 计算返回值
            $method = $class->getMethod($value);
            if ($method->hasReturnType()) {
                if ($method->getReturnType()->allowsNull()) {
                    $annotation .= '?';
                }
                if (!$method->getReturnType()->isBuiltin()) {
                    $annotation .= ' \\';
                }
                $annotation .= $method->getReturnType()->getName() . ' ';
            }
            $annotation .= trim($value) . trim($result[2][$key]);
        }
        $stub = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'facade.stub');
        $classFacade = $this->getClassName(($info['dirname'] == '.' ? '' : $info['dirname'] . '/') . $info['filename'], "service/facade", '');
        $s_stub = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'facade.stub';
        $pathname = $this->getPathName($classFacade);
        file_put_contents($pathname, $this->buildClass($classFacade, $s_stub, [
            'serviceClass' => $serverClassName,
            'annotation' => $annotation,
        ]));
        (new Output())->writeln('<info>' . $classFacade . ' created successfully.</info>');
    }
}
