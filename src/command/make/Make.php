<?php
// +----------------------------------------------------------------------
// | Facade
// +----------------------------------------------------------------------
// | Date: 2021/04/16 08:36
// +----------------------------------------------------------------------
// | Author: zt <21723614@qq.com>
// +--------------------------------------------------------------------

namespace mtmuo\think\command\make;

use think\console\Command;

class Make extends Command
{
    protected function getPathName(string $name): ?string
    {
        $name = str_replace('app\\', '', $name);

        $pathname = $this->app->getBasePath() . ltrim(str_replace('\\', '/', $name), '/') . '.php';

        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }

        return $pathname;
    }

    protected function buildClass(string $className, string $stub_path, array $config = [])
    {
        $config = array_merge([
            'className' => '',
            'namespace' => '',
            'app_namespace' => '',
            'serviceClass' => '',
            'date' => date("Y-d-m H:i:s"),
            'annotation' => "",
        ], $config);
        $stub = file_get_contents($stub_path);

        $config['namespace'] = trim(implode('\\', array_slice(explode('\\', $className), 0, -1)), '\\');

        $config['className'] = str_replace($config['namespace'] . '\\', '', $className);

        return str_replace(
            ['{%className%}', '{%namespace%}', '{%app_namespace%}', '{%date%}', '{%annotation%}', '{%serviceClass%}'],
            [$config['className'], $config['namespace'], $config['app_namespace'], $config['date'], $config['annotation'],$config['serviceClass']],
            $stub);
    }

    protected function getClassName($name, $prefix = "", $suffix = ""): string
    {
        $name = $prefix . '/' . $name . $suffix;
        if (strpos($name, '\\') !== false) {
            return $name;
        }

        if (strpos($name, '@')) {
            [$app, $name] = explode('@', $name);
        } else {
            $app = '';
        }

        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->getNamespace($app) . '\\' . $name;
    }

    protected function getNamespace(string $app): string
    {
        return 'app' . ($app ? '\\' . $app : '');
    }
}
