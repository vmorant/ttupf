# Requisits #
  * Apache
  * MySQL
  * PHP

## Apache ##
Per configurar l'Apache per tal de poder accedir a ttupf s'ha d'afegir el següent codi a l'arxiu httpd.conf:

S'ha de reemplaçar `/ruta/working/copy` amb la ruta real.

```
# ttupf

# Be sure to only have this line once in your configuration
NameVirtualHost 127.0.0.1:80

# This is the configuration for your project

<VirtualHost 127.0.0.1:80>
  ServerName ttupf.localhost
    DocumentRoot "/ruta/working/copy/ttupf/src/web"
  DirectoryIndex index.php
  <Directory "/ruta/working/copy/ttupf/src/web">
    AllowOverride All
    Allow from All
  </Directory>

  Alias /sf /ruta/working/copy/ttupf/src/lib/vendor/symfony/data/web/sf
  <Directory "/ruta/working/copy/ttupf/src/lib/vendor/symfony/data/web/sf">
    AllowOverride All
    Allow from All
  </Directory>

</VirtualHost>
```

També s'ha d'afegir una entrada al fitxer `hosts` (Windows: C:\Windows\System32\drivers\etc\hosts, Linux: /etc/hosts):
127.0.0.1    ttupf.localhost

Ara es pot anar a http://ttupf.local i veure l'aplicació.

Més informació: http://www.symfony-project.org/getting-started/1_4/en/05-Web-Server-Configuration

## MySQL ##
S'ha de configurar la codificació de caràcters per defecte del servidor de MySQL. D'altra manera, en crear la base de dades amb el symfony, es guardaran les dades UTF-8 com si fossin Latin-1.

S'ha d'editar el fitxer `my.ini` (sota Windows) o `my.cnf` (sota Linux). A l'apartat de `[mysqld]` s'ha d'afegir el següent:

```
[mysqld]
...

# ttupf
default-character-set = utf8 
collation-server = utf8_spanish_ci
```

# Instalació des de zero en Windows #

Si es parteix d'una instal·lació des de zero en Windows caldrà instal·lar Apache 2.2 i despres PHP 5.2.x. La versió de PHP haurà de ser la versió en zip VC6 Thread Safe.

**Nota: Aqui ja podem fer la part de configuració per accedir a ttupf de l'apartat anterior.**

El següent pas és editar el arxiu httpd.conf en la carpeta conf de la instalació d'Apache.

Primer haurem de treure el comentari (#) de la línia

`LoadModule rewrite_module modules/mod_rewrite.so`

i tot seguit afegirem aquestes linies en el apartat on es carreguen els mòduls
```
#Carregar el modul de PHP5 
LoadModule php5_module "C:/Program Files/PHP/php5apache2_2.dll"
AddType application/x-httpd-php .php
AddType application/x-httpd-php .php3
AddType application/x-httpd-php .php4
AddType application/x-httpd-php .php5
PHPIniDir "C:/Program Files/PHP"
```
**Nota: La ultima línia caldrà canviar la ruta del directori per la que estigui instal·lat/extret el PHP 5 i on es pot localitzar el arxiu "php.ini-development".**

Finalment fem una copia del arxiu "php.ini-development" en la mateixa carpeta i el renombrarem per "php.ini".

Reiniciem el servidor Apache i haurem acabat.

Mes informació:
http://www.jorgeoyhenard.com/instalacion-de-php-5-en-windows-7-con-apache/2275/

http://www.jorgeoyhenard.com/instalar-apache-2-2-en-windows-7/2253/

# Ubuntu #
La instal.lacio sota Ubuntu es detalla a continuacio:
## Instal.lar Apache2 ##
`sudo apt-get install apache2`

## Instal.lar MySQL ##
`sudo apt-get install mysql-server`

## Instal.lar PHP5 ##
`sudo apt-get install php5 php5-common libapache2-mod-php5 php5-gd php5-dev curl libcurl3 libcurl3-dev php5-curl php5-mysql`