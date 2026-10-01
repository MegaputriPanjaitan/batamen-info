<?php

namespace Database\Seeders;

use App\Models\InternalSurveyEmployee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (config('admin.name') && config('admin.email') && config('admin.password')) {
            User::query()->updateOrCreate(
                ['email' => config('admin.email')],
                [
                    'name' => config('admin.name'),
                    'password' => config('admin.password'),
                    'is_admin' => true,
                ],
            );
        }

        $employees = [
            ['198205112005011001', 'SYAFRIADI LUBIS'],
            ['198410272002122002', 'NELSY DEPARI'],
            ['197304101998032001', 'LUSIANA TARIHORAN'],
            ['197105051992032001', 'ELSINTHA DAMAYANTI'],
            ['197312212001121001', 'BUDIYANTO'],
            ['196706121989031001', 'SYUHADA'],
            ['197311272002121001', 'DARTIMNOV M.T HARAHAP'],
            ['197604222003122001', 'MARIANI BR MARBUN'],
            ['197306111993032001', 'TUMIAR SIMANJORANG'],
            ['198001172010012010', 'TITIN WAHYUNASARI DONGORAN'],
            ['198106282000031001', 'NANANG SURYA PURNAMA'],
            ['199101242019011001', 'JOKO PRABOWO'],
            ['199209232019012001', 'DIAN STEVANY TONGLI'],
            ['199106032018011003', 'M. TAUFIK RAHMAN'],
            ['199511132017122001', 'SHELA NATASHA'],
            ['199308172017121001', 'HUTRI ZEBUA'],
            ['199310092017122001', 'NUR FAIRUZ DIBA NASUTION'],
            ['199104202025062004', 'DIAJENG KARTIKA SARI'],
            ['199211242025061005', 'RIFAL SAPTA HADI'],
            ['199301102025061006', 'ANDRE YOSUA SURBAKTI'],
            ['199303162025061008', 'TEUKU MEURAH ALBAR'],
            ['199608072025061004', 'HARTO ALFREDO SIREGAR'],
            ['199608232025062015', 'ANNISA DWI MARINA'],
            ['199609052025062014', 'ELSY INDRIANI'],
            ['199909162025061006', 'YUSRIL IHZA MAHENDRA'],
            ['200010172025062014', 'NINDIA'],
            ['197207051998032002', 'BETTY IRAWATI SINAGA'],
            ['198005152010121003', 'NAEK PARLINDUNGAN SIBUEA'],
            ['198808132015032002', 'ADELINA IRMADEWITA SIAGIAN'],
            ['199410112017122001', 'AGNI RAMANIYA MAHARANI'],
            ['198803162019012001', 'TRIDOLA SIREGAR'],
            ['199202132019012001', 'FEBRINA SIRINGORINGO'],
            ['199609192019012001', 'INDRIANA NURKAMSIAH'],
            ['199812162020121001', 'ROY ALEXANDER'],
        ];

        foreach ($employees as [$nip, $name]) {
            InternalSurveyEmployee::query()->updateOrCreate(
                ['nip' => $nip],
                ['name' => $name, 'is_active' => true],
            );
        }
    }
}
