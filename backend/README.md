# Backend - Laravel 10 + MySQL

## Tecnologias Utilizadas
- Laravel 10
- MySQL
- Composer para dependências

## Como rodar o projeto

### 1. Clone o repositório git bash
- git clone https://github.com/Larissa-Tavares/enterprise-management.git
- cd enterprise-management

### 2. Configurar o backend (Laravel)
### Entre na pasta do backend
- cd backend

### Instale as dependências do Laravel:
composer install

### Configure o .env

### Rode as migrations e seeders para criar tabelas e popular dados iniciais:
php artisan migrate --seed

### Inicie o servidor Laravel:
php artisan serve

### O backend estará disponível em: http://127.0.0.1:8000