php-multisite-router
====================

Just a simple multi-site router written in PHP - was just a quick throw-together project to help someone in ##php on freenode


Note:  you'l need to configure your hosting location to redirect all requests via index.php.  An example using an old .htaccess method is here:


# BEGIN osBlast
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_URI} "/site/images/"
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END osBlast


Simply save these lines in your public_html (or www root directory) as ".htaccess".

If you do use this method, you may need to enable mod_rewrite on your apache server.

Regards,
Anthony
