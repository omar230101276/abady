# Abady Photography Portfolio & Session Booking Platform

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red.svg?logo=laravel)](https://laravel.com)
[![Tailwind CSS Version](https://img.shields.io/badge/Tailwind_CSS-v4.0-blue.svg?logo=tailwindcss)](https://tailwindcss.com)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D_8.2-777bb4.svg?logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

**Abady** is a premium, modern photography portfolio and client booking management system designed for professional photographers. It features a fully-dynamic showcase frontend, a self-service client booking portal, and a powerful, responsive administration panel.

---

## 🌟 Key Features

### 📸 Portfolio & Client-Facing Interface
*   **Dynamic Landing Page:** Features categorized highlights, recent work showcase, client testimonials, and a featured video reel.
*   **Media Galleries:** Responsive photo albums powered by Cloudinary and video player grids.
*   **Interactive Booking System:** Self-service calendar and timeslot booking system.
*   **Client Booking Portal:** Allows clients to look up, track, update details, or cancel their bookings using a secure reference ID and token verification.
*   **Brand & Collaborations:** Showcase partnerships, brands, and case studies.

### 🛡️ Admin Management Panel (Dashboard)
*   **Overview Stats:** Displays key metrics (total bookings, pending messages, system health).
*   **Calendar & Schedule View:** Integrated visual calendar for tracking upcoming shoots.
*   **Booking Management:** Update booking statuses (Pending, Confirmed, Completed, Cancelled) and edit appointment details.
*   **Availability Control:** Easily block dates for holidays or vacations, and configure operational time slots.
*   **Cloudinary Album Manager:** Upload high-resolution photos directly to Cloudinary and organize them into custom albums.
*   **Video & Collaboration Manager:** Easily manage videography feeds and partner brand assets.
*   **Global App Settings:** Toggle business hours, contact info, and portfolio settings.

---

## 🛠️ Technology Stack

*   **Backend:** [Laravel 12.x](https://laravel.com/) (PHP >= 8.2)
*   **Frontend Styling:** [Tailwind CSS v4.0](https://tailwindcss.com/) & Alpine.js
*   **Asset Bundler:** [Vite](https://vite.dev/)
*   **Database:** SQLite (local development), PostgreSQL (production/Neon)
*   **Media Hosting:** [Cloudinary API](https://cloudinary.com/) (for cloud image storage and optimization)

---

## 🚀 Local Development Setup

Follow these steps to run the project locally on your machine.

### Prerequisites
*   **PHP:** Version `8.2` or higher (with PDO, SQLite, BCMath, and cURL extensions enabled)
*   **Composer:** For dependency management
*   **Node.js & NPM:** For building frontend assets
*   **Git**

### Installation

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/omar230101276/abady.git
    cd abady
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

3.  **Install NPM Dependencies:**
    ```bash
    npm install
    ```

4.  **Environment Setup:**
    Duplicate the environment template file:
    ```bash
    copy .env.example .env
    ```
    Open `.env` and configure your credentials (especially database and Cloudinary integration).

5.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

6.  **Run Database Migrations & Seeders:**
    ```bash
    php artisan migrate --seed
    ```

7.  **Build Frontend Assets:**
    *   For Development (with hot reloading):
        ```bash
        npm run dev
        ```
    *   For Production:
        ```bash
        npm run build
        ```

### Running the App
You can use the built-in starter scripts:
*   **On Windows (PowerShell):** Run `.\start-local.ps1`
*   **On Windows (Command Prompt):** Run `start-local.bat`
*   **Alternatively (Manual):** Run `php artisan serve` in one terminal and `npm run dev` in another.

The website will be available at `http://127.0.0.1:8000` and the Admin login at `http://127.0.0.1:8000/admin/login`.

---

## ☁️ Production Deployment

This project is configured for cloud deployment on **Render.com** utilizing **Neon Serverless Postgres** and **Cloudinary**.

For step-by-step instructions on setting up environments, Docker configurations, SSL modes, and databases, refer to the [Render Deployment Guide](file:///e:/System/Xampp/htdocs/Abady/render_deployment_guide.md).

---

## 📄 License

The Abady Portfolio Platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
