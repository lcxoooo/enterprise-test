# MicroService Lumen Framework
(lumen + dingo + repository)

## ʹ��

```
$ git clone
$ php composer.phar install
$ cp .env.example .env
$ vim .env
        DB_*
            ��д���ݿ�������� your database configuration
        APP_KEY
            lumen ȡ����key:generate ��������Ҹ��ط�����һ�°�
            md5(uniqid())��str_random(32) ֮��ģ�������jwt:secret��������copyһ��
$ php artisan migrate
$ �����ĵ� apidoc -i App/Http/Controller/Api/v1 -o public/apidoc
$ api�ĵ���public/apidoc����, Ҳ���Կ������ `����api�ĵ�`

```