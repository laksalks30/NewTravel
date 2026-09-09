<?php
/**
 * Script untuk menambahkan destinasi wisata lengkap dengan foto autentik ke database
 * Jalankan: php seed_destinasi.php
 */
include "koneksi.php";

// Hapus data duplikat "India" jika ada
$conn->query("DELETE FROM destinasi WHERE nama_destinasi = 'India'");

echo "=== SEED DESTINASI WISATA INDONESIA ===\n\n";

// Daftar lengkap 44 destinasi wisata Indonesia dengan gambar autentik
// Format: [nama, harga, kategori, kota, lat, lng, gambar_path, deskripsi]
$destinasi = [

    // =====================
    //       B A L I
    // =====================
    [
        'Pantai Kuta Bali', 25000, 'Wisata Alam', 'Bali',
        -8.7180, 115.1686,
        'images/destinasi/pantai_kuta.jpg',
        'Pantai Kuta adalah salah satu pantai paling terkenal di Bali. Terletak di sisi barat daya pulau, pantai ini menawarkan hamparan pasir putih yang luas, ombak yang sempurna untuk berselancar, dan pemandangan sunset yang spektakuler. Kawasan sekitar pantai dipenuhi dengan hotel, restoran, dan pusat perbelanjaan yang membuatnya menjadi destinasi wisata favorit wisatawan domestik maupun mancanegara.'
    ],
    [
        'Pantai Kelingking Nusa Penida', 150000, 'Wisata Alam', 'Bali',
        -8.7523, 115.4445,
        'images/Bali/pantai kelingking.jpg',
        'Pantai Kelingking di Nusa Penida adalah salah satu pantai terindah di dunia dengan formasi tebing kapur yang menyerupai kepala T-Rex. Air lautnya berwarna biru kehijauan jernih dengan pasir putih bersih yang kontras dengan tebing curam di sekelilingnya. Untuk mencapai bibir pantai, pengunjung harus menuruni tangga curam yang menantang namun sangat sepadan dengan pemandangan eksotis yang menanti di bawah.'
    ],
    [
        'Pura Besakih', 60000, 'Wisata Religi', 'Bali',
        -8.3742, 115.4529,
        'images/destinasi/pura_besakih.jpg',
        'Pura Besakih adalah pura terbesar dan paling suci di Bali, terletak di lereng Gunung Agung. Kompleks pura ini terdiri dari lebih dari 86 pura individual dengan arsitektur khas Bali yang megah. Dikenal sebagai "Pura Ibu" (Mother Temple), tempat ini menjadi pusat kegiatan keagamaan Hindu Bali dan menawarkan pemandangan spiritual yang menakjubkan dengan latar belakang gunung tertinggi di Bali.'
    ],
    [
        'Ubud Monkey Forest', 80000, 'Wisata Alam', 'Bali',
        -8.5187, 115.2586,
        'images/destinasi/monkey_forest.jpg',
        'Sacred Monkey Forest Sanctuary di Ubud adalah hutan lindung yang dihuni oleh ratusan monyet ekor panjang (Macaca fascicularis). Kawasan ini memiliki lebih dari 186 spesies pohon di area seluas 12.5 hektar dengan tiga pura suci di dalamnya. Selain berinteraksi dengan monyet, pengunjung dapat menikmati suasana hutan tropis yang teduh, jembatan kayu artistik, dan patung-patung batu berbalut lumut yang misterius.'
    ],
    [
        'Tegallalang Rice Terrace', 35000, 'Wisata Alam', 'Bali',
        -8.4312, 115.2792,
        'images/destinasi/tegallalang.jpg',
        'Tegallalang Rice Terrace adalah salah satu sawah terasering paling ikonik di Bali yang terletak di utara Ubud. Pemandangan undak-undakan sawah hijau yang mengikuti kontur bukit menciptakan panorama alam yang memesona. Area ini juga dilengkapi dengan swing (ayunan) raksasa dan spot foto Instagramable yang menjadi daya tarik tersendiri bagi wisatawan muda dari seluruh dunia.'
    ],
    [
        'Garuda Wisnu Kencana', 125000, 'Wisata Budaya', 'Bali',
        -8.8104, 115.1676,
        'images/destinasi/garuda_wisnu_kencana.jpg',
        'Garuda Wisnu Kencana (GWK) Cultural Park adalah taman budaya di Bali yang terkenal dengan patung Dewa Wisnu menunggangi Garuda setinggi 122 meter, menjadikannya salah satu patung tertinggi di dunia. Taman ini menawarkan pertunjukan seni tradisional Bali, pameran seni kontemporer, dan amphitheater megah untuk acara-acara besar. Pemandangan dari atas bukit GWK menghadirkan panorama selatan Bali yang luar biasa.'
    ],
    [
        'Tirta Empul', 50000, 'Wisata Religi', 'Bali',
        -8.4153, 115.3153,
        'images/destinasi/tirta_empul.jpg',
        'Pura Tirta Empul adalah pura air suci yang terletak di desa Tampaksiring. Pura ini terkenal dengan kolam pemandian suci (petirtaan) yang airnya mengalir dari mata air alami. Umat Hindu Bali datang ke sini untuk melakukan ritual melukat (pembersihan spiritual) dengan cara membasuh diri di bawah pancuran air suci yang berjumlah 30 pancuran. Arsitektur pura yang indah dikelilingi taman tropis yang asri.'
    ],
    [
        'Pantai Sanur', 10000, 'Wisata Alam', 'Bali',
        -8.6783, 115.2632,
        'images/destinasi/pantai_sanur.jpg',
        'Pantai Sanur adalah pantai yang tenang dan ramah keluarga di sisi timur Bali. Berbeda dengan Kuta yang bergelombang besar, Sanur memiliki ombak yang lebih tenang berkat terumbu karang penghalang. Pantai ini terkenal dengan sunrise yang memukau, jalur jogging dan bersepeda sepanjang pantai, serta deretan perahu jukung tradisional yang berjejer rapi di tepian pantai.'
    ],

    // =====================
    //   B A N Y U W A N G I
    // =====================
    [
        'Pantai Pulau Merah', 15000, 'Wisata Alam', 'Banyuwangi',
        -8.5753, 114.0196,
        'images/Banyuwangi/pantai pulau merah.jpg',
        'Pantai Pulau Merah adalah pantai eksotis di Banyuwangi yang mendapatkan namanya dari bukit kecil bertanah kemerahan di tengah laut. Pantai berpasir halus dengan ombak yang konsisten menjadikannya spot surfing populer bahkan telah menjadi tuan rumah kompetisi selancar internasional. Saat air surut, pengunjung bisa berjalan kaki menuju pulau kecil dan menikmati pemandangan sunset yang luar biasa.'
    ],
    [
        'Teluk Hijau', 10000, 'Wisata Alam', 'Banyuwangi',
        -8.5647, 114.0412,
        'images/Banyuwangi/Teluk hijau.jpg',
        'Teluk Hijau (Green Bay) adalah permata tersembunyi di kawasan Taman Nasional Meru Betiri, Banyuwangi. Teluk kecil ini memiliki air laut berwarna hijau zamrud yang jernih dengan pasir putih yang dikelilingi tebing-tebing berbatu dan hutan tropis lebat. Untuk mencapainya, pengunjung harus trekking melewati hutan selama sekitar 30 menit, yang justru menambah sensasi petualangan menuju surga tersembunyi ini.'
    ],
    [
        'Taman Nasional Baluran', 30000, 'Wisata Alam', 'Banyuwangi',
        -7.8499, 114.3763,
        'images/destinasi/baluran.jpg',
        'Taman Nasional Baluran dijuluki "Africa van Java" karena savana luasnya yang menyerupai sabana Afrika. Taman ini memiliki luas sekitar 25.000 hektar dengan ekosistem yang beragam mulai dari savana Bekol, hutan mangrove, hingga pantai Bama. Pengunjung dapat melihat banteng Jawa, rusa, merak hijau, dan berbagai satwa liar lainnya berkeliaran bebas dengan latar belakang Gunung Baluran yang megah.'
    ],
    [
        'Air Terjun Jagir', 15000, 'Wisata Alam', 'Banyuwangi',
        -8.2166, 114.1510,
        'images/destinasi/air_terjun_jagir.jpg',
        'Air Terjun Jagir adalah air terjun menakjubkan di Banyuwangi yang terletak di kawasan lereng Gunung Raung. Air terjun ini memiliki aliran kembar yang mengalir deras di antara tebing bebatuan yang diselimuti lumut hijau dan vegetasi alami. Suasana di sekitarnya sangat asri dengan hutan tropis yang rimbun, udara sejuk pegunungan, dan kolam alami yang menyegarkan.'
    ],
    [
        'Pantai Boom Banyuwangi', 5000, 'Wisata Alam', 'Banyuwangi',
        -8.2134, 114.3737,
        'images/destinasi/pantai_boom.jpg',
        'Pantai Boom adalah pantai ikonik di pusat Kota Banyuwangi yang menghadap langsung ke Selat Bali dengan pemandangan Gunung Agung di kejauhan. Pantai ini memiliki dermaga marina modern, area festival budaya Gandrung Sewu, dan pasir hitam berkilau yang eksotis. Tempat favorit warga dan wisatawan untuk menikmati sunrise dan bersantai di sore hari.'
    ],
    [
        'Desa Wisata Osing', 25000, 'Wisata Kampung', 'Banyuwangi',
        -8.3250, 114.1830,
        'images/destinasi/desa_osing.jpg',
        'Desa Wisata Osing Kemiren adalah desa adat suku Osing yang merupakan penduduk asli Banyuwangi. Di desa ini wisatawan dapat mempelajari budaya, tradisi, dan kehidupan sehari-hari masyarakat Osing. Pengunjung bisa melihat rumah adat tradisional, menyaksikan pertunjukan tari Gandrung dan barong, serta mencicipi kuliner khas pecel pitik yang otentik.'
    ],
    [
        'De Djawatan Forest', 10000, 'Wisata Alam', 'Banyuwangi',
        -8.1325, 114.2518,
        'images/destinasi/de_djawatan.jpg',
        'De Djawatan adalah hutan trembesi purba di Benculuk, Banyuwangi, yang dipenuhi pohon-pohon trembesi raksasa berusia ratusan tahun dengan dahan menjulang dan lumut gantung yang eksotis. Suasana magis hutan ini sering disebut mirip dengan Fangorn Forest di film Lord of the Rings. Cahaya matahari pagi yang menerobos rimbunnya dedaunan menciptakan siluet dramatis yang sangat mempesona.'
    ],

    // =====================
    //      M A L A N G
    // =====================
    [
        'Kampung Warna Warni Jodipan', 5000, 'Wisata Kampung', 'Malang',
        -7.9838, 112.6356,
        'images/Malang/kampung warna warni.jpg',
        'Kampung Warna Warni Jodipan adalah kampung tematik di tepi Sungai Brantas yang dicat dengan warna-warni cerah. Awalnya perkampungan kumuh, kini menjadi destinasi wisata Instagramable yang populer. Setiap dinding rumah dihiasi mural dan cat warna-warni yang menciptakan suasana ceria. Terdapat jembatan kaca yang menghubungkan ke Kampung Tridi di seberang sungai.'
    ],
    [
        'Batu Night Spectacular', 100000, 'Wisata Budaya', 'Malang',
        -7.8784, 112.5238,
        'images/Malang/BNS.jpg',
        'Batu Night Spectacular (BNS) adalah taman hiburan malam terbesar di Jawa Timur yang terletak di Kota Batu. Taman ini menawarkan berbagai wahana permainan seru, taman lampion raksasa warna-warni, panggung musik, pasar malam modern, dan pemandangan gemerlap lampu kota Batu dari ketinggian.'
    ],
    [
        'Coban Rondo Waterfall', 30000, 'Wisata Alam', 'Malang',
        -7.8784, 112.4693,
        'images/destinasi/coban_rondo.jpg',
        'Coban Rondo adalah air terjun megah setinggi 84 meter yang terletak di lereng Gunung Panderman, Kota Batu. Air terjun ini dikelilingi hutan pinus yang rindang dengan udara sejuk khas pegunungan. Selain menikmati deburan air terjun alami, pengunjung dapat berpetualang di taman labirin (Labyrinth), area outbound, dan jalur berkemah yang asri.'
    ],
    [
        'Jatim Park 2', 120000, 'Wisata Budaya', 'Malang',
        -7.8843, 112.5304,
        'images/destinasi/jatim_park_2.jpg',
        'Jatim Park 2 adalah taman wisata edukasi bertaraf internasional di Kota Batu yang mencakup Batu Secret Zoo dan Museum Satwa. Pengunjung dapat melihat koleksi satwa langka dari seluruh dunia dalam habitat modern yang menyerupai alam aslinya, serta museum satwa dengan diorama raksasa fauna purba dan modern.'
    ],
    [
        'Pantai Balekambang', 20000, 'Wisata Alam', 'Malang',
        -8.3899, 112.5249,
        'images/destinasi/pantai_balekambang.jpg',
        'Pantai Balekambang adalah pantai ikonik di Malang Selatan yang dijuluki "Tanah Lot-nya Jawa Timur". Ciri khas utamanya adalah Pura Ismoyo yang berdiri anggun di atas pulau karang Pulau Ismoyo, terhubung dengan daratan utama melalui jembatan beton sepanjang 100 meter melintasi deburan ombak Samudra Hindia.'
    ],
    [
        'Museum Angkut', 100000, 'Wisata Budaya', 'Malang',
        -7.8807, 112.5235,
        'images/destinasi/museum_angkut.jpg',
        'Museum Angkut adalah museum transportasi interaktif terbesar di Asia Tenggara yang terletak di Kota Batu. Museum ini memamerkan lebih dari 300 koleksi kendaraan bersejarah dari berbagai belahan dunia, dipadukan dengan zona bertema kota-kota dunia seperti Gangster Town, Pecinan, Batavia, Broadway, hingga Istana Buckingham London.'
    ],
    [
        'Taman Selecta', 35000, 'Wisata Alam', 'Malang',
        -7.8546, 112.5002,
        'images/destinasi/taman_selecta.jpg',
        'Taman Rekreasi Selecta adalah taman bunga legendaris di Kota Batu yang telah ada sejak era kolonial Belanda tahun 1928. Terletak di ketinggian 1.100 mdpl, taman ini menyuguhkan hamparan taman bunga berwarna-warni yang tertata rapi, kolam renang alami air pegunungan, wahana sky bike, dan panorama Gunung Arjuno yang menyejukkan.'
    ],

    // =====================
    //    S U R A B A Y A
    // =====================
    [
        'Kampung Arab Surabaya', 5000, 'Wisata Kampung', 'Surabaya',
        -7.2479, 112.7385,
        'images/destinasi/kampung_arab.jpg',
        'Kampung Arab Surabaya di kawasan Ampel adalah salah satu perkampungan Arab tertua di Nusantara yang berpusat di sekitar Masjid Agung Sunan Ampel. Kawasan ini memiliki atmosfer khas Timur Tengah dengan lorong-lorong pertokoan yang menjual parfum, busana muslim, kurma, dan kuliner khas seperti nasi kebuli dan roti maryam.'
    ],
    [
        'Tugu Pahlawan', 10000, 'Wisata Budaya', 'Surabaya',
        -7.2458, 112.7378,
        'images/destinasi/tugu_pahlawan.jpg',
        'Tugu Pahlawan adalah monumen tugu setinggi 41.15 meter berbentuk paku terbalik dengan 10 lengkungan yang menjadi simbol abadi perjuangan arek-arek Suroboyo dalam pertempuran 10 November 1945. Di bawah monumen terdapat Museum 10 Nopember yang menyimpan diorama dan rekaman pidato Bung Tomo yang membakar semangat kemerdekaan.'
    ],
    [
        'Kebun Binatang Surabaya', 30000, 'Wisata Alam', 'Surabaya',
        -7.2968, 112.7366,
        'images/destinasi/kebun_binatang_surabaya.jpg',
        'Kebun Binatang Surabaya (KBS) adalah salah satu kebun binatang tertua dan terlengkap di Asia Tenggara yang didirikan pada tahun 1916. Berlokasi strategis di dekat patung ikonik Suro dan Boyo, KBS menjadi rumah bagi ribuan satwa dari berbagai spesies termasuk komodo, gajah sumatera, dan harimau dalam area konservasi yang rindang.'
    ],
    [
        'House of Sampoerna', 0, 'Wisata Budaya', 'Surabaya',
        -7.2340, 112.7373,
        'images/destinasi/house_of_sampoerna.jpg',
        'House of Sampoerna adalah kompleks museum bersejarah bertaraf internasional yang menempati bangunan megah bergaya kolonial Belanda tahun 1862. Pengunjung dapat melihat sejarah industri tembakau kretek Indonesia, galeri seni, serta menyaksikan langsung ribuan pelinting kretek tradisional dari balik balkon kaca.'
    ],
    [
        'Masjid Al-Akbar Surabaya', 0, 'Wisata Religi', 'Surabaya',
        -7.3290, 112.7169,
        'images/destinasi/masjid_al_akbar.jpg',
        'Masjid Nasional Al-Akbar Surabaya adalah masjid terbesar kedua di Indonesia setelah Masjid Istiqlal. Bangunan megah ini memiliki kubah utama berwarna biru-toska yang sangat ikonik dan menara setinggi 99 meter yang dilengkapi lift untuk menikmati panorama 360 derajat lanskap Kota Surabaya dari ketinggian.'
    ],
    [
        'Suramadu Bridge', 0, 'Wisata Budaya', 'Surabaya',
        -7.1833, 112.7836,
        'images/destinasi/jembatan_suramadu.jpg',
        'Jembatan Nasional Suramadu adalah jembatan kabel pancang (cable-stayed) terpanjang di Indonesia dengan panjang 5.438 meter yang melintasi Selat Madura menghubungkan Surabaya dan Pulau Madura. Pemandangan jembatan ini sangat spektakuler, terutama saat senja dan malam hari ketika lampu-lampu megahnya berpendar di atas lautan.'
    ],
    [
        'Hutan Mangrove Wonorejo', 25000, 'Wisata Alam', 'Surabaya',
        -7.3113, 112.8196,
        'images/destinasi/mangrove_wonorejo.jpg',
        'Ekowisata Hutan Mangrove Wonorejo adalah kawasan konservasi hutan bakau seluas lebih dari 200 hektar di pesisir timur Surabaya. Pengunjung dapat menyusuri jembatan kayu (boardwalk) yang membelah lebatnya tanaman bakau, menikmati tur perahu susur sungai menuju muara, dan mengamati burung-burung migran.'
    ],

    // =====================
    //  Y O G Y A K A R T A
    // =====================
    [
        'Candi Borobudur', 50000, 'Wisata Budaya', 'Yogyakarta',
        -7.6079, 110.2038,
        'images/Yogyakarta/Candi Borobudurjpg.jpg',
        'Candi Borobudur adalah candi Buddha terbesar di dunia yang dibangun pada abad ke-8 dan merupakan Situs Warisan Dunia UNESCO. Monumen megah ini memiliki 2.672 panel relief naratif yang dipahat indah pada dinding batu dan 504 arca Buddha yang tersebar di stupa-stupa berundak dengan latar pemandangan bukit Menoreh.'
    ],
    [
        'Pantai Drini', 10000, 'Wisata Alam', 'Yogyakarta',
        -8.1452, 110.5698,
        'images/Yogyakarta/Pantai Drini.jpg',
        'Pantai Drini adalah pantai pasir putih eksotis di pesisir Gunungkidul dengan keunikan pulau karang kecil di tengah pantai yang membagi perairan menjadi dua karakter: sisi barat dengan ombak menantang dan sisi timur yang tenang bak laguna alami berair jernih, aman untuk berenang dan bermain kano.'
    ],
    [
        'Kraton Yogyakarta', 15000, 'Wisata Budaya', 'Yogyakarta',
        -7.8052, 110.3642,
        'images/destinasi/kraton_yogyakarta.jpg',
        'Kraton Ngayogyakarta Hadiningrat adalah kompleks istana resmi Kesultanan Yogyakarta yang menjadi episentrum kebudayaan Jawa yang masih hidup. Di dalam keraton, pengunjung dapat mengagumi arsitektur tradisional Jawa adiluhung, koleksi benda pusaka kerajaan, dan pertunjukan seni tari serta gamelan klasik.'
    ],
    [
        'Malioboro', 0, 'Wisata Budaya', 'Yogyakarta',
        -7.7928, 110.3658,
        'images/destinasi/malioboro.jpg',
        'Jalan Malioboro adalah jalan legendaris yang menjadi jantung kehidupan wisata Kota Yogyakarta. Membentang dari Tugu Jogja hingga Titik Nol Kilometer, kawasan pedestrian yang ramah pejalan kaki ini dipenuhi pedagang suvenir, batik, musisi jalanan, andong tradisional, dan deretan kuliner malam legendaris.'
    ],
    [
        'Pantai Parangtritis', 10000, 'Wisata Alam', 'Yogyakarta',
        -8.0260, 110.3265,
        'images/destinasi/pantai_parangtritis.jpg',
        'Pantai Parangtritis adalah pantai legendaris di pesisir selatan Yogyakarta yang kental dengan mitos Ratu Kidul. Menawarkan hamparan pasir besi hitam yang luas, deburan ombak Samudra Hindia yang bertenaga, gumuk pasir langka di sekitarnya, serta pemandangan matahari terbenam (sunset) yang magis.'
    ],
    [
        'Goa Jomblang', 500000, 'Wisata Alam', 'Yogyakarta',
        -8.0432, 110.6416,
        'images/destinasi/goa_jomblang.jpg',
        'Goa Jomblang di Gunungkidul adalah gua vertikal spektakuler yang terkenal dengan fenomena "Cahaya Surga" (heavenly light), yaitu berkas sinar matahari yang menerobos lubang atap gua di kedalaman 60 meter menerangi hutan purba bawah tanah. Petualangan caving kelas dunia yang sangat mendebarkan.'
    ],
    [
        'Taman Sari Water Castle', 15000, 'Wisata Budaya', 'Yogyakarta',
        -7.8100, 110.3591,
        'images/destinasi/taman_sari.jpg',
        'Taman Sari adalah bekas kompleks taman air dan pemandian peristirahatan keluarga Sultan Yogyakarta yang dibangun pada pertengahan abad ke-18. Memiliki arsitektur perpaduan Jawa, Portugis, dan Eropa dengan kolam pemandian Umbul Pasiraman, menara pengintai, dan masjid bawah tanah Sumur Gumuling yang unik.'
    ],
    [
        'Hutan Pinus Mangunan', 5000, 'Wisata Alam', 'Yogyakarta',
        -7.9344, 110.4053,
        'images/destinasi/hutan_pinus_mangunan.jpg',
        'Hutan Pinus Mangunan di Dlingo, Bantul, adalah kawasan ekowisata perbukitan yang dipenuhi deretan pohon pinus tinggi menjulang dengan udara pegunungan yang sejuk dan asri. Dilengkapi dengan panggung teater kayu alami, gardu pandang lembah kabut, dan spot-spot foto alam yang menawan.'
    ],
    [
        'Desa Wisata Nglanggeran', 15000, 'Wisata Kampung', 'Yogyakarta',
        -7.8566, 110.5135,
        'images/destinasi/nglanggeran.jpg',
        'Desa Wisata Nglanggeran di Gunungkidul adalah peraih penghargaan Desa Wisata Terbaik Dunia versi UNWTO. Menampilkan formasi megah Gunung Api Purba Nglanggeran yang berusia 60 juta tahun, Embung Nglanggeran, perkebunan kakao, serta keramahan homestay warga pedesaan berwawasan lingkungan.'
    ],
];

$inserted = 0;
$skipped = 0;

foreach ($destinasi as $d) {
    list($nama, $harga, $kategori, $kota, $lat, $lng, $gambar_path, $deskripsi) = $d;

    $checkStmt = $conn->prepare("SELECT id_destinasi FROM destinasi WHERE nama_destinasi = ?");
    $checkStmt->bind_param("s", $nama);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        $id = $row['id_destinasi'];
        $checkStmt->close();

        // Update gambar jika ada
        $fullPath = __DIR__ . '/' . str_replace('/', DIRECTORY_SEPARATOR, $gambar_path);
        if (file_exists($fullPath)) {
            $imgData = file_get_contents($fullPath);
            $upStmt = $conn->prepare("UPDATE destinasi SET gambar_destinasi = ?, harga_destinasi = ?, deskripsi_destinasi = ? WHERE id_destinasi = ?");
            $upStmt->bind_param("sisi", $imgData, $harga, $deskripsi, $id);
            $upStmt->execute();
            $upStmt->close();
            echo "  [UPDATE] #$id — $nama ($gambar_path)\n";
        }
        $skipped++;
        continue;
    }
    $checkStmt->close();

    $gambar_data = null;
    $fullPath = __DIR__ . '/' . str_replace('/', DIRECTORY_SEPARATOR, $gambar_path);
    if (file_exists($fullPath)) {
        $gambar_data = file_get_contents($fullPath);
    }

    $stmt = $conn->prepare("INSERT INTO destinasi 
        (nama_destinasi, harga_destinasi, kategori_destinasi, kota_destinasi, latitude, longitude, gambar_destinasi, deskripsi_destinasi) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sissddss", $nama, $harga, $kategori, $kota, $lat, $lng, $gambar_data, $deskripsi);
    if ($stmt->execute()) {
        $newId = $conn->insert_id;
        echo "  [INSERT] #$newId — $nama ($kota, $kategori)\n";
        $inserted++;
    }
    $stmt->close();
}

echo "\n=== SELESAI ===\n";
echo "Total Baru: $inserted, Diperbarui: $skipped\n";
$conn->close();
?>
