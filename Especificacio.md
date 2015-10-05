# Especificació #

ttupf té com a objectiu permetre crear horaris personalitzats a partir dels horaris de totes les carreres i cursos de l'ESuP. En aquests horaris personalitzats només es poblaran els períodes que interessi a l'usuari: les assignatures que estigui cursant. D'aquesta manera s'evita haver de consultar dues o més pàgines d'horaris.

## Tasques d'usuaris ##
Un primer esborrany de les tasques d'usuari són els següents.

### Pàgina d'inici ###
Quan l'usuari arribi a ttupf, es pot trobar amb tres casos diferents segons si és:

  * Usuari amb sessió iniciada i horari configurat:
    * Pàgina d'horari personalitzat

  * Usuari amb sessió iniciada però l'horari sense configurar:
    * Informació sobre com configurar l'horari

  * Usuari sense sessió iniciada o sense registrar:
    * Informació sobre l'aplicació
    * Informació sobre com registrar-se i configurar l'horari
    * Formulari d'inici de sessió

### Registre d'usuari ###
Un visitant es podrà registrar per poder crear l'horari personalitzat. La informació requerida serà:

  * (?) Nom d'usuari (?)
  * Adreça de correu electrònic (potser limitar-ho a domini @`*`.upf.edu?)
  * Contrasenya

### Inici de sessió ###
Un usuari registrat podrà iniciar sessió omplint el formulari d'inici de sessió:

  * (?) Nom d'usuari (?)
  * Adreça de correu
  * Contrasenya

### Restabliment contrasenya ###
En sol.licitar el restabliment, si l'adreça és vàlida, l'aplicació hi enviarà un correu amb un enllaç. Aquest enllaç portarà a una pàgina de l'aplicació, on l'usuari podrà especificar una contrasenya nova.

### Configuració de l'horari ###
L'usuari registrat i amb sessió iniciada podrà configurar el seu horari personalitzat en aquesta pàgina. Haurà de:

  1. Escollir una carrera i curs
  1. A partir de les assignatures disponibles per a aquell curs, escollir assignatures per a incloure a l'horari.
  1. Opcionalment, configurar cada assignatura amb els seus grups de pràctica/seminari.
  1. Repetir passos 1-3 els cops necessaris.

Omplint el grup de seminari es prepoblarà el camp de pràctiques segons el que s'hagi introduït al seminari, sempre deixant la possibilitat d'editar el camp del grup de pràctiques (pels casos en què el grup de seminaris no deriva del grup de pràctiques). També es permet un grup buit. E.g. si l'usuari introdueix 222 al camp de seminari per a una assignatura, el camp de pràctiques es prepoblarà amb 22.

## Model de dades ##
La base de dades inicial consisteix en el següent model:

### sfGuardUser ###
Aquesta taula prové del plugin sfDoctrineGuardPlugin. Les columnes següents són les més importants.
  * id
  * user\_name
  * email\_address

### CarreraCurs ###
Taula amb una entrada per cada grup de teoria de cada curs de cada carrera.
  * id
  * nom
  * curs
  * grup\_teoria
  * url\_horari

### Assignatura ###
Taula amb una entrada per cada assignatura.
  * id
  * nom
  * carrera\_curs\_id (clau forana a CarreraCurs.id)

### Sessio ###
Taula amb les dades per a cada classe de cada assignatura. A partir de la informació d'aquesta taula es poblarà l'horari personalitzat.
  * id
  * data\_hora\_inici
  * data\_hora\_fi
  * assignatura\_id (clau forana a Assignatura.id)
  * aula
  * tipus
  * grup\_practiques
  * grup\_seminari

### UsuariTeAssignatures ###
Taula on es guardaran les classes que hagi escollit l'usuari per al seu horari.
  * id
  * usuari\_id (clau forana a Usuari.id)
  * assignatura\_id (clau forana a Assignatura.id)
  * grup\_practiques
  * grup\_seminari

## Anàlisi dels horaris ##
Per a poder poblar horaris personalitzats s'ha d'analitzar tots els horaris existents a l'ESuP. El primer pas per a cada carrera serà insertar a la base de dades les seves assignatures (a la taula Assignatura). Després, per a cada setmana, s'hauran d'insertar les classes amb la seva informació a la taula Classe.

S'ha de decidir com i quan fer aquests anàlisis per mantenir la base de dades actualitzada en tot moment. Una possibilitat és comprovar si hi ha hagut canvis a l'horari de l'ESuP cada cop que es carrega un horari personalitzat: si no hi ha hagut canvi (en la data de modificació del document HTML per exemple), mostrar directament l'horari. Si n'hi ha hagut, tornar a fer l'anàlisi per als cursos que tingui configurat l'usuari.

### Format de períodes (caselles) ###
Aquesta secció documenta els diferents formats que pot tenir una casella de període (classe). El cas base és:

Assignatura

Tipus

Informació extra

La informació extra canvia segons el tipus de classe. Per a classes de teoria és l'aula (o grup de teoria: aula en cas d'haver grups A i B de teoria). Per a pràctiques i seminaris és "grup: aula" si la classe no està partida, "franja horària\rgrups: aules\rfranja 2\rgrups: aules" si ho està.

S'han trobat les següents excepcions al cas base:

  * Es poden juntar dos grups de seminari a una mateixa aula. E.g. S111 + S112 54.023, o S111 i S112: 54.004.
  * Pot haver-hi més d'una aula per una classe de teoria, encara que normalment no hi hagi grup A i grup B de teoria (segons l'Enric).
  * Pot haver un període que estigui partit en seminari/teoria o seminari/pràctica, inclús solapant-se les franjes horàries.
  * Pot no haver-hi cap tipus de classe definida. En aquest cas es dona per suposat que és teoria.