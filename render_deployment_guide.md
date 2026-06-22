# Deployment Guide: Render.com & Neon Postgres Database

This guide explains how to deploy your Laravel Photographer Portfolio application to **Render.com** using **Neon Serverless Postgres** as the database.

---

## Prerequisites

Before starting, ensure you have:
1. A **GitHub** account (where your repository is pushed).
2. A **Neon** account ([neon.tech](https://neon.tech/)) for Postgres.
3. A **Render** account ([render.com](https://render.com/)).
4. Your **Cloudinary** credentials (since uploads are stored on Cloudinary).

---

## Step 1: Create a Neon Database

1. Log in to [Neon Console](https://console.neon.tech/).
2. Create a new project (e.g., `abady-db`).
3. Choose the **PostgreSQL version** (v15 or v16 is recommended).
4. Select a region close to your primary audience.
5. Once created, copy the **Connection string** from your Dashboard. It will look like this:
   ```text
   postgres://username:password@ep-some-host.region.aws.neon.tech/neondb?sslmode=require
   ```
6. Extract the individual connection parameters for later:
   - **Host:** `ep-some-host.region.aws.neon.tech`
   - **Database:** `neondb`
   - **Username:** `username`
   - **Password:** `password`
   - **Port:** `5432`

---

## Step 2: Add Docker Files to Your Codebase

We have already created the following deployment files in your repository root:
*   [Dockerfile](file:///o:/System/Xampp/htdocs/Abady/Dockerfile) — Standard multi-stage build that compiles CSS/JS assets using Node, installs Composer dependencies, and serves the application via an optimized Nginx/PHP-FPM image.
*   [.dockerignore](file:///o:/System/Xampp/htdocs/Abady/.dockerignore) — Optimizes build speed by excluding local caches and dev dependencies.

Commit and push these files to GitHub:
```bash
git add Dockerfile .dockerignore
git commit -m "Add Docker files for Render deployment"
git push origin main
```

---

## Step 3: Deploy on Render.com

1. Go to your [Render Dashboard](https://dashboard.render.com/) and click **New +** -> **Web Service**.
2. Connect your GitHub repository (`omar230101276/abady`).
3. Configure the service settings:
   *   **Name:** `abady` (or any custom name)
   *   **Region:** Select the same region as your Neon database.
   *   **Branch:** `main`
   *   **Runtime:** **Docker**
   *   **Instance Type:** **Free** (or Starter for production traffic)
4. Scroll down to **Advanced** and expand the settings.

### Configure the Release Command
To automatically run migrations before each deploy, configure the **Release Command**:
```bash
php artisan migrate --force
```

---

## Step 4: Configure Environment Variables on Render

In the Web Service settings under **Environment**, add the following keys:

| Environment Variable | Recommended Value | Description |
| :--- | :--- | :--- |
| **`APP_NAME`** | `Abady` | The name of your application. |
| **`APP_ENV`** | `production` | Sets the application to production mode. |
| **`APP_DEBUG`** | `false` | Disables debug mode for security. |
| **`APP_KEY`** | *Paste your generated key* | Run `php artisan key:generate --show` locally to copy it. |
| **`APP_URL`** | `https://your-app-name.onrender.com` | Your public Render web service URL. |
| **`DB_CONNECTION`** | `pgsql` | Configures Laravel to connect via Postgres. |
| **`DB_HOST`** | *Your Neon host* | Example: `ep-some-host.region.aws.neon.tech` |
| **`DB_PORT`** | `5432` | Standard Postgres port. |
| **`DB_DATABASE`** | `neondb` (or custom name) | Your Neon database name. |
| **`DB_USERNAME`** | *Your Neon username* | |
| **`DB_PASSWORD`** | *Your Neon password* | |
| **`DB_SSLMODE`** | `require` | **Crucial:** Neon requires SSL connections. |
| **`CLOUDINARY_URL`** | *Your Cloudinary API URL* | Cloud URL for uploading photos/videos. |

---

## Step 5: Deploy & Monitor

Click **Create Web Service**. Render will:
1. Pull your code from GitHub.
2. Build the Docker container (running NPM and Composer).
3. Run the **Release Command** to run migrations on your Neon database.
4. Spin up the server.

Once the logs show `SUCCESS` and status is **Live**, open your public website link!
