{{-- Ni pixel <noscript> (il enregistrerait sans consentement), ni panneau /admin, ni courriels, ni PDF. --}}
<!-- Matomo, Site ID 18, matomo.normaxis.com -->
<script>
  var _paq = window._paq = window._paq || [];
  {{-- Socle CNIL : Matomo n'est chargé qu'avec l'accord du visiteur (bandeau, politique de confidentialité). --}}
  window.chargerMatomo = function () {
      if (window.chargerMatomo.fait) { return; }
      window.chargerMatomo.fait = true;
      _paq.push(["setExcludedQueryParams", ["account","accountnum","address","address1","address2","address3","addressline1","addressline2","adres","adresse","adresse1","adresse2","adresse3","adresse_email","adresseemail","adressepostale","age","alter","auth","authpw","bic","billingaddress","billingaddress1","billingaddress2","calle","cardnumber","carte","cartebancaire","carteidentite","cb","cc","ccc","cccsc","cccvc","cccvv","ccexpiry","ccexpmonth","ccexpyear","ccname","ccnumber","cctype","cell","cellphone","city","civilite","civilit\u00e9","cle","clientid","clientsecret","cl\u00e9","codepostal","company","consumerkey","consumersecret","contrasenya","contrase\u00f1a","courriel","cp","creditcard","creditcardnumber","cvc","cvv","datedenaissance","dateexpiration","datenaissance","dateofbirth","debitcard","departement","direcci\u00f3n","dob","domain","d\u00e9partement","ebost","email","emailaddress","emailadresse","entreprise","epos","epost","eposta","exp","expiration","familyname","firma","firstname","formlogin","fullname","gender","genre","geschlecht","gst","gstnumber","handynummer","has\u0142o","heslo","iban","ibanaccountnum","ibanaccountnumber","id","identifiant","identifier","identitenationale","indirizzo","kartakredytowa","kennwort","keyconsumerkey","keyconsumersecret","konto","kontonr","kontonummer","kredietkaart","kreditkarte","kreditkort","lastname","login","mail","mdp","mobiili","mobile","mobilne","mot_de_passe","motdepasse","nachname","name","nationalite","nickname","nom","nomcomplet","nomdefamille","nomfamille","nss","numero_fiscal","numerocarte","numerocarteidentite","numerocompte","numerodecarte","numerofiscal","numeroidentite","numeromobile","numeropasseport","numerosecuritesociale","numerotelephone","numerotva","numfiscal","numsecu","numtva","osoite","parole","pass","passeport","passord","password","passwort","pasword","paswort","paword","pays","phone","pin","plz","portable","postalcode","postcode","postleitzahl","prenom","privatekey","pr\u00e9nom","publickey","pw","pwd","pword","pwrd","questionsecrete","region","reponsesecrete","rib","rue","secret","secretcl\u00e9","secretq","secretquestion","securitesociale","sexe","shippingaddress","shippingaddress1","shippingaddress2","signature","siren","siret","socialsec","socialsecuritynumber","societe","socsec","sokak","ssn","steuernummer","strasse","street","surname","swift","tax","taxnumber","tel","telefon","telefonnr","telefonnummer","telefono","telephone","titre","token","token_auth","tokenauth","tva","t\u00e9l\u00e9phone","ulica","user","username","utilisateur","vat","vatnumber","via","ville","voie","vorname","wachtwoord","wagwoord","webhooksecret","website","zip","zipcode"]]);
      // Parcours entre les sites du groupe (21/10/2026) : un lien vers un autre site du groupe porte l'identifiant
      // du visiteur (pk_vid, valable 10 minutes, le temps d'accepter la mesure sur le site d'arrivée) ; chaque page
      // part aussi vers le site Matomo 19 « GROUPE NORMAXIS », avec le même identifiant que celui du site.
      _paq.push(['setDomains', ['*.irice-certification.com', '*.irice.fr', '*.effinature.com', '*.biodiversity-performance.com', '*.efficarbone.com', '*.scope-impact.org', '*.biodiversite-immobilier.com', '*.novastone.fr', '*.stratoglide.com', '*.normatys.org', '*.normaxis.com']]);
      _paq.push(['enableCrossDomainLinking']);
      _paq.push(['setCrossDomainLinkingTimeout', 600]);
      if (!/[?&]pk_vid=/.test(location.search)) {
          _paq.push([function () { var t = Matomo.getAsyncTrackers(); if (t.length > 1) { t[1].setVisitorId(t[0].getVisitorId()); } }]);
      }
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//matomo.normaxis.com/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '18']);
        _paq.push(['addTracker', 'https://matomo.normaxis.com/matomo.php', '19']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
      })();
  };
  if (/(?:^|; )mesure_choix=ok/.test(document.cookie)) { window.chargerMatomo(); }
</script>
<script>
  /* Mesure des contacts (19/10/2026), mêmes noms que normaxis.com : « Contact / Clic courriel », « Contact / Clic
     téléphone », « Formulaire de contact / Envoi », avec l'adresse de la page. Les événements attendent dans la file
     _paq : rien ne part tant que le visiteur n'a pas accepté la mesure. Formulaires de connexion exclus, ainsi que ceux marqués data-sans-mesure (mot de passe oublié). */
  (function () {
    var mesurer = function (categorie, action) {
      (window._paq = window._paq || []).push(['trackEvent', categorie, action, window.location.pathname]);
    };
    document.addEventListener('click', function (e) {
      var lien = e.target && e.target.closest ? e.target.closest('a[href^="mailto:"], a[href^="tel:"]') : null;
      if (lien) { mesurer('Contact', lien.getAttribute('href').indexOf('tel:') === 0 ? 'Clic téléphone' : 'Clic courriel'); }
    }, true);
    document.addEventListener('submit', function (e) {
      var f = e.target;
      if (f && f.querySelector && f.querySelector('input[type="email"], textarea') && !f.querySelector('input[type="password"]') && !f.hasAttribute('data-sans-mesure')) {
        mesurer('Formulaire de contact', 'Envoi');
      }
    }, true);
  })();
</script>
<!-- End Matomo Code -->
@include('partials.rgpd.bandeau', ['politique' => route('confidentialite'), 'accent' => '#0C3342', 'lang' => 'fr'])
