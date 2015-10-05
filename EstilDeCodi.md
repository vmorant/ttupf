# Introducció #

L'estil de codi que farem servir serà una personalització de la pròpia de Symfony, disponible [aquí](http://trac.symfony-project.org/wiki/HowToContributeToSymfony#CodingStandards). Les modificacions que hi ha fan referència a la indentació i les claus d'obertura.

## Resum ##
  * La indentació es fa amb un caràcter tabulador i mai utilitzant caràcters espai.
  * La clau d'obertura la col·loquem al final de la mateixa línia on posem el títol de la funció, iteració, constructor, i en general qualsevol sentència que requereixi claus, tenint en compte que hi ha d'haver un espai entre el títol i la clau.
```
<?php
class sfFoo {
    public function bar() {
        sfCoffee::make();
    }
}
```

  * Per nomenar variables, funcions i mètodes es fa servir camelCase.
  * Per nomenar arguments, opcions i paràmetres es fa servir barra\_baixa.
  * Sempre s'utilitzen claus a les declaracions de control (if/while/for).
```
...
if(condicio) {
    codi;
}
...
```

  * Mai s'acaben els fitxers .php amb la etiqueta `?>`. No és necessari, i la seva presència pot causar problemes (si per alguna raó s'introduiexen espais o línies en blanc després de la etiqueta `?>` es poden generar errors).
  * Sempre s'especifica el tipus de cada argument a les signatures de funcions i mètodes.
  * Cada funció i mètode ha de tenir el seu bloc [phpdoc](http://www.phpdoc.org/about.php):
    * Les declaracions que comencen amb @ no tenen punt final.
    * Les línies @param declaren el tipus i nom de variable. Si la variable pot ser de més d'un tipus s'especifica un tipus "mixed".
    * Idealment les línies que comencen amb @ estan aliniades amb espais.
```
<?php
/**
 * Notifies all listeners of a given event.
 *
 * @param  sfEvent  $event  A sfEvent instance
 *
 * @return sfEvent          The sfEvent instance
 */
public function notify(sfEvent $event)
```