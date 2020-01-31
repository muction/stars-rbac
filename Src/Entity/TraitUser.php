<?php
namespace Stars\Rbac\Entity;

use Illuminate\Support\Facades\Auth;
use Stars\Peace\Entity\AttachmentEntity;

trait TraitUser
{
    /**
     * 检查是否有角色
     * @param $roleName
     * @return
     */
    public static function hasRole( $roleName ){

        return self::join('role_users' , 'role_users.user_id' ,'=' , 'users.id' )
            ->join( 'roles' ,'roles.id','=' , 'role_users.role_id')
            ->where( 'users.id', '=', Auth::id())
            ->where( function( $query ) use ($roleName) {
                if(is_array( $roleName )){
                     $query->whereIn( 'roles.title', $roleName );
                }elseif ( is_string( $roleName ) ){
                    $query->where( 'roles.title', '=', $roleName );
                }
            })
            ->count();
    }

    /**
     * 是可有可执行动作
     * @param $permission
     * @return mixed
     */
    public static function can( $permission ){
        return self::join('role_users' , 'role_users.user_id' ,'=' , 'users.id' )
            ->join( 'roles' ,'roles.id','=' , 'role_users.role_id')
            ->join( 'role_permissions' ,'roles.id','=' , 'role_permissions.role_id')
            ->join( 'permissions' ,'permissions.id','=' , 'role_permissions.permission_id')
            ->where( 'users.id', '=', Auth::id())
            ->where( function( $query ) use ($permission){
                if(is_array( $permission)){
                    $query->whereIn( 'permissions.title', $permission );
                }elseif ( is_string($permission )){
                    $query->where( 'permissions.title', '=', $permission );
                }
            } )
            ->count();
    }

    /**
     * 登录用户信息
     * @param string $key
     * @return mixed
     */
    public static function loginUserInfo( $key ='' ){

        $info= self::info( Auth::id() );
        $info = $info ? $info->toArray() : [];
        if( $key ){
            //头像
            if(in_array( $key , ['portrait'] )){
                return isset( $info['portrait_info']['save_file_path'] ) ? asset('storage/'.$info['portrait_info']['save_file_path'].'/'.$info['portrait_info']['save_file_name'] ): '';
            }
            return isset( $info[$key] ) && $key ? $info[$key] : '';
        }
        return $info;
    }

    /**
     * 包含角色
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(){
        return $this->belongsToMany( RoleEntity::class , 'role_users' ,  'user_id' ,'role_id' );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function portraitInfo(){
        return $this->hasOne( AttachmentEntity::class, 'id', 'portrait' );
    }
}
