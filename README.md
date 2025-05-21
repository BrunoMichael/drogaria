# 💊 Drogaria - Sistema de Gestão com Laravel + Filament

Este sistema foi desenvolvido em PHP utilizando o framework Laravel e o painel administrativo Filament. Ele atende aos requisitos propostos na prova técnica.

> ⚠️ Durante o desenvolvimento, considerei utilizar Delphi para comunicação com a API, mas optei por manter tudo em Laravel para garantir agilidade e cumprimento do prazo.

---

## 📋 Requisitos Técnicos

* PHP 8.2+
* Composer
* Laravel 12
* MySQL
* PhpMyAdmin
* Filament Admin Panel

---

## 💻 Instalação no Windows

Você pode seguir o guia completo de instalação do ambiente no Windows:

👉 [https://github.com/BrunoMichael/drogaria/blob/main/instalacao-windows.md](https://github.com/BrunoMichael/drogaria/blob/main/instalacao-windows.md)

---

## 🚀 Como Rodar o Projeto

**1.** Clone o repositório:
  
    git clone https://github.com/BrunoMichael/drogaria.git
    cd drogaria
   
**2.** Instale as dependências do PHP:

    composer install

**3.** Copie o arquivo de ambiente e gere a key:

    cp .env.example .env
    php artisan key:generate

**4.** Configure o banco de dados no arquivo `.env`.

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=drogaria
    DB_USERNAME=root
    DB_PASSWORD=admin123+

> ⚠️ **Atenção:** Não use aspas no valor da senha, a menos que ela contenha espaços. Aspas desnecessárias podem causar falha na conexão.

Certifique-se de que o MySQL esteja rodando e que o banco de dados especificado (`drogaria`) exista. Você pode criar o banco manualmente ou usar uma migration depois de instalar as dependências.

**5.** Rode as migrations e seeders (se houver):

- Migrations:

    php artisan migrate

- Seeders:

    php artisan db:seed

**6.** Inicie o servidor local:

    php artisan serve

---

## 👤 Criar Usuário Administrador

Para acessar o painel Filament, crie um usuário via terminal:

```bash
php artisan make:filament-user
```

---

## 📘 Documentação da API (Swagger)

A documentação da API REST está disponível localmente após iniciar o servidor:

📎 [http://127.0.0.1:8000/api/documentation](http://127.0.0.1:8000/api/documentation)

---

## 🗂️ Estrutura de Código

Abaixo estão algumas pastas importantes:

* app/Filament → Painel administrativo
* app/Http/Controllers/Api → Controllers da API
* app/Models → Models do sistema
