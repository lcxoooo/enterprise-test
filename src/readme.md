# MicroService Lumen Framework
(lumen + dingo + repository)

## 使用

```
$ git clone
$ php composer.phar install
$ cp .env.example .env
$ vim .env
        DB_*
            填写数据库相关配置 your database configuration
        APP_KEY
            lumen 取消了key:generate 所以随便找个地方生成一下吧
            md5(uniqid())，str_random(32) 之类的，或者用jwt:secret生成两个copy一下
$ php artisan migrate
$ 生成文档 apidoc -i App/Http/Controller/Api/v1 -o public/apidoc
$ api文档在public/apidoc里面, 也可以看上面的 `在线api文档`

```