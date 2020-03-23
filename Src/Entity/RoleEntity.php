<?php
namespace Stars\Rbac\Entity;

use Stars\Peace\Foundation\EntityEntity;

class RoleEntity extends EntityEntity
{
    protected $table = "roles";

    protected $fillable = ['title' ,'display_name' ,'description' ,'status'];

    protected $with = ['permissions' ,'menus'];

    use TraitRole ;

    /**
     * 获取所有的角色
     * @return mixed
     */
    public static function index(){
        return self::orderBy('id', 'DESC')->get();
    }

    /**
     * @param array $storage
     * @return mixed
     */
    public static function storage( array $storage){

        return self::create($storage );
    }

    /**
     * @param array $storage
     * @param $infoId
     * @return mixed
     */
    public static function edit( array $storage, $infoId ){
        $info = self::find( $infoId );
        if(!$info){
            return false;
        }
        return $info->update( $storage );
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public static function info( $infoId ){
        return self::with('permissions')->find( $infoId );
    }

    /**
     * @param $infoId
     * @return bool
     */
    public static function remove( $infoId ){
        $info = self::find( $infoId );
        if(!$info){
            return false;
        }
        return $info->delete();
    }


    /**
     * @param int $roleId
     * @param array $permissionIds
     * @return bool|mixed
     */
    public static function bindPermission(int $roleId, array $permissionIds , $menus ){
        $role = self::info ( $roleId );
        if(!$role){
            return false;
        }
        //清除原配置角色
        $role->permissions()->detach();

        if($permissionIds){
            $role->permissions()->attach( $permissionIds , self::now() );
        }

        //清除原配置菜单
        $role->menus()->detach();

        if( $menus && is_array($menus) ){
            $role->menus()->attach( $menus , self::now() );
        }
        return $role;
    }
}
