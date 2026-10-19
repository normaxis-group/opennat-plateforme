// Page de l'outil OpenNAT servie par la plateforme : ajoute le jeton CSRF de Laravel aux appels qui modifient
// (POST vers /api) et renvoie vers la connexion si la session a expiré.
(function () {
  const meta = document.querySelector('meta[name="csrf-token"]');
  const jeton = meta ? meta.content : '';
  const fetchOrigine = window.fetch.bind(window);
  window.fetch = function (ressource, options = {}) {
    const methode = (options.method || 'GET').toUpperCase();
    if (methode !== 'GET' && methode !== 'HEAD') {
      const entetes = new Headers(options.headers || {});
      entetes.set('X-CSRF-TOKEN', jeton);
      entetes.set('X-Requested-With', 'XMLHttpRequest');
      options = Object.assign({}, options, { headers: entetes, credentials: 'same-origin' });
    }
    return fetchOrigine(ressource, options).then(function (reponse) {
      if (reponse.status === 401 || reponse.status === 419) {
        window.location.href = '/connexion';
      }
      return reponse;
    });
  };
})();
