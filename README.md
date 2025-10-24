# Prisma App
<p>Sistema de gestão empresarial</p>

## Docker - Rodando local
-------------------------
<p>Para executar o projeto localmente é necessário instalar o <a href="https://www.docker.com/products/docker-desktop">Docker</a>. Após instalado, abra o editor dentro da pasta do projeto e execute o comando abaixo pelo terminal:</p>

```` bash
docker-compose up --build
````

## Configurando URL local
-------------------------
<p>Com o container funcionando, é necessário configurar a URL local para acesso ao projeto. Execute o script <strong>hosts-windows.bat</strong> ou rode o comando abaixo pelo CMD (Executando como administrador):</p>

```` cmd
echo 127.0.0.1  prisma.local >> c:\windows\system32\drivers\etc\hosts
````

## Versionando banco local
--------------------------
<p>Caso realize alguma modificação no banco local e queira versionar o mesmo, execute o script <strong>dump-local.sh</strong> que está dentro da pasta <strong>dococker/mysql/scripts</strong>.</p>