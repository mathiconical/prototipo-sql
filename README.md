<h2 align="center">Protótipo de Sistema de Atividades para SQL</h2>

## Instalação

A aplicação está em Laravel e usa PHP 8.1, para iniciar você pode usar um AMPP ou usar o docker do MySQL.

#### Instalando container do MySQL

```bash
docker build -t analuiza .
docker run -p 3306:3306 --name analuiza -e MYSQL_ROOT_PASSWORD=root -d analuiza
```

#### Iniciando servidor via Artisan

```php
php artisan serve --port=8080
```

#### Iniciando servidor de dev do Vite

```javascript node
npm run dev
```

## License

Esse projeto está sob a licensa MIT. [MIT license](https://opensource.org/licenses/MIT).
