{{--
    Section « Cookies et traceurs » des politiques de confidentialité (socle CNIL du groupe, 18/10/2026).
    Paramètres, tous facultatifs : $session (cookie de session, par défaut celui de config/session.php),
    $ab (cookie du test A/B, par défaut celui de config/conversion.php s'il existe des tests),
    $langue (['nom', 'durée'] ou null), $souvenir (nom du cookie « Se souvenir de moi », ou null), $matomo ('cookies', 'sans-cookie' ou null),
    $services (services tiers chargés par le site, parmi « bunny », « jsdelivr », « unpkg », « cartes »
    et « google-maps » : chacun est déclaré, puisqu'il reçoit l'adresse IP du visiteur),
    $lang ('fr' ou 'en', par défaut la langue de l'application).
    Le bloc de réglage s'appuie sur le bandeau (partial rgpd.bandeau), présent sur toutes les pages publiques.
    Matomo relève du consentement (adresses IP complètes, aucune purge réglée) : ne rien écrire ici
    sur une IP tronquée ou une durée de purge tant que la configuration de Matomo ne le permet pas.
--}}
@php
    $en = str_starts_with($lang ?? app()->getLocale(), 'en');
    $matomo = $matomo ?? 'cookies';
    $session = $session ?? config('session.cookie');
    $ab = $ab ?? (config('conversion.enabled', true) && config('conversion.slots') ? config('conversion.cookie') : null);
    $lignes = [];
    $lignes[] = [$session, $en ? 'Keeps your browsing session (strictly necessary)' : 'Maintien de la session de navigation (strictement nécessaire)', $en ? '2 hours' : '2 heures'];
    $lignes[] = ['XSRF-TOKEN', $en ? 'Protects forms against forged requests (strictly necessary)' : 'Protection des formulaires contre les requêtes forgées (strictement nécessaire)', $en ? '2 hours' : '2 heures'];
    if (! empty($souvenir)) {
        $lignes[] = [$souvenir, $en ? 'Keeps you signed in, only if you tick “Remember me” (strictly necessary)' : 'Maintien de la connexion, seulement si vous cochez « Se souvenir de moi » (strictement nécessaire)', $en ? '400 days' : '400 jours'];
    }
    if (! empty($langue)) {
        $lignes[] = [$langue[0], $en ? 'Remembers the language you chose' : 'Mémorisation de la langue choisie', $langue[1]];
    }
    $lignes[] = ['mesure_choix', $en ? 'Remembers your choice (consent or refusal) about audience measurement (strictly necessary)' : 'Mémorisation de votre choix (accord ou refus) sur la mesure d\'audience (strictement nécessaire)', $en ? '6 months' : '6 mois'];
    if (! empty($ab)) {
        $lignes[] = [$ab, $en ? 'Shows you the same version of a page during a layout test (A/B test), counted only in aggregate, without identifying you; not set if you decline measurement' : 'Affichage d\'une même version de page pendant un test de présentation (test A/B), compté seulement en totaux, sans vous identifier ; non déposé si vous refusez la mesure', $en ? '30 days' : '30 jours'];
    }
    if ($matomo === 'cookies') {
        $lignes[] = ['_pk_id', $en ? 'Audience measurement, only with your consent: tells visits apart' : 'Mesure d\'audience, seulement avec votre accord : distinction des visites', $en ? '13 months' : '13 mois'];
        $lignes[] = ['_pk_ses', $en ? 'Audience measurement, only with your consent: groups the pages of one visit' : 'Mesure d\'audience, seulement avec votre accord : regroupement des pages d\'une même visite', $en ? '30 minutes' : '30 minutes'];
    }
    $declarations = [
        'bunny' => [
            "Les polices de caractères sont chargées depuis Bunny Fonts (BunnyWay d.o.o., Slovénie, Union européenne), qui reçoit à cette occasion l'adresse IP du visiteur, sans déposer de cookie.",
            "Fonts are loaded from Bunny Fonts (BunnyWay d.o.o., Slovenia, European Union), which receives the visitor's IP address in the process, without setting any cookie.",
        ],
        'jsdelivr' => [
            "Certains scripts d'affichage (Alpine.js, Chart.js) sont chargés depuis le réseau de diffusion jsDelivr, qui reçoit l'adresse IP du visiteur et s'appuie sur des prestataires dont certains sont situés hors de l'Union européenne.",
            "Some display scripts (Alpine.js, Chart.js) are loaded from the jsDelivr delivery network, which receives the visitor's IP address and relies on providers some of which are located outside the European Union.",
        ],
        'unpkg' => [
            "La bibliothèque cartographique Leaflet est chargée depuis le réseau de diffusion unpkg, qui reçoit l'adresse IP du visiteur et s'appuie sur des prestataires situés hors de l'Union européenne.",
            "The Leaflet mapping library is loaded from the unpkg delivery network, which receives the visitor's IP address and relies on providers located outside the European Union.",
        ],
        'cartes' => [
            "L'outil TerraLink affiche, une fois une analyse lancée, une carte dont les fonds sont chargés depuis les serveurs d'OpenStreetMap (fondation OpenStreetMap, Royaume-Uni, pays reconnu par la Commission européenne comme assurant un niveau de protection adéquat) et de la Géoplateforme de l'IGN (France) : ces serveurs reçoivent l'adresse IP du visiteur pour lui transmettre les images de la carte.",
            "Once an analysis is launched, the TerraLink tool displays a map whose base layers are loaded from the servers of OpenStreetMap (OpenStreetMap Foundation, United Kingdom, a country recognised by the European Commission as providing an adequate level of protection) and of the French IGN Géoplateforme: these servers receive the visitor's IP address in order to send the map images.",
        ],
        'google-maps' => [
            "La carte Google Maps de la page d'accueil n'est chargée qu'après un clic sur « Afficher la carte » ; Google reçoit alors l'adresse IP du visiteur.",
            "The Google Maps map on the home page only loads after you click \"Show map\"; Google then receives the visitor's IP address.",
        ],
    ];
    $tiersTexte = collect($services ?? [])->map(fn ($c) => $declarations[$c][$en ? 1 : 0])->implode(' ');
    $td = 'padding:6px 10px;border-bottom:1px solid #e5e7eb;vertical-align:middle;text-align:left';
@endphp
<div class="rgpd-cookies">
    <style>.rgpd-cookies p{margin:0 0 .9em}.rgpd-cookies table{margin:0}</style>
    @if ($en)
        <p>This site sets no advertising cookie and loads no third-party tracking service{{ $tiersTexte === '' ? ': no Google service, no social network button, no advertising network.' : '. '.$tiersTexte }} Strictly necessary cookies{{ empty($ab) ? ' are' : ' and the A/B test cookie are' }} exempt from consent under the rules of the French data protection authority (CNIL); audience measurement cookies are only set with your consent.</p>
    @else
        <p>Ce site ne dépose aucun cookie publicitaire et ne charge aucun service de suivi tiers{{ $tiersTexte === '' ? ' : ni service Google, ni bouton de réseau social, ni régie publicitaire.' : '. '.$tiersTexte }} Les cookies strictement nécessaires{{ empty($ab) ? ' sont' : ' et le cookie de test A/B sont' }} dispensés de consentement au titre de l'article 82 de la loi Informatique et Libertés, dans les conditions fixées par la CNIL ; les cookies de mesure d'audience ne sont déposés qu'avec votre accord.</p>
    @endif
    <div style="overflow-x:auto;margin:12px 0">
        <table style="width:100%;border-collapse:collapse;table-layout:fixed;font-size:.95em">
            <colgroup><col style="width:24%"><col style="width:52%"><col style="width:24%"></colgroup>
            <thead>
                <tr>
                    <th style="{{ $td }};font-weight:600">{{ $en ? 'Name' : 'Nom' }}</th>
                    <th style="{{ $td }};font-weight:600">{{ $en ? 'Purpose' : 'Finalité' }}</th>
                    <th style="{{ $td }};font-weight:600;text-align:center">{{ $en ? 'Lifetime' : 'Durée' }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lignes as [$nom, $finalite, $duree])
                    <tr>
                        <td style="{{ $td }};font-family:ui-monospace,Consolas,monospace;word-break:break-all">{{ $nom }}</td>
                        <td style="{{ $td }}">{{ $finalite }}</td>
                        <td style="{{ $td }};text-align:center">{{ $duree }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($matomo)
        @if ($en)
            <p>With your consent, audience measurement uses Matomo, installed on the NORMAXIS group's servers in the European Union{{ $matomo === 'sans-cookie' ? ', in cookieless mode' : '' }}. Matomo is only loaded once you click « Accept ». It records the pages viewed, the referring site, the type of device and browser, and the IP address. It only serves to produce traffic statistics for the publisher of the site and for the NORMAXIS group, whose sites share one measurement: if you accept it on several of these sites, moving from one to another is linked by an identifier passed in the link, so as to follow your path between them. The data are neither sold nor passed on outside the group nor used for advertising. Visit data are kept for as long as the site is operated, in order to follow changes in its traffic. Legal basis: your consent, which you can withdraw at any time below.</p>
        @else
            <p>Avec votre accord, la mesure d'audience repose sur Matomo, installé sur les serveurs du groupe NORMAXIS, dans l'Union européenne{{ $matomo === 'sans-cookie' ? ', en mode sans cookie' : '' }}. Matomo n'est chargé qu'après un clic sur « Accepter ». Il enregistre les pages consultées, le site de provenance, le type de terminal et de navigateur, et l'adresse IP. Il sert uniquement à produire des statistiques de fréquentation pour l'éditeur du site et pour le groupe NORMAXIS, dont les sites partagent une même mesure : si vous l'acceptez sur plusieurs de ces sites, le passage de l'un à l'autre est relié par un identifiant transmis dans le lien, pour suivre votre parcours entre eux. Les données ne sont ni vendues, ni transmises hors du groupe, ni utilisées à des fins publicitaires. Les données de visite sont conservées pendant la durée d'exploitation du site, pour suivre l'évolution de sa fréquentation. Base légale : votre consentement, que vous pouvez retirer à tout moment ci-dessous.</p>
        @endif
        <div class="mesure-reglage" style="border:1px solid #e5e7eb;border-radius:8px;padding:12px 14px;margin:12px 0">
            <p style="margin:0 0 8px"><strong>{{ $en ? 'Your choice:' : 'Votre choix :' }}</strong> <span data-mesure-statut data-langue="{{ $en ? 'en' : 'fr' }}">{{ $en ? 'No choice recorded: measurement is not active.' : 'Aucun choix enregistré : la mesure n\'est pas active.' }}</span></p>
            <div style="display:flex;gap:8px;flex-wrap:wrap;max-width:24rem">
                <button type="button" data-mesure="refuser">{{ $en ? 'Decline measurement' : 'Refuser la mesure' }}</button>
                <button type="button" data-mesure="accepter">{{ $en ? 'Accept measurement' : 'Accepter la mesure' }}</button>
            </div>
        </div>
    @endif
    @if ($en)
        <p>You can also block or delete cookies in your browser settings; the strictly necessary cookies are then recreated at your next visit, and forms may not work without them.</p>
    @else
        <p>Vous pouvez aussi bloquer ou supprimer les cookies depuis les réglages de votre navigateur ; les cookies strictement nécessaires sont alors recréés à la visite suivante, et les formulaires peuvent ne pas fonctionner sans eux.</p>
    @endif
</div>
