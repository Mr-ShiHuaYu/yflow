<?php

namespace Yflow\core\handler;

/**
 * 办理人权限处理器
 * 用户获取工作流中用到的 permissionFlag 和 handler
 * permissionFlag: 办理人权限标识，比如用户，角色，部门等，用于校验是否有权限办理任务
 * handler: 当前办理人唯一标识，就是确定唯一用的，如用户 id，通常用来入库，记录流程实例创建人，办理人,如create_by,update_by
 *
 */
interface PermissionHandler
{
    /**
     * 办理人权限标识，比如用户，角色，部门等，用于校验是否有权限办理任务
     * 后续在 FlowParams::getPermissionFlag() 中获取
     * 返回当前用户权限集合
     *
     * @return array<string>
     */
    public function permissions(): array;

    /**
     * 获取当前办理人：就是确定唯一用的，如用户 id，通常用来入库，记录流程实例创建人，办理人
     * 后续在 FlowParams::getHandler() 中获取
     *
     * @return string|null
     */
    public function getHandler(): ?string;

    /**
     * 转换办理人，比如设计器中预设了能办理的人，如果其中包含角色或者部门 id 等，可以通过此接口进行转换成用户 id
     *
     * @param array<string> $permissions
     * @return array<string>
     */
    public function convertPermissions(array $permissions): array;
}
