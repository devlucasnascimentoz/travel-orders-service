#!/bin/bash
# Patch para LogManager.php
sed -i "s/trim(\$this->app\['config'\]/trim(\$this->app\['config'\] ?? '')/g" vendor/laravel/framework/src/Illuminate/Log/LogManager.php

# Patch para LoadConfiguration.php
sed -i "s/\$app->get('config')->all()/(\$app->bound('config') ? \$app->get('config')->all() : [])/g" vendor/laravel/framework/src/Illuminate/Foundation/LoadConfiguration.php

# Garante permiss?es
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
