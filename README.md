<div id="top"></div>

# ドキュメント

## 目次

1. [使用技術一覧](#使用技術一覧)
2. [概要](#概要)
3. [使用言語](#使用言語)
4. [公開環境](#公開環境)
5. [想定local環境](#想定local環境)
6. [準備](#準備)

## 使用技術一覧

<!-- シールド一覧 -->
<!-- 該当するプロジェクトの中から任意のものを選ぶ-->
<p style="display: inline">
  <!-- フロントエンドのフレームワーク一覧 -->
  <img src="https://img.shields.io/badge/-Bootstrap-563D7C.svg?logo=bootstrap&style=for-the-badge">
  <!-- バックエンドのフレームワーク一覧 -->
  <img src="https://img.shields.io/badge/-Laravel-E74430.svg?logo=django&style=for-the-badge">
  <!-- フロントエンドの言語一覧 -->
  <img src="https://img.shields.io/badge/html5-E34F26?logo=html5&logoColor=fff&style=for-the-badge">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=000&style=for-the-badge">
  <img src="https://img.shields.io/badge/CSS-639?logo=css&logoColor=fff&style=for-the-badge">
  <!-- バックエンドの言語一覧 -->
  <img src="https://img.shields.io/badge/-Php-777BB4.svg?logo=python&style=for-the-badge">
  <!-- DB一覧 -->
  <img src="https://img.shields.io/badge/-Mysql-4479A1.svg?logo=python&style=for-the-badge">
  <!-- インフラ一覧 -->
  <img src="https://img.shields.io/badge/-Amazon%20aws-232F3E.svg?logo=python&style=for-the-badge">

</p>

<!-- プロジェクトについて -->
## 概要
- 「garden-switch-enduser-app」は、(株)ガーデンがサービス展開している「Switch」のエンドユーザ向けアプリケーション
- 本アプリケーションは、APIを経由してホールの稼働に関するデータをユーザに提供する
- /admin, /userとユーザの導線は2種類存在する

### 特徴
- APIとの連携が必須(今後定期的に更新していく)

<!-- 言語、フレームワークのバージョンを記載 -->
## 使用言語
| 言語・フレームワーク | バージョン |
| ----------| ---------- |
| php       | 8.4.7      |
| Laravel   | 12.26.4    |
| Bootstrap | 5.3.2      |
| MySQL     | 8.0.31     |

その他のパッケージのバージョンはcomposer.json を参照

<!-- インフラの一覧 -->
## 公開環境
| 項目  | 内容 |
| --------------------- | ---------- |
| インフラ_アプリケーション  | AWS(EC2) |
| インフラ_アプリケーション_OS | Ubuntu |
| インフラ_データベース  | AWS(RDS)    |

## 想定local環境
| 項目  | 内容 |
| --------------------- | ---------- |
| 動作確認済みPC | MacBook Pro 16GB Apple M2 Max (メモリ:64GB)|
| 動作確認済みOS | Sequoia 15.5 |

<!-- 準備 -->
## 準備
- [timedatectlの設定]以下のコマンドを実行(timedatectlを実行して、LocalTime及びTimeZoneがJSTになっていればOK)
```
sudo timedatectl set-timezone Asia/Tokyo
```
- [DB初期設定]migrate実行
```
php artisan migrate
```
- [管理者(admin)初期設定]
```
php artisan command:create-admin
```
<br>
<br>

# 以下、Laravelのライセンス情報

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
