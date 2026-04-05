<?php

namespace Yflow\core\annotation;

use Attribute;

/**
 * Bean 注解
 * 用于标记需要自动注入到容器的类
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Bean
{
    public function __construct(
        public ?string $name = null,
        public bool    $singleton = true
    )
    {
    }
}

