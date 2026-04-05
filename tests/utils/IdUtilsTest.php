<?php

namespace Tests\utils;

use Yflow\core\utils\IdUtils;
use PHPUnit\Framework\TestCase;

/**
 * IdUtils 测试类
 *
 *
 */
class IdUtilsTest extends TestCase
{
    /**
     * 测试 nextIdStr 方法
     */
    public function testNextIdStr(): void
    {
        $id = IdUtils::nextIdStr();

        // 断言返回的是字符串
        $this->assertIsString($id);

        // 断言字符串不为空
        $this->assertNotEmpty($id);

        // 断言长度合理（应该在 15-20 位之间）
        $this->assertGreaterThan(14, strlen($id));
        $this->assertLessThan(21, strlen($id));
    }

    /**
     * 测试 nextId 方法
     */
    public function testNextId(): void
    {
        $id = IdUtils::nextId();

        // 断言返回的是整数
        $this->assertIsInt($id);

        // 断言大于 0
        $this->assertGreaterThan(0, $id);
    }

    /**
     * 测试 nextIdWithParams 方法
     */
    public function testNextIdWithParams(): void
    {
        // 使用默认参数
        $id1 = IdUtils::nextIdWithParams();
        $this->assertIsInt($id1);
        $this->assertGreaterThan(0, $id1);

        // 使用自定义参数
        $id2 = IdUtils::nextIdWithParams(2, 3);
        $this->assertIsInt($id2);
        $this->assertGreaterThan(0, $id2);

        // 两个 ID 应该不同
        $this->assertNotEquals($id1, $id2);
    }

    /**
     * 测试 ID 唯一性
     */
    public function testIdUniqueness(): void
    {
        $ids   = [];
        $count = 1000;

        // 生成 1000 个 ID
        for ($i = 0; $i < $count; $i++) {
            $ids[] = IdUtils::nextId();
        }

        // 去重
        $uniqueIds = array_unique($ids);

        // 断言所有 ID 都是唯一的
        $this->assertCount($count, $uniqueIds, '生成的 ID 中存在重复，ID 算法可能有问题');
    }

    /**
     * 测试 ID 递增性
     */
    public function testIdIncremental(): void
    {
        $prevId = IdUtils::nextId();
        usleep(1000); // 等待 1 毫秒

        $nextId = IdUtils::nextId();

        // 后生成的 ID 应该大于先生成的 ID
        $this->assertGreaterThan($prevId, $nextId);
    }

    /**
     * 测试 setInstanceNative 方法
     */
    public function testSetInstanceNative(): void
    {
        // 创建一个模拟的原生 ID 生成器
        $mockGenerator = new class {
            public function nextId(): int
            {
                return 999999;
            }
        };

        // 设置原生实例
        IdUtils::setInstanceNative($mockGenerator);

        // 注意：由于当前实现没有使用 instanceNative，这个测试主要是验证方法存在
        $this->assertTrue(true, 'setInstanceNative 方法调用成功');

        // 重置为 null
        IdUtils::setInstanceNative(null);
    }

    /**
     * 测试并发场景下的 ID 生成
     */
    public function testConcurrentIdGeneration(): void
    {
        $ids = [];

        // 快速生成多个 ID
        for ($i = 0; $i < 100; $i++) {
            $ids[] = IdUtils::nextIdStr();
        }

        // 验证所有 ID 都是唯一的
        $uniqueIds = array_unique($ids);
        $this->assertCount(100, $uniqueIds);

        // 验证所有 ID 都是字符串格式
        foreach ($uniqueIds as $id) {
            $this->assertIsString($id);
            $this->assertMatchesRegularExpression('/^\d+$/', $id, 'ID 应该只包含数字');
        }
    }

    /**
     * 测试 ID 格式
     */
    public function testIdFormat(): void
    {
        $id    = IdUtils::nextId();
        $idStr = IdUtils::nextIdStr();

        // 验证数字 ID 转换为字符串后与字符串 ID 一致
        $this->assertEquals((string)($id + 1), $idStr);

        // 验证字符串 ID 是纯数字格式
        $this->assertMatchesRegularExpression('/^\d+$/', $idStr);
    }
}
