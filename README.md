<h2 align="center">
    <a href="https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin">
    🎓 Faculty of Information Technology (DaiNam University)
    </a>
</h2>
<h2 align="center">
    Open Source Software Development
</h2>
<div align="center">
    <p align="center">
        <img alt="AIoTLab Logo" width="170" src="https://github.com/user-attachments/assets/711a2cd8-7eb4-4dae-9d90-12c0a0a208a2" />
        <img alt="AIoTLab Logo" width="180" src="https://github.com/user-attachments/assets/dc2ef2b8-9a70-4cfa-9b4b-f6c2f25f1660" />
        <img alt="DaiNam University Logo" width="200" src="https://github.com/user-attachments/assets/77fe0fd1-2e55-4032-be3c-b1a705a1b574" />
    </p>

[![AIoTLab](https://img.shields.io/badge/AIoTLab-green?style=for-the-badge)](https://www.facebook.com/DNUAIoTLab)
[![Faculty of Information Technology](https://img.shields.io/badge/Faculty%20of%20Information%20Technology-blue?style=for-the-badge)](https://dainam.edu.vn/vi/khoa-cong-nghe-thong-tin)
[![DaiNam University](https://img.shields.io/badge/DaiNam%20University-orange?style=for-the-badge)](https://dainam.edu.vn)

</div>

---
## 1. Giới thiệu hệ thống
Hệ thống Quản lý Đặt vé Xem phim được xây dựng nhằm hỗ trợ người dùng dễ dàng đặt vé trực tuyến, tra cứu lịch chiếu và quản lý vé đã đặt.  
Đối với quản trị viên, hệ thống cung cấp chức năng quản lý phim, suất chiếu, phòng chiếu và theo dõi doanh thu.  

**Các chức năng chính:**
- Người dùng:
  - Đăng ký / đăng nhập
  - Xem danh sách phim, lịch chiếu
  - Đặt vé xem phim, xem lại vé đã đặt
- Quản trị viên:
  - Quản lý phim
  - Quản lý lịch chiếu, phòng chiếu
  - Quản lý vé và doanh thu

---

<div align="center">

### Ngôn ngữ & Công nghệ chính
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![HTML](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Glossary/HTML5)
[![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

### Cơ sở dữ liệu
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)

### Môi trường chạy
[![XAMPP](https://img.shields.io/badge/XAMPP-F37623?style=for-the-badge&logo=xampp&logoColor=white)](https://www.apachefriends.org/index.html)

</div>

---

## 3. Hình ảnh các chức năng
--- Chức năng đăng nhập ---
<img width="1920" height="919" alt="image" src="https://github.com/user-attachments/assets/624c1ca9-52e0-4f97-b634-3cb68b79d5f1" />
--- Giao diện trang chủ ---
<img width="1910" height="921" alt="image" src="https://github.com/user-attachments/assets/c020691e-cca0-47d2-be13-f6c8449e675b" />
--- Giao diện trang quản lý ---
<img width="1920" height="923" alt="image" src="https://github.com/user-attachments/assets/e7554fab-9f6b-4150-8586-359cea51f592" />
--- Quản lý phim ---
<img width="1920" height="921" alt="image" src="https://github.com/user-attachments/assets/27eefab0-d3a4-4d85-89c0-38508244bc92" />
--- Quản lý rạp chiếu ---
<img width="1920" height="435" alt="image" src="https://github.com/user-attachments/assets/0de63dce-8d75-4207-9918-6d8c3cf1d933" />
--- Quản lý đặt vé ---
<img width="1397" height="693" alt="image" src="https://github.com/user-attachments/assets/ffb80c7b-e0de-40f8-85da-8beaff2ecc72" />


## 🚀 4. Các project đã thực hiện dựa trên Platform

Một số project sinh viên đã thực hiện:
- #### [Khoá 15](./docs/projects/K15/README.md)
- #### [Khoá 16]() (Coming soon)
## 5. Các bước cài đặt
1. **Cài đặt XAMPP**  
   - Tải và cài XAMPP: [https://www.apachefriends.org](https://www.apachefriends.org)  
   - Khởi động Apache và MySQL trong XAMPP Control Panel.  

2. **Tạo cơ sở dữ liệu**  
   - Mở [http://localhost/phpmyadmin](http://localhost/phpmyadmin)  
   - Tạo database mới, ví dụ: `movie_booking`  
   - Import file `db_movie.sql` trong thư mục dự án vào database vừa tạo.  

3. **Copy source code vào thư mục htdocs**  
   - Giải nén project vào:  
     ```
     C:\xampp\htdocs\project_movie_booking
     ```  

4. **Cấu hình kết nối database**  
   - Mở file `includes/db_connect.php` (hoặc file cấu hình kết nối)  
   - Chỉnh sửa thông tin nếu cần:
     ```php
     $servername = "localhost";
     $username   = "root";
     $password   = "";
     $dbname     = "movie_booking";
     ```

5. **Chạy hệ thống**  
   - Mở trình duyệt và truy cập:  
     ```
     http://localhost/project_movie_booking
     ```  

6. **Tài khoản mẫu** (nếu có)  
   - Admin: `admin / admin123`  
   - User: `user / 123456`  

---


📌 *Lưu ý: Có thể tùy chỉnh tên database, tài khoản admin, giao diện theo nhu cầu.*
