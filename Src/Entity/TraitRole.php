<?php


namespace Stars\Rbac\Entity;


use Stars\Peace\Entity\NavMenuEntity;

trait TraitRole
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(){

        return $this->belongsToMany( PermissionEntity::class  ,'role_permissions' ,'role_id' ,'permission_id' );
    }

    /**
     * @return mixed
     */
    public function menus(){

        return $this->belongsToMany( NavMenuEntity::class , 'role_menus' , 'role_id' ,'menu_id' );
    }
}
