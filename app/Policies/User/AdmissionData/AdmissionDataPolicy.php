<?php

namespace App\Policies\User\AdmissionData;

use App\Models\Role;
use App\Models\User;
use App\Models\Admissiondata;
use Illuminate\Auth\Access\Response;

class AdmissionDataPolicy
{
    //  Rolls
    //  
    //  1	Super Admin
    //  2	Admin
    //  3	Principal
    //  4	CEO
    //  5	Head
    //  6	Teaching
    //  7	Co Ordinator
    //  8	Supervisor
    //  9	Assistant To Supervisor
    //  10	Assistant CAP Director
    //  11	Admin Clerk
    //  12	Cap Clerk
    //  13	Clerk
    //  14	Admission Clerk
    //  15	Non Teaching
    //  Role Types
    //  1 Super Admin
    //  2 Teaching
    //  3 Non Teaching
    //  4 Management Member
    //  5 Other

    public function access(User $user): bool
    {
        $allowedRoleIds = [1, 3];

        return in_array($user->role_id, $allowedRoleIds);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Admissiondata $admissiondata): bool
    {
        $allowedRoleIds = [1, 3];

        return in_array($user->role_id, $allowedRoleIds);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {   
        $allowedRoleIds = [1, 3];

        return in_array($user->role_id, $allowedRoleIds);
    }

    /**
     * Determine whether the user can edit the model.
     */
    public function edit(User $user, Admissiondata $admissiondata): bool
    {   

        $allowedRoleIds = [1, 3];

        return in_array($user->role_id, $allowedRoleIds);

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Admissiondata $admissiondata): bool
    {
        $allowedRoleIds = [1, 3];

        return in_array($user->role_id, $allowedRoleIds);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Admissiondata $admissiondata): bool
    {
        $allowedRoleIds = [1, 3];

        return in_array($user->role_id, $allowedRoleIds);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Admissiondata $admissiondata): bool
    {
        $allowedRoleIds = [1, 3];

        return in_array($user->role_id, $allowedRoleIds);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forcedelete(User $user, Admissiondata $admissiondata): bool
    {
        $allowedRoleIds = [1, 3];

        return in_array($user->role_id, $allowedRoleIds);
    }
}
