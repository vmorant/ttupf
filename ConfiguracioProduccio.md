# Introducció #

Aquesta pàgina descriu com desplegar ttupf en un servidor compartit, on no hi ha possibilitat d'editar el fitxer `httpd.conf` per a crear `VirtualHost`.

Segons l'allotjament, la carpeta cap a on s'hagi de fer l'enllaç simbòlic pot variar. Hi ha proveïdors que serveixen els fitxers a partir d'un directori `html` dins del directori del domini, per exemple:
```
domini.tld/
  html/
```
En aquest cas els enllaços haurien d'apuntar a `domini.tld/html/ttupf`.

# Requisits #
  * Accés SSH
  * Client SVN al servidor
  * PHP 5.3

# Desplegament #
Els passos que s'explica a continuació s'han de realitzar a través d'una connexió SSH al servidor.

## Descàrrega ##
S'ha de descarregar els fitxers del projecte al servidor. La manera més fàcil és fer un `svn checkout` del repositori. A partir d'una carpeta que no es serveixi a la web es fa el checkout:

`$ svn co https://ttupf.googlecode.com/svn/trunk/src ~/ttupf`

## Configuració de rutes ##
S'ha de crear dos enllaços simbòlics per a què ttupf estigui disponible des de la web. Aquest pas és equivalent a la configuració del fitxer `httpd.conf` esmentat a ConfiguracioLocal:

`$ ln -s ~/ttupf/web/ ~/domini.tld/ttupf`

`$ ln -s ~/ttupf/lib/vendor/symfony/data/web/sf/ ~/domini.tld/ttupf/sf`

La carpeta `domini.tld` és la carpeta web arrel. Tal com s'ha fet aquí, ttupf estaria disponible a l'adreça `http://domini.tld/ttupf`.

## Configuració del projecte ##
Ara s'ha de donar accés a la base de dades editant el fitxer `~/ttupf/config/databases.yml` i canviant els paràmetres adients (host, usuari i contrasenya, normalment).
També s'ha de copiar el fitxer `~/ttupf/apps/frontend/config/app.yml.tmpl` a `~/ttupf/apps/frontend/config/app.yml`. Això permet que es pugui accedir a ttupf per l'entorn de producció en comptes de l'entorn dev.

## Configuració correu ##
### Servidor SMTP ###
Per tal que es puguin registrar usuaris s'ha de configurar el servidor de correu sortint i també l'adreça remitent que farà servir el projecte.

Primer s'ha de copiar el fitxer `~/ttupf/apps/frontend/config/factories.yml.tmpl` a `~/ttupf/apps/frontend/config/factories.yml`. Després cal editar-lo, deixant l'apartat `prod:` de la següent manera:

```
prod:
  mailer:
    param:
      delivery_strategy: realtime
      transport:
        class: Swift_SmtpTransport
        param:
          host:        mail.domini.tld
          port:        25
          encryption:  ~
          username:    usuari@domini.tld
          password:    contrasenya

  logger:
    class:   sfNoLogger
    param:
      level:   err
      loggers: ~
```
### Adreça remitent ###
S'ha d'especificar quina adreça de correu remitent fer servir. Això es fa al fitxer `~/ttupf/apps/frontend/config/app.yml`:
```
sfApplyPlugin:
    from:
      email: "ttupf@domini.tld"
      fullname: "L'equip de ttupf"
```

Un cop fet això podem accedir a ttupf a través de la URL `http://domini.tld/ttupf`.