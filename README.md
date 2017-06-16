## Megatron 框架

Megatron框架是一个MVC框架，包括了基本的视图，逻辑层，模型层。可以根据自己的需求定制功能。

## Megatron 目录结构

* index.php 项目引导文件
* Megatron/Config 配置目录
* Megatron/Data 系统模板文件
* Megatron/Extends 系统扩展目录
* Megatron/Lib 系统目录

## Megatron 初始化

```index.html
define('APP_NAME', 'Index');//项目名称
define('DEBUG', true);//开启调试模式

require "./Megatron/Megatron.php";//导入框架入口文件
```

