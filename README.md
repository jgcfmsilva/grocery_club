Grocery Club 🛒
Grocery Club é uma mercearia online desenvolvida com Laravel, Blade e Livewire.
A aplicação oferece uma experiência moderna e dinâmica, com envio de e-mails assíncrono e cache de produtos utilizando Redis.
Tudo é executado dentro de containers Docker com Laravel Sail.

⚙️ Tecnologias Utilizadas
Laravel – Backend PHP

Blade – Sistema de templates

Livewire – Componentes dinâmicos e reativos

Tailwind CSS – Estilização moderna (via Vite)

Redis – Cache e filas

Docker + Laravel Sail – Ambiente isolado

MySQL – Base de dados

🚀 Requisitos
Docker e Docker Compose instalados

Node.js (16+)

PHP 8.1+ (caso não use Sail)

Composer

NPM (para frontend com Tailwind)

🛠️ Instalação e Configuração do Projeto
Clone o repositório
git clone https://github.com/seu-usuario/grocery-club.git
cd grocery-club

Instalar dependências PHP
composer install

Instalar dependências JavaScript
npm install

Subir os containers Docker com Sail
./vendor/bin/sail up -d

Executar as migrações
./vendor/bin/sail artisan migrate:fresh

Popular o banco de dados com dados iniciais
./vendor/bin/sail artisan db:seed

Criar link simbólico do storage
./vendor/bin/sail artisan storage:link

Compilar os assets com Tailwind CSS
npm run dev

💌 Fila de E-mails (Queue Server)
O sistema de envio de e-mails está implementado com filas, usando Redis como driver.

✅ Como iniciar o processador de filas:
./vendor/bin/sail artisan queue:work --queue=emails

Esse comando mantém o worker ativo processando os e-mails em background.

🧠 Cache com Redis
O cache dos produtos e outras operações é feito utilizando Redis.

✅ Como testar se a cache está funcionando:
Abrir o Tinker:
./vendor/bin/sail artisan tinker

No shell interativo, digitar:
cache()->put('teste_redis', 'ok', 60); cache()->get('teste_redis');

Se o Redis estiver corretamente configurado, o retorno será:
=> "ok"
