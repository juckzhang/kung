#!/bin/bash
# 添加web用户
useradd -ms /bin/bash nobody
groupadd nobody

# 更新源
apt-get update

# 创建/data1目录
mkdir /data1
# 编译安装openresty
apt-get install -y libpcre3-dev libssl-dev perl make build-essential curl
cd /opt && wget https://openresty.org/download/openresty-1.15.8.1.tar.gz && tar -zxvf openresty-1.15.8.1.tar.gz
cd openresty-1.15.8.1
./configure --prefix=/data1/openresty
make && make install

# 安装php
apt-get install -y php7.0 php7.0-cli php7.0-curl php7.0-gd php7.0-json php7.0-mysql php7.0-odbc php7.0-opcache php7.0-bz2 php7.0-mbstring php7.0-mcrypt php7.0-zip php7.0-fpm

# 修改目录权限
mkdir -p /data1/openresty/htdocs
mkdir /data1/openresty/logs && chmod 0777 /data1/openresty/logs

# 下载项目代码
cd /data1/openresty/htdocs
git clone https://github.com/juckzhang/kung.git kung

# 创建文件上传目录
mkdir -p kung/frontend/web/upload/media-poster
mkdir -p kung/frontend/web/upload/media-pdf

# 添加项目入口文件
cp -f kung/frontend/web/index kung/frontend/web/index.php
cp -f kung/backend/web/index kung/backend/web/index.php
cp -f kung/console/yii kung/console/yii.php

# 拷贝配置文件
cp -f kung/conf/nginx.conf /data1/openresty/nginx/conf/nginx.conf
cp -f kung/conf/php.ini /etc/php/7.0/fpm/php.ini
cp -f kung/conf/php-fpm.conf /etc/php/7.0/fpm/php-fpm.conf
cp -f kung/conf/php-fpm.d/www.conf /etc/php/7.0/fpm/pool.d/www.conf

# 修改目录所属人
chown -R nobody.nobody kung

# 启动服务
`which php-fpm7.0`
/data1/openresty/nginx/sbin/nginx

# 删除文件
rm -rf /opt/openresty-1.13.6.2*