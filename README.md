# 🛒 Grocery Club

**Grocery Club** é uma mercearia online desenvolvida com **Laravel**, **Blade** e **Livewire**, oferecendo uma experiência moderna, responsiva e dinâmica. A aplicação utiliza **Redis** para cache e envio de e-mails assíncronos, tudo executado dentro de containers **Docker** com **Laravel Sail**.

---

## ⚙️ Tecnologias Utilizadas

- **Laravel** – Backend robusto em PHP
- **Blade** – Sistema de templates server-side
- **Livewire** – Componentes dinâmicos e reativos
- **Tailwind CSS** – Estilização moderna com utilitários (via Vite)
- **Redis** – Cache e filas assíncronas
- **Docker + Laravel Sail** – Ambiente isolado e portátil
- **MySQL** – Base de dados relacional

---

## 🚀 Requisitos

Certifique-se de ter os seguintes requisitos instalados antes de iniciar:

- [Docker](https://www.docker.com/) e Docker Compose
- [Node.js](https://nodejs.org/) (versão 16 ou superior)
- [PHP 8.1+](https://www.php.net/) (caso não utilize Laravel Sail)
- [Composer](https://getcomposer.org/)
- [NPM](https://www.npmjs.com/) (para o frontend com Tailwind CSS)

---

## 🛠️ Instalação e Configuração

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/grocery-club.git
   cd grocery-club

2. **Instalar dependêncas PHP**
   ```bash
  composer install

3. **Instalar dependêncas JavaScript**
   ```bash
 npm install

 4. **Correr com os containers**
   ```bash
 ./vendor/bin/sail up -d

5. **Criar as tabelas**
   ```bash
 ./vendor/bin/sail artisan migrate:fresh

6. **Inserir dados**
   ```bash
 ./vendor/bin/sail artisan db:seed

7. **Link do storage**
   ```bash
 ./vendor/bin/sail artisan storage:link

8. **Tailwind**
   ```bash
 npm run dev

💌 Envio de E-mails Assíncronos

O sistema de envio de e-mails utiliza filas com Redis como driver.

▶️ Como iniciar o processador de filas:
   ```bash
./vendor/bin/sail artisan queue:work --queue=emails


🧠 Cache com Redis

Operações de cache (como listagem de produtos) são otimizadas com Redis.

✅ Teste rápido da cache:
	1.	Acesse o Tinker:
     ```bash
./vendor/bin/sail artisan tinker

    2.	Digite:
   ```bash
cache()->put('teste_redis', 'ok', 60);
cache()->get('teste_redis');

	3.	Resultado esperado:
=> "ok"



  

   

   
