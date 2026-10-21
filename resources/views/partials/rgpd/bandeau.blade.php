{{--
    Bandeau de consentement à la mesure d'audience, en barre fine en bas de page (OpenNAT, 21/10/2026, demande
    de Cédric : une seule ligne, la plus fine possible, boutons à droite). Socle CNIL du groupe du 18/10/2026 : Matomo garde les
    adresses IP complètes, il ne se charge donc qu'après « Accepter » (window.chargerMatomo, partial matomo).
    Le texte peut inciter, les boutons non : « Refuser » a exactement la même taille, la même place et le même
    style qu'« Accepter » (lignes directrices de la CNIL, motif des sanctions de 2021 et 2022 contre les refus
    plus difficiles que l'accord). Le choix est gardé six mois dans le cookie « mesure_choix » (« ok » ou « refus »).
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
<div id="mesure-bandeau" role="region" aria-label="{{ $en ? 'Audience measurement' : "Mesure d'audience" }}" hidden>
    <p class="mb-texte">
        @if ($en)
            Help us improve this site: audience measurement, no advertising.
        @else
            Aidez-nous à améliorer ce site : mesure d'audience, sans publicité.
        @endif
        <a href="{{ $politique }}">{{ $en ? 'Learn more' : 'En savoir plus' }}</a>
    </p>
    <div class="mb-actions">
        <button type="button" data-mesure="refuser">{{ $en ? 'Decline' : 'Refuser' }}</button>
        <button type="button" data-mesure="accepter">{{ $en ? 'Accept' : 'Accepter' }}</button>
    </div>
</div>
</template>
<style>
    #mesure-bandeau{position:fixed;left:0;right:0;bottom:0;z-index:2147483000;display:flex;align-items:center;justify-content:space-between;gap:12px;background:#fff;color:#1f2937;border-top:1px solid #e5e7eb;box-shadow:0 -2px 8px rgba(0,0,0,.06);padding:4px 12px;font:12px/1.3 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif}
    #mesure-bandeau[hidden]{display:none}
    #mesure-bandeau .mb-texte{margin:0;min-width:0}
    #mesure-bandeau a{color:inherit;text-decoration:underline;white-space:nowrap}
    #mesure-bandeau .mb-actions{display:flex;gap:6px;flex:none}
    #mesure-bandeau button{padding:3px 12px;font-size:12px;min-width:5.5rem}
    .mesure-reglage button{flex:1;padding:6px 12px;font-size:13px}
    #mesure-bandeau button,.mesure-reglage button{cursor:pointer;border-radius:5px;font-weight:600;font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;line-height:1.2;border:1px solid {{ $accent }};background:#fff;color:{{ $accent }}}
    #mesure-bandeau button:hover,.mesure-reglage button:hover{background:{{ $accent }};color:#fff}
    #mesure-bandeau button:focus-visible,.mesure-reglage button:focus-visible{outline:2px solid {{ $accent }};outline-offset:2px}
    @media (max-width:480px){#mesure-bandeau button{min-width:0;padding:3px 9px}}
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
