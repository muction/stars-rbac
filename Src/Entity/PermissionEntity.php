<?php
namespace Stars\Rbac\Entity;

use Stars\Peace\Foundation\EntityEntity;

class PermissionEntity extends EntityEntity
{
    protected $table = 'permissions';
    protected $fillable = ['title' ,'display_name' ,'description' ,'status' ,'type'];

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
        return self::with('typeInfo')->find( $infoId );
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
     * @param int $size
     * @return mixed
     */
    public static function paginatePage( $size= 15){
        return self::orderByDesc('id')->paginate( $size ) ;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function typeInfo(){
        return $this->hasOne( PermissionTypeEntity::class,'id' , 'type' );
    }

}