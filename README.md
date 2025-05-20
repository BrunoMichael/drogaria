# Sistema de Gerenciamento de Drogaria

Este sistema foi desenvolvido em **PHP** utilizando o framework **Laravel** juntamente com o **Filament**, atendendo todos os requisitos definidos na prova técnica.

> Obs: Durante o desenvolvimento, tentei até instalar o Delphi para integrar com a API criada no Laravel, mas optei por não seguir com isso para evitar comprometer o prazo de entrega.

---

## 🔧 Requisitos Técnicos

- PHP 8.2+
- Composer
- Laravel 10+
- Filament Admin Panel

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

1. No menu Iniciar, digite **"Editar variáveis de ambiente do sistema"** e abra.
2. Clique em **"Variáveis de Ambiente"**.
3. Na seção "Variáveis do sistema", selecione a variável **Path** e clique em **Editar**.
4. Clique em **Novo** e adicione:

```
C:\server\php-8.2.28
```

5. Confirme todas as janelas com **OK**.

---

### 3. Verificar PHP

Abra o terminal (cmd ou Git Bash) e digite:

```bash
php -v
```

Se tudo estiver certo, verá a versão do PHP instalada.

---

## 📦 Criar Projeto Laravel com Composer

```bash
composer create-project --prefer-dist laravel/laravel drogaria
cd drogaria
```

---

## 🧱 Comandos Essenciais do Laravel

### Criar Model com Migration
```bash
php artisan make:model Modelo -m
```

### Iniciar servidor Laravel
```bash
php artisan serve
```

### Criar usuário Filament Admin
```bash
php artisan make:filament-user
```

---

## 💡 Servidor Manual com PHP (caso não use `artisan serve`)

```bash
php -S localhost:8080 -t C:\server\www
```

---

## 📘 Observações Finais

- O painel administrativo foi implementado com **Filament**, permitindo uma gestão completa e responsiva da aplicação.
- A API foi criada e documentada utilizando boas práticas REST.
- O projeto está pronto para ser estendido ou integrado com outras tecnologias conforme a necessidade.

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