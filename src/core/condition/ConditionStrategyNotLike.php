<?php

/*
 *    Copyright 2026, Y-Flow (974988176@qq.com).
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *       https://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace Yflow\core\condition;

use Yflow\core\enums\ConditionType;

/**
 * ConditionStrategyNotLike - 条件表达式不包含 notLike@@flag|4
 * 条件表达式不包含 notLike@@flag|4 and eq@@flag|5 or lt@@flag|6
 *
 *
 */
class ConditionStrategyNotLike extends AbstractConditionStrategy
{

    /**
     * 获取类型
     *
     * @return string
     */
    public function getType(): string
    {
        return ConditionType::NOT_LIKE->value;
    }

    /**
     * 执行表达式后置方法
     *
     * @param string $value 表达式最后一个参数，比如：eq@@flag|5 的 [5]
     * @param string $variableValue 流程变量值
     * @return bool|null 执行结果
     */
    public function afterEval(string $value, string $variableValue): ?bool
    {
        return !str_contains($variableValue, $value);
    }
}
