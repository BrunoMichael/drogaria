
# Sistema de Gerenciamento de Drogaria

Este sistema foi desenvolvido em **PHP** utilizando o framework **Laravel** juntamente com o **Filament**, cumprindo todos os requisitos definidos na prova técnica.

> Obs: Durante o desenvolvimento, cheguei a tentar instalar o Delphi para realizar a comunicação com a API desenvolvida em Laravel, mas optei por não seguir com essa abordagem a fim de não comprometer o prazo de entrega.

---

## 🔧 Requisitos Técnicos

- PHP 8.2+
- Composer
- Laravel 10+
- Filament Admin Panel
- MySQL

---

## 🚀 Instalação do Ambiente no Windows

### 1. Baixar o PHP

Faça o download do PHP 8.2 para Windows:

🔗 [Download PHP 8.2.28 - VS16 x64](https://windows.php.net/downloads/releases/php-8.2.28-Win32-vs16-x64.zip)

Extraia o conteúdo em um diretório, por exemplo:

```
C:\server\php-8.2.28
```

---

### 2. Adicionar PHP ao PATH do Sistema

1. No menu Iniciar, procure por **"Editar variáveis de ambiente do sistema"** e abra.
2. Clique em **"Variáveis de Ambiente"**.
3. Em "Variáveis do sistema", selecione **Path** e clique em **Editar**.
4. Clique em **Novo** e adicione:

```
C:\server\php-8.2.28
```

5. Confirme todas as janelas com **OK**.

---

### 3. Verificar a Instalação do PHP

Abra o terminal (cmd ou Git Bash) e execute:

```bash
php -v
```

Você deverá ver a versão do PHP instalada.

---

### 4. Instalar o MySQL Manualmente

Baixe o instalador do MySQL no site oficial:

🔗 [Download MySQL](https://dev.mysql.com/downloads/installer/)

Durante a instalação:

- Escolha a opção "Developer Default" ou "Server only".
- Configure uma senha para o usuário root.
- Finalize a instalação.

---

## 📦 Criar Projeto Laravel com Composer

Execute no terminal:

```bash
composer create-project --prefer-dist laravel/laravel drogaria
cd drogaria
```

---

## 🧱 Comandos Essenciais do Laravel

### Criar um Model com Migration
```bash
php artisan make:model Modelo -m
```

### Criar usuário Filament (para acesso ao painel admin)
```bash
php artisan make:filament-user
```

### Iniciar o Servidor Laravel
```bash
php artisan serve
```

---

## 💡 Iniciar Servidor Manualmente com PHP

Caso prefira não usar o `php artisan serve`, execute:

```bash
php -S localhost:8080 -t C:\server\www
```

---

## 📘 Observações Finais

- O painel administrativo foi criado com **Filament**, facilitando a gestão dos dados da aplicação.
- A API foi construída utilizando boas práticas REST.
- A estrutura está preparada para escalabilidade e integração com outras ferramentas.

---

## 📂 Estrutura Recomendada de Diretórios

```
C:\server\
├── php-8.2.28\
├── www\
│   └── drogaria\
```

---

Desenvolvido com dedicação para a avaliação técnica. Qualquer dúvida ou sugestão, fico à disposição!
