# Introducció #

S'han fet servir diversos plugins de Symfony per accelerar el desenvolupament de ttupf. Seguidament es detalla la instal.lació i configuració dels plugins.

# sfDoctrineGuardPlugin #

Aquest plugin augmenta les capacitats de rols d'usuari del propi Symfony, i conjuntament amb sfForkedDoctrineApply (que proporciona registre d'usuaris nous).

## Configuració ##
Aquí es detallen els punts on la guia d'[instal.lació](http://www.symfony-project.org/plugins/sfDoctrineGuardPlugin) és diferent de la realitat.

  * Després d'instal.lar el plugin no cal editar `config/ProjectConfiguration.class.php` per activar-lo.

# sfForkedDoctrineApplyPlugin #

Aquest plugin permet l'autogestió dels usuaris: registre, canvi de contrasenya, etc. Ho fa tot amb confirmació per email per evitar spam.

## Configuració ##
Enllaços:
  * [Instal.lació plugin](http://www.symfony-project.org/plugins/sfForkedDoctrineApplyPlugin/1_4_0?tab=plugin_readme)
  * [Configuració correu](http://www.symfony-project.org/jobeet/1_4/Doctrine/en/16#chapter_16_sub_delivery_strategy)