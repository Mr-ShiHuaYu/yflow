<?php

namespace Yflow\core\strategy;

use Yflow\core\exception\FlowException;
use Yflow\core\utils\ObjectUtil;

/**
 * HandlerStrategy 默认方法实现 trait
 * 用于处理 Java 接口中的默认方法
 *
 *
 */
trait HandlerStrategyDefaultTrait
{
    /**
     * 当选择截取，并且希望拼接上某些字符串，在进行截取
     *
     * @return string
     */
    public function interceptStr(): string
    {
        return "";
    }

    /**
     * 执行表达式
     *
     * @param string $expression
     * @param array|null $variable
     * @return array|null
     * @throws FlowException
     * @throws FlowException
     */
    public function eval(string $expression, ?array $variable): ?array
    {
        return $this->afterEval($this->preEval($expression, $variable));
    }

    /**
     * 表达式计算后处理
     *
     * @param mixed $o
     * @return array<string>|null
     */
    public function afterEval(mixed $o): ?array
    {
        if (ObjectUtil::isNull($o)) {
            return null;
        }

        if (is_array($o)) {
            // PHP 数组（对应 Java 的 List）
            return array_map(fn($item) => (string)$item, $o);
        }

        if (is_object($o) && get_class($o) === 'stdClass') {
            // stdClass 对象，转换为数组
            return array_map(fn($item) => (string)$item, (array)$o);
        }

        // 其他类型转为单元素数组（对应 Java 的 Collections.singletonList）
        return [(string)$o];
    }
}
