<?php
namespace Stars\Rbac\Entity;

use Stars\Peace\Foundation\EntityEntity;

class RoleEntity extends EntityEntity
{
    protected $table = "roles";

    protected $fillable = ['title' ,'display_name' ,'description' ,'status'];

    protected $with = ['permissions'];

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
    public static function bindPermission(int $roleId, array $permissionIds){
        $role = self::info ( $roleId );
        if(!$role){
            return false;
        }
        $role->permissions()->detach();

        if($permissionIds){
            $role->permissions()->attach( $permissionIds , self::now() );
        }
        return $role;
    }
}