<?php
/**
 * Home Vision Vektoros Piktogram Rendszer (SVG Icons)
 * Minden ikon a téma szerinti aktuális szövegszínt örökli (currentColor),
 * így sötét és világos módban is tökéletesen és élesen jelenik meg.
 */

function hv_icon($name, $extraClass = '', $ariaLabel = '') {
    static $icons = null;
    if ($icons === null) {
        $icons = [
            // Téma váltó (Nap és Félhold)
            'theme' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" fill="none"/><path d="M12 3a9 9 0 0 1 0 18z" fill="currentColor"/></svg>',
            
            // Bejelentkezés kulcs
            'key' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 2l-2 2m-1.5 1.5l-3 3-2-2-4 4a6.5 6.5 0 1 0 2.5 2.5l4-4-2-2 3-3 1.5 1.5 2-2z"/><circle cx="7.5" cy="16.5" r="1.5" fill="currentColor"/></svg>',
            
            // Regisztráció / Szerkesztés
            'edit' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
            
            // Felhasználói profil
            'user' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
            
            // Kijelentkezés
            'logout' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
            
            // Telefon
            'phone' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
            
            // Mobiltelefon
            'mobile' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
            
            // Email boríték
            'mail' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
            
            // Nyitvatartás / Óra
            'clock' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            
            // Jelszó / Lakat
            'lock' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
            
            // Jelszó felfedése szem
            'eye' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
            
            // Tipp villanykörte
            'bulb' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18h6m-4 3h2M12 2a7 7 0 0 0-4 12.7V17a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2.3A7 7 0 0 0 12 2z"/></svg>',
            
            // Mentés lemez
            'save' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>',
            
            // Pipa
            'check' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>',
            
            // Főoldal / Ház
            'home' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
            
            // Garancia pajzs
            'shield' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
            
            // Energia / Villám
            'zap' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
            
            // Nívódíj / Kupa
            'award' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>',
            
            // Kiemelt / Bestseller lángnyelv
            'flame' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>',
            
            // Tervcsomag doboz
            'package' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>',
            
            // Bevásárlókocsi / Vásárlás
            'cart' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
            
            // Alaprajz vonalzó / Terv
            'ruler' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12L12 2l10 10-10 10L2 12zm7-7l2 2m-4 2l2 2m-4 2l2 2m-4 2l2 2"/></svg>',
            
            // Galéria / Látványterv kamera
            'camera' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
            
            // 3D Kocka / Modell
            'cube' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>',
            
            // Forgatás
            'rotate' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>',
            
            // Nap (Nappal)
            'sun' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>',
            
            // Hold (Éjszaka)
            'moon' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>',
            
            // Háztető
            'roof' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12l10-9 10 9M4 11v9h16v-9"/></svg>',
            
            // GYIK Kérdőjel
            'help' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            
            // Figyelmeztetés háromszög
            'warning' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            
            // Hiba X
            'error' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            
            // Sikeres körös pipa
            'success' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            
            // Információ körös i
            'info' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
            
            // Hálószoba / Ágy
            'bed' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/></svg>',
            
            // Fürdőszoba / Zuhanyzó
            'bath' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6h6m-3-3v3M4 12h16a1 1 0 0 1 1 1v3a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4v-3a1 1 0 0 1 1-1z"/><line x1="6" y1="20" x2="6" y2="22"/><line x1="18" y1="20" x2="18" y2="22"/></svg>',
            
            // Garázs / Autó
            'car' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2 11.2 2 11.6 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>',
            
            // Terasz / Növény / Zöld övezet
            'leaf' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>',
            
            // Admin korona
            'crown' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14v2H5z"/></svg>',
            
            // Dátum naptár
            'calendar' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
            
            // Üzenetek / Postaláda
            'inbox' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>',
            
            // Újratöltés / Frissítés
            'refresh' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>',

            // Kivitelezés daru / építkezés
            'construction' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 22h20M7 2v20M17 2v20M2 12h20M2 7h20M2 17h20"/></svg>',

            // Hivatalos dokumentum / Szerződés
            'file' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',

            // Jobbra mutató nyíl
            'arrow_right' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',

            // Lefelé mutató nyíl
            'arrow_down' => '<svg class="hv-icon" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>'
        ];
    }

    $svg = $icons[$name] ?? '';
    if (!$svg) return '';

    if ($extraClass) {
        $svg = str_replace('class="hv-icon"', 'class="hv-icon ' . htmlspecialchars($extraClass) . '"', $svg);
    }
    if ($ariaLabel) {
        $svg = str_replace('aria-hidden="true"', 'aria-label="' . htmlspecialchars($ariaLabel) . '" role="img"', $svg);
    }
    return $svg;
}
