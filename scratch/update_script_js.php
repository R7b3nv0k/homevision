<?php
$script = file_get_contents(__DIR__ . '/../js/script.js');
$translationsJs = file_get_contents(__DIR__ . '/translations_output.js');

// 1. Add SVG icon definitions at the top or before showTopNotification
$svgIconsBlock = <<<JS
// --- Home Vision Vektoros Piktogramok (SVG Icons) ---
const HV_ICONS = {
    warning: '<svg class="hv-icon" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    error: '<svg class="hv-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
    success: '<svg class="hv-icon" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
    info: '<svg class="hv-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
    user: '<svg class="hv-icon" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'
};
JS;

// Replace showTopNotification emoji icons
$oldShowTop = <<<OLD
    let icon = '⚠️';
    let title = 'Figyelem';
    if (type === 'error') {
        icon = '❌';
        title = 'Hiba történt';
    } else if (type === 'success') {
        icon = '✅';
        title = 'Sikeres művelet!';
    } else if (type === 'info') {
        icon = 'ℹ️';
        title = 'Értesítés';
    }
OLD;

$newShowTop = <<<NEW
    let icon = HV_ICONS.warning;
    let title = 'Figyelem';
    if (type === 'error') {
        icon = HV_ICONS.error;
        title = 'Hiba történt';
    } else if (type === 'success') {
        icon = HV_ICONS.success;
        title = 'Sikeres művelet!';
    } else if (type === 'info') {
        icon = HV_ICONS.info;
        title = 'Értesítés';
    }
NEW;

$script = str_replace($oldShowTop, $newShowTop, $script);

// Replace button replacement in auth handler
$oldAuthBtn = "newBtn.textContent = '👤 Profil';";
$newAuthBtn = "newBtn.innerHTML = HV_ICONS.user + ' <span class=\"i18n-text\">Profil</span>';";
$script = str_replace($oldAuthBtn, $newAuthBtn, $script);

// Replace translations object
$pattern = '/const translations = \{.*?\n\};/s';
if (!preg_match($pattern, $script)) {
    echo "ERROR: Could not match translations object in script.js\n";
    exit(1);
}
$script = preg_replace($pattern, trim($translationsJs), $script);

// Replace updateLanguage function with smarter one that preserves icons and handles text nodes
$oldUpdateLang = <<<OLD
function updateLanguage(lang) {
    currentLang = lang;
    localStorage.setItem('homevision_lang', lang);

    document.querySelectorAll('[data-i18n]').forEach(element => {
        const key = element.getAttribute('data-i18n');
        if (translations[lang] && translations[lang][key]) {
            element.textContent = translations[lang][key];
        }
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
        const key = element.getAttribute('data-i18n-placeholder');
        if (key === 'email') element.placeholder = lang === 'hu' ? 'Email cím' : (lang === 'es' ? 'Correo electrónico' : 'Email address');
        if (key === 'password') element.placeholder = lang === 'hu' ? 'Jelszó' : (lang === 'es' ? 'Contraseña' : 'Password');
        if (key === 'name') element.placeholder = lang === 'hu' ? 'Teljes név' : (lang === 'es' ? 'Nombre completo' : 'Full Name');
        if (key === 'message') element.placeholder = lang === 'hu' ? 'Üzenet...' : (lang === 'es' ? 'Mensaje...' : 'Message...');
    });

    if (langToggleBtn) {
        langToggleBtn.textContent = nextLangMap[lang] || 'EN';
    }
}
OLD;

$newUpdateLang = <<<NEW
function updateLanguage(lang) {
    currentLang = lang;
    localStorage.setItem('homevision_lang', lang);

    document.querySelectorAll('[data-i18n]').forEach(element => {
        const key = element.getAttribute('data-i18n');
        if (translations[lang] && translations[lang][key]) {
            const val = translations[lang][key];
            const textSpan = element.querySelector('.i18n-text');
            if (textSpan) {
                textSpan.textContent = val;
            } else if (element.children.length === 0) {
                element.textContent = val;
            } else {
                // Keressük a sima szöveges csomópontot az SVG vagy egyéb elemek mellett
                let hasTextNode = false;
                for (let node of element.childNodes) {
                    if (node.nodeType === Node.TEXT_NODE && node.nodeValue.trim()) {
                        node.nodeValue = ' ' + val;
                        hasTextNode = true;
                        break;
                    }
                }
                if (!hasTextNode) {
                    element.innerHTML = val;
                }
            }
        }
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(element => {
        const key = element.getAttribute('data-i18n-placeholder');
        if (translations[lang] && translations[lang][key]) {
            element.placeholder = translations[lang][key];
        } else {
            if (key === 'email') element.placeholder = lang === 'hu' ? 'Email cím' : (lang === 'es' ? 'Correo electrónico' : 'Email address');
            if (key === 'password') element.placeholder = lang === 'hu' ? 'Jelszó' : (lang === 'es' ? 'Contraseña' : 'Password');
            if (key === 'name') element.placeholder = lang === 'hu' ? 'Teljes név' : (lang === 'es' ? 'Nombre completo' : 'Full Name');
            if (key === 'phone') element.placeholder = lang === 'hu' ? 'Telefonszám (+36...)' : (lang === 'es' ? 'Número de teléfono' : 'Phone number');
            if (key === 'message') element.placeholder = lang === 'hu' ? 'Üzenet...' : (lang === 'es' ? 'Mensaje...' : 'Message...');
            if (key === 'city') element.placeholder = lang === 'hu' ? 'pl. Budapest' : (lang === 'es' ? 'ej. Madrid' : 'e.g. New York');
            if (key === 'address') element.placeholder = lang === 'hu' ? 'pl. Kossuth Lajos u. 12.' : (lang === 'es' ? 'ej. Calle Mayor 12' : 'e.g. 5th Avenue 12');
        }
    });

    if (langToggleBtn) {
        langToggleBtn.textContent = nextLangMap[lang] || 'EN';
    }
}
NEW;

$script = str_replace($oldUpdateLang, $newUpdateLang, $script);

// Prepend HV_ICONS before showTopNotification
$script = str_replace('// --- Felső Lebegő Értesítés (Top Notification Toast / Banner) ---', $svgIconsBlock . "\n\n// --- Felső Lebegő Értesítés (Top Notification Toast / Banner) ---", $script);

file_put_contents(__DIR__ . '/../js/script.js', $script);
echo "Successfully updated js/script.js!\n";
