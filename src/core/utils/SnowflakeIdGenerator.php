<?php

namespace Yflow\core\utils;


use InvalidArgumentException;
use RuntimeException;

/**
 * 简化的雪花 ID 生成器（内部类）
 */
class SnowflakeIdGenerator
{
    private const EPOCH               = 1288834974657; // 起始时间戳
    private const SEQUENCE_BITS       = 12;
    private const WORKER_ID_BITS      = 5;
    private const DATA_CENTER_ID_BITS = 5;

    private const MAX_WORKER_ID      = -1 ^ (-1 << self::WORKER_ID_BITS);
    private const MAX_DATA_CENTER_ID = -1 ^ (-1 << self::DATA_CENTER_ID_BITS);
    private const SEQUENCE_MASK      = -1 ^ (-1 << self::SEQUENCE_BITS);

    private const WORKER_ID_SHIFT      = self::SEQUENCE_BITS;
    private const DATA_CENTER_ID_SHIFT = self::SEQUENCE_BITS + self::WORKER_ID_BITS;
    private const TIMESTAMP_LEFT_SHIFT = self::SEQUENCE_BITS + self::WORKER_ID_BITS + self::DATA_CENTER_ID_BITS;

    private int $workerId;
    private int $dataCenterId;
    private int $sequence      = 0;
    private int $lastTimestamp = -1;

    public function __construct(int $workerId, int $dataCenterId)
    {
        if ($workerId > self::MAX_WORKER_ID || $workerId < 0) {
            throw new InvalidArgumentException("Worker ID can't be greater than " . self::MAX_WORKER_ID . ' or less than 0');
        }
        if ($dataCenterId > self::MAX_DATA_CENTER_ID || $dataCenterId < 0) {
            throw new InvalidArgumentException("Data center ID can't be greater than " . self::MAX_DATA_CENTER_ID . ' or less than 0');
        }

        $this->workerId     = $workerId;
        $this->dataCenterId = $dataCenterId;
    }

    public function nextId(): int
    {
        $timestamp = $this->timeGen();

        if ($timestamp < $this->lastTimestamp) {
            $diff = $this->lastTimestamp - $timestamp;
            throw new RuntimeException("Clock moved backwards! Refusing to generate id for $diff milliseconds");
        }

        if ($this->lastTimestamp === $timestamp) {
            $this->sequence = ($this->sequence + 1) & self::SEQUENCE_MASK;
            if ($this->sequence === 0) {
                $timestamp = $this->tilNextMillis($this->lastTimestamp);
            }
        } else {
            $this->sequence = 0;
        }

        $this->lastTimestamp = $timestamp;

        return (($timestamp - self::EPOCH) << self::TIMESTAMP_LEFT_SHIFT) |
            ($this->dataCenterId << self::DATA_CENTER_ID_SHIFT) |
            ($this->workerId << self::WORKER_ID_SHIFT) |
            $this->sequence;
    }

    private function tilNextMillis(int $lastTimestamp): int
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }

    private function timeGen(): int
    {
        return floor(microtime(true) * 1000);
    }
}
