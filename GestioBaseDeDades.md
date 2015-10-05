# Recarregar els canvis introduïts al model #

Una de les commandes que es poden fer servir directament des de la línia de comandes amb el doctrine és
"php symfony doctrine:build --all --and-load". Aquesta comanda és útil per refer les classes lligades al model si s'han introduït canvis, i exactament el que passa és que la base de dades perd tota la informació, i al fer un "load" s'introdueixen les entrades dels arxius de pre-configuració (fixtures).


# Recarregar els canvis introduïts al model sense perdre la informació de la base de dades #

Aquesta comanda funciona prou bé quan l'aplicació no està en funcionament. A la que ja hi ha informació important a la base de dades, s'ha de guardar el que hi ha, refer totes les classes i tornar a col·locar el que hi havia abans. Ho podem fer en dos passos:

  * php symfony doctrine:data-dump

Guarda tota la informació de la base de dades en el fitxer: "data/fixtures/data.yml"

  * symfony doctrine:build --all --and-load="data/fixtures/data.yml"

Carrega a la base de dades tot allò que hi hagi en el fitxer escollit.

# Recarregar els canvis introduïts al model sense perdre la informació de la base de dades quan hi ha informació encriptada #

El problema arriba quan hem de carregar a la base de dades informació encriptada. La comanda anterior el que fa és utilitzar els mètodes de les classes per a carregar la informació. Per exemple, per a la classe sf\_guard\_user, quan col·loquem altre cop la informació, quan hem de col·locar el password d'un usuari, ens trobem amb el problema que doctrine l'encripta altre cop.

La solució és canviar els mètodes de les classes que s'encarreguen d'afegir informació encriptada com els passwords dels usuaris, per exemple canviar el mètode 'setPassword()' de la classe sf\_guard\_user de forma que si el password de l'usuari que s'està afegint té també l'atribut 'salt' omplert, que l'afegeixi sense ecriptar, perquè ja ho està, d'encriptat. Suposo que aquest mètode és extensible a altres paràmetres d'altres classes que utilitzin encriptació.