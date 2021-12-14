# WFCamp

Ben & Chris sont d'accord pour créer un nouveau framework PHP&JS combiné (BlaBlaCode)

# Initialisation

Pour démarrer il faudra cloner le projet et se déplacer dans le dossier du projet pour lancer :

```composer install```
```yarn install```

Ensuite, il suffira de démarrer votre serveur symfony avec 
```symfony server:start```
```yarn encore dev-server```



# Base de données

**Attention : Un serveur SQL est nécessaire, et une stack \*AMP est supposée dans ce projet, par conséquent modifiez les variables d'environnement en conséquence**

Une fois les variables d'environnement installées executez la commande

```symfony console doctrine:schema:create```
