<?php
/**
 * Script untuk menambahkan banyak destinasi wisata ke database
 * Jalankan sekali saja: php seed_destinasi.php
 */
include "koneksi.php";

// Hapus data duplikat "India" yang tidak relevan
$conn->query("DELETE FROM destinasi WHERE nama_destinasi = 'India'");

echo "=== SEED DESTINASI WISATA ===\n\n";

// Daftar lengkap destinasi wisata Indonesia
// Format: [nama, harga, kategori, kota, lat, lng, gambar_path/url, deskripsi]
$destinasi = [

    // =====================
    //       B A L I
    // =====================
    [
        'Pantai Kuta Bali', 25000, 'Wisata Alam', 'Bali',
        -8.7180, 115.1686,
        'images/Bali/gallary.jpg',
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
        'images/Bali/Pura ulun danu bratan.png',
        'Pura Besakih adalah pura terbesar dan paling suci di Bali, terletak di lereng Gunung Agung. Kompleks pura ini terdiri dari lebih dari 86 pura individual dengan arsitektur khas Bali yang megah. Dikenal sebagai "Pura Ibu" (Mother Temple), tempat ini menjadi pusat kegiatan keagamaan Hindu Bali dan menawarkan pemandangan spiritual yang menakjubkan dengan latar belakang gunung tertinggi di Bali.'
    ],
    [
        'Ubud Monkey Forest', 80000, 'Wisata Alam', 'Bali',
        -8.5187, 115.2586,
        'images/Bali/gallary pura ulun.jpg',
        'Sacred Monkey Forest Sanctuary di Ubud adalah hutan lindung yang dihuni oleh ratusan monyet ekor panjang (Macaca fascicularis). Kawasan ini memiliki lebih dari 186 spesies pohon di area seluas 12.5 hektar dengan tiga pura suci di dalamnya. Selain berinteraksi dengan monyet, pengunjung dapat menikmati suasana hutan tropis yang teduh, jembatan kayu artistik, dan patung-patung batu berbalut lumut yang misterius.'
    ],
    [
        'Tegallalang Rice Terrace', 35000, 'Wisata Alam', 'Bali',
        -8.4312, 115.2792,
        'images/Bali/Tanah Lot.jpg',
        'Tegallalang Rice Terrace adalah salah satu sawah terasering paling ikonik di Bali yang terletak di utara Ubud. Pemandangan undak-undakan sawah hijau yang mengikuti kontur bukit menciptakan panorama alam yang memesona. Area ini juga dilengkapi dengan swing (ayunan) raksasa dan spot foto Instagramable yang menjadi daya tarik tersendiri bagi wisatawan muda dari seluruh dunia.'
    ],
    [
        'Garuda Wisnu Kencana', 125000, 'Wisata Budaya', 'Bali',
        -8.8104, 115.1676,
        'images/Bali/gallary.jpg',
        'Garuda Wisnu Kencana (GWK) Cultural Park adalah taman budaya di Bali yang terkenal dengan patung Dewa Wisnu menunggangi Garuda setinggi 122 meter, menjadikannya salah satu patung tertinggi di dunia. Taman ini menawarkan pertunjukan seni tradisional Bali, pameran seni kontemporer, dan amphitheater megah untuk acara-acara besar. Pemandangan dari atas bukit GWK menghadirkan panorama selatan Bali yang luar biasa.'
    ],
    [
        'Tirta Empul', 50000, 'Wisata Religi', 'Bali',
        -8.4153, 115.3153,
        'images/Bali/Pura ulun danu bratan.png',
        'Pura Tirta Empul adalah pura air suci yang terletak di desa Tampaksiring. Pura ini terkenal dengan kolam pemandian suci (petirtaan) yang airnya mengalir dari mata air alami. Umat Hindu Bali datang ke sini untuk melakukan ritual melukat (pembersihan spiritual) dengan cara membasuh diri di bawah pancuran air suci yang berjumlah 30 pancuran. Arsitektur pura yang indah dikelilingi taman tropis yang asri.'
    ],
    [
        'Pantai Sanur', 10000, 'Wisata Alam', 'Bali',
        -8.6783, 115.2632,
        'images/Bali/pantai kelingking.jpg',
        'Pantai Sanur adalah pantai yang tenang dan ramah keluarga di sisi timur Bali. Berbeda dengan Kuta yang bergelombang besar, Sanur memiliki ombak yang lebih tenang berkat terumbu karang penghalang. Pantai ini terkenal dengan sunrise yang memukau, jalur jogging dan bersepeda sepanjang pantai, serta deretan restoran seafood tradisional. Sanur juga menjadi titik keberangkatan speedboat menuju Nusa Penida dan Nusa Lembongan.'
    ],

    // =====================
    //   B A N Y U W A N G I
    // =====================
    [
        'Pantai Pulau Merah', 15000, 'Wisata Alam', 'Banyuwangi',
        -8.5753, 114.0196,
        'images/Banyuwangi/pantai pulau merah.jpg',
        'Pantai Pulau Merah adalah pantai eksotis di Banyuwangi yang mendapatkan namanya dari bukit kecil berwarna kemerahan di tengah laut. Pantai berpasir halus dengan ombak yang konsisten menjadikannya spot surfing populer bahkan telah menjadi tuan rumah kompetisi selancar internasional. Saat air surut, pengunjung bisa berjalan kaki menuju pulau kecil dan menikmati pemandangan sunset yang luar biasa.'
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
        'images/Banyuwangi/gallary.jpg',
        'Taman Nasional Baluran dijuluki "Africa van Java" karena savana luasnya yang menyerupai sabana Afrika. Taman ini memiliki luas sekitar 25.000 hektar dengan ekosistem yang beragam mulai dari savana, hutan mangrove, hutan monsun, hingga pantai. Pengunjung dapat melihat banteng, rusa, kera ekor panjang, merak, dan berbagai satwa liar lainnya berkeliaran bebas di habitat aslinya.'
    ],
    [
        'Air Terjun Jagir', 15000, 'Wisata Alam', 'Banyuwangi',
        -8.2166, 114.1510,
        'images/Banyuwangi/gallary.jpg',
        'Air Terjun Jagir adalah air terjun menakjubkan di Banyuwangi yang terletak di kawasan lereng Gunung Raung. Air terjun ini memiliki ketinggian sekitar 20 meter dengan debit air yang deras mengalir di antara bebatuan yang diselimuti lumut hijau. Suasana di sekitarnya sangat asri dengan hutan tropis yang rimbun, udara sejuk pegunungan, dan suara gemericik air yang menenangkan.'
    ],
    [
        'Pantai Boom Banyuwangi', 5000, 'Wisata Alam', 'Banyuwangi',
        -8.2134, 114.3737,
        'images/Banyuwangi/pantai pulau merah.jpg',
        'Pantai Boom adalah pantai ikonik di pusat Kota Banyuwangi yang menghadap langsung ke Selat Bali dengan pemandangan Gunung Agung di kejauhan. Pantai ini telah direnovasi menjadi kawasan wisata modern dengan taman, jogging track, dan area kuliner. Spot terbaik untuk menikmati sunrise dengan siluet Pulau Bali sebagai latar belakang. Di sini juga sering diadakan festival seni dan budaya Banyuwangi.'
    ],
    [
        'Desa Wisata Osing', 25000, 'Wisata Kampung', 'Banyuwangi',
        -8.3250, 114.1830,
        'images/Banyuwangi/Teluk hijau.jpg',
        'Desa Wisata Osing Kemiren adalah desa adat suku Osing yang merupakan penduduk asli Banyuwangi. Di desa ini wisatawan dapat mempelajari budaya, tradisi, dan kehidupan sehari-hari masyarakat Osing. Pengunjung bisa melihat rumah adat tradisional, menyaksikan pertunjukan tari gandrung, belajar membuat batik Osing, serta mencicipi kuliner khas seperti pecel pitik dan nasi tempong yang otentik.'
    ],
    [
        'De Djawatan Forest', 10000, 'Wisata Alam', 'Banyuwangi',
        -8.1325, 114.2518,
        'images/Banyuwangi/kawah ijen.jpg',
        'De Djawatan adalah hutan tropis unik di Banyuwangi yang dipenuhi pohon-pohon trembesi raksasa berusia puluhan tahun dengan akar-akar besar menjulang. Suasana mistis dan magis hutan ini sering disebut mirip dengan hutan di film Lord of the Rings. Kabut pagi yang menyelimuti hutan menciptakan atmosfer dramatis yang sempurna untuk fotografi. Kawasan ini juga memiliki padang rumput hijau yang luas di sekelilingnya.'
    ],

    // =====================
    //      M A L A N G
    // =====================
    [
        'Kampung Warna Warni Jodipan', 5000, 'Wisata Kampung', 'Malang',
        -7.9838, 112.6356,
        'images/Malang/kampung warna warni.jpg',
        'Kampung Warna Warni Jodipan adalah kampung tematik di tepi Sungai Brantas yang dicat dengan warna-warni cerah. Awalnya perkampungan kumuh, kini menjadi destinasi wisata Instagramable yang populer. Setiap dinding rumah dihiasi mural dan cat warna-warni yang menciptakan suasana ceria. Terdapat jembatan kaca yang menghubungkan ke Kampung Tridi (3D) di seberang sungai dengan lukisan tiga dimensi yang interaktif.'
    ],
    [
        'Batu Night Spectacular', 100000, 'Wisata Budaya', 'Malang',
        -7.8784, 112.5238,
        'images/Malang/BNS.jpg',
        'Batu Night Spectacular (BNS) adalah taman hiburan malam terbesar di Jawa Timur yang terletak di Kota Batu. Taman ini menawarkan berbagai wahana permainan seru, pertunjukan lampion raksasa, area kuliner, serta atraksi cahaya spektakuler. Wahana utamanya termasuk roller coaster, haunted house, dan area bermain air. Pemandangan kota Batu dari ketinggian di malam hari yang gemerlap menjadi bonus tersendiri.'
    ],
    [
        'Coban Rondo Waterfall', 30000, 'Wisata Alam', 'Malang',
        -7.8784, 112.4693,
        'images/Malang/gallary.jpg',
        'Coban Rondo adalah air terjun megah setinggi 84 meter yang terletak di lereng Gunung Panderman, Kota Batu. Air terjun ini dikelilingi hutan pinus yang rindang dengan udara sejuk khas pegunungan. Selain menikmati keindahan air terjun, pengunjung dapat berpetualang di Labyrinth (taman labirin), area outbound, dan flying fox yang mendebarkan. Legendanya yang romantis menambah daya tarik mistis tempat ini.'
    ],
    [
        'Jatim Park 2', 120000, 'Wisata Budaya', 'Malang',
        -7.8843, 112.5304,
        'images/Malang/BNS.jpg',
        'Jatim Park 2 adalah taman wisata edukasi di Kota Batu yang menggabungkan kebun binatang modern, museum satwa, dan Batu Secret Zoo. Pengunjung dapat melihat koleksi ratusan spesies hewan dari seluruh dunia dalam habitat yang ditata menyerupai alam aslinya. Museum Satwa menampilkan replika hewan purba dan diorama ekosistem dunia. Fasilitas lengkap dengan wahana permainan, restoran, dan toko suvenir.'
    ],
    [
        'Pantai Balekambang', 20000, 'Wisata Alam', 'Malang',
        -8.3899, 112.5249,
        'images/Malang/gallary.jpg',
        'Pantai Balekambang adalah pantai indah di Kabupaten Malang yang sering dijuluki "Tanah Lot-nya Jawa Timur". Pantai ini memiliki tiga pulau kecil di lepas pantainya, salah satunya terhubung oleh jembatan menuju Pura Ismoyo yang berdiri di atas batu karang. Ombaknya yang besar cocok untuk berselancar, sementara pasir coklatnya yang lembut ideal untuk berjemur dan bermain. Panorama sunset di pantai ini sangat memukau.'
    ],
    [
        'Museum Angkut', 100000, 'Wisata Budaya', 'Malang',
        -7.8807, 112.5235,
        'images/Malang/kampung warna warni.jpg',
        'Museum Angkut adalah museum transportasi terbesar di Asia Tenggara yang terletak di Kota Batu. Museum ini menampilkan koleksi lebih dari 300 jenis kendaraan dari berbagai era dan negara, mulai dari becak tradisional hingga mobil klasik Eropa. Setiap zona dirancang dengan setting kota-kota terkenal dunia seperti Hollywood, Buckingham Palace, dan Gangnam Korea. Pengalaman interaktif dan spot foto yang tak terhitung jumlahnya.'
    ],
    [
        'Taman Selecta', 35000, 'Wisata Alam', 'Malang',
        -7.8546, 112.5002,
        'images/Malang/gunung bromo.jpg',
        'Taman Selecta adalah taman rekreasi legendaris di Kota Batu yang telah ada sejak zaman kolonial Belanda tahun 1928. Terletak di ketinggian 1.100 mdpl, taman ini menawarkan udara sejuk pegunungan dengan taman bunga yang indah, kolam renang alami berair pegunungan, dan wahana bermain keluarga. Area Kampung Indian dan zona petualangan menambah variasi aktivitas. Pemandangan Gunung Arjuno dan Welirang menjadi latar belakang yang sempurna.'
    ],

    // =====================
    //    S U R A B A Y A
    // =====================
    [
        'Kampung Arab Surabaya', 5000, 'Wisata Kampung', 'Surabaya',
        -7.2479, 112.7385,
        'images/Surabaya/Kembang Jepun.jpg',
        'Kampung Arab Surabaya di kawasan Ampel adalah salah satu perkampungan Arab tertua di Indonesia yang telah ada sejak abad ke-15. Kawasan ini memiliki suasana khas Timur Tengah dengan gang-gang sempit, toko-toko rempah dan parfum, serta pedagang kurma dan buah kering. Pusat kawasan ini adalah Masjid Sunan Ampel yang bersejarah, salah satu masjid tertua di Jawa yang didirikan oleh Sunan Ampel, salah satu Wali Songo penyebar Islam di Nusantara.'
    ],
    [
        'Tugu Pahlawan', 10000, 'Wisata Budaya', 'Surabaya',
        -7.2458, 112.7378,
        'images/Surabaya/gallary.jpg',
        'Tugu Pahlawan adalah monumen ikonik setinggi 41.15 meter yang menjadi simbol kepahlawanan kota Surabaya. Monumen ini dibangun untuk mengenang peristiwa heroik pertempuran 10 November 1945 melawan pasukan Sekutu dan Belanda. Di bawah tugu terdapat Museum 10 Nopember yang menampilkan diorama, koleksi senjata, dan dokumentasi perjuangan arek-arek Suroboyo. Monumen ini menjadi landmark utama dan kebanggaan Kota Pahlawan.'
    ],
    [
        'Kebun Binatang Surabaya', 30000, 'Wisata Alam', 'Surabaya',
        -7.2968, 112.7366,
        'images/Surabaya/gallary.jpg',
        'Kebun Binatang Surabaya (KBS) adalah salah satu kebun binatang tertua dan terlengkap di Asia Tenggara yang berdiri sejak tahun 1916. Menempati area seluas 15 hektar, KBS memiliki koleksi lebih dari 3.500 satwa dari 351 spesies termasuk komodo, orangutan, harimau Sumatera, dan gajah. Taman ini juga memiliki area bermain anak, danau buatan, dan taman-taman teduh yang cocok untuk piknik keluarga di akhir pekan.'
    ],
    [
        'House of Sampoerna', 0, 'Wisata Budaya', 'Surabaya',
        -7.2340, 112.7373,
        'images/Surabaya/Kembang Jepun.jpg',
        'House of Sampoerna adalah museum dan galeri seni yang berlokasi di bangunan kolonial bergaya art deco yang dibangun tahun 1862. Dahulu merupakan panti asuhan dan kini menjadi museum yang menceritakan sejarah rokok kretek dan keluarga Sampoerna. Pengunjung dapat menyaksikan proses pembuatan rokok kretek secara manual oleh ratusan pekerja. Arsitektur bangunannya yang megah dan café rooftop menjadi daya tarik tersendiri.'
    ],
    [
        'Masjid Al-Akbar Surabaya', 0, 'Wisata Religi', 'Surabaya',
        -7.3290, 112.7169,
        'images/Surabaya/patung 4 budha.jpg',
        'Masjid Nasional Al-Akbar Surabaya adalah masjid terbesar kedua di Indonesia setelah Masjid Istiqlal Jakarta. Masjid megah ini memiliki kubah biru yang ikonik dan menara setinggi 99 meter yang dapat dinaiki pengunjung untuk menikmati panorama 360 derajat Kota Surabaya dari ketinggian. Arsitekturnya menggabungkan gaya modern dan tradisional Islam dengan interior yang luas mampu menampung hingga 60.000 jamaah.'
    ],
    [
        'Suramadu Bridge', 0, 'Wisata Budaya', 'Surabaya',
        -7.1833, 112.7836,
        'images/Surabaya/Kelenteng Sanggar Agung.jpg',
        'Jembatan Suramadu (Surabaya-Madura) adalah jembatan terpanjang di Indonesia dengan panjang total 5.438 meter yang menghubungkan Pulau Jawa dan Pulau Madura melintasi Selat Madura. Jembatan ini menjadi ikon infrastruktur modern Indonesia dan menawarkan pemandangan laut yang spektakuler terutama saat malam hari dengan lampu-lampu penerangan yang gemerlap. Area di sekitar kaki jembatan kini berkembang menjadi kawasan wisata kuliner seafood.'
    ],
    [
        'Hutan Mangrove Wonorejo', 25000, 'Wisata Alam', 'Surabaya',
        -7.3113, 112.8196,
        'images/Surabaya/gallary.jpg',
        'Hutan Mangrove Wonorejo adalah ekowisata konservasi hutan bakau seluas 200 hektar di pesisir timur Surabaya. Pengunjung dapat menyusuri boardwalk kayu sepanjang 2 kilometer yang meliuk-liuk di antara pohon-pohon mangrove yang rimbun. Area ini menjadi habitat bagi berbagai spesies burung, ikan, kepiting, dan satwa liar lainnya. Perahu kecil tersedia untuk menjelajah sungai-sungai kecil di dalam hutan mangrove. Spot foto dan area edukasi lingkungan juga tersedia.'
    ],

    // =====================
    //  Y O G Y A K A R T A
    // =====================
    [
        'Candi Borobudur', 50000, 'Wisata Budaya', 'Yogyakarta',
        -7.6079, 110.2038,
        'images/Yogyakarta/Candi Borobudurjpg.jpg',
        'Candi Borobudur adalah candi Buddha terbesar di dunia dan merupakan Situs Warisan Dunia UNESCO yang dibangun pada abad ke-9 oleh Dinasti Syailendra. Candi ini memiliki 2.672 panel relief dan 504 arca Buddha yang tersebar di 10 tingkat berbentuk piramida berundak. Dari puncak candi, pengunjung dapat menikmati panorama Gunung Merapi dan Merbabu yang memesona. Sunrise dari Bukit Punthuk Setumbu dengan latar Borobudur menjadi pengalaman magis.'
    ],
    [
        'Pantai Drini', 10000, 'Wisata Alam', 'Yogyakarta',
        -8.1452, 110.5698,
        'images/Yogyakarta/Pantai Drini.jpg',
        'Pantai Drini adalah pantai cantik di Gunungkidul yang unik karena memiliki pulau karang kecil di tengah pantai yang membagi area menjadi dua sisi. Sisi barat memiliki ombak besar cocok untuk berselancar, sementara sisi timur tenang seperti laguna dan aman untuk berenang. Pasir putihnya yang lembut dan air laut berwarna toska menjadi pemandangan yang mempesona. Warung-warung seafood di tepi pantai menyajikan ikan bakar segar.'
    ],
    [
        'Kraton Yogyakarta', 15000, 'Wisata Budaya', 'Yogyakarta',
        -7.8052, 110.3642,
        'images/Yogyakarta/gallary.jpg',
        'Kraton Ngayogyakarta Hadiningrat adalah istana resmi Kesultanan Yogyakarta yang masih berfungsi hingga kini sebagai kediaman sultan dan pusat kebudayaan Jawa. Kompleks istana ini memiliki arsitektur Jawa klasik yang megah dengan paviliun-paviliun (pendopo) berukir indah, museum koleksi kerajaan, dan taman sari. Pertunjukan gamelan, tari klasik, dan wayang kulit rutin digelar di dalam keraton sebagai pelestarian budaya.'
    ],
    [
        'Malioboro', 0, 'Wisata Budaya', 'Yogyakarta',
        -7.7928, 110.3658,
        'images/Yogyakarta/gallary.jpg',
        'Jalan Malioboro adalah ikon wisata belanja dan kuliner terpopuler di Yogyakarta yang membentang sepanjang 1 kilometer. Sepanjang jalan ini dipenuhi pedagang kaki lima yang menjual batik, kerajinan perak, wayang kulit, dan suvenir khas Jogja. Di malam hari, angkringan-angkringan berjejer menyajikan nasi kucing, wedang ronde, dan kopi joss legendaris. Suasana meriah dengan seniman jalanan, becak hias, dan andong menambah pesona kawasan ini.'
    ],
    [
        'Pantai Parangtritis', 10000, 'Wisata Alam', 'Yogyakarta',
        -8.0260, 110.3265,
        'images/Yogyakarta/Pantai Drini.jpg',
        'Pantai Parangtritis adalah pantai paling legendaris di Yogyakarta yang terletak sekitar 27 km selatan kota. Pantai ini terkenal dengan legenda Ratu Kidul (Ratu Pantai Selatan) dan tradisi larangan mengenakan pakaian berwarna hijau. Selain berenang dan bermain pasir, pengunjung dapat menikmati aktivitas sandboarding di gumuk pasir (parangkusumo) yang unik. Sunset di Parangtritis dengan latar tebing-tebing dramatis menjadi pemandangan yang tak terlupakan.'
    ],
    [
        'Goa Jomblang', 500000, 'Wisata Alam', 'Yogyakarta',
        -8.0432, 110.6416,
        'images/Yogyakarta/Candi Borobudurjpg.jpg',
        'Goa Jomblang adalah goa vertikal di Gunungkidul yang terkenal dengan fenomena "Cahaya Surga" (Heaven Light). Sinar matahari yang masuk melalui lubang goa menciptakan pilar cahaya keemasan yang spektakuler di dalam goa bawah tanah. Untuk mencapai dasar goa sedalam 60 meter, pengunjung harus di-rappelling menggunakan tali. Di bawah terdapat hutan purba bawah tanah dan sungai bawah tanah yang menakjubkan. Pengalaman caving yang sangat menantang dan tak terlupakan.'
    ],
    [
        'Taman Sari Water Castle', 15000, 'Wisata Budaya', 'Yogyakarta',
        -7.8100, 110.3591,
        'images/Yogyakarta/CANDI PRAMBANAN.jpg',
        'Taman Sari adalah bekas taman dan pemandian kerajaan Kesultanan Yogyakarta yang dibangun pada abad ke-18. Kompleks ini dulunya berfungsi sebagai tempat istirahat, taman meditasi, dan benteng pertahanan bagi keluarga sultan. Arsitekturnya unik karena memadukan gaya Jawa, Portugis, dan Cina. Kolam pemandian utama (umbul binangun) yang telah direstorasi menjadi spot foto populer. Di bawah tanah terdapat labirin lorong-lorong dan ruangan rahasia yang misterius.'
    ],
    [
        'Hutan Pinus Mangunan', 5000, 'Wisata Alam', 'Yogyakarta',
        -7.9344, 110.4053,
        'images/Yogyakarta/Pantai Drini.jpg',
        'Hutan Pinus Mangunan adalah kawasan wisata alam di perbukitan Dlingo, Bantul, yang menawarkan pemandangan hutan pinus yang asri dan udara segar pegunungan. Pohon-pohon pinus yang tinggi menjulang dengan lantai hutan berselimut daun kering menciptakan atmosfer romantis ala hutan Eropa. Spot-spot foto ikonik seperti platform kayu, gardu pandang, dan ayunan di ketinggian menjadi daya tarik utama. Dari sini pengunjung juga bisa menikmati panorama sunset di atas perbukitan.'
    ],
    [
        'Desa Wisata Nglanggeran', 15000, 'Wisata Kampung', 'Yogyakarta',
        -7.8566, 110.5135,
        'images/Yogyakarta/gallary.jpg',
        'Desa Wisata Nglanggeran di Gunungkidul adalah desa wisata terbaik di Asia Tenggara versi ASEAN. Desa ini menawarkan pengalaman wisata berbasis masyarakat dengan atraksi utama berupa Gunung Api Purba Nglanggeran yang berusia 60 juta tahun. Pengunjung bisa mendaki gunung batu purba, menikmati embung (danau buatan) yang indah, belajar membuat cokelat dari kakao lokal, serta menginap di homestay warga dengan suasana pedesaan Jawa yang autentik.'
    ],
];

$inserted = 0;
$skipped = 0;
$errors = 0;

foreach ($destinasi as $d) {
    list($nama, $harga, $kategori, $kota, $lat, $lng, $gambar_path, $deskripsi) = $d;

    // Cek apakah destinasi dengan nama yang sama sudah ada
    $checkStmt = $conn->prepare("SELECT id_destinasi FROM destinasi WHERE nama_destinasi = ?");
    $checkStmt->bind_param("s", $nama);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "  [SKIP] $nama (sudah ada di database)\n";
        $skipped++;
        $checkStmt->close();
        continue;
    }
    $checkStmt->close();

    // Baca gambar dari file lokal
    $gambar_data = null;
    $full_path = __DIR__ . '/' . str_replace('/', DIRECTORY_SEPARATOR, $gambar_path);
    if (file_exists($full_path)) {
        $gambar_data = file_get_contents($full_path);
    } else {
        echo "  [WARN] File gambar tidak ditemukan: $gambar_path — insert tanpa gambar\n";
    }

    $stmt = $conn->prepare("INSERT INTO destinasi 
        (nama_destinasi, harga_destinasi, kategori_destinasi, kota_destinasi, latitude, longitude, gambar_destinasi, deskripsi_destinasi) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    $null_gambar = $gambar_data;
    $stmt->bind_param(
        "sissddss",
        $nama,
        $harga,
        $kategori,
        $kota,
        $lat,
        $lng,
        $null_gambar,
        $deskripsi
    );

    if ($stmt->execute()) {
        $newId = $conn->insert_id;
        echo "  [OK]   #$newId — $nama ($kota, $kategori) — Rp " . number_format($harga, 0, ',', '.') . "\n";
        $inserted++;
    } else {
        echo "  [ERR]  $nama — " . $stmt->error . "\n";
        $errors++;
    }
    $stmt->close();
}

