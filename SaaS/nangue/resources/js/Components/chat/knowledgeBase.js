const faq = [
  // === COMPTE UTILISATEUR ===
  { keywords: ['compte', 'inscription', 'creer compte', 's\'inscrire', 'enregistrer'], answer: 'Pour créer un compte, cliquez sur "Inscription" depuis la page d\'accueil. Remplissez vos informations (nom, email, mot de passe) et validez votre email.' },
  { keywords: ['connexion', 'login', 'connecter', 'identifier'], answer: 'Utilisez votre email et mot de passe sur la page de connexion. Si vous avez oublié votre mot de passe, cliquez sur "Mot de passe oublié".' },
  { keywords: ['deconnexion', 'logout', 'quitter', 'deconnecter'], answer: 'Cliquez sur votre avatar en haut à droite, puis "Déconnexion".' },
  { keywords: ['mot de passe', 'mdp', 'password', 'oublié'], answer: 'Sur la page de connexion, cliquez sur "Mot de passe oublié". Saisissez votre email et suivez les instructions reçues.' },
  { keywords: ['profil', 'modifier profil', 'photo', 'avatar'], answer: 'Votre profil est accessible depuis l\'icône utilisateur en haut à droite. Vous pouvez modifier votre nom, email, photo et mot de passe.' },
  { keywords: ['supprimer compte', 'desactiver', 'effacer'], answer: 'Dans les paramètres de votre profil, section "Supprimer le compte". Attention : cette action est irréversible.' },
  { keywords: ['email', 'changer email', 'modifier email'], answer: 'Allez dans votre profil > Informations personnelles, modifiez votre email et validez avec le nouveau mot de passe.' },

  // === RÔLES : PARTICULIER vs BAILLEUR ===
  { keywords: ['role', 'particulier', 'bailleur', 'difference', 'mode'], answer: 'En tant que Particulier, vous pouvez consulter les annonces, contacter les bailleurs, planifier des visites et sauvegarder vos favoris. Le mode Bailleur vous permet de gérer vos biens, publier des annonces, gérer les contrats, quittances et locataires.' },
  { keywords: ['devenir bailleur', 'passer bailleur', 'espace bailleur', 'activer bailleur'], answer: 'Rendez-vous dans "Espace bailleur" dans la barre latérale. Vous devrez fournir des documents justificatifs (pièce d\'identité, justificatif de domicile, titre de propriété). La vérification prend généralement 24 à 48 heures.' },
  { keywords: ['verification', 'verifier', 'documents', 'justificatifs', 'validation bailleur'], answer: 'La vérification bailleur nécessite : une pièce d\'identité valide, un justificatif de domicile de moins de 3 mois, et un justificatif de propriété (titre de propriété, acte notarié ou avis d\'imposition).' },
  { keywords: ['statut verification', 'attente', 'pending', 'en cours'], answer: 'Vous pouvez vérifier le statut de votre demande depuis "Espace bailleur". Les statuts possibles sont : En attente (documents soumis), Vérifié (accès accordé), Rejeté (documents non conformes).' },
  { keywords: ['verification rejetee', 'refuse', 'documents invalides'], answer: 'Si votre vérification est rejetée, vous recevrez une notification avec le motif. Rendez-vous dans "Espace bailleur" pour soumettre de nouveaux documents.' },
  { keywords: ['test', 'utilisateur test', 'demo', 'brevel'], answer: 'L\'utilisateur test "brevelnangue3@gmail.com" a accès aux deux espaces (Particulier et Bailleur) sans vérification. Connectez-vous avec cet email pour tester toutes les fonctionnalités.' },

  // === PROPRIÉTÉS ===
  { keywords: ['propriete', 'bien', 'immobilier', 'logement', 'appartement', 'maison'], answer: 'La section "Mes propriétés" liste tous vos biens immobiliers. Vous pouvez ajouter, modifier ou supprimer une propriété. Chaque bien peut avoir plusieurs photos, une description, un prix, une surface, et une adresse.' },
  { keywords: ['ajouter propriete', 'nouveau bien', 'creer bien'], answer: 'Cliquez sur "Ajouter une propriété" depuis la page des propriétés (bailleur). Remplissez les informations : type de bien, surface, prix, adresse, description, et ajoutez des photos par glisser-déposer.' },
  { keywords: ['modifier propriete', 'editer bien', 'changer'], answer: 'Depuis la liste des propriétés, cliquez sur le bouton Modifier (icône crayon) sur la carte du bien. Vous pouvez changer toutes les informations et réorganiser les photos.' },
  { keywords: ['supprimer propriete', 'retirer bien', 'effacer propriete'], answer: 'Sur la fiche du bien, cliquez sur "Supprimer" et confirmez. La propriété et toutes ses données associées (contrats, visites) seront supprimées.' },
  { keywords: ['photo', 'image', 'photos propriete'], answer: 'Lors de la création/modification d\'un bien, la zone de dépôt accepte les fichiers JPG, PNG, WEBP. Vous pouvez glisser-déposer pour réordonner les photos, et survoler une photo pour afficher la corbeille de suppression.' },
  { keywords: ['adresse', 'localisation', 'map', 'carte'], answer: 'L\'adresse du bien est automatiquement géolocalisée sur une carte Leaflet. Vous pouvez voir l\'emplacement exact dans la fiche détaillée du bien.' },
  { keywords: ['prix', 'loyer', 'surface', 'pieces', 'chambres'], answer: 'Ces informations sont modifiables dans la fiche du bien. Le prix s\'affiche sur les annonces et dans les statistiques.' },

  // === ANNONCES / PUBLICATIONS ===
  { keywords: ['annonce', 'publication', 'publier', 'diffuser', 'mettre en ligne'], answer: 'Les annonces sont créées depuis "Publications". Une fois une propriété créée, vous pouvez la publier sur les plateformes partenaires. Chaque annonce peut être activée ou désactivée.' },
  { keywords: ['creer annonce', 'nouvelle publication'], answer: 'Depuis "Publications", cliquez sur "Nouvelle publication". Sélectionnez la propriété concernée, ajoutez des photos, un titre accrocheur, une description détaillée, et fixez le prix.' },
  { keywords: ['modifier annonce', 'editer publication'], answer: 'Dans la liste des publications, cliquez sur l\'icône Modifier. Vous pouvez changer le titre, le prix, les photos, et le statut (active/brouillon).' },
  { keywords: ['desactiver annonce', 'masquer', 'retirer publication'], answer: 'Vous pouvez désactiver une annonce depuis les actions rapides (bouton oeil). L\'annonce restera dans vos brouillons mais ne sera plus visible par les particuliers.' },
  { keywords: ['supprimer annonce', 'effacer publication'], answer: 'Cliquez sur la corbeille dans la liste des publications et confirmez la suppression.' },

  // === LOCATAIRES ===
  { keywords: ['locataire', 'tenant', 'locataires'], answer: 'La section "Locataires" liste tous vos locataires actifs et anciens. Vous pouvez voir leurs informations, contrats en cours, et historique des paiements.' },
  { keywords: ['ajouter locataire', 'nouveau locataire'], answer: 'Depuis "Locataires", cliquez sur "Ajouter un locataire". Saisissez son nom, email, téléphone et assignez-lui un bien.' },
  { keywords: ['modifier locataire', 'editer locataire'], answer: 'Dans la fiche locataire, cliquez sur Modifier pour mettre à jour ses coordonnées ou changer le bien associé.' },

  // === CONTRATS & BAUX ===
  { keywords: ['contrat', 'bail', 'location', 'louer'], answer: 'Les contrats de location sont gérés dans "Contrats & baux". Chaque contrat est lié à un bien et un locataire. Il inclut la date de début, la durée, le montant du loyer et les conditions.' },
  { keywords: ['creer contrat', 'nouveau bail', 'signer bail'], answer: 'Allez dans "Contrats & baux" > "Nouveau contrat". Sélectionnez le bien, le locataire, la date de début, la durée (1 an, 3 ans), le montant du loyer et des charges.' },
  { keywords: ['modifier contrat', 'resilier', 'terminer contrat'], answer: 'Dans la fiche contrat, vous pouvez modifier les termes ou résilier le contrat. La résiliation met fin au bail à la date indiquée.' },
  { keywords: ['renouvellement', 'reconduire', 'prolonger bail'], answer: 'Pour renouveler un contrat, utilisez l\'option "Renouveler" depuis la fiche contrat. Vous pouvez ajuster le loyer avant de générer le nouveau bail.' },
  { keywords: ['depot de garantie', 'caution', 'garantie'], answer: 'Le dépôt de garantie est configurable dans le contrat. Il est généralement d\'un mois de loyer hors charges. Sa restitution se fait dans les 2 mois suivant la remise des clés.' },

  // === QUITTANCES ===
  { keywords: ['quittance', 'quittances', 'recu', 'paiement loyer'], answer: 'Les quittances de loyer sont générées automatiquement. Elles sont disponibles dans "Quittances". Vous pouvez les télécharger en PDF pour chaque mois.' },
  { keywords: ['generer quittance', 'creer quittance', 'emettre'], answer: 'Depuis "Quittances", cliquez sur "Générer une quittance". Sélectionnez le contrat et la période. La quittance inclut le loyer, les charges, et le solde.' },
  { keywords: ['telecharger quittance', 'pdf', 'imprimer quittance'], answer: 'Chaque quittance dispose d\'un bouton de téléchargement PDF. Vous pouvez aussi imprimer directement depuis le navigateur.' },
  { keywords: ['historique quittances', 'archives'], answer: 'Toutes les quittances sont conservées dans l\'historique. Vous pouvez filtrer par période ou par locataire.' },
  { keywords: ['quittance impayee', 'loyer non paye', 'retenu'], answer: 'Si un loyer est impayé, la quittance mentionne "Impayé". Vous pouvez relancer le locataire depuis la section Messages.' },

  // === VISITES ===
  { keywords: ['visite', 'visiter', 'rendez-vous', 'rdv', 'calendrier'], answer: 'Les visites sont planifiées depuis le calendrier. Vous pouvez voir les créneaux disponibles, confirmer ou annuler les rendez-vous.' },
  { keywords: ['planifier visite', 'programmer rdv'], answer: 'Depuis "Calendrier des visites", cliquez sur "Planifier une visite". Sélectionnez le bien, la date, l\'heure, et le visiteur. Une notification sera envoyée.' },
  { keywords: ['calendrier', 'agenda', 'planning'], answer: 'Le calendrier affiche toutes les visites planifiées. Vous pouvez basculer entre vue mensuelle et vue liste. Les visites sont colorées par statut (confirmée, en attente, annulée).' },
  { keywords: ['confirmer visite', 'accepter rdv', 'annuler visite'], answer: 'Depuis la fiche de la visite, cliquez sur "Confirmer" ou "Annuler". Une notification sera envoyée au visiteur.' },
  { keywords: ['visite terminee', 'effectuee'], answer: 'Après une visite, marquez-la comme "Terminée" pour la faire apparaître dans l\'historique. Vous pouvez ajouter des commentaires.' },

  // === FAVORIS ===
  { keywords: ['favori', 'favoris', 'aimer', 'sauvegarder', 'coeur', 'heart'], answer: 'Cliquez sur le cœur ♥ sur une annonce pour l\'ajouter à vos favoris. Retrouvez toutes vos annonces sauvegardées dans "Mes favoris".' },
  { keywords: ['retirer favori', 'supprimer favori', 'enlever'], answer: 'Dans la page des favoris, cliquez à nouveau sur le cœur ♥ pour retirer l\'annonce de vos favoris.' },

  // === MESSAGES ===
  { keywords: ['message', 'messagerie', 'chat', 'discussion', 'conversation'], answer: 'La messagerie intégrée vous permet d\'échanger avec les bailleurs et les locataires. Les conversations sont organisées par bien ou par contact.' },
  { keywords: ['envoyer message', 'ecrire', 'nouveau message'], answer: 'Depuis "Messages", sélectionnez un contact dans la liste de gauche, tapez votre message dans le champ en bas et appuyez sur Entrée ou cliquez sur Envoyer.' },
  { keywords: ['notification', 'alerte', 'nouveau message', 'sonnerie'], answer: 'Les notifications apparaissent dans l\'en-tête (cloche). Un badge indique le nombre de messages non lus. Les notifications sont également envoyées par email.' },

  // === RECHERCHE ===
  { keywords: ['recherche', 'chercher', 'trouver', 'filtrer'], answer: 'La barre de recherche permet de trouver des biens par mot-clé (ville, code postal, type de bien). Utilisez les filtres avancés pour affiner par prix, surface, nombre de pièces.' },
  { keywords: ['filtre', 'filtres', 'trier', 'categorie'], answer: 'Les filtres disponibles : prix min/max, surface min/max, type de bien (appartement, maison, studio), nombre de pièces, disponibilité. Les filtres se cumulent.' },
  { keywords: ['recherche vocale', 'microphone', 'parler', 'voix'], answer: 'Cliquez sur l\'icône microphone dans la barre de recherche pour utiliser la recherche vocale. Dictez votre recherche (ex: "appartement 3 pièces Paris"). Compatible Chrome et Edge.' },
  { keywords: ['recherche enregistree', 'sauvegarder recherche', 'alerte'], answer: 'Après avoir défini vos filtres, cliquez sur "Sauvegarder cette recherche". Vous la retrouverez dans "Recherches sauvegardées" et serez notifié en cas de nouvelles annonces correspondantes.' },

  // === STATISTIQUES & ANALYTIQUES ===
  { keywords: ['statistique', 'statistiques', 'analytics', 'analyse', 'chiffre'], answer: 'La section "Analytiques" présente vos indicateurs clés : revenus mensuels, taux d\'occupation, nombre de propriétés, locataires actifs, etc. Les données sont présentées sous forme de graphiques Chart.js.' },
  { keywords: ['revenu', 'revenus', 'chiffre affaire', 'ca'], answer: 'Les revenus sont affichés sous forme de graphique à barres dans le Dashboard et Analytics. Vous voyez l\'évolution mensuelle avec les montants perçus.' },
  { keywords: ['taux occupation', 'occupation', 'remplissage'], answer: 'Le taux d\'occupation compare le nombre de biens loués par rapport au total de vos propriétés. Un graphique dédié dans Analytics montre l\'évolution mensuelle.' },

  // === TABLEAU DE BORD ===
  { keywords: ['dashboard', 'tableau de bord', 'accueil', 'apercu'], answer: 'Le tableau de bord vous donne un aperçu rapide : statistiques (propriétés, locataires, visites, revenus), propriétés récentes, dernières publications, actions rapides et locataires actifs.' },
  { keywords: ['actions rapides', 'raccourci'], answer: 'Les actions rapides sont les boutons en haut du Dashboard : Ajouter un bien, Nouvelle publication, Planifier une visite, Générer une quittance.' },

  // === MODE SOMBRE ===
  { keywords: ['mode sombre', 'dark mode', 'theme', 'nuit', 'clair'], answer: 'Cliquez sur l\'icône soleil/lune dans l\'en-tête pour basculer entre le mode clair et sombre. Le choix est sauvegardé et persiste après reconnexion.' },
  { keywords: ['mode clair', 'light mode', 'jour'], answer: 'Pour repasser en mode clair, cliquez à nouveau sur l\'icône lune/soleil dans l\'en-tête.' },

  // === NAVIGATION ===
  { keywords: ['menu', 'sidebar', 'barre laterale', 'navigation'], answer: 'La barre latérale gauche donne accès à toutes les sections : Dashboard, Propriétés, Publications, Locataires, Contrats, Quittances, Visites, Messages, Favoris, Analytiques, Paramètres.' },
  { keywords: ['retour', 'page precedente', 'back'], answer: 'Utilisez le fil d\'Ariane (breadcrumb) en haut de chaque page pour revenir à la section parente, ou cliquez sur le logo pour revenir à l\'accueil.' },

  // === PARAMÈTRES ===
  { keywords: ['parametre', 'parametres', 'settings', 'configuration'], answer: 'Les paramètres sont accessibles depuis l\'icône en forme de roue dentée dans la barre latérale ou depuis le menu utilisateur en haut à droite.' },
  { keywords: ['notification reglage', 'regler notifications', 'email notification'], answer: 'Dans Paramètres > Notifications, vous pouvez choisir de recevoir les alertes par email (nouveau message, visite confirmée, quittance disponible) ou seulement dans l\'application.' },

  // === EXPORTATION ===
  { keywords: ['exporter', 'export', 'csv', 'telecharger donnees'], answer: 'Les données peuvent être exportées au format CSV depuis la plupart des listes (propriétés, locataires, contrats, quittances). Utilisez le bouton "Exporter" en haut des listes.' },

  // === DÉPANNAGE ===
  { keywords: ['bug', 'probleme', 'erreur', 'plante', 'ne marche pas'], answer: 'Si vous rencontrez un problème, essayez de rafraîchir la page (F5). Si le problème persiste, contactez le support technique via "Aide" > "Contacter le support".' },
  { keywords: ['page blanche', 'rien affiche', 'erreur chargement'], answer: 'Essayez de vider le cache de votre navigateur (Ctrl+F5). Vérifiez votre connexion internet. Si le problème persiste, contactez le support.' },
  { keywords: ['lent', 'ralentissement', 'performance', 'chargement long'], answer: 'Si l\'application est lente, essayez de fermer les onglets inutiles. Les photos en haute résolution peuvent ralentir le chargement. Passez en mode liste plutôt que grille.' },

  // === CONTACT & SUPPORT ===
  { keywords: ['support', 'aide', 'contact', 'assistance', 'service client'], answer: 'Vous pouvez contacter le support depuis "Aide" > "Nous contacter" ou directement par email à support@immo-saas.fr. Notre équipe répond sous 24 à 48 heures.' },
  { keywords: ['signalement', 'abus', 'contenu inapproprié'], answer: 'Pour signaler un contenu inapproprié, utilisez le bouton "Signaler" sur l\'annonce concernée ou contactez le support.' },
];

export default faq;
