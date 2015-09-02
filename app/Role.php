<?php

namespace App;


class Role
{
    //
    static   $roles   =  ['admin'=>'管理员', 'agent'=>'代理商', 'operator'=>'审核员'];
    static   $grants = [ ];
    static   $roles_grants = [
      'admin'     => ['/admin/ideas'=>'广告审核', '/admin/users'=>'客户管理', '/admin/administrators'=>'用户管理', '/admin/recharge/create'=>'充值', '/admin/recharge/store'=>'充值保存', '/admin/medias'=>'媒体管理'],
      'agent'     => ['/admin/users'=>'客户管理', '/admin/recharge/create'=>'充值', '/admin/recharge/store'=>'充值保存'],
      'operator'  => ['/admin/ideas'=>'广告审核'],
    ];
    static function grants($role) {
         return  array_keys(self ::  $roles_grants[$role]);
    }
}
