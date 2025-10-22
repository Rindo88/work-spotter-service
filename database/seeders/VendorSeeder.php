<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Rfid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $vendorsData = [
            [
                'name' => 'Budi Cilok',
                'email' => 'budi.cilok@example.com',
                'business_name' => 'Cilok Mang Budi',
                'description' => 'Menjual cilok kenyal dengan bumbu kacang khas Bandung. Biasanya keliling sekitar perumahan Metland Cileungsi.',
                'latitude' => -6.4045,
                'longitude' => 106.9593,
                'photo_url' => 'https://images.unsplash.com/photo-1606112219348-204d7d8b94ee',
            ],
            [
                'name' => 'Sari Batagor',
                'email' => 'sari.batagor@example.com',
                'business_name' => 'Batagor Bu Sari',
                'description' => 'Batagor gurih isi tahu dan ikan tenggiri, keliling di sekitaran Pasar Cileungsi.',
                'latitude' => -6.4051,
                'longitude' => 106.9632,
                'photo_url' => 'https://images.unsplash.com/photo-1617196034796-8b89c6858a16',
            ],
            [
                'name' => 'Udin Mie Ayam',
                'email' => 'udin.mieayam@example.com',
                'business_name' => 'Mie Ayam Udin',
                'description' => 'Mie ayam lezat dengan topping ayam manis dan pangsit, biasa mangkal dekat SDN Cileungsi 01.',
                'latitude' => -6.4062,
                'longitude' => 106.9608,
                'photo_url' => 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f',
            ],
            [
                'name' => 'Tono Gorengan',
                'email' => 'tono.gorengan@example.com',
                'business_name' => 'Gorengan Pak Tono',
                'description' => 'Menjual gorengan hangat seperti tempe, tahu isi, pisang, dan risol. Keliling komplek Griya Alam Sentosa.',
                'latitude' => -6.4058,
                'longitude' => 106.9577,
                'photo_url' => 'https://images.unsplash.com/photo-1625940426249-8d03be0a0bcb',
            ],
            [
                'name' => 'Euis Es Cendol',
                'email' => 'euis.cendol@example.com',
                'business_name' => 'Es Cendol Bu Euis',
                'description' => 'Es cendol manis dengan santan segar dan gula aren, biasanya di area Pasar Cileungsi.',
                'latitude' => -6.4053,
                'longitude' => 106.9652,
                'photo_url' => 'https://images.unsplash.com/photo-1605096393576-9a20d9a41f2e',
            ],
            [
                'name' => 'Rahmat Sate',
                'email' => 'rahmat.sate@example.com',
                'business_name' => 'Sate Ayam Rahmat',
                'description' => 'Sate ayam dengan bumbu kacang kental, berjualan sore hingga malam di sekitaran Jl. Narogong.',
                'latitude' => -6.4041,
                'longitude' => 106.9589,
                'photo_url' => 'https://images.unsplash.com/photo-1603079849638-4b36cbd2b3d1',
            ],
            [
                'name' => 'Joko Siomay',
                'email' => 'joko.siomay@example.com',
                'business_name' => 'Siomay Mang Joko',
                'description' => 'Siomay khas Bandung dengan saus kacang dan jeruk limau, keliling sekitar Pondok Sukamaju.',
                'latitude' => -6.4073,
                'longitude' => 106.9621,
                'photo_url' => 'https://images.unsplash.com/photo-1588392382834-a891154bca4d',
            ],
            [
                'name' => 'Darto Rujak',
                'email' => 'darto.rujak@example.com',
                'business_name' => 'Rujak Buah Kang Darto',
                'description' => 'Rujak segar dengan sambal kacang pedas, sering mangkal depan SDN Bojong Kulur.',
                'latitude' => -6.4048,
                'longitude' => 106.9610,
                'photo_url' => 'https://images.unsplash.com/photo-1621072157488-166e3a7c9a74',
            ],
            [
                'name' => 'Lina Bubur',
                'email' => 'lina.bubur@example.com',
                'business_name' => 'Bubur Ayam Lina',
                'description' => 'Bubur ayam dengan topping cakwe dan ati ampela, pagi-pagi sudah keliling komplek Cileungsi Hijau.',
                'latitude' => -6.4089,
                'longitude' => 106.9644,
                'photo_url' => 'https://images.unsplash.com/photo-1617196034796-8b89c6858a16',
            ],
            [
                'name' => 'Rudi Tahu Bulat',
                'email' => 'rudi.tahubulat@example.com',
                'business_name' => 'Tahu Bulat Rudi',
                'description' => 'Tahu bulat digoreng dadakan 500-an, keliling setiap sore di Cileungsi Garden.',
                'latitude' => -6.4069,
                'longitude' => 106.9590,
                'photo_url' => 'https://images.unsplash.com/photo-1625940426249-8d03be0a0bcb',
            ],
            [
                'name' => 'Tati Kue Cubit',
                'email' => 'tati.kuecubit@example.com',
                'business_name' => 'Kue Cubit Bu Tati',
                'description' => 'Kue cubit lembut berbagai rasa seperti green tea dan cokelat keju. Keliling sore hari di Perumahan Griya Alam Sentosa.',
                'latitude' => -6.4085,
                'longitude' => 106.9572,
                'photo_url' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092',
            ],
            [
                'name' => 'Anwar Bakso',
                'email' => 'anwar.bakso@example.com',
                'business_name' => 'Bakso Pak Anwar',
                'description' => 'Bakso urat pedas dan bakso telur khas Solo. Mangkal di sekitar Cileungsi Kidul.',
                'latitude' => -6.4042,
                'longitude' => 106.9661,
                'photo_url' => 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f',
            ],
            [
                'name' => 'Dewi Es Doger',
                'email' => 'dewi.esdoger@example.com',
                'business_name' => 'Es Doger Bu Dewi',
                'description' => 'Es doger manis dengan campuran tape dan kelapa muda. Biasanya keliling dekat Alun-alun Cileungsi.',
                'latitude' => -6.4068,
                'longitude' => 106.9639,
                'photo_url' => 'https://images.unsplash.com/photo-1604908811907-8a473b05bd1a',
            ],
            [
                'name' => 'Agus Nasi Goreng',
                'email' => 'agus.nasigoreng@example.com',
                'business_name' => 'Nasi Goreng Mas Agus',
                'description' => 'Nasi goreng kampung pedas dengan tambahan pete dan telur dadar. Jualan malam hari.',
                'latitude' => -6.4056,
                'longitude' => 106.9613,
                'photo_url' => 'https://images.unsplash.com/photo-1604908177328-027e7e6e85d8',
            ],
            [
                'name' => 'Yanti Pecel Lele',
                'email' => 'yanti.pecellele@example.com',
                'business_name' => 'Pecel Lele Bu Yanti',
                'description' => 'Pecel lele sambal bawang khas Lamongan. Mangkal depan minimarket di Cileungsi Kota.',
                'latitude' => -6.4038,
                'longitude' => 106.9627,
                'photo_url' => 'https://images.unsplash.com/photo-1617196034796-8b89c6858a16',
            ],
            [
                'name' => 'Ujang Ketoprak',
                'email' => 'ujang.ketoprak@example.com',
                'business_name' => 'Ketoprak Ujang Betawi',
                'description' => 'Ketoprak khas Betawi dengan bihun dan bumbu kacang gurih. Sering keliling pagi hari.',
                'latitude' => -6.4071,
                'longitude' => 106.9584,
                'photo_url' => 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f',
            ],
            [
                'name' => 'Nana Es Kelapa',
                'email' => 'nana.eskelapa@example.com',
                'business_name' => 'Es Kelapa Nana',
                'description' => 'Es kelapa muda segar dengan sirup dan es batu. Biasa di area Lapangan Cileungsi.',
                'latitude' => -6.4064,
                'longitude' => 106.9601,
                'photo_url' => 'https://images.unsplash.com/photo-1565958011705-44e211f7a29b',
            ],
            [
                'name' => 'Heri Soto Mie',
                'email' => 'heri.sotomie@example.com',
                'business_name' => 'Soto Mie Heri',
                'description' => 'Soto mie bogor dengan kikil dan risol khas. Mangkal di pinggir Jalan Raya Cileungsi.',
                'latitude' => -6.4047,
                'longitude' => 106.9579,
                'photo_url' => 'https://images.unsplash.com/photo-1617196034796-8b89c6858a16',
            ],
            [
                'name' => 'Tasya Karedok',
                'email' => 'tasya.karedok@example.com',
                'business_name' => 'Karedok Tasya',
                'description' => 'Karedok segar dengan sayur mentah dan sambal kacang pedas. Keliling sore hari.',
                'latitude' => -6.4075,
                'longitude' => 106.9655,
                'photo_url' => 'https://images.unsplash.com/photo-1621072157488-166e3a7c9a74',
            ],
            [
                'name' => 'Reno Lontong Sayur',
                'email' => 'reno.lontongsayur@example.com',
                'business_name' => 'Lontong Sayur Reno',
                'description' => 'Lontong sayur padang dengan sambal goreng kentang dan telur. Mangkal pagi hari.',
                'latitude' => -6.4033,
                'longitude' => 106.9597,
                'photo_url' => 'https://images.unsplash.com/photo-1604908177328-027e7e6e85d8',
            ],
            [
                'name' => 'Dian Kue Pancong',
                'email' => 'dian.kuepancong@example.com',
                'business_name' => 'Kue Pancong Dian',
                'description' => 'Kue pancong kelapa manis dengan aroma harum. Keliling sore di Komplek Cileungsi Baru.',
                'latitude' => -6.4059,
                'longitude' => 106.9586,
                'photo_url' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092',
            ],
            [
                'name' => 'Hendri Martabak',
                'email' => 'hendri.martabak@example.com',
                'business_name' => 'Martabak Bang Hendri',
                'description' => 'Martabak manis dan telur, rasa keju, kacang, dan cokelat. Jualan malam depan Indomaret.',
                'latitude' => -6.4049,
                'longitude' => 106.9629,
                'photo_url' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092',
            ],
            [
                'name' => 'Fitri Cakwe',
                'email' => 'fitri.cakwe@example.com',
                'business_name' => 'Cakwe Bu Fitri',
                'description' => 'Cakwe goreng gurih dan empuk. Dijual pagi-pagi di sekitar SD dan TK.',
                'latitude' => -6.4078,
                'longitude' => 106.9634,
                'photo_url' => 'https://images.unsplash.com/photo-1625940426249-8d03be0a0bcb',
            ],
            [
                'name' => 'Jamal Nasi Uduk',
                'email' => 'jamal.nasiuduk@example.com',
                'business_name' => 'Nasi Uduk Bang Jamal',
                'description' => 'Nasi uduk wangi dengan lauk ayam goreng dan sambal kacang. Keliling pagi hari.',
                'latitude' => -6.4061,
                'longitude' => 106.9641,
                'photo_url' => 'https://images.unsplash.com/photo-1621072157488-166e3a7c9a74',
            ],
            [
                'name' => 'Wati Es Teh',
                'email' => 'wati.esteh@example.com',
                'business_name' => 'Es Teh Jumbo Wati',
                'description' => 'Minuman es teh manis jumbo segar, sering keliling area sekolah.',
                'latitude' => -6.4057,
                'longitude' => 106.9594,
                'photo_url' => 'https://images.unsplash.com/photo-1604908811907-8a473b05bd1a',
            ],
            [
                'name' => 'Anton Jasuke',
                'email' => 'anton.jasuke@example.com',
                'business_name' => 'Jagung Susu Keju Anton',
                'description' => 'Jagung manis kukus dengan campuran susu dan keju. Keliling sore hari.',
                'latitude' => -6.4039,
                'longitude' => 106.9582,
                'photo_url' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092',
            ],
            [
                'name' => 'Iwan Tahu Gejrot',
                'email' => 'iwan.tahugejrot@example.com',
                'business_name' => 'Tahu Gejrot Kang Iwan',
                'description' => 'Tahu gejrot pedas dengan bumbu bawang khas Cirebon. Keliling komplek Cileungsi.',
                'latitude' => -6.4043,
                'longitude' => 106.9605,
                'photo_url' => 'https://images.unsplash.com/photo-1625940426249-8d03be0a0bcb',
            ],
            [
                'name' => 'Sinta Es Buah',
                'email' => 'sinta.esbuah@example.com',
                'business_name' => 'Es Buah Segar Sinta',
                'description' => 'Es buah campur segar dengan sirup dan susu kental manis. Jualan siang hari.',
                'latitude' => -6.4070,
                'longitude' => 106.9625,
                'photo_url' => 'https://images.unsplash.com/photo-1565958011705-44e211f7a29b',
            ],
            [
                'name' => 'Teguh Odading',
                'email' => 'teguh.odading@example.com',
                'business_name' => 'Odading Kang Teguh',
                'description' => 'Odading empuk dan manis, sering dijual pagi dekat pasar tradisional.',
                'latitude' => -6.4067,
                'longitude' => 106.9659,
                'photo_url' => 'https://images.unsplash.com/photo-1600891964599-f61ba0e24092',
            ],
            [
                'name' => 'Rika Basreng',
                'email' => 'rika.basreng@example.com',
                'business_name' => 'Basreng Pedas Bu Rika',
                'description' => 'Basreng pedas kering dengan level kepedasan 1–5, sering jualan di sekolah-sekolah.',
                'latitude' => -6.4050,
                'longitude' => 106.9637,
                'photo_url' => 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f',
            ],
            [
                'name' => 'Yusuf Cireng',
                'email' => 'yusuf.cireng@example.com',
                'business_name' => 'Cireng Kang Yusuf',
                'description' => 'Cireng isi ayam pedas dan keju lumer. Keliling sore hari di perumahan Cileungsi Hijau.',
                'latitude' => -6.4060,
                'longitude' => 106.9649,
                'photo_url' => 'https://images.unsplash.com/photo-1625940426249-8d03be0a0bcb',
            ],

        ];

        foreach ($vendorsData as $index => $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'phone' => '08' . rand(1111111111, 9999999999),
                'role' => 'vendor'
            ]);

            $vendor = Vendor::create([
                'user_id' => $user->id,
                'business_name' => $data['business_name'],
                'description' => $data['description'],
                'category_id' => 1,
                'address' => 'Sekitaran Cileungsi',
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'type' => 'informal',
                'is_rfid' => $index < 15, // 15 pertama pakai RFID
                'profile_picture' => $data['photo_url'] ?? null,    
            ]);

            if ($vendor->is_rfid) {
                Rfid::create([
                    'uid' => 'RFID' . strtoupper(substr(md5($vendor->id . time()), 0, 8)),
                    'vendor_id' => $vendor->id,
                    'is_active' => true,
                    'description' => 'RFID untuk ' . $vendor->business_name,
                ]);
            }
        }

        $this->command->info('✅ 30 Pedagang informal berhasil dibuat (15 dengan RFID)');
    }
}
