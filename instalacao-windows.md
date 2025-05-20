## 1. Instalar o PHP

Baixe a versão 8.2 do PHP neste link:

```bash
https://windows.php.net/downloads/releases/php-8.2.28-Win32-vs16-x64.zip
```

Extraia o conteúdo do arquivo ZIP para o diretório:

```bash
C:\server\php-8.2.28
```

### Adicionando o PHP ao PATH do sistema

- Abra as **Editar as variáveis de ambiente do sistema** do Windows.
- No bloco **Variáveis de usuário**, selecione a variável `Path` e clique em **Editar**.
- Clique em **Novo** e adicione o caminho abaixo:

```bash
C:\server\php-8.2.28
```

### Verificando a instalação

Abra o **Prompt de Comando (CMD)** e execute:

```bash
php -v
```

Se tudo estiver correto, você verá uma saída semelhante a esta:

```bash
PHP 8.2.28 (cli) (built: Mar 11 2025 18:37:30) (ZTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.2.28, Copyright (c) Zend Technologies
```

Claro! Aqui está uma versão aprimorada da seção **"2. Instalar o MySQL"**, com texto mais claro, organizado e fluido:

---

## 2. Instalar o MySQL

> **Observação:** Se você já tem o MySQL instalado, pode pular esta etapa.

### Passo 1 – Baixar o MySQL

Acesse o link abaixo para baixar o MySQL 5.5:

```bash
https://dev.mysql.com/downloads/mysql/5.5.html?os=3&version=5
```

* Selecione a opção **Windows (x86, 64-bit), ZIP Archive**
* Crie a pasta:

```bash
C:\server\mysql
```

* Extraia o conteúdo do arquivo ZIP para essa pasta.

### Passo 2 – Criar diretórios necessários

Crie as seguintes subpastas:

```bash
C:\server\mysql\data
C:\server\mysql\logs
```

### Passo 3 – Criar o arquivo de configuração

Crie um arquivo chamado `my.ini` dentro de `C:\server\mysql\`, com o seguinte conteúdo:

```ini
[mysqld]
basedir=C:/server/mysql
datadir=C:/server/mysql/data
port=3306
sql_mode=NO_ENGINE_SUBSTITUTION,STRICT_TRANS_TABLES
```

---

### Inicializando e registrando o MySQL

#### Inicializar o banco de dados (sem senha)

Abra o **PowerShell como administrador** e execute:

```bash
cd C:\server\mysql\bin
.\mysqld --initialize-insecure
```

#### Registrar o MySQL como serviço do Windows

Ainda no PowerShell:

```bash
.\mysqld --install MySQL --defaults-file="C:\server\mysql\my.ini"
```

> **Resultado esperado:** Serviço registrado com sucesso.

#### Iniciar o serviço do MySQL

```bash
net start MySQL
```

> **Resultado esperado:** O serviço MySQL foi iniciado com êxito.

Claro! Aqui está uma versão melhorada das seções **3. Instalar o PhpMyAdmin** e **4. Instalar o Composer**, com uma linguagem mais clara e padronizada:

## 3. Instalar o PhpMyAdmin

Baixe a versão mais recente do PhpMyAdmin 5.2.2:

```bash
https://files.phpmyadmin.net/phpMyAdmin/5.2.2/phpMyAdmin-5.2.2-all-languages.zip
```

### Passos:

- Crie a pasta:

```bash
C:\server\www\phpmyadmin
```

- Extraia o conteúdo do arquivo ZIP para essa pasta.

> PhpMyAdmin estará acessível via navegador após configurar um servidor web (como Apache ou Nginx apontando para `C:\server\www`).

## 4. Instalar o Composer

Baixe o instalador oficial do Composer:

```bash
https://getcomposer.org/download/
```

### Durante a instalação:

* Quando solicitado, selecione o executável do PHP:

```bash
C:\server\php-8.2.28\php.exe
```

> Após a instalação, abra o **CMD** e execute `composer -V` para verificar se está funcionando corretamente.

Claro! Aqui está a seção **5. Configurar o `php.ini`** revisada, com linguagem mais clara, estrutura mais limpa e instruções diretas:

---

## 5. Configurar o `php.ini`

### Passo 1 – Localizar o arquivo de configuração

Acesse o diretório:

```bash
C:\server\php-8.2.28\
```

Se o arquivo `php.ini` **não existir**, renomeie um dos modelos disponíveis:

* `php.ini-development` → `php.ini`
  **ou**
* `php.ini-production` → `php.ini`

> A versão *development* é recomendada para ambientes de desenvolvimento.

---

### Passo 2 – Configurar o diretório de extensões

Abra o arquivo `php.ini` em um editor de texto e localize a linha:

```ini
extension_dir = "ext"
```

Altere para:

```ini
extension_dir = "C:\server\php-8.2.28\ext"
```

---

### Passo 3 – Habilitar extensões essenciais

Ainda no `php.ini`, descomente (remova o `;` do início) as seguintes linhas:

```ini
extension=curl
extension=ffi
extension=ftp
extension=fileinfo
extension=gd
extension=gettext
extension=gmp
extension=intl
extension=imap
extension=mbstring
extension=exif      ; Must be after mbstring as it depends on it
extension=mysqli
; extension=oci8_12c  ; Use with Oracle Database 12c Instant Client
; extension=oci8_19  ; Use with Oracle Database 19 Instant Client
extension=odbc
extension=openssl
; extension=pdo_firebird
extension=pdo_mysql
; extension=pdo_oci
extension=pdo_odbc
extension=pdo_pgsql
extension=pdo_sqlite
extension=pgsql
extension=shmop
```
> Extensões como `oci8` ou `pdo_oci` podem ser ativadas se você for usar Oracle.

## 6. Iniciar o servidor manualmente

Abra o **CMD** e execute:

```bash
cd C:\server\php-8.2.28
php.exe -S localhost:8000 -t C:\server\www
```

O servidor estará disponível em:

```
http://localhost:8000
```

Acesse o PhpMyAdmin:

```
http://localhost:8000/phpmyadmin/index.php
```

## 7. Corrigir erro "AllowNoPassword" no PhpMyAdmin

Caso ocorra erro ao tentar logar sem senha no PhpMyAdmin:

- Abra o **PowerShell como administrador**.
- Acesse o MySQL:

```bash
C:\server\mysql\bin\mysql.exe -u root
```

- Execute os comandos abaixo para definir uma senha:

```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'admin123+';
FLUSH PRIVILEGES;
EXIT;
```

- Reinicie o serviço do MySQL:

```bash
net stop MySQL
net start MySQL
```

- Agora você poderá acessar com as credenciais:

* **Usuário:** `root`
* **Senha:** `admin123+`

#### 🧪 Pronto! Ambiente Windows configurado para rodar projetos Laravel com PHP 8.2, MySQL e PhpMyAdmin.