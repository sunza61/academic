<?php
/*--------------------------------------------------------------
PSU Passport for laravel By Thida Engneering Expert..
How to use
use App\Classes\PSUPassport;
$user = PSUPassport::Auth('user','password'); //Return user property
----------------------------------------------------------------*/
namespace App\Classes;
use Illuminate\Support\Collection;
class PSUPassport
{
    public static function Auth($username, $password)
    {
        $attribute = [
            "cn", "dn", "samaccountname", "employeeid", "citizenid", "company",
            "campusid", "department", "departmentid", "physicaldeliveryofficename", "positionid",
            "description", "displayname", "title", "personaltitle", "personaltitleid", "givenname",
            "sn", "sex", "userprincipalname", "mail"
        ];
        $basedn = "dc=psu,dc=ac,dc=th";
        $conn = ldap_connect("ldaps://dc2.psu.ac.th:636");
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $bind = @ldap_bind($conn, $username . "@psu.ac.th", $password);
        if ($bind) {
            $sr = ldap_search(
                $conn,
                $basedn,
                "(&(objectClass=user)(objectCategory=person)(sAMAccountName=" . $username . "))",
                $attribute
            );
            $info = ldap_get_entries($conn, $sr)[0];
            $data = collect($attribute)->mapWithKeys(function ($key) use($info)  {
                return [$key => $info[$key][0]];
            });
            return $data;
        } else {
            return false;
        }
    }
}
