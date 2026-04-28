<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project\ProjectContract;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectContractPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }
    }

    public function update(User $user, ProjectContract $contract)
    {
        // 1. ถ้าเป็น Staff ที่มีสิทธิ์คุม "บริการ/วิทยากร" (Kusuma) ให้ผ่าน
        if ($user->hasAnyPermission(['manage-service', 'manage-speaker'])) {
            return true;
        }

        // 2. ถ้าเป็น User ทั่วไป เช็คผ่านตารางแม่
        return $user->id === $contract->academicProject->created_by;
    }

    public function delete(User $user, ProjectContract $contract)
    {
        return $this->update($user, $contract);
    }
}