<?php
// Module Info

// Le nom du module
define("_MI_formulize_NAME","Formulaire");

// Une brève descrition du module
define("_MI_formulize_DESC","Pour créer des formulaires complexes, et analyser les données");

// admin/menu.php
define("_MI_formulize_ADMENU0","Gestion de formulaires");
define("_MI_formulize_ADMENU1","Menu");

// notifications
define("_MI_formulize_NOTIFY_FORM_DESC", "Notifications relative au formulaire en cours");
define("_MI_formulize_NOTIFY_FORM", "Notifications de Formulaires");
define("_MI_formulize_NOTIFY_NEWENTRY", "Nouvelle entrée de formulaire");
define("_MI_formulize_NOTIFY_NEWENTRY_CAP", "Notifiez moi toute nouvelle entrée de formulaire");
define("_MI_formulize_NOTIFY_NEWENTRY_DESC", "cette option de notification alerte les utilisateurs quand une nouvelle entrée de formulaire est saisie");
define("_MI_formulize_NOTIFY_NEWENTRY_MAILSUB", "Nouvelle entrée dans un formulaire");

define("_MI_formulize_NOTIFY_UPENTRY", "Mise à jour d'entrée de formulaire");
define("_MI_formulize_NOTIFY_UPENTRY_CAP", "Notifiez moi quand quelqu'un met à jour cette entrée de formulaire");
define("_MI_formulize_NOTIFY_UPENTRY_DESC", "Cette option de notification alerte les utilisateurs quand une personne met à jour une entrée dans ce formulaire");
define("_MI_formulize_NOTIFY_UPENTRY_MAILSUB", "Entrée de formulaire mise à jour");

define("_MI_formulize_NOTIFY_DELENTRY", "Effacement d'entrée de formulaire");
define("_MI_formulize_NOTIFY_DELENTRY_CAP", "Notifiez moi quand une entrée de formulaire est effacée");
define("_MI_formulize_NOTIFY_DELENTRY_DESC", "Cette option de notification alerte les utilisateurs quand une entrée de formulaire est effacée");
define("_MI_formulize_NOTIFY_DELENTRY_MAILSUB", "Entrée de formulaire effacée");

//	préférences
define("_MI_formulize_TEXT_WIDTH","Largeur des boîtes de texte par défaut");
define("_MI_formulize_TEXT_MAX","Longueur maximum des boîtes texte par défaut");
define("_MI_formulize_TAREA_ROWS","Nombre de lignes des aires de saisies de texte par défaut");
define("_MI_formulize_TAREA_COLS","Nombre de colonnes des aires de saisie de texte par défaut");
define("_MI_formulize_DELIMETER","Délimitation pour les cases à cocher et les boutons radio");
define("_MI_formulize_DELIMETER_SPACE","Espace blanc");
define("_MI_formulize_DELIMETER_BR","Coupure de ligne");
define("_MI_formulize_SEND_METHOD","Méthode d'envoi");
define("_MI_formulize_SEND_METHOD_DESC","Note: les formulaires remplis par des utilisateurs anonymes ne peuvent pas Ítre envoyés en messages privés.");
define("_MI_formulize_SEND_METHOD_MAIL","Email");
define("_MI_formulize_SEND_METHOD_PM","Message privé");
define("_MI_formulize_SEND_GROUP","Envoyer au groupe");
define("_MI_formulize_SEND_ADMIN","Envoyer à l'administrateur du site uniquement");
define("_MI_formulize_SEND_ADMIN_DESC","Les réglages de \"Envoyer au groupe\" seront ignorés");
define("_MI_formulize_PROFILEFORM","Quel formulaire doit être utilisé comme une étape d'inscription et lorsqu'on voit ou édite les comptes d'utilisateurs? (usage du module Registration Codes requise)");

define("_MI_formulize_ALL_DONE_SINGLES","Est ce que le bouton 'Tout fini' doit apparaitre en bas du formulaire lorsqu'une entrée est éditée, ou créée pour les formulaires à une entrée par utilisateur?");
define("_MI_formulize_SINGLESDESC","Le bouton 'Tout fait' est utilisé pour sortir d'un formulaire sans l'enregistrer.  Si une modification est faite et que vous cliquez le bouton 'Tout fini' sans cliquer préalablement sur 'Sauvegarder', vous aurez un avertissement comme quoi toutes les données n'ont pas été sauvegardée.  Si le bouton est affiché, il n'y a aucun moyen de sauvegarder et quitter en une seule opération.  Cela peut porter à confusion.  Mettez cette option sur 'Oui' pour enlever le bouton 'Tout fini' et faire que le bouton 'Sauvegarder' permette aussi de quitter en une seule procédure.  Cettte option n'a aucune conséquence sur les formulaires ou plusieurs entrées peuvent être faite en même temps (le formulaire recharge sous forme vierge à chaque fois que le bouton 'Sauvegarder' est utilisé).");

define("_MI_formulize_LOE_limit", "Quel est le nombre maximum d'entrées à afficher dans la liste des entrées, sans confirmation de l'utilisateur pour voir toutes les entrées?");
define("_MI_formulize_LOE_limit_DESC", "Lorsqu'une sélection est très large, l'affichage de la liste des entrées peut être fastidieuse, et durer au delà de plusieurs minutes. Définissez le nombre maximum d'entrées à afficher d'un coup.  Si une sélection contient plus d'entrée que la limite, il sera demandé à l'utilisateur s'il veut tout afficher ou non.");

define("_MI_formulize_USETOKEN", "Utiliser la sécurité identifiant système pour valider les soumissions de formulaires?");
define("_MI_formulize_USETOKENDESC", "Par défaut, lors d'une soumission, aucune donnée n'est sauvegardée sauf si Formulize peut valider un identifiant unique soumis avec le formulaire.  C'est une défense partielle contre les attaques de scripts croisés, permettant de s'assurer que les personnes visitant actuellement votre site peuvent soumettre un formulaire.  Dans certaines circonstances, dépendantes de Firewall ou d'autres facteurs, l'identifiant ne peut être validé même si cela devrait se produire.  Si cela vous arrive avec répétition, vous pouvez désactiver cette sécurité ici.  <b>NOTE: vous pouvez passer au dessus de cette préférence globale sur une base Screen par Screen.</b>");

define("_MI_formulize_NUMBER_DECIMALS", "Par défaut, combien de chiffre après la virgule, donc pour les décimales, doivent être allouées aux nombres?");
define("_MI_formulize_NUMBER_DECIMALS_DESC", "Normalement, laissez cela à 0, sauf si vous souhaitez que les nombres dans tous les formulaires aient un certain nombre de places pour les décimales.");
define("_MI_formulize_NUMBER_PREFIX", "Par défaut, est ce qu'un symbole doit être montré avant les nombres?");
define("_MI_formulize_NUMBER_PREFIX_DESC", "Par exemple, si tous votre site n'utilise que des dollars dans les formulaires, alors mettez '$' ici. Dans tous les autres cas laissez blanc.");
define("_MI_formulize_NUMBER_DECIMALSEP", "Par défaut, si les décimales sont utilisées, quelle ponctuation doit les séparer du reste des nombres?");
define("_MI_formulize_NUMBER_SEP", "Par défaut, quelle ponctuation doit séparer les milliers dans les nombres?");

define("_MI_formulize_HEADING_HELP_LINK", "Voulez vous que le lien d'aide ([?]) apparaisse en haut de chaque colonne dans la liste des entrées?");
define("_MI_formulize_HEADING_HELP_LINK_DESC", "Ce lien engendre une fenêtre de type popup qui montre les détails à propos d'une question dans le formulaire, comme le texte complet de la question, le choix des options si la question est un bouton radio, etc.");
       
define("_MI_formulize_USECACHE", "Utiliser le cache pour accélérer les Procédures?");
define("_MI_formulize_USECACHEDESC", "Par défaut, le cache est activé.");

define("_MI_formulize_DOWNLOADDEFAULT", "Lors de l'export des données, utiliser une astuce de compatibilité pour des versions d'Excel par défaut?");
define("_MI_formulize_DOWNLOADDEFAULT_DESC", "Lors de l'export des données, ils peuvent cocher une case sur la page de téléchargement qui ajoute un code spécial au fichier qui est nécessaire pour faire apparaitre correctement les caractères accentués dan,s certaines versions de Microsoft Excel.  Ce réglage contrôle le fait que cette case soit cochée par défaut ou non. Faites un test d'export, pour voir s'il vaut mieux pour votre installation que cette option soit activée ou non.");
       
define("_MI_formulize_LOGPROCEDURE", "Demander les identifiants pour surveiller Procédures et paramètres?");
define("_MI_formulize_LOGPROCEDUREDESC", "Par défaut, la vérification des identifiants est désactivée.");


// Le nom du module
define("_MI_formulizeMENU_NAME","MonMenu");

// Une brève descrition du module
define("_MI_formulizeMENU_DESC","Montre un menu individuel configurable dans un bloc");

// Noms des blocs pour ce module (pas tous les modules ont des blocs)
define("_MI_formulizeMENU_BNAME","Menu des Formulaires");




?>