# 🛒 Grocery Club

**Grocery Club** é uma mercearia online desenvolvida com Laravel, Blade e Livewire, oferecendo uma experiência moderna, responsiva e dinâmica. A aplicação utiliza Redis para cache e envio de e-mails assíncronos, tudo executado dentro de containers Docker com Laravel Sail.

---

✨ O projeto combina tecnologias modernas para criar uma plataforma simples, leve e escalável:

Laravel como backend em PHP  
Blade como sistema de templates  
Livewire para componentes dinâmicos e reativos  
Tailwind CSS para estilização moderna (via Vite)  
Redis como mecanismo de cache e filas assíncronas  
Docker + Laravel Sail para ambiente isolado  
MySQL como base de dados relacional

---

## ⚙️ Requisitos

Antes de começares, garante que tens o seguinte instalado:  
🐳 Docker e Docker Compose  
🟢 Node.js (versão 16 ou superior)  
🐘 PHP 8.1+ (caso não uses Sail)  
📦 Composer  
📁 NPM (para compilar o frontend com Tailwind)

---

## 🚀 Instalação e Configuração

Clona o repositório:  
git clone https://github.com/seu-usuario/grocery-club.git  
cd grocery-club

Instala as dependências PHP:  
composer install

Instala as dependências JavaScript:  
npm install

Sobe os containers com Laravel Sail:  
./vendor/bin/sail up -d

Cria as tabelas da base de dados:  
./vendor/bin/sail artisan migrate:fresh

Popula a base de dados com dados de exemplo:  
./vendor/bin/sail artisan db:seed

Cria o link simbólico do diretório de armazenamento:  
./vendor/bin/sail artisan storage:link

Compila os assets do frontend com Tailwind CSS:  
npm run dev

---

## 💌 Envio de E-mails Assíncronos

O sistema usa filas com Redis para processar os e-mails em background.  
Para iniciar o worker da fila de e-mails:  
./vendor/bin/sail artisan queue:work --queue=emails

---

## 🧠 Testar Cache com Redis

Para garantir que o Redis está a funcionar corretamente:  
Abre o Tinker com:  
./vendor/bin/sail artisan tinker

Depois digita:  
cache()->put('teste_redis', 'ok', 60);  
cache()->get('teste_redis');

Se tudo estiver certo, o retorno será:  
"ok"

---

## 📄 Licença

Este projeto é open source e está licenciado sob a MIT License.  
Desenvolvido com ❤️ usando Laravel, Redis, Docker e boas práticas modernas.

---

Se precisares de ajuda adicional ou quiseres contribuir, abre uma issue ou faz um pull request. 🚀  
Obrigado por usares o Grocery Club! 🙌  
#codeWithCare 💻
