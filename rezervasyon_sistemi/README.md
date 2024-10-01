Laboratuvar Randevu Sistemi

Bu proje,  Laboratuvar için tasarlanmış bir cihaz rezervasyon sistemidir. Sistem iki tür kullanıcıya sahiptir: normal kullanıcılar ve yöneticiler.
Kullanıcı Türleri
Yönetici (Admin)

- Kullanıcı ekleyebilir veya silebilir.
- Diğer kullanıcılara yönetici ya da kullanıcı yetkisi verebilir.
- Cihaz durumunu yönetebilir (aktif/pasif).
- Rezervasyonları onaylayabilir veya reddedebilir.
- Takvime erişebilir ve rezervasyonları görüntüleyebilir.
- Yöneticiler için özel olarak rezervasyon yapabilir.

Kullanıcı

- Sisteme giriş yapabilir.
- Cihaz detaylarını görüntüleyebilir.
- Rezervasyon oluşturabilir, takvime erişebilir.
- Kendi rezervasyonlarını yönetebilir (görüntüle, sil).
- Profil bilgilerini güncelleyebilir.

Önemli Dosyalar ve Fonksiyonlar

- **add_user.php**: Kullanıcı ekler.
- **delete_user.php**: Kullanıcı siler.
- **admin_dashboard.php**: Yönetici ana sayfası.
- **admin_reservations.php**: Rezervasyon yönetimi.
- **admin_rez.php**: Yöneticilerin rezervasyon yapmasını sağlar.
- **admin_takvim.php**: Takvime erişim ve rezervasyon tarihlerini görüntüleme.
- **admin_users.php**: Kullanıcıları yönetir ve yetki atar.
- **calendar.php**: Rezervasyon oluşturur.
- **devices.php**: Cihaz detaylarını görüntüler.
- **make_reservation.php**: Hem kullanıcılar hem de yöneticiler için rezervasyon isteklerini gönderir.
- **profile.php**: Kullanıcı profil bilgilerini yönetir.
- **reservation.php**: Kullanıcı rezervasyonlarına erişim sağlar.
- **takvim.php**: Takvime erişim sağlar.
- **test_db.php**: Veritabanı bağlantı testini yapar.
- **user.php**: Kullanıcı ana sayfası.
- **index.php**: Giriş sayfası, kullanıcı kaydı ve şifre sıfırlama.
- **login.php**: Kullanıcı girişi.
- **register.php**: Kullanıcı kaydı.
- **verification.php**: Kullanıcı hesap doğrulama için token oluşturur.
- **logout.php**: Kullanıcının sistemden çıkış yapmasını sağlar.
- **config.php**: Veritabanı bağlantısı.
- **forgot_password.php**: Şifre sıfırlama işlevi.
- **PHPMailer**: E-posta işlemleri için kullanılan kütüphane.

E-posta İşlemleri

Sistem, şifre sıfırlama, hesap doğrulama, rezervasyon onayı ve reddi gibi işlemler için e-postalar gönderir. Bu görevler için PHPMailer kütüphanesi kullanılır.

Dizin Yapısı

```
.
├── add_user.php
├── admin_dashboard.php
├── admin_reservations.php
├── admin_rez.php
├── admin_takvim.php
├── admin_users.php
├── calendar.php
├── ccip photos
│   ├── fat_et.jpg
│   ├── table_1.jpg
│   └── ...
├── config.php
├── devices.php
├── en
│   ├── add_user.php
│   └── ...
├── forgot_password.php
├── index.php
├── login.php
├── logout.php
├── make_reservation.php
├── PHPMailer
│   ├── LICENSE
│   └── ...
├── profile.php
├── README.md
├── register.php
├── reservations.php
├── scripts.js
├── styles.css
├── takvim.php
├── test_db.php
├── user.php
└── verification.php
```

Bu proje Emir Esad Şahin tarafından yapılmıştır.