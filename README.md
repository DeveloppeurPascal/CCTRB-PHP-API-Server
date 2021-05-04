# COVID Contact Tracing Restau / Bar (serveur d'API en PHP)

Ce code est lié au projet [CCTRB](https://cctrb.fr) et à son [dépôt de sources en Delphi](https://github.com/DeveloppeurPascal/COVID-ContactTracing-RestauBar) pour l'application mobile des clients d'établissements recevant du public et le logiciel de gestion des établissements.

L'API du serveur PHP utilise la même base de données et les mêmes appels que la version Delphi. Se reporter à [cette page de documentation](https://github.com/DeveloppeurPascal/COVID-ContactTracing-RestauBar/blob/main/ServeurCCTRB/doc-API-serveur.txt).

La version Delphi du serveur est disponible à [cette adresse](https://github.com/DeveloppeurPascal/COVID-ContactTracing-RestauBar/tree/main/ServeurCCTRB).

-----

## TODO LIST

* sur connexion à la base de données, renvoyer un code d'erreur HTTP spécifique en cas d'anomalie
* sur les différentes API, renvoyer un code d'erreur si la base est KO
* tester etbadd et faire API suivantes (etb + cli)
* changer les index en redirection vers ../ sur les dossiers à protéger
* dans le fichier de configuration, pour le choix de la base de données, ajouter un test en fonction de l'IP locale et fixer les paramètres de la base publique :
if (("127.0.0.1" == $_SERVER["SERVER_ADDR"]) || ("::1" == $_SERVER["SERVER_ADDR"])) {
* dans la ligne de connexion, ajouter le SET NAMES UTF8 pour MySQL/MariaDB (au cas où) :
$db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=UTF8",DB_USER,DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

