</php

              'signup'

endpoint {

 POST "https://localhost/phpvisa/router.php"

expects: {
  key: "12345",
  route: string        // "signup"
  telephone: string    // ex: "678901234"
  email: string        // ex: "test@email.com"
  ismobileLogin: bool  // true = via SMS, false = via email
}

returns: {
  status: string       // "success"
  message: string      // Message de confirmation ou d'erreur
  user: integer|null   // L'ID de l'utilisateur nouvellement créé (ou null)
  otp: integer         // Le code OTP généré
  response: object     // Réponse brute de l'API SMS ou null si email
}

}

                      'add-beneficiary' 

endpoint {
  url: "https://localhost/phpvisa/router.php",
  request: 'POST',
  body: {
     key: "12345",
    route: 'add-beneficiaries',
    beneficiary_name: 'John Smith', ← ceci est un nom de beneficiaire juste dde teste dans mon postman
    beneficiary_mobile_number: '678901234' ← ceci est un numero juste dde teste dans mon postman
  }

  returns: {
  status: string       // "success"
  message: string      // Message de confirmation ou d'erreur
}

}

                      'add-notifications'

endpoint {
  url: "https://localhost/phpvisa/router.php",
  request: 'POST',
  body: {
    key: "12345",
    route: 'add-notifications',
    user_id: 12,
    category: 'deposit',
    amount: 15000,
    message: 'Fonds ajoutés avec succès'
  }
  returns: {
  status: string       // "success"
  message: string      // Message de confirmation ou d'erreur
}
}

             'toggle-card'

endpoint {
  url: "https://localhost/phpvisa/router.php",
  request: 'POST',
  body: {
     key: "12345",
    route: 'toggle-card',
    action: 'freeze',             // ou 'unfreeze'
    card_id: 4
  },
  headers: {
    session_token: 'USER_SESSION_ID' // (si tu gères les sessions manuellement)
  }
   returns: {
  status: string       // "success"
  message: string      // Message de confirmation ou d'erreur
  new_status:string    // nouveau statut
  notifications:string      // notification envoyer 
}
}

              'delete'

endpoint {
  url: "https://localhost/MaxwellCards/router.php",
  request: 'POST',
  body: {
    key: "12345",
    route: 'delete-card',
    action: 'delete',
    card_id: 4
  },
  headers: {
    session_token: 'USER_SESSION_ID'
  }
   returns: {
  status: string       // "success"
  message: string      // Message de confirmation ou d'erreur
}
}

                    'fund-card'

endpoint {
  url: "https://localhost/MaxwellCards/router.php",
  request: 'POST',
  body: {
     key: "12345",
    route: 'fund-card',
    card_id: 4,
    amount: 25000,
    currency: 'XAF'  // optionnel, par défaut 'XAF'
  },
  headers: {
    session_token: 'USER_SESSION_ID'
  }
   returns: {
  status: string       // "success"
  message: string      // Message de confirmation ou d'erreur
}
}

               'all-transactions'

endpoint {
  url: "https://localhost/MaxwellCards/router.php",
  request: 'POST',
  body: {
   key: "12345",
    route: 'all-transactions'
  },
  headers: {
    session_token: 'USER_SESSION_ID'
  }
   returns: {
  status: string       // "success"
  data: array      // liste de toute les tansaction disponible pour un client
}
}


                     'get-beneficiaries'
endpoint {
  url: "https://localhost/MaxwellCards/router.php",
  request: 'POST',
  body: {
     key: "12345",
    route: 'get-beneficiaries'
  },
  headers: {
    session_token: 'USER_SESSION_ID'
  }
   returns: {
  status: string       // "success"
  message: string      // Message de confirmation ou d'erreur
}
}

                     "get_recent_transactions" 

endpoint{
  url: "https://localhost/MaxwellCards/router.php",
  request: "POST",
  body: {
     key: "12345",
    route: "recent_transactions" // nom exact de ta méthode dans router.php
  },
  headers: {
    session_token: "USER_SESSION_ID"  // token/session de l'utilisateur connecté
  }
  returns: {
  status: string       // "success"
  data: array      // liste de toute les tansaction disponible pour un client
}
}

                     "kyc"

 endpoint {
  url: "https://localhost/MaxwellCards/router.php",
  request: "POST",
  body: {
    key: "12345",
    route: "kyc",             // Nom exact de la route dans ton routeur
    id: USER_ID,
    address: "Adresse utilisateur",
    profession: "Profession",
    activity_sector: "Secteur d'activité",
    city: "Ville",
    country_id: 123,
    id_type: "National ID",
    id_number: "123456789",
    display_photo: "data:image/png;base64,iVBORw0KGgoAAAANS...",        
    id_card_image: "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...", 
    photo_with_id_card: "data:image/png;base64,iVBORw0KGgoAAAANS..."
  },
  headers: {
    session_token: "USER_SESSION_ID"
  }
  returns: {
  status: string       // "success"
  data: array      // liste de toute les tansaction disponible pour un client
}
}

                  'withdraw-card'

 endpoint {
  url: "https://localhost/MaxwellCards/router.php",
  request: "POST",
  body: {
    key: "12345",
    route: "withdraw-card",       // Correspond à la route dans ton router.php
    card_id: 123,                 // ID de la carte à débiter
    amount: 5000,                 // Montant à retirer
    currency: "XAF"               // Optionnel, défaut "XAF"
  },
  headers: {
    session_token: "USER_SESSION_ID"  // Token/session utilisateur
  }
  returns: {
  status: string       // "success"
  balance: double      // retourne le solde du client
  notifications : string  // liste des notifications
}
}












