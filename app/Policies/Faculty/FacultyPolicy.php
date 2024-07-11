<?php

namespace App\Policies\Faculty;

use App\Models\Faculty;
use Illuminate\Auth\Access\Response;

class FacultyPolicy
{
    //  Roles
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

    
    public function viewAny(Faculty $faculty): bool
    {   
        $allowed_roles=[1];
        $allowed_role_types = [1]; 
        if(isset($faculty->role_id))
        {

            if(in_array($faculty->role_id,$allowed_roles))
            {
                return true;
            }
            elseif(isset($faculty->role->roletype->id))
            {
                return in_array($faculty->role->roletype->id, $allowed_role_types) ;
            }
        }
        else
        {
            return false;
        }
    }

    public function view(Faculty $faculty): bool
    {
        $allowed_roles=[1];
        $allowed_role_types = [1,3,4]; 
        if(isset($faculty->role_id))
        {

            if(in_array($faculty->role_id,$allowed_roles))
            {
                return true;
            }
            elseif(isset($faculty->role->roletype->id))
            {
                return in_array($faculty->role->roletype->id, $allowed_role_types) ;
            }
        }
        else
        {
            return false;
        }
    }

    public function create(Faculty $faculty): bool
    {
        $allowed_roles=[1];
        $allowed_role_types = [1]; 
        if(isset($faculty->role_id))
        {

            if(in_array($faculty->role_id,$allowed_roles))
            {
                return true;
            }
            elseif(isset($faculty->role->roletype->id))
            {
                return in_array($faculty->role->roletype->id, $allowed_role_types) ;
            }
        }
        else
        {
            return false;
        }
    }

    public function update(Faculty $faculty): bool
    {
        $allowed_roles=[1];
        $allowed_role_types = [1]; 
        if(isset($faculty->role_id))
        {

            if(in_array($faculty->role_id,$allowed_roles))
            {
                return true;
            }
            elseif(isset($faculty->role->roletype->id))
            {
                return in_array($faculty->role->roletype->id, $allowed_role_types) ;
            }
        }
        else
        {
            return false;
        }
    }

    public function delete(Faculty $faculty): bool
    {
        $allowed_roles=[1];
        $allowed_role_types = [1]; 
        if(isset($faculty->role_id))
        {

            if(in_array($faculty->role_id,$allowed_roles))
            {
                return true;
            }
            elseif(isset($faculty->role->roletype->id))
            {
                return in_array($faculty->role->roletype->id, $allowed_role_types) ;
            }
        }
        else
        {
            return false;
        }
    }

    public function restore(Faculty $faculty): bool
    {
        $allowed_roles=[1];
        $allowed_role_types = [1]; 
        if(isset($faculty->role_id))
        {

            if(in_array($faculty->role_id,$allowed_roles))
            {
                return true;
            }
            elseif(isset($faculty->role->roletype->id))
            {
                return in_array($faculty->role->roletype->id, $allowed_role_types) ;
            }
        }
        else
        {
            return false;
        }
    }

    public function changestatus(Faculty $faculty): bool
    {
        $allowed_roles=[1];
        $allowed_role_types = [1]; 
        if(isset($faculty->role_id))
        {

            if(in_array($faculty->role_id,$allowed_roles))
            {
                return true;
            }
            elseif(isset($faculty->role->roletype->id))
            {
                return in_array($faculty->role->roletype->id, $allowed_role_types) ;
            }
        }
        else
        {
            return false;
        }
    }

    public function forceDelete(Faculty $faculty): bool
    {
        $allowed_roles=[1];
        $allowed_role_types = [1]; 
        if(isset($faculty->role_id))
        {

            if(in_array($faculty->role_id,$allowed_roles))
            {
                return true;
            }
            elseif(isset($faculty->role->roletype->id))
            {
                return in_array($faculty->role->roletype->id, $allowed_role_types) ;
            }
        }
        else
        {
            return false;
        }
    }
}
