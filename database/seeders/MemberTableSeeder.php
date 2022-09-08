<?php

use App\Member;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Member::create([
            'kode_member' => 'M1234',
            'nama' => 'Abcd',
            'alamat' => 'Frontend Dev',
            'telepon' => '0821231412141',
            'jenis_kelamin' => 'Laki-Laki',
            'ukuran_baju' => 'L',
            'ukuran_celana' => '39',
            'ukuran_sepatu' => '40',
        ]);

    }

    

}