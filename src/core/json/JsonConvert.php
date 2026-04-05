<?php

namespace Yflow\core\json;


/**
 * map 和 json 字符串转换工具接口
 *
 *
 */
interface JsonConvert
{
    /**
     * 将字符串转为 map
     *
     * @param string $jsonStr json 字符串
     * @return array<string, mixed>
     */
    public function strToMap(string $jsonStr): array;

    /**
     * 将字符串转为 bean
     *
     * @template T
     * @param string $jsonStr json 字符串
     * @param string $clazz 类名
     * @return object|null
     */
    public function strToBean(string $jsonStr, string $clazz): ?object;

    /**
     * 将字符串转为集合
     * TODO 未测试
     *
     * @template T
     * @param string $jsonStr json 字符串
     * @return array|null
     */
    public function strToList(string $jsonStr): ?array;

    /**
     * 将对象转为字符串
     *
     * @param mixed $variable 对象
     * @return string|null json 字符串
     */
    public function objToStr(mixed $variable): ?string;
}
