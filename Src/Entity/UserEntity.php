<?php
namespace Stars\Rbac\Entity;

use Stars\Peace\Entity\AttachmentEntity;
use Stars\Peace\Foundation\EntityEntity;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserEntity extends EntityEntity
{

    protected $table = 'users';

    protected $fillable = [ 'username' ,'password' ,'email' ,'phone' ,'portrait' ,'branch' , 'status' ,'last_login_time'];

    protected $with = ['roles'];

    use TraitUser;

    /**
     * @param array $storage
     * @param array $roles
     * @return
     */
    public static function storage(array $storage , array $roles){

        $user= self::create( $storage );

        if($roles){
            $user->roles()->attach($roles , self::now() );
        }

        return $user;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public static function info( $userId ){

        return self::with(['portraitInfo'])->find($userId );
    }

    /**
     * @param array $where
     * @return mixed
     */
    public static function detail(array $where ){
        return self::where( $where )->first();
    }

    /**
     * @param $userId
     * @return bool
     */
    public static function remove( $userId ){
        $user = self::info( $userId );
        if(!$user){
            return false;
        }
        $user->roles()->detach();
        return $user->delete();
    }

    /**
     * @param $storage
     * @param $userId
     * @param array $roles
     * @param bool $isEditProfile
     * @return bool
     */
    public static function edit($storage , $userId , array $roles , $isEditProfile=false ){

        $user = self::info( $userId );
        if(!$user){
            return false;
        }

        $user->update( $storage);

        if($isEditProfile){
            return $user;
        }


        $user->roles()->detach();
        if($roles){
            $user->roles()->attach( $roles , self::now() );
        }

        return $user;
    }

    /**
     * @param int $size
     * @return mixed
     */
    public static function paginatePage( $size= 15){
        return self::orderByDesc('id')->paginate( $size ) ;
    }


}