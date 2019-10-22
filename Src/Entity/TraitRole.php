<?php


namespace Stars\Rbac\Entity;


trait TraitRole
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(){

        return $this->belongsToMany( PermissionEntity::class  ,'role_permissions' ,'role_id' ,'permission_id' );
    }
}