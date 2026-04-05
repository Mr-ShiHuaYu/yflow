<?php

namespace Yflow\impl\helper;

use Yflow\core\exception\FlowException;
use Yflow\core\invoker\FrameInvoker;
use Exception;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Spel表达式解析助手
 *
 *
 */
class SpelHelper
{
    /**
     * 表达式语言实例
     */
    private static ?ExpressionLanguage $expressionLanguage = null;

    /**
     * 获取表达式语言实例
     *
     * @return ExpressionLanguage
     */
    private static function getExpressionLanguage(): ExpressionLanguage
    {
        if (self::$expressionLanguage === null) {
            self::$expressionLanguage = new ExpressionLanguage();
        }
        return self::$expressionLanguage;
    }

    /**
     * 解析表达式
     *
     * @param string $expression 表达式
     * @param array<string, mixed> $variable 变量
     * @return mixed 解析结果
     * @throws FlowException
     */
    public static function parseExpression(string $expression, ?array $variable): mixed
    {
        // 确保表达式是有效的
        if (empty($expression)) {
            return false;
        }

        // 处理表达式格式，移除可能的#{}包裹
        $expression = trim($expression, '#{}');

        // 准备变量
        $variables = $variable ?? [];

        // 处理@Bean引用
        $expression = self::processBeanReferences($expression, $variables);

        // 处理#variable格式的变量引用
        $expression = self::processVariableReferences($expression, $variables);

        // 处理${variable}格式的变量引用
        $expression = self::processDollarVariableReferences($expression, $variables);

        try {
            // 解析表达式
            return self::getExpressionLanguage()->evaluate($expression, $variables);
        } catch (Exception) {
            // 尝试直接处理方法调用表达式
            if (str_contains($expression, '(') && str_contains($expression, ')')) {
                // 尝试手动解析方法调用
                return self::handleMethodCall($expression, $variables);
            }
            // 如果表达式解析失败，尝试直接返回变量值
            if (isset($variables[$expression])) {
                return $variables[$expression];
            }
            // 不尝试使用eval函数，直接返回false
            return false;
        }
    }

    /**
     * 处理方法调用表达式
     *
     * @param string $expression 表达式
     * @param array $variables 变量
     * @return mixed 处理结果
     */
    private static function handleMethodCall(string $expression, array $variables): mixed
    {
        // 尝试解析方法调用表达式
        // 例如：user.eval(flag) -> 调用$variables['user']->eval($variables['flag'])
        $pattern = '/(\w+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\(([^)]*)\)/';

        if (preg_match($pattern, $expression, $matches)) {
            $objectName = $matches[1];
            $methodName = $matches[2];
            $args = $matches[3];

            // 检查对象是否存在
            if (isset($variables[$objectName]) && is_object($variables[$objectName])) {
                $object = $variables[$objectName];

                // 检查方法是否存在
                if (method_exists($object, $methodName)) {
                    // 解析参数
                    $parsedArgs = [];
                    $argList = explode(',', $args);
                    foreach ($argList as $arg) {
                        $arg = trim($arg);
                        // 检查参数是否是变量
                        if (isset($variables[$arg])) {
                            $parsedArgs[] = $variables[$arg];
                        } else {
                            // 尝试将参数转换为合适的类型
                            if (is_numeric($arg)) {
                                $parsedArgs[] = (float)$arg;
                            } elseif (strtolower($arg) === 'true') {
                                $parsedArgs[] = true;
                            } elseif (strtolower($arg) === 'false') {
                                $parsedArgs[] = false;
                            } else {
                                $parsedArgs[] = $arg;
                            }
                        }
                    }

                    // 调用方法
                    return call_user_func_array([$object, $methodName], $parsedArgs);
                }
            }
        }

        return false;
    }

    /**
     * 处理${variable}格式的变量引用
     *
     * @param string $expression 表达式
     * @param array $variables 变量
     * @return string 处理后的表达式
     */
    private static function processDollarVariableReferences(string $expression, array $variables): string
    {
        // 匹配${variable}格式的变量引用
        $pattern = '/\$\{([^}]+)}/';

        return preg_replace_callback($pattern, function ($matches) use ($variables) {
            $varName = trim($matches[1]);
            // 检查变量是否存在
            if (isset($variables[$varName])) {
                // 如果是简单变量引用，直接返回变量值
                if (preg_match('/^\w+$/', $varName)) {
                    return $variables[$varName];
                }
            }
            return $matches[0];
        }, $expression);
    }

    /**
     * 处理变量引用，将#variable转换为variable
     *
     * @param string $expression 表达式
     * @param array $variables 变量
     * @return string 处理后的表达式
     */
    private static function processVariableReferences(string $expression, array $variables): string
    {
        // 匹配#variable格式的变量引用
        $pattern = '/#(\w+)/';

        return preg_replace_callback($pattern, function ($matches) use ($variables) {
            $varName = $matches[1];
            // 检查变量是否存在
            if (isset($variables[$varName])) {
                // 将#variable替换为variable
                return $varName;
            }
            return $matches[0];
        }, $expression);
    }

    /**
     * 处理Bean引用
     *
     * @param string $expression 表达式
     * @param array $variables 变量
     * @return string 处理后的表达式
     * @throws FlowException
     */
    private static function processBeanReferences(string $expression, array &$variables): string
    {
        // 匹配@Bean引用，如@user
        $pattern = '/@(\w+)/';

        return preg_replace_callback($pattern, function ($matches) use (&$variables) {
            $beanName = $matches[1];

            // 检查变量中是否已存在该Bean
            if (!isset($variables[$beanName])) {
                // 尝试实例化对应的类
                $bean = FrameInvoker::getBean($beanName);
                if ($bean) {
                    $variables[$beanName] = $bean;
                } else {
                    throw new FlowException("Bean $beanName 不存在");
                }
            }

            // 将@user替换为user，以便ExpressionLanguage能够访问
            return $beanName;
        }, $expression);
    }

    /**
     * 处理${}包裹的表达式
     *
     * @param string $expression 表达式
     * @param array $variables 变量
     * @return bool 处理结果
     */
    public static function processDollarExpression(string $expression, array $variables): bool
    {
        // 移除${}包裹
        $expression = trim($expression, '${}');

        // 处理变量引用，将#variable转换为variable
        $expression = self::processVariableReferences($expression, $variables);

        try {
            // 使用ExpressionLanguage来评估表达式
            $result = self::getExpressionLanguage()->evaluate($expression, $variables);
            return (bool)$result;
        } catch (Exception) {
            return false;
        }
    }
}