echo "\n=== SELESAI ===\n";
echo "Ditambahkan: $inserted destinasi baru\n";
echo "Dilewati:    $skipped (sudah ada)\n";
echo "Error:       $errors\n";

// Tampilkan total destinasi
$totalResult = $conn->query("SELECT COUNT(*) as total FROM destinasi");
$total = $totalResult->fetch_assoc()['total'];
echo "\nTotal destinasi di database: $total\n";

// Tampilkan ringkasan per kota
echo "\nRingkasan per kota:\n";
$cityResult = $conn->query("SELECT kota_destinasi, COUNT(*) as jumlah FROM destinasi GROUP BY kota_destinasi ORDER BY jumlah DESC");
while ($row = $cityResult->fetch_assoc()) {
    echo "  {$row['kota_destinasi']}: {$row['jumlah']} destinasi\n";
}

// Tampilkan ringkasan per kategori
echo "\nRingkasan per kategori:\n";
$catResult = $conn->query("SELECT kategori_destinasi, COUNT(*) as jumlah FROM destinasi GROUP BY kategori_destinasi ORDER BY jumlah DESC");
while ($row = $catResult->fetch_assoc()) {
    echo "  {$row['kategori_destinasi']}: {$row['jumlah']} destinasi\n";
}

$conn->close();
?>
