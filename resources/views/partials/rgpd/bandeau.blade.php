{{--
    Bandeau de consentement à la mesure d'audience (socle CNIL du groupe, 18/10/2026).
    Matomo n'est pas configuré pour l'exemption de la CNIL (adresses IP complètes) : il ne se charge
    qu'après « Accepter » (window.chargerMatomo, défini par le partial matomo). Refuser pèse le même
    poids qu'accepter. Le choix est gardé six mois dans le cookie « mesure_choix » (« ok » ou « refus »).
    Repris d'efficarbone pour OpenNAT (21/10/2026), sans test A/B.
    Le partial peut être inclus dans le <head> comme dans le <body> : le bandeau est un <template>
    recopié dans la page au chargement, et une seconde inclusion sur la même page est sans effet.
    Paramètres : $politique (URL de la politique de confidentialité), $accent (couleur des boutons),
    $lang ('fr' ou 'en', par défaut la langue de l'application).
--}}
@php
    $en = str_starts_with($lang ?? app()->getLocale(), 'en');
    $accent = $accent ?? '#2B2F44';
@endphp
<template id="mesure-bandeau-modele">
<div id="mesure-bandeau" role="region" aria-label="{{ $en ? 'Audience measurement' : 'Mesure d\'audience' }}" hidden>
    <p class="mb-titre">{{ $en ? 'Audience measurement' : 'Mesure d\'audience' }}</p>
    <p class="mb-texte">
        @if ($en)
            With your consent, this site counts its visits with Matomo, installed on our own servers in the European Union:
            no advertising, nothing passed on to third parties. Your choice is kept for 6 months and can be changed at any time.
        @else
            Avec votre accord, ce site compte ses visites avec Matomo, installé sur nos serveurs, dans l'Union européenne :
            aucune publicité, rien de transmis à un tiers. Votre choix est gardé 6 mois et modifiable à tout moment.
        @endif
        <a href="{{ $politique }}">{{ $en ? 'Privacy policy' : 'Politique de confidentialité' }}</a>
    </p>
    <div class="mb-actions">
        <button type="button" data-mesure="refuser">{{ $en ? 'Decline' : 'Refuser' }}</button>
        <button type="button" data-mesure="accepter">{{ $en ? 'Accept' : 'Accepter' }}</button>
    </div>
</div>
</template>
<style>
    #mesure-bandeau{position:fixed;left:16px;bottom:16px;z-index:2147483000;max-width:26rem;background:#fff;color:#1f2937;border:1px solid #e5e7eb;border-radius:10px;box-shadow:0 10px 30px rgba(0,0,0,.15);padding:16px 18px;font:14px/1.5 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif}
    #mesure-bandeau[hidden]{display:none}
    #mesure-bandeau .mb-titre{margin:0 0 4px;font-weight:600;font-size:15px}
    #mesure-bandeau .mb-texte{margin:0}
    #mesure-bandeau a{color:inherit;text-decoration:underline}
    #mesure-bandeau .mb-actions{display:flex;gap:8px;margin-top:12px}
    #mesure-bandeau button,.mesure-reglage button{flex:1;cursor:pointer;border-radius:6px;padding:8px 12px;font:600 14px/1.2 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;border:1px solid {{ $accent }};background:#fff;color:{{ $accent }}}
    #mesure-bandeau button:hover,.mesure-reglage button:hover{background:{{ $accent }};color:#fff}
    #mesure-bandeau button:focus-visible,.mesure-reglage button:focus-visible{outline:2px solid {{ $accent }};outline-offset:2px}
    @media (max-width:480px){#mesure-bandeau{left:12px;right:12px;bottom:12px;max-width:none}}
</style>
<script>
(function () {
    if (window.mesureAudience) { return; }
    var NOM = 'mesure_choix', DUREE = 182 * 86400;
    function lire() { var m = document.cookie.match(/(?:^|; )mesure_choix=([^;]*)/); return m ? m[1] : null; }
    function poser(v) { document.cookie = NOM + '=' + v + '; Max-Age=' + DUREE + '; Path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : ''); }
    function effacerTraceurs() {
        var hote = location.hostname, racine = hote.replace(/^www\./, '');
        document.cookie.split('; ').forEach(function (c) {
            var n = c.split('=')[0];
            if (/^_pk_/.test(n) || /_ab$/.test(n)) {
                ['', hote, '.' + racine].forEach(function (d) { document.cookie = n + '=; Max-Age=0; Path=/' + (d ? '; Domain=' + d : ''); });
            }
        });
    }
    var bandeau = null;
    function majReglages() {
        var choix = lire();
        var textes = {
            fr: { refus: 'Mesure refusée.', ok: 'Mesure acceptée.', aucun: "Aucun choix enregistré : la mesure n'est pas active." },
            en: { refus: 'Measurement declined.', ok: 'Measurement accepted.', aucun: 'No choice recorded: measurement is not active.' }
        };
        document.querySelectorAll('[data-mesure-statut]').forEach(function (el) {
            var t = textes[el.getAttribute('data-langue') === 'en' ? 'en' : 'fr'];
            el.textContent = t[choix === 'refus' || choix === 'ok' ? choix : 'aucun'];
        });
    }
    window.mesureAudience = {
        acceptee: function () { return lire() === 'ok'; },
        refuser: function () {
            poser('refus'); effacerTraceurs();
            // Matomo déjà chargé sur la page (accord retiré) : plus aucun envoi ni cookie.
            if (window._paq) { window._paq.push(['requireConsent']); window._paq.push(['forgetConsentGiven']); window._paq.push(['disableCookies']); }
            if (bandeau) { bandeau.hidden = true; } majReglages();
        },
        accepter: function () {
            poser('ok'); if (bandeau) { bandeau.hidden = true; } majReglages();
            if (window.chargerMatomo) { window.chargerMatomo(); }
        },
        ouvrir: function () { if (bandeau) { bandeau.hidden = false; } }
    };
    document.addEventListener('click', function (e) {
        var b = e.target.closest ? e.target.closest('[data-mesure]') : null;
        if (!b) { return; }
        var a = b.getAttribute('data-mesure');
        if (window.mesureAudience[a]) { e.preventDefault(); window.mesureAudience[a](); }
    });
    function demarrer() {
        var modele = document.getElementById('mesure-bandeau-modele');
        if (modele && !document.getElementById('mesure-bandeau')) { document.body.appendChild(modele.content.cloneNode(true)); }
        bandeau = document.getElementById('mesure-bandeau');
        if (bandeau && !lire()) { bandeau.hidden = false; }
        majReglages();
    }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', demarrer); } else { demarrer(); }
})();
</script>
