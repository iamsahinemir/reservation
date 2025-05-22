# 🧪 Laboratory Reservation System | Laboratuvar Randevu Sistemi

This project is a device reservation system designed for laboratory environments. The system supports two types of users: **Administrators** and **Regular Users**.

Bu proje, laboratuvar ortamı için tasarlanmış bir cihaz rezervasyon sistemidir. Sistem, iki tür kullanıcıyı destekler: **Yöneticiler** ve **Normal Kullanıcılar**.

---

## 👤 User Types | Kullanıcı Türleri

### 🔐 Administrator | Yönetici

* Add or remove users

* Grant or revoke admin privileges

* Manage device status (active/passive)

* Approve or reject reservations

* Access calendar and view reservations

* Create reservations on behalf of others

* Kullanıcı ekleyebilir veya silebilir

* Diğer kullanıcılara yönetici ya da kullanıcı yetkisi verebilir

* Cihaz durumunu yönetebilir (aktif/pasif)

* Rezervasyonları onaylayabilir veya reddedebilir

* Takvime erişebilir ve rezervasyonları görüntüleyebilir

* Yöneticiler için özel olarak rezervasyon yapabilir

---

### 👥 User | Kullanıcı

* Log into the system

* View device details

* Create reservations and access the calendar

* Manage their own reservations (view/delete)

* Update profile information

* Sisteme giriş yapabilir

* Cihaz detaylarını görüntüleyebilir

* Rezervasyon oluşturabilir, takvime erişebilir

* Kendi rezervasyonlarını yönetebilir (görüntüle, sil)

* Profil bilgilerini güncelleyebilir

---

## 📂 Key Files & Functionalities | Önemli Dosyalar ve Fonksiyonlar

| File                     | Description (English)               | Açıklama (Türkçe)                                     |
| ------------------------ | ----------------------------------- | ----------------------------------------------------- |
| `add_user.php`           | Adds new users                      | Yeni kullanıcı ekler                                  |
| `delete_user.php`        | Deletes existing users              | Mevcut kullanıcıyı siler                              |
| `admin_dashboard.php`    | Admin main dashboard                | Yönetici ana sayfası                                  |
| `admin_reservations.php` | Manage reservations                 | Rezervasyon yönetimi                                  |
| `admin_rez.php`          | Admin-specific reservation feature  | Yöneticiler için rezervasyon yapma                    |
| `admin_takvim.php`       | Admin calendar view                 | Takvim erişimi ve rezervasyon tarihlerini görüntüleme |
| `admin_users.php`        | Manage user roles and permissions   | Kullanıcıları yönetme ve yetki atama                  |
| `calendar.php`           | Reservation creation interface      | Rezervasyon oluşturur                                 |
| `devices.php`            | Displays device details             | Cihaz detaylarını görüntüler                          |
| `make_reservation.php`   | Submit reservation requests         | Rezervasyon isteklerini gönderir                      |
| `profile.php`            | Manage user profile                 | Kullanıcı profilini yönetir                           |
| `reservation.php`        | Access user reservations            | Kullanıcının rezervasyonlarına erişim                 |
| `takvim.php`             | General calendar access             | Takvim erişimi sağlar                                 |
| `test_db.php`            | Database connection test            | Veritabanı bağlantısını test eder                     |
| `user.php`               | Regular user dashboard              | Kullanıcı ana sayfası                                 |
| `index.php`              | Login, register, and reset password | Giriş, kayıt ve şifre sıfırlama sayfası               |
| `login.php`              | Handles user login                  | Kullanıcı girişi                                      |
| `register.php`           | User registration                   | Yeni kullanıcı kaydı                                  |
| `verification.php`       | Email token verification            | Hesap doğrulama token’ı üretir                        |
| `logout.php`             | Logout functionality                | Kullanıcının çıkış yapmasını sağlar                   |
| `config.php`             | Database configuration              | Veritabanı bağlantı ayarları                          |
| `forgot_password.php`    | Reset password function             | Şifre sıfırlama işlemi                                |
| `PHPMailer/`             | Email handling library              | E-posta işlemleri için kullanılan kütüphane           |

---

## 📧 Email Integration | E-posta İşlemleri

The system sends emails for password resets, account verification, and reservation confirmations. This is handled using the **PHPMailer** library.

Sistem, şifre sıfırlama, hesap doğrulama ve rezervasyon onayı gibi işlemler için e-posta gönderir. Bu işlemler **PHPMailer** kütüphanesi ile yapılır.

---

## 📁 Directory Structure | Dizin Yapısı

```bash
.
├── add_user.php
├── admin_dashboard.php
├── admin_reservations.php
├── admin_rez.php
├── admin_takvim.php
├── admin_users.php
├── calendar.php
├── ccip photos/
├── config.php
├── devices.php
├── en/
├── forgot_password.php
├── index.php
├── login.php
├── logout.php
├── make_reservation.php
├── PHPMailer/
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

---

## 👨‍💻 Developed By | Geliştirici

**Emir Esad Şahin**
*This project was developed as part of an institutional laboratory automation solution.*

---


