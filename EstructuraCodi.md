# Estructura del codi #

S'ha de decidir encara la estructura que tindrà el codi font. Alguns enllaços interessants poden ser:
  * http://blog.fedecarg.com/2008/08/11/scalable-and-flexible-directory-structure-for-web-applications/
  * http://www.otton.org/2008/08/13/better-php-project-layouts/

## Framework o custom ##

Hi ha la opció d'utilitzar un framework per a la base de l'aplicació o bé fer-lo nosaltres sencer. Un framework és bàsicament un conjunt de llibreries que facilita el disseny i desenvolupament d'un projecte.

Utilitzant un framework ens evitaríem haver de "tornar a inventar la roda" i també aprofitaríem els avantatges que ofereixen els frameworks en quant a la seguretat de l'aplicació (validació d'entrada de dades per part de l'usuari, XSS, CSRF, etc.).

### Candidats de framework ###

#### Symfony ####
Un framework per a PHP 5, sembla que té documentació molt bona i hi ha un llibre (publicat com a pàgina web també a http://www.symfony-project.org/jobeet/1_4/Doctrine/en/) d'introducció. Aquest llibre pot ser molt útil per guiar-nos durant el procés de disseny i implementació del projecte.

##### ttupf amb Symfony #####
Fent proves amb el framework de Symfony s'ha arribat a una possible estructura preliminar. Symfony treballa amb el patró de Model-View-Controller, el que implica dissenyar l'aplicació seguint les pautes que estableix aquest model. Les parts fonamentals del patró són:

  * Model: són les dades sobre les que es treballa. Per a ttupf correspon a la base de dades que conté tota la informació sobre carreres, assignatures, classes, usuaris i horaris. Al model hi van totes les funcions que generen les dades (anàlisi d'horaris de l'ESuP, trobar totes les classes per a una setmana d'un usuari, etc.).
  * View: és el codi relacionat amb mostrar a l'usuari les dades obtingudes (les pàgines HTML de l'aplicació). Es fa amb plantilles.
  * Controller: és el "coordinador" entre el model i els views; fa arribar les dades necessàries del model als views.

Symfony treballa amb aplicacions que tenen mòduls que tenen accions. Els mòduls estan lligats a "models" (taules a la base de dades) i les accions representen pàgines. Hi ha una acció executeIndex() per mostrar la pàgina d'inici d'un mòdul, per exemple.

El mòdul principal de ttupf serà el d'Horari. Aquí és on aniran les accions com mostrar l'horari o configurar-lo. Segurament hi haurà un altre mòdul Usuari on aniran les accions registre, inici de sessió i restabliment de contrasenya.

#### CodeIgniter ####
Un altre framework pen a PHP, s'ha d'investigar haviam què tal és.