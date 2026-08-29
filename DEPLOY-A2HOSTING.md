# 🚀 دليل نشر NORA WORLD على A2 Hosting

## المتطلبات
- ✅ استضافة A2 Hosting (عندك)
- ✅ PHP 8.3 أو أعلى ( activates من cPanel → Select PHP Version)
- ✅ قاعدة بيانات MySQL (مجانية مع الاستضافة)
- ✅ Domain مربوط بالاستضافة

---

## الخطوة 1: إعداد PHP

1. ادخل **cPanel** → **Select PHP Version**
2. اختار **PHP 8.3** أو **8.4**
3. فعّل هال.extensions:
   - ✅ pdo_mysql
   - ✅ mbstring
   - ✅ exif
   - ✅ pcntl
   - ✅ bcmath
   - ✅ gd
   - ✅ zip
   - ✅ intl
   - ✅ opcache
   - ✅ fileinfo
   - ✅ ctype
4. اضغط **Apply**

---

## الخطوة 2: إنشاء قاعدة البيانات

1. ادخل **cPanel** → **MySQL Databases**
2. أنشئ قاعدة بيانات جديدة: `noraworld_db`
3. أنشئ مستخدم جديد: `noraworld_user`
4. أضف المستخدم لقاعدة البيانات مع **All Privileges**
5. احفظ اسم قاعدة البيانات وكلمة المرور

---

## الخطوة 3: رفع الملفات

### الطريقة الأولى: SSH (مضبوطة)

افتح Terminal وشغّل:

```bash
# 1. ادخل على الاستضافة
ssh your-username@your-domain.com

# 2. اذهب لمجلد public_html
cd public_html

# 3. حذف الملفات القديمة (إذا فيه شي)
rm -rf *

# 4. ارجع للمجلد الأعلى
cd ..

# 5. نزّل الملفات
# (من جهازك المحلي شغّل):
```

**من جهازك المحلي:**

```bash
cd /path/to/nora-world

# اضغط المشروع
zip -r noraworld.zip . -x "vendor/*" "node_modules/*" ".git/*" ".env"

# ارفع للשרת عبر SCP
scp noraworld.zip your-username@your-domain.com:~/

# ادخل على ال服务器
ssh your-username@your-domain.com

# فك الضغط
cd ~
unzip noraworld.zip -d public_html

# ادخل المجلد
cd public_html
```

### الطريقة الثانية: File Manager في cPanel

1. ادخل **cPanel** → **File Manager**
2. اذهب لمجلد `public_html`
3. اضغط **Upload** → اختر ملف ZIP
4. بعد الرفع → اضغط **Extract**

---

## الخطوة 4: تثبيت المكتبات

```bash
cd ~/public_html

# تثبيت Composer (إذا مو موجود)
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# تثبيت مكتبات Laravel
composer install --no-dev --optimize-autoloader
```

---

## الخطوة 5: إعداد البيئة

```bash
cd ~/public_html

# نسخ ملف البيئة
cp .env.production .env

# عدّل الإعدادات
nano .env
```

**غيّر هالقيم في `.env`:**
```
APP_URL=https://your-domain.com

DB_DATABASE=noraworld_db
DB_USERNAME=noraworld_user
DB_PASSWORD=YOUR_PASSWORD_HERE
```

---

## الخطوة 6: إعداد Laravel

```bash
cd ~/public_html

# توليد APP_KEY
php artisan key:force

# تشغيل Migration
php artisan migrate --force

# إضافة بيانات تجريبية (اختياري)
php artisan db:seed --force

# تنظيف الكاش
php artisan config:cache
php artisan route:cache
php artisan view:cache

# إنشاء رابط Storage
php artisan storage:link
```

---

## الخطوة 7: إعداد الصلاحيات

```bash
cd ~/public_html

chmod -R 775 storage bootstrap/cache
chmod -R 775 public/storage
chmod 644 .env
chmod 644 .htaccess
```

---

## الخطوة 8: إعداد .htaccess

تأكد إن `.htaccess` موجود في `public_html`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

و`.htaccess` في `public_html/public/`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L]

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## ⚡ اختصار سريع

إذا عندك SSH، شغّل هالأوامر كلها دفعة وحدة:

```bash
ssh your-username@your-domain.com

cd ~/public_html

# رفع وتفكيك (من جهازك)
# zip -r noraworld.zip . -x "vendor/*" "node_modules/*" ".git/*"
# scp noraworld.zip server:~/

unzip ~/noraworld.zip -d .
composer install --no-dev --optimize-autoloader
cp .env.production .env
# nano .env  # عدّل DB credentials
php artisan key:force
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

## 🔧 حل المشاكل

### الخطأ 500
```bash
chmod -R 775 storage bootstrap/cache
tail -f storage/logs/laravel.log
```

### خطأ قاعدة البيانات
```bash
php artisan migrate --force
# تأكد من إعدادات DB في .env
```

### الخطأ "No application encryption key"
```bash
php artisan key:force
```

### الصفحة بيضاء
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

## ✅ التحقق من النجاح

```bash
# تحقق من إصدار PHP
php -v

# تحقق من Laravel
php artisan --version

# تحقق من الاتصال بقاعدة البيانات
php artisan db:monitor

# تحقق من الموقع
curl -I https://your-domain.com
```

**رابط الموقع النهائي:** `https://your-domain.com`

🎉 **مبروك! NORA WORLD شغّال على استضافتك المدفوعة!**
