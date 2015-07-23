<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Role;
class Admin extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'administrators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'administrator_id', 'status','role'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    /**
     * appends 
     * 
     * @var string
     * @access protected
     */
    protected $appends = ['role_name'];
    /**
     * getRoleNameAttribute 
     * 
     * @access public
     * @return mixed
     */
    function  getRoleNameAttribute()
    {
        return isset(Role :: $roles[$this->attributes['role']]) ?  Role :: $roles[$this->attributes['role']] : '';  
    }
    /**
     * hasGrant 
     * 
     * @param mixed $grant 
     * @access public
     * @return mixed
     */
    function hasGrant($grant)
    {
        $grants = Role ::grants($this->attributes['role']);
        return in_array($grant, $grants);
    }
}
