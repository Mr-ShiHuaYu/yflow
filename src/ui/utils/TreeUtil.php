<?php

namespace Yflow\ui\utils;

use Yflow\core\dto\Tree;
use Yflow\core\utils\StringUtils;

class TreeUtil
{
    private function __construct()
    {
    }

    /**
     * 构建所需要树结构
     *
     * @param array<Tree> $trees 部门列表
     * @return array<Tree> 树结构列表
     */
    public static function buildTree(array $trees): array
    {
        $returnList = [];
        $tempList = array_map(function(Tree $tree) {
            return $tree->getId();
        }, $trees);

        foreach ($trees as $dept) {
            // 如果是顶级节点, 遍历该父节点的所有子节点
            if (!in_array($dept->getParentId(), $tempList)) {
                self::recursionFn($trees, $dept);
                $returnList[] = $dept;
            }
        }

        if (empty($returnList)) {
            $returnList = $trees;
        }

        return $returnList;
    }

    /**
     * 递归列表
     *
     * @param array<Tree> $list
     * @param Tree $t
     */
    private static function recursionFn(array $list, Tree $t): void
    {
        // 得到子节点列表
        $childList = self::getChildList($list, $t);
        $t->setChildren($childList);

        foreach ($childList as $tChild) {
            if (self::hasChild($list, $tChild)) {
                self::recursionFn($list, $tChild);
            }
        }
    }

    /**
     * 判断是否有子节点
     *
     * @param array<Tree> $list
     * @param Tree $t
     * @return bool
     */
    private static function hasChild(array $list, Tree $t): bool
    {
        return !empty(self::getChildList($list, $t));
    }

    /**
     * 得到子节点列表
     *
     * @param array<Tree> $list
     * @param Tree $t
     * @return array<Tree>
     */
    private static function getChildList(array $list, Tree $t): array
    {
        $tree_list = [];
        foreach ($list as $n) {
            if (StringUtils::isNotEmpty($n->getParentId()) && $n->getParentId() === $t->getId()) {
                $tree_list[] = $n;
            }
        }
        return $tree_list;
    }
}
