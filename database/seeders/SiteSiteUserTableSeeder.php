<?php
namespace Database\Seeders;

use Komma\KMS\Users\Models\KmsUserRole;
use Komma\KMS\Users\Models\KmsUser;
use App\Users\Models\SiteUser;
use App\Users\Models\SiteUserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SiteUserTableSeeder extends Seeder
{
    public static function getSiteUserDefaultCredentials()
    {
        return [
            'email' => 'siteuser@komma.nl',
            'password' => '$tilD3H0nger11',
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new SiteUser();
        $user->role = SiteUserRole::Customer;
        $user->username = 'Site User';
        $user->email = self::getSiteUserDefaultCredentials()['email'];
        $user->password = Hash::make(self::getSiteUserDefaultCredentials()['password']);
        $user->first_name = 'Site';
        $user->last_name = 'User';
        $user->culture = 'nl-NL';
        $user->save();
    }
}