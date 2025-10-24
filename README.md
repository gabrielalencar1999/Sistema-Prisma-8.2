# Prisma App

>Sistema de gestão empresarial

## Ambiente local com Docker

Este projeto utiliza Docker Compose e roda com PHP 8.2 (Apache), MySQL 8 e Nginx como proxy.

### Requisitos
- Windows 10/11, macOS ou Linux
- Docker Desktop (ou Docker Engine + Docker Compose v2)

### Serviços (docker-compose.yml)
- mysql: MySQL 8.0 (scripts iniciais em `docker/mysql/data`)
- phpmyadmin: opcional (porta 7070)
- web: Apache + PHP 8.2 servindo `./src`
- nginx: proxy HTTP para o `web` (expõe a porta 8080)

## Primeira execução
1) Crie um arquivo `.env` na raiz com os valores mínimos:
```env
APP_NAME=prisma
MYSQL_ROOT_PASSWORD=troque_esta_senha
DB_NAME=prisma
DB_USER=root
DB_PASSWORD=${MYSQL_ROOT_PASSWORD}
DB_PORT=3306
TOKEN=defina_um_token
```

2) Suba os serviços (na raiz do projeto):
```bash
docker compose up -d --build
```

3) Acesse:
- Aplicação: http://localhost:8080
- phpMyAdmin (opcional): http://localhost:7070
  - Host: mysql | Usuário: root | Senha: (MYSQL_ROOT_PASSWORD)

## Desenvolvimento
- Código-fonte: `./src` é montado no container `web` (as alterações refletem sem rebuild)
- Composer: instalado no container `web` (scripts de entrypoint cuidam do install/dumpautoload na pasta `src/api`)
- Logs: use `docker compose logs -f nginx web` para acompanhar

## Comandos úteis
- Subir/atualizar:
```bash
docker compose up -d --build
```
- Parar:
```bash
docker compose stop
```
- Derrubar tudo:
```bash
docker compose down
```
- Status:
```bash
docker compose ps
```
- Logs em tempo real:
```bash
docker compose logs -f nginx web
```
- Entrar no container `web`:
```bash
docker compose exec web sh
```

## Banco de dados
- Conexão interna (aplicação): `DB_HOST=mysql`, `DB_PORT=3306`
- Scripts de inicialização: `docker/mysql/data` (executados automaticamente no primeiro start do MySQL)
- Acesso via phpMyAdmin: http://localhost:7070

## Solução de problemas
- Sem resposta no navegador (ERR_EMPTY_RESPONSE):
  - Verifique o Nginx e teste o upstream:
    ```bash
    docker compose ps
    docker compose logs --tail=100 nginx
    docker compose exec nginx sh -lc 'getent hosts web && curl -sv http://web/ | head'
    ```
- Apache interno não responde:
  - Teste dentro do `web`:
    ```bash
    docker compose exec web sh -lc 'apachectl -t && curl -sv http://127.0.0.1/ | head'
    ```
- Porta em uso:
  - Edite as portas em `docker-compose.yml` (ex.: `8080:80` do Nginx ou `7070:80` do phpMyAdmin) e suba novamente

## URL local (opcional)
Windows (Prompt como Administrador):
```cmd
echo 127.0.0.1  prisma.local >> C:\Windows\System32\drivers\etc\hosts
```
Acesse: http://prisma.local:8080