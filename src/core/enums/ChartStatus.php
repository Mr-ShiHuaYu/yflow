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

namespace Yflow\core\enums;

use Yflow\core\utils\CollUtil;
use Yflow\core\utils\ObjectUtil;
use Yflow\core\utils\StringUtils;


/**
 * 流程图状态
 *
 *
 * @since 2023/3/31 12:16
 */
class ChartStatus
{
    /**
     * 未办理
     */
    public const NOT_DONE = 0;

    /**
     * 待办理
     */
    public const TO_DO = 1;

    /**
     * 已办理
     */
    public const DONE = 2;

    /**
     * 默认颜色配置
     */
    private const DEFAULT_COLORS = [
        self::NOT_DONE => [166, 178, 189],
        self::TO_DO    => [255, 197, 90],
        self::DONE     => [135, 206, 250],
    ];

    /**
     * 自定义颜色配置
     */
    private static ?array $customColor         = null;
    private static ?array $customColorClassics = null;
    private static ?array $customColorMimic    = null;

    /**
     * 初始化自定义颜色
     */
    public static function initCustomColor(
        ?array $chartStatusColor,
        ?array $chartStatusColorClassics,
        ?array $chartStatusColorMimic
    ): void
    {
        self::initCustomColorsArray(self::$customColor, $chartStatusColor);
        self::initCustomColorsArray(self::$customColorClassics, $chartStatusColorClassics);
        self::initCustomColorsArray(self::$customColorMimic, $chartStatusColorMimic);
    }

    /**
     * 初始化自定义颜色数组
     */
    private static function initCustomColorsArray(?array &$target, ?array $source): void
    {
        if ($target === null) {
            $target = [];
        }

        if ($source !== null && CollUtil::isNotEmpty($source) && count($source) === 3) {
            foreach ($source as $i => $statusColor) {
                if (StringUtils::isNotEmpty($statusColor)) {
                    $colorArr = explode(',', $statusColor);
                    if (count($colorArr) === 3) {
                        $target[$i] = [
                            (int)$colorArr[0],
                            (int)$colorArr[1],
                            (int)$colorArr[2],
                        ];
                    }
                }
            }
        }
    }

    /**
     * 确保静态属性已初始化
     */
    private static function ensureInitialized(): void
    {
        if (self::$customColor === null) {
            self::$customColor = [];
        }
        if (self::$customColorClassics === null) {
            self::$customColorClassics = [];
        }
        if (self::$customColorMimic === null) {
            self::$customColorMimic = [];
        }
    }

    /**
     * 获取未办理状态的颜色
     */
    public static function getNotDone(?string $modelValue): array
    {
        return self::getColorByKey(self::NOT_DONE, $modelValue);
    }

    /**
     * 获取待办理状态的颜色
     */
    public static function getToDo(?string $modelValue): array
    {
        return self::getColorByKey(self::TO_DO, $modelValue);
    }

    /**
     * 获取已办理状态的颜色
     */
    public static function getDone(?string $modelValue): array
    {
        return self::getColorByKey(self::DONE, $modelValue);
    }

    /**
     * 根据 key 获取颜色
     *
     * @param int $key 状态 key
     * @param string|null $modelValue 模式值
     * @return array RGB 颜色数组
     */
    public static function getColorByKey(int $key, ?string $modelValue): array
    {
        self::ensureInitialized();

        $color = null;

        if ($modelValue === ModelEnum::CLASSICS) {
            $color = self::$customColorClassics[$key] ?? null;
        } elseif ($modelValue === ModelEnum::MIMIC) {
            $color = self::$customColorMimic[$key] ?? null;
        }

        if (ObjectUtil::isNull($color)) {
            $color = self::$customColor[$key] ?? null;
        }

        return ObjectUtil::defaultIfNull($color, fn() => self::DEFAULT_COLORS[$key] ?? [166, 178, 189]);
    }

    /**
     * 根据 key 获取颜色（简化版）
     *
     * @param int $key 状态 key
     * @return array|null RGB 颜色数组
     */
    public static function getColorByKeySimple(int $key): ?array
    {
        self::ensureInitialized();

        $color = self::$customColor[$key] ?? null;
        return ObjectUtil::defaultIfNull($color, fn() => self::DEFAULT_COLORS[$key] ?? null);
    }

    /**
     * 判断是否未办理
     */
    public static function isNotDone(?int $key): bool
    {
        return ObjectUtil::isNotNull($key) && $key === self::NOT_DONE;
    }

    /**
     * 判断是否待办理
     */
    public static function isToDo(?int $key): bool
    {
        return ObjectUtil::isNotNull($key) && $key === self::TO_DO;
    }

    /**
     * 判断是否已办理
     */
    public static function isDone(?int $key): bool
    {
        return ObjectUtil::isNotNull($key) && $key === self::DONE;
    }

    /**
     * 获取所有状态值
     */
    public static function values(): array
    {
        return [
            self::NOT_DONE,
            self::TO_DO,
            self::DONE,
        ];
    }

    /**
     * 根据 key 获取描述
     */
    public static function getValueByKey(int $key): ?string
    {
        $map = [
            self::NOT_DONE => '未办理',
            self::TO_DO    => '待办理',
            self::DONE     => '已办理',
        ];
        return $map[$key] ?? null;
    }

    /**
     * 根据 key 获取枚举数组
     */
    public static function getByKey(int $key): ?array
    {
        $map = [
            self::NOT_DONE => ['key' => self::NOT_DONE, 'value' => '未办理'],
            self::TO_DO    => ['key' => self::TO_DO, 'value' => '待办理'],
            self::DONE     => ['key' => self::DONE, 'value' => '已办理'],
        ];
        return $map[$key] ?? null;
    }
}
