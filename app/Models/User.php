<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //สำหรับโปรเจคที่กลุ่มผู้ใช้เป็นคนใน PSU
        'name',
        'username',
        'email',
        'email_verified_at',
        'objectguid',
        'password',
        'distinguishedname',
        'personid',
        'citizenid',
        'company',
        'department',
        'physicaldeliveryofficename',
        'description',
        'displayname',
        'title',
        'personaltitle',
        'givenname',
        'givensname',
        'userprincipalname',
        'remember_token',
        

        // สำหรับโปรเจคที่กลุ่มผู้ใช้เป็นคนใน PSU และคนนอก (ตัวอย่างนี้คือ น.ศ. ที่ทำการดึงข้อมูลมากจากมหาลัย กับ บุคลากร )
        // 'student_id',
        // 'student_name',
        // 'title_id',
        // 'degree_id',
        // 'major_id',
        // 'division_id',
        // 'acadmic_title_id',
        // 'country_id',
        // 'advisor',
        // 'email',
        // 'username',
        // 'password',
        // 'type',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
