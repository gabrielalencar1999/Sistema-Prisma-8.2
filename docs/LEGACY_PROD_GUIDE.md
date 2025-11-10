# Guia de Produção – Aplicação Legada (Prisma)

Este documento resume as ações necessárias para rodar o legado em produção (HostGator/WHM), os principais comandos e pontos de atenção.

## 1) Servidor e Docker
- Instalar Docker/Compose (AlmaLinux 9):
```bash
dnf -y install yum-utils
dnf config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
dnf -y install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
systemctl enable --now docker
```
- Ajuste do daemon:
```bash
cat >/etc/docker/daemon.json <<'EOF'
{
  "exec-opts": ["native.cgroupdriver=systemd"],
  "log-driver": "json-file",
  "log-opts": { "max-size": "10m", "max-file": "3" },
  "storage-driver": "overlay2"
}
EOF
systemctl restart docker
```

## 2) Código e variáveis de ambiente
- Clone do repo em `/opt/prisma-app/prod`.
- `.env` em produção fica FORA do Git. Sugestões:
  - Adicionar `.env` a `.git/info/exclude` no servidor.
  - Ou marcar como `skip-worktree` se estiver indexado.
- Exemplo de `.env` (produção):
```env
APP_NAME=prisma-legacy
DB_HOST=host.docker.internal
DB_PORT=3306
DB_NAME=bd_prisma
DB_USER=admsistemaprisma
DB_PASSWORD=********
TOKEN=
```

## 3) Build e logs
- Crie o diretório de logs no volume montado antes de subir:
```bash
mkdir -p /opt/prisma-app/prod/src/logs
```
- Subir stack:
```bash
cd /opt/prisma-app/prod
docker compose up -d --build
```

## 4) Proxy Apache (WHM) e SSL
- O domínio raiz serve o Prisma; Laravel fica sob `/app`.
- No include SSL de `gestor.sistemaprisma.com.br` (ordem importa):
```
ProxyPreserveHost On
RequestHeader set X-Forwarded-Proto "https"

ProxyPass /app/ http://127.0.0.1:8082/
ProxyPassReverse /app/ http://127.0.0.1:8082/
ProxyPassReverseCookiePath / /app/

ProxyPass / http://127.0.0.1:8080/
ProxyPassReverse / http://127.0.0.1:8080/
```
- Aplicar:
```bash
/scripts/ensure_vhost_includes --user=wwgest
/scripts/rebuildhttpdconf
/scripts/restartsrv_httpd
```
- SSL via AutoSSL (WHM → Manage AutoSSL).

## 5) MySQL
- Conceder privilégios ao usuário da app:
```bash
mysql -u root -p -e "GRANT ALL PRIVILEGES ON `bd_prisma`.* TO 'admsistemaprisma'@'localhost','admsistemaprisma'@'%'; FLUSH PRIVILEGES;"
```
- Se a app referir schemas adicionais (ex.: `info.*`), conceder também.

## 6) Cookies e sessão
- Ajustes no `php.ini` do contêiner `web` para coexistência `/` e `/app`:
```
session.cookie_path = /
session.cookie_secure = On
session.cookie_samesite = Lax
; opcional: session.cookie_domain = gestor.sistemaprisma.com.br
```
- Reiniciar o Apache do container:
```bash
docker compose exec web sh -lc 'apachectl -k graceful'
```

## 7) Comandos úteis
- Ver estado:
```bash
docker compose ps
docker compose logs --tail=200 nginx web
```
- Testes locais:
```bash
curl -I http://127.0.0.1:8080        # Prisma (raiz)
curl -I http://127.0.0.1:8082        # Laravel (separado)
```
- Acesso ao container:
```bash
docker compose exec web sh
```
- Atualização de código em produção (mantendo .env local):
```bash
git fetch
git pull --rebase
# .env permanece ignorado via .git/info/exclude ou skip-worktree
```

## 8) Problemas comuns
- `AH02291: Cannot access directory '/var/www/html/logs/'`:
  - Crie a pasta de logs no volume montado (host) antes de subir.
- `/app` servindo conteúdo do legado:
  - Ordem das regras no Apache: coloque `/app/` ANTES de `/`.
  - Confirme que o serviço Laravel está ouvindo em `127.0.0.1:8082`.
- Conexão MySQL falha via TLS:
  - Teste com `--skip-ssl` no cliente; ajuste `REQUIRE NONE` no usuário ou configure CA.
