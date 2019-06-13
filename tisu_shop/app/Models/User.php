<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends \Ml\Models\User implements JWTSubject
{
    //
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'sex', 'login_at', 'login_ip', 'username', 'bool_admin', 'avatar', 'phone', 'open_id', 'user_type','qq_code','wx_code'
    ];

    public function isSuperAdmin()
    {

        return $this->bool_admin == 1;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // 模型关联

    /**
     *  卖家:店铺 = 1:n
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shops()
    {
        return $this->hasMany(Shop::class, 'user_id', 'id');
    }

    /**管理员：店铺=1:n
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopManage(){
        return $this->hasMany(ShopManager::class,'user_id','id');
    }
   public function order(){
       return $this->hasMany(Order::class,'user_id','id');
   }
    /**
     * 买家-微信用户信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buyer()
    {
        return $this->hasOne(Buyer::class, 'open_id', 'open_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function defaultAddress()
    {
        return $this->hasOne(BuyerAddress::class, 'user_id', 'id')->where('is_default', '=', 1);
    }

    ## 用户类型
    const USER_TYPE_ADMIN = 'admin';
    const USER_TYPE_SELLER = 'seller';
    const USER_TYPE_BUYER = 'buyer';
    public static $userTypeMap = [
        self::USER_TYPE_ADMIN => '后台用户',
        self::USER_TYPE_SELLER => '卖家(红人)',
        self::USER_TYPE_BUYER => '买家(商城用户)',
    ];

    ### 状态
    const STATUS_Y = 1;
    const STATUS_N = 0;
    public static $statusMap = [
        self::STATUS_Y => '启用',
        self::STATUS_N => '停用',
    ];
}
