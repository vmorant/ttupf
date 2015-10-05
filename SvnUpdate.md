# Introducció #

Després de fer un svn update cal tornar a carregar la base de dades, ja que només s'actualitzen els fitxers.


# Detalls #

Havent fet un `svn up` cal obrir una finestra de comandes a la carpeta on es trobi el codi font de ttupf.

Un cop allà s'executa la següent comanda.
```
$ php symfony doctrine:build --all --and-load
```
Amb això la base de dades es tornarà a crear d'acord amb els canvis rebuts en actualitzar la còpia local.