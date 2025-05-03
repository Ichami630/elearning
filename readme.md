# 📄 Summary of .htaccess

This `.htaccess` file is used in the `/backend/` folder.
It handles:

## 1. ✅ CORS Headers (Cross-Origin Resource Sharing)
- Allows your frontend (React app) to communicate with the PHP backend via `fetch` or `axios`.
- Enables `GET`, `POST`, and `OPTIONS` methods.
- Accepts requests from **any origin** (`*`), useful for development or APIs open to multiple frontends.

## 2. 🔒 Hiding `.php` Extensions
- Allows accessing `login` instead of `login.php` in URLs.

## 📚 App.js Overview – eLearning Platform Routing

This file sets up the routing structure for your React-based eLearning platform, using **React Router v6**
