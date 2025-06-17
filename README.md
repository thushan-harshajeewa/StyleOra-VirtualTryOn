<h1 align="center">
  👗✨ StyleOra - Virtual Try-On E-Commerce Website ✨🛍️
</h1>

<p align="center">
  <img src="https://media.giphy.com/media/WoWm8YzFQJg5i/giphy.gif" width="200"/>
</p>

<p align="center">
  <b>A cutting-edge AI-powered fashion experience that lets users try clothes on virtually before purchasing. 🧠🛒</b>
</p>

<p align="center">
  <a href="https://github.com/thushan-harshajeewa/StyleOra-VirtualTryOn">
    <img alt="Stars" src="https://img.shields.io/github/stars/thushan-harshajeewa/StyleOra-VirtualTryOn?style=for-the-badge" />
  </a>
  <a href="https://github.com/thushan-harshajeewa/StyleOra-VirtualTryOn/issues">
    <img alt="Issues" src="https://img.shields.io/github/issues/thushan-harshajeewa/StyleOra-VirtualTryOn?style=for-the-badge" />
  </a>
  <img alt="Repo Size" src="https://img.shields.io/github/repo-size/thushan-harshajeewa/StyleOra-VirtualTryOn?style=for-the-badge" />
</p>

---

## 🌐 Project Overview

**StyleOra** is an AI-powered virtual try-on e-commerce platform where users can:

- Upload their own image 👤📷  
- Select clothes from the catalog 👗🧥  
- Virtually try them on with realistic AI fitting powered by deep learning 🤖  
- Shop like a traditional clothing e-commerce platform 🛍️💳  

---

## 🏗️ Tech Stack

| Layer            | Technology                                                                 |
|------------------|----------------------------------------------------------------------------|
| 🎨 Frontend      | [React](https://reactjs.org/) + [TypeScript](https://www.typescriptlang.org/) |
| ⚙️ Main Backend  | [Laravel](https://laravel.com/) – Full-featured e-commerce backend          |
| 🧠 AI Backend    | [Flask](https://flask.palletsprojects.com/) – Virtual Try-On model API      |
| 🔐 Admin Panel   | [Django](https://www.djangoproject.com/) – Admin dashboard using built-in admin |
| 🗄️ Database      | [MySQL](https://www.mysql.com/) – Shared database for Laravel & Django       |

---

## 🧪 Try-On Results

Below are some examples of how our virtual try-on system works:

| 👤 Person Image | 👚 Clothing Image | 🧠 Try-On Output |
|----------------|------------------|------------------|
| ![person](https://via.placeholder.com/150x200?text=Person) | ![cloth](https://via.placeholder.com/150x200?text=Clothing) | ![result](https://via.placeholder.com/150x200?text=Result) |
| ![person2](https://via.placeholder.com/150x200?text=Person+2) | ![cloth2](https://via.placeholder.com/150x200?text=Clothing+2) | ![result2](https://via.placeholder.com/150x200?text=Result+2) |
| ![person3](https://via.placeholder.com/150x200?text=Person+3) | ![cloth3](https://via.placeholder.com/150x200?text=Clothing+3) | ![result3](https://via.placeholder.com/150x200?text=Result+3) |

> 📝 You can update these image links with your real image results from the `flask-backend` AI output.

---

## 🗂️ Project Structure

```
StyleOra-VirtualTryOn/
│
├── frontend/              # React + TypeScript frontend
│
├── laravel-backend/       # Main backend for e-commerce logic
│
├── flask-backend/         # AI model backend
│
└── django-admin/          # Admin dashboard (Django)
```

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/thushan-harshajeewa/StyleOra-VirtualTryOn.git
cd StyleOra-VirtualTryOn
```

---

### 2. Run Each Component

#### ➤ Frontend (React + TypeScript)

```bash
cd frontend
npm install
npm start
```

#### ➤ Laravel Backend

```bash
cd laravel-backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

✅ Ensure MySQL is running and `.env` has the correct DB config.

#### ➤ Flask Backend (AI Try-On API)

```bash
cd flask-backend
pip install -r requirements.txt
python app.py
```

#### ➤ Django Admin Panel

```bash
cd django-admin
pip install -r requirements.txt
python manage.py migrate
python manage.py createsuperuser
python manage.py runserver
```

---

## 🛠️ Features

✅ Upload user photo  
✅ Browse and choose clothes  
✅ Real-time AI virtual try-on  
✅ Add to cart, checkout, payment simulation  
✅ Admin dashboard to manage products, users, orders  

---

## 📸 Demo (Coming Soon!)

🎥 A full walkthrough and live demo will be available soon!

---

## 📌 Contribution Guidelines

1. Fork this repo 🍴  
2. Create a new branch 🌿  
3. Commit your changes 💾  
4. Push to the branch 🚀  
5. Create a Pull Request 📥

---

## 👤 Author

> **Thushan Harshajeewa Liyanage**  
> 🧑‍💻 GitHub: [@thushan-harshajeewa](https://github.com/thushan-harshajeewa)  
> 📧 Email: [Your Email Here]  

---

## ⭐️ Show Your Support

If you like this project, please give it a star ⭐️ on  
👉 [GitHub](https://github.com/thushan-harshajeewa/StyleOra-VirtualTryOn)  
Follow for more awesome projects! 🚀

<p align="center">
  <img src="https://media.giphy.com/media/3o7aD2saalBwwftBIY/giphy.gif" width="300"/>
</p>
---

## 🛠️ Features

- ✅ Secure user authentication (email/password)
- ✅ Google Sign-In integration 🔐
- ✅ Email verification system 📧
- ✅ Upload user photo for virtual try-on 👤
- ✅ Browse and choose clothes from catalog 👗
- ✅ Filter products by:
  - Size 📏
  - Gender 🚻
  - Brand 🏷️
  - Category 📂
  - Color 🎨
  - Price 💰
- ✅ Real-time AI virtual try-on using uploaded image 🧠
- ✅ Add to Cart 🛒
- ✅ Update Cart (quantity, remove item) 🔄
- ✅ Place Order & Simulate Payment 💳
- ✅ Order history and tracking 📦
- ✅ Responsive UI across all devices 📱💻
- ✅ Admin dashboard to manage:
  - Users 👥
  - Products 🧾
  - Orders 📋
  - Categories 🗃️

---

## 🖼️ UI Screenshots

> Below are some screenshots representing key features of StyleOra.

| 🖥️ Page | 🖼️ UI Screenshot |
|--------|------------------|
| 🔐 Login Page | ![login](https://via.placeholder.com/300x200?text=Login+Page) |
| 📧 Email Verification | ![email](https://via.placeholder.com/300x200?text=Email+Verification) |
| 🛍️ Product Catalog | ![catalog](https://via.placeholder.com/300x200?text=Product+Catalog) |
| 🎨 Product Filters | ![filters](https://via.placeholder.com/300x200?text=Filters) |
| 🧠 Try-On Interface | ![tryon](https://via.placeholder.com/300x200?text=Virtual+Try-On) |
| 🛒 Cart Page | ![cart](https://via.placeholder.com/300x200?text=Cart+Page) |
| 💳 Payment Simulation | ![payment](https://via.placeholder.com/300x200?text=Payment) |
| 🧑‍💼 Admin Dashboard | ![admin](https://via.placeholder.com/300x200?text=Admin+Dashboard) |

> 📝 Replace the placeholder images with your actual UI screenshots for full effect.

