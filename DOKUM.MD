1. 27 April 2025
    - Buat Register
    - Buat Login
    - Buat index.php
    - Buat config database.php
    (Notes :    
    1. kedepannya bikin eror flash message di login/register)
    ![alt text](image-1.png)
    ![alt text](image-2.png)

2. 28 April 2025
    - Buat Dashboard Admin
    - Ngubah Routing / index.php
    (Notes :    
    1. buat style_admin.css karna biar css nya ga kepanjangan/jadi gaperlu capek scroll nantinya
    2. buat header_admin.php karna nntnya bakal beda menuju kaya css sama js dan kawan kawan
    3. sementara pake vanilla css dlu, untuk icon disini make font awesome
    4. itu hamburger/sidebar sementara gitu dlu aja kayanya, jd di layar gede jg defaultnya ketutup
    5. ngubah routing yg tadinya make if-else jadi switch-case karna biar enak aja jadi gaperlu ngecek kondisi 1 1)
    ![alt text](image.png)

3. 29 April 2025
    - Buat Daftar Kos di Admin
    - Buat Page Edit Kamar
    - Bikin file Sidebar (Tadinya cuma ada di Dashboard)
    (Notes :
    1. tombol tambah kamar belom berfungsi, lagi malas
    2. lupa apalagi)
    ![alt text](image-3.png)
    ![alt text](image-4.png)

4. 30 April 2025
    - Intinya Page Admin Sisa Verif Pengguna dah
    (Notes :
    1. Maap gada gambar, males ss
    2. Teks di status sewa kaya "Selesai" atau "Sewa" nantinya bakal di stylein
    3. Status pembayaran di history pembayaran juga)

5. 1 Mei 2025
    - Page verif kelar
    - Done styling di pembayaran, keluhan
    - Nambah kolom 'Status' di Feedback/keluhan
    (Notes :
    1. blm ada fitur kirim ke email saat verif
    2. blm responsive)

6. 20 Mei 2025 
    - Silahkan liat di commit aja ya teman2 (malas)
    - Intinya fitur yang belum ada itu :
        1. Cek Akun
        2. Cek dan Perpanjang Sewa Penyewa
        3. Fitur Kirim Email
        4. Edit data penyewa, admin (kaya email, nama, no telp, dll)
        5. Responsive (baru beberapa aja)
        6. Di tampilan home blm ada kontak, fasilitas, sama alamat
        7. Fitur tambah akun admin lewat akun super admin


7. 31 Mei 2025 
    - Silahkan liat di commit aja ya
    Notes :
    1. ada fitur cekDanUpdateSewaSelesai auto, jadi dia bakal ngecek si tanggal selesai nya itu hari ini atau bukan, kalau hari ini dia bakal ngubah status2 yg ada,
    2. fitur tsb sementara ditaro di authcontroller (jadi sehabis si adminnya login jd ke run itu fitur), kalo mau auto run nantinya pas hosting pake cron job (jadi itu program bakal ke run setiap jam 1 malem misalnya), mirip kaya task scheduler lah