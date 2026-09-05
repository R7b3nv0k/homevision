// --- Sötét / Világos mód kezelése és perzisztálása (LocalStorage) ---
const htmlElement = document.documentElement;
const savedTheme = localStorage.getItem('homevision_theme');
if (savedTheme) {
    htmlElement.setAttribute('data-theme', savedTheme);
}

const themeToggleBtn = document.getElementById('themeToggle');
if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', () => {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        htmlElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('homevision_theme', newTheme);
    });
}


// --- Login / Register Modal logika sima animációkkal ---
const modal = document.getElementById('authModal');
const loginBtn = document.getElementById('loginBtn');
const closeBtn = document.querySelector('.close-btn');
const switchModeBtn = document.getElementById('switchMode');
const modalTitle = document.getElementById('modalTitle');
const authSubmitBtn = document.querySelector('.auth-submit');

let isLogin = true; // Állapotkövetés

// Modál megnyitása
function openModal() {
    if (modal) {
        modal.classList.add('show');
    }
}

// Modál bezárása
function closeModal() {
    if (modal) {
        modal.classList.remove('show');
    }
}

if (loginBtn) {
    loginBtn.addEventListener('click', openModal);
}

if (closeBtn) {
    closeBtn.addEventListener('click', closeModal);
}

// Kattintás a modálon kívül -> bezárás
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});

// ESC billentyű -> modál bezárása
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
        closeModal();
    }
});

// Váltás Login és Register között (csak vizuális)
if (switchModeBtn) {
    switchModeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        isLogin = !isLogin;

        if (isLogin) {
            modalTitle.setAttribute('data-i18n', 'login_title');
            authSubmitBtn.setAttribute('data-i18n', 'login_submit');
            switchModeBtn.setAttribute('data-i18n', 'register_link');
            switchModeBtn.previousElementSibling.setAttribute('data-i18n', 'no_account');
        } else {
            modalTitle.setAttribute('data-i18n', 'register_title');
            authSubmitBtn.setAttribute('data-i18n', 'register_submit');
            switchModeBtn.setAttribute('data-i18n', 'login_link');
            switchModeBtn.previousElementSibling.setAttribute('data-i18n', 'has_account');
        }
        updateLanguage(currentLang);
    });
}


// --- Gombok dinamikus kurzorkövető fényhatása (Interactive Cursor Glow) ---
function initButtonGlow() {
    const buttons = document.querySelectorAll('.btn-primary, .btn-large, .btn-icon, .btn-text');
    buttons.forEach(button => {
        button.addEventListener('mousemove', (e) => {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            button.style.setProperty('--mouse-x', `${x}px`);
            button.style.setProperty('--mouse-y', `${y}px`);
        });
    });
}

document.addEventListener('DOMContentLoaded', initButtonGlow);
initButtonGlow();


// --- Nyelvváltás (Magyar / Angol / Spanyol) & Perzisztálás ---
const langToggleBtn = document.getElementById('langToggle');

// Következő nyelv felirata a gombra
const nextLangMap = {
    hu: 'EN',
    en: 'ES',
    es: 'HU'
};

// Szótár az oldal szövegeihez (HU, EN, ES)
const translations = {
    hu: {
        home: "Főoldal", plans: "Tervek", implementation: "Megvalósítás", media: "Média", about: "Rólunk", contact: "Kapcsolat",
        login: "Bejelentkezés",
        hero_title: "Tervezd meg a jövőd otthonát",
        hero_desc: "Prémium minőségű, azonnal megvásárolható háztervek és inspirációk egy helyen.",
        hero_cta: "Tervek felfedezése",
        login_title: "Bejelentkezés", login_submit: "Belépés",
        no_account: "Nincs még fiókod?", register_link: "Regisztrálj!",
        register_title: "Regisztráció", register_submit: "Fiók létrehozása",
        has_account: "Már van fiókod?", login_link: "Lépj be!",
        plans_title: "Megvásárolható Terveink",
        sqm: "120 m²", rooms: "4 szoba",
        sqm_150: "150 m²", rooms_5: "5 szoba",
        sqm_80: "80 m²", rooms_3: "3 szoba",
        details_btn: "Részletek",
        media_title: "Már megépült otthonok (Galéria)",
        about_title: "Kik vagyunk mi?",
        about_text_1: "A Home Vision egy fiatal, innovatív csapat, amelynek célja, hogy a modern és élhető házterveket mindenki számára könnyen elérhetővé tegye.",
        about_text_2: "Hisszük, hogy a letisztult design és a funkcionális terek ötvözése teremti meg a tökéletes otthont. Terveinket úgy alkottuk meg, hogy azonnal építhetőek legyenek, időt és pénzt spórolva a jövőbeli építtetőknek.",
        contact_title: "Lépj velünk kapcsolatba",
        send_btn: "Üzenet küldése",
        back_btn: "Vissza a tervekhez",
        rotate_hint: "(Kattints és húzd a modell forgatásához, görgővel nagyíthatsz)",
        price_label: "Teljes tervdokumentáció ára:",
        brutto: "(bruttó)",
        buy_btn: "Terv Vásárlása",
        desc_title: "A terv leírása",
        nordic_desc: "A Nordic Family egy modern, skandináv stílusjegyeket magán viselő, egyszintes családi ház. Hatalmas üvegfelületeinek köszönhetően a nappali egész nap fényárban úszik. Az alaprajz különválasztja a szülői hálót a gyerekszobáktól, így biztosítva a maximális intimitást. A terv tartalmazza az építészeti, gépészeti és villamossági tervdokumentációt is.",
        // Megvalósítás oldal
        impl_badge: "Prémium Kivitelezés & Munkapartner",
        impl_title: "A Tervektől a Kulcsátadásig",
        impl_lead: "Házterveink nem csupán papíron léteznek — a Térmágus Kft.-vel karöltve valósággá váltjuk álmaid otthonát prémium minőségben, fix határidőkkel és garanciával.",
        partner_title: "Kiemelt Munkapartnerünk: Térmágus Kft.",
        partner_badge: "Hivatalos Kivitelező Partner",
        partner_desc: "A Térmágus Kft. professzionális építőipari generálkivitelezőként a Home Vision háztervek hivatalos és dedikált kivitelező partnere. Több évtizedes szakmai tapasztalattal, saját mérnökcsapattal és modern gépparkkal biztosítják, hogy a tervrajzokon megálmodott részletek milliméteres pontossággal valósuljanak meg a valóságban is.",
        partner_feat_1_title: "Kulcsrakész Generálkivitelezés",
        partner_feat_1_desc: "Az alapozástól az utolsó lámpatest felszereléséig mindent egy kézben tartunk, így nincs szükség külön alvállalkozók keresésére.",
        partner_feat_2_title: "Energiatakarékos Megoldások",
        partner_feat_2_desc: "A+ és A++ energetikai besorolású épületek, hőszivattyús fűtés, hővisszanyerős szellőztetés és napelem-előkészítés.",
        partner_feat_3_title: "Fix Árak és Garanciák",
        partner_feat_3_desc: "Szerződésben rögzített átadási határidők, rejtett költségek nélküli tételes árajánlat és teljes körű szerkezeti garancia.",
        workflow_title: "A Megvalósítás 4 Lépése",
        step_1_title: "1. Tervegyeztetés & Telekfelmérés",
        step_1_desc: "Kiválasztott háztervedet a Térmágus Kft. mérnökei a telek adottságaihoz, tájolásához és a helyi szabályzathoz igazítják.",
        step_2_title: "2. Részletes Költségvetés",
        step_2_desc: "Teljes körű, tételes árajánlat készítése ütemezett fizetési mérföldkövekkel és rögzített kivitelezési időtartammal.",
        step_3_title: "3. Precíz Kivitelezés",
        step_3_desc: "Folyamatos mérnöki felügyelet, prémium alapanyagok és rendszeres státuszjelentések a megrendelőnek.",
        step_4_title: "4. Kulcsátadás & Garancia",
        step_4_desc: "Sikeres műszaki átadás-átvétel, a használatbavételi engedély intézése és az új otthon kulcsainak átadása.",
        impl_cta_title: "Építsd fel velünk álmaid otthonát!",
        impl_cta_desc: "Vedd fel a kapcsolatot csapatunkkal és a Térmágus Kft. szakembereivel egy kötetlen, díjmentes konzultációért!",
        impl_cta_btn: "Konzultáció Kérése",
        // Google Értékelések
        reviews_badge: "Kiváló 4.9 ★★★★★ (64 Google vélemény)",
        reviews_title: "Amit ügyfeleink mondanak rólunk",
        reviews_lead: "Valós visszajelzések olyan építtetőktől, akik a Home Vision terveivel és a Térmágus Kft. professzionális kivitelezésével valósították meg új otthonukat.",
        rev_1_name: "Kovács Péter", rev_1_sub: "Helyi idegenvezető • 12 vélemény", rev_1_date: "2 hete",
        rev_1_text: "A Nordic Family tervet vásároltuk meg, és a Térmágus Kft. segítségével építettük fel. A tervezéstől a kulcsátadásig minden zökkenőmentes volt, a nappali hatalmas ablakai és a beáradó természetes fény egyszerűen lenyűgözőek!",
        rev_2_name: "Nagy Eszter", rev_2_sub: "Google felhasználó", rev_2_date: "1 hónapja",
        rev_2_text: "Nagyon örülök, hogy rátaláltunk a Home Visionre! A tervdokumentáció azonnal letölthető volt, a mérnöki csapat pedig minden kérdésünkre villámgyorsan és rendkívül szakszerűen válaszolt.",
        rev_3_name: "Tóth Balázs", rev_3_sub: "Helyi idegenvezető • 28 vélemény", rev_3_date: "3 hete",
        rev_3_text: "A Villa Minimal terv alapján építkeztünk. Tartottunk az elszálló költségektől, de a Térmágus Kft.-vel rögzített áron és pontos határidőre készült el az épület. 5 csillagos élmény!",
        rev_4_name: "Dr. Szabó Zoltán", rev_4_sub: "Google felhasználó", rev_4_date: "2 hónapja",
        rev_4_text: "Profi, modern szemléletű csapat. A weboldalon lévő interaktív 3D modell már előre tökéletes képet adott a terekről, a minőségi anyaghasználat és a kivitelezés pedig magáért beszél. Csak ajánlani tudom.",
        rev_5_name: "Varga Dániel", rev_5_sub: "Helyi idegenvezető • 8 vélemény", rev_5_date: "3 hete",
        rev_5_text: "Az Eco Compact tervet választottuk. A téli fűtésszámlánk szinte elenyésző a hőszivattyús rendszernek és az A+ szigetelésnek köszönhetően. Zseniális koncepció!",
        rev_6_name: "Kiss Mónika", rev_6_sub: "Google felhasználó", rev_6_date: "4 napja",
        rev_6_text: "Páratlan rugalmasság és segítőkészség! Külön dicséret a többnyelvű oldalért és a Térmágus Kft. megbízható generálkivitelezési garanciájáért. Köszönjük!",
        footer_desc: "Prémium minőségű, azonnal megvásárolható és építhető modern családi háztervek tervezőirodája.",
        footer_company_info: "Hivatalos Cégadatok",
        footer_hq: "Székhely:",
        footer_tax: "Adószám:",
        footer_reg: "Cégjegyzékszám:",
        footer_bank: "Bankszámla:",
        footer_quicklinks: "Gyorslinkek",
        footer_contact: "Elérhetőségek",
        footer_hours: "H-P: 09:00 - 17:00",
        footer_rights: "© 2026 Home Vision Kft. Minden jog fenntartva.",
        footer_credit: "Web oldalt készítette:Katona Ruben(Térmágus KFT.)"
    },
    en: {
        home: "Home", plans: "Plans", implementation: "Realization", media: "Media", about: "About us", contact: "Contact",
        login: "Login",
        hero_title: "Design your future home",
        hero_desc: "Premium quality, ready-to-buy house plans and inspiration in one place.",
        hero_cta: "Explore Plans",
        login_title: "Login", login_submit: "Sign In",
        no_account: "Don't have an account?", register_link: "Register!",
        register_title: "Registration", register_submit: "Create Account",
        has_account: "Already have an account?", login_link: "Sign in!",
        plans_title: "Available House Plans",
        sqm: "120 sqm", rooms: "4 rooms",
        sqm_150: "150 sqm", rooms_5: "5 rooms",
        sqm_80: "80 sqm", rooms_3: "3 rooms",
        details_btn: "View Details",
        media_title: "Built Homes (Gallery)",
        about_title: "Who are we?",
        about_text_1: "Home Vision is a young, innovative team dedicated to making modern and livable house plans easily accessible to everyone.",
        about_text_2: "We believe that combining clean design with functional spaces creates the perfect home. Our plans are created to be ready-to-build, saving time and money for future builders.",
        contact_title: "Get in touch with us",
        send_btn: "Send Message",
        back_btn: "Back to Plans",
        rotate_hint: "(Click and drag to rotate, scroll to zoom)",
        price_label: "Total Plan Documentation Price:",
        brutto: "(gross)",
        buy_btn: "Buy Plan",
        desc_title: "Plan Description",
        nordic_desc: "The Nordic Family is a modern, single-story family house with Scandinavian design elements. Its huge glass surfaces let the living room bathe in light all day long. The floor plan separates the parental bedroom from the children's rooms, ensuring maximum privacy. The plan includes architectural, mechanical, and electrical design documentation.",
        // Implementation page
        impl_badge: "Premium Construction & Work Partner",
        impl_title: "From Blueprint to Turnkey Handover",
        impl_lead: "Our house plans do not only exist on paper — hand in hand with Térmágus Kft., we turn your dream home into reality with premium quality, fixed deadlines, and warranties.",
        partner_title: "Featured Work Partner: Térmágus Kft.",
        partner_badge: "Official Construction Partner",
        partner_desc: "As a professional general construction contractor, Térmágus Kft. is the official and dedicated construction partner for Home Vision house plans. With decades of professional experience, an in-house engineering team, and modern technological equipment, they ensure that every detail envisioned on paper is built with millimeter precision.",
        partner_feat_1_title: "Turnkey General Construction",
        partner_feat_1_desc: "From the foundation to the final light fixture, everything is managed under one roof with no need to seek outside subcontractors.",
        partner_feat_2_title: "Energy Efficient Solutions",
        partner_feat_2_desc: "A+ and A++ energy rated buildings, heat pump heating, heat-recovery ventilation, and solar panel preparation.",
        partner_feat_3_title: "Fixed Pricing & Warranty",
        partner_feat_3_desc: "Contractually guaranteed handover deadlines, itemized transparent quotes with zero hidden costs, and comprehensive structural warranties.",
        workflow_title: "The 4 Steps of Execution",
        step_1_title: "1. Plan Review & Site Survey",
        step_1_desc: "Engineers from Térmágus Kft. adapt your selected house plan to your parcel orientation and local building regulations.",
        step_2_title: "2. Itemized Budgeting",
        step_2_desc: "Preparation of a comprehensive, itemized budget with scheduled payment milestones and a fixed construction timeframe.",
        step_3_title: "3. Precision Construction",
        step_3_desc: "Continuous engineering supervision, premium building materials, and regular photo and video progress reports.",
        step_4_title: "4. Turnkey Handover & Warranty",
        step_4_desc: "Successful technical handover, occupancy permit assistance, and delivering the keys to your brand-new dream home.",
        impl_cta_title: "Build your dream home with us!",
        impl_cta_desc: "Contact our team and the specialists at Térmágus Kft. for a complimentary, no-obligation consultation!",
        impl_cta_btn: "Request Consultation",
        // Google Reviews
        reviews_badge: "Excellent 4.9 ★★★★★ (64 Google Reviews)",
        reviews_title: "What Our Clients Say About Us",
        reviews_lead: "Real feedback from builders who turned their dream homes into reality with Home Vision plans and Térmágus Kft.'s professional execution.",
        rev_1_name: "Péter Kovács", rev_1_sub: "Local Guide • 12 reviews", rev_1_date: "2 weeks ago",
        rev_1_text: "We purchased the Nordic Family plan and built it with Térmágus Kft. From blueprint to turnkey handover, everything was smooth. The huge glass windows and flooded natural light in the living room are simply breathtaking!",
        rev_2_name: "Eszter Nagy", rev_2_sub: "Google user", rev_2_date: "1 month ago",
        rev_2_text: "So happy we found Home Vision! The plan documentation was instantly downloadable and their engineering team answered all our technical questions swiftly and professionally.",
        rev_3_name: "Balázs Tóth", rev_3_sub: "Local Guide • 28 reviews", rev_3_date: "3 weeks ago",
        rev_3_text: "We built our home based on the Villa Minimal plan. We were worried about budget escalations, but with Térmágus Kft. our house was built for a fixed contract price and delivered on time. A 5-star experience!",
        rev_4_name: "Dr. Zoltán Szabó", rev_4_sub: "Google user", rev_4_date: "2 months ago",
        rev_4_text: "Professional, forward-thinking team. The interactive 3D model on the site gave us an accurate picture beforehand, and the construction quality speaks for itself. Highly recommended!",
        rev_5_name: "Dániel Varga", rev_5_sub: "Local Guide • 8 reviews", rev_5_date: "3 weeks ago",
        rev_5_text: "We chose the Eco Compact design. Our heating bills are virtually zero thanks to the heat pump and superior A+ insulation. An absolute stroke of genius!",
        rev_6_name: "Mónika Kiss", rev_6_sub: "Google user", rev_6_date: "4 days ago",
        rev_6_text: "Exceptional flexibility and support! Big praise for the multilingual interface and the reliable construction warranty from Térmágus Kft. Thank you!",
        footer_desc: "Architectural design studio for premium quality, modern, ready-to-build house plans.",
        footer_company_info: "Company Details",
        footer_hq: "Headquarters:",
        footer_tax: "Tax ID:",
        footer_reg: "Reg. Number:",
        footer_bank: "Bank Account:",
        footer_quicklinks: "Quick Links",
        footer_contact: "Contact Us",
        footer_hours: "Mon-Fri: 09:00 - 17:00",
        footer_rights: "© 2026 Home Vision Ltd. All rights reserved.",
        footer_credit: "Web oldalt készítette:Katona Ruben(Térmágus KFT.)"
    },
    es: {
        home: "Inicio", plans: "Planos", implementation: "Construcción", media: "Galería", about: "Nosotros", contact: "Contacto",
        login: "Iniciar Sesión",
        hero_title: "Diseña la casa de tu futuro",
        hero_desc: "Planos arquitectónicos de calidad premium y listos para construir, con inspiración en un solo lugar.",
        hero_cta: "Explorar Planos",
        login_title: "Iniciar Sesión", login_submit: "Entrar",
        no_account: "¿No tienes una cuenta?", register_link: "¡Regístrate!",
        register_title: "Registro", register_submit: "Crear Cuenta",
        has_account: "¿Ya tienes cuenta?", login_link: "¡Inicia sesión!",
        plans_title: "Nuestros Planos Disponibles",
        sqm: "120 m²", rooms: "4 habitaciones",
        sqm_150: "150 m²", rooms_5: "5 habitaciones",
        sqm_80: "80 m²", rooms_3: "3 habitaciones",
        details_btn: "Ver Detalles",
        media_title: "Casas Construidas (Galería)",
        about_title: "¿Quiénes somos?",
        about_text_1: "Home Vision es un equipo joven e innovador cuyo objetivo es hacer que los planos de casas modernas y habitables sean accesibles para todos.",
        about_text_2: "Creemos que la unión del diseño limpio y los espacios funcionales crea el hogar perfecto. Nuestros planos están diseñados para ser construidos de inmediato, ahorrando tiempo y dinero a los futuros propietarios.",
        contact_title: "Ponte en contacto con nosotros",
        send_btn: "Enviar Mensaje",
        back_btn: "Volver a los planos",
        rotate_hint: "(Haz clic y arrastra para rotar el modelo, usa la rueda para hacer zoom)",
        price_label: "Precio de la documentación completa del plano:",
        brutto: "(bruto)",
        buy_btn: "Comprar Plano",
        desc_title: "Descripción del plano",
        nordic_desc: "Nordic Family es una moderna casa unifamiliar de una sola planta con características de diseño escandinavo. Gracias a sus enormes superficies acristaladas, el salón se inunda de luz natural durante todo el día. La distribución separa el dormitorio principal de las habitaciones infantiles, asegurando la máxima privacidad. El plano incluye la documentación de diseño arquitectónico, mecánico y eléctrico.",
        // Página de construcción
        impl_badge: "Construcción Premium & Socio de Trabajo",
        impl_title: "Del Plano a la Entrega de Llaves",
        impl_lead: "Nuestros planos no existen solo en papel: junto con Térmágus Kft., convertimos la casa de tus sueños en realidad con calidad premium, plazos fijos y garantía.",
        partner_title: "Socio Destacado: Térmágus Kft.",
        partner_badge: "Socio Oficial de Construcción",
        partner_desc: "Como empresa constructora general profesional, Térmágus Kft. es el socio oficial y exclusivo para la ejecución de los planos de Home Vision. Con décadas de experiencia, equipo propio de ingenieros y tecnología avanzada, aseguran una construcción milimétrica y fiel al diseño.",
        partner_feat_1_title: "Construcción General Llave en Mano",
        partner_feat_1_desc: "Desde los cimientos hasta el último detalle de iluminación, todo gestionado por un solo equipo profesional sin intermediarios.",
        partner_feat_2_title: "Eficiencia Energética Superior",
        partner_feat_2_desc: "Viviendas de clase A+ y A++, sistemas de aerotermia, ventilación con recuperación de calor y preinstalación de placas solares.",
        partner_feat_3_title: "Precios Fijos y Garantía Total",
        partner_feat_3_desc: "Plazos de entrega contractuales rigurosos, presupuestos transparentes sin sorpresas y garantía estructural total.",
        workflow_title: "Los 4 Pasos de la Construcción",
        step_1_title: "1. Revisión de Planos & Terreno",
        step_1_desc: "Los ingenieros de Térmágus Kft. adaptan el plano elegido a las características y orientación de tu parcela y a la normativa.",
        step_2_title: "2. Presupuesto Detallado",
        step_2_desc: "Elaboración de una oferta completa y pormenorizada con hitos de pago programados y plazos de obra fijos.",
        step_3_title: "3. Construcción de Precisión",
        step_3_desc: "Supervisión técnica continua, materiales de primera calidad e informes periódicos con fotos y vídeos para el cliente.",
        step_4_title: "4. Entrega de Llaves & Garantía",
        step_4_desc: "Inspección final satisfactoria, tramitación de licencias y entrega formal de las llaves de tu nuevo hogar.",
        impl_cta_title: "¡Construye con nosotros la casa de tus sueños!",
        impl_cta_desc: "¡Contacta con nuestro equipo y los especialistas de Térmágus Kft. para una consulta personalizada y gratuita!",
        impl_cta_btn: "Solicitar Consulta",
        // Google Reviews
        reviews_badge: "Excelente 4.9 ★★★★★ (64 Reseñas en Google)",
        reviews_title: "Lo que dicen nuestros clientes",
        reviews_lead: "Opiniones y experiencias reales de propietarios que hicieron realidad su hogar con los planos de Home Vision y la construcción de Térmágus Kft.",
        rev_1_name: "Péter Kovács", rev_1_sub: "Guía Local • 12 reseñas", rev_1_date: "Hace 2 semanas",
        rev_1_text: "Compramos el plano Nordic Family y lo construimos junto a Térmágus Kft. Desde los planos hasta la entrega de llaves todo fue impecable; ¡los grandes ventanales y la luz del salón son espectaculares!",
        rev_2_name: "Eszter Nagy", rev_2_sub: "Usuario de Google", rev_2_date: "Hace 1 mes",
        rev_2_text: "¡Qué gran acierto haber elegido Home Vision! La documentación se descargó al instante y su equipo de ingenieros resolvió cada duda con enorme rapidez y amabilidad.",
        rev_3_name: "Balázs Tóth", rev_3_sub: "Guía Local • 28 reseñas", rev_3_date: "Hace 3 semanas",
        rev_3_text: "Construimos nuestra casa con el plano Villa Minimal. Temíamos los sobrecostes habituales de obra, pero con Térmágus Kft. se completó a precio cerrado y en la fecha prevista. ¡5 estrellas!",
        rev_4_name: "Dr. Zoltán Szabó", rev_4_sub: "Usuario de Google", rev_4_date: "Hace 2 meses",
        rev_4_text: "Un equipo altamente cualificado e innovador. El modelo 3D interactivo nos permitió entender a la perfección cada espacio, y el resultado final construido es inmejorable.",
        rev_5_name: "Dániel Varga", rev_5_sub: "Guía Local • 8 reseñas", rev_5_date: "Hace 3 semanas",
        rev_5_text: "Elegimos el plano Eco Compact. Nuestra factura de climatización es mínima gracias al sistema de aerotermia y el aislamiento A+. ¡Una solución fantástica!",
        rev_6_name: "Mónika Kiss", rev_6_sub: "Usuario de Google", rev_6_date: "Hace 4 días",
        rev_6_text: "¡Atención al cliente y flexibilidad insuperables! Gran detalle disponer del sitio web en varios idiomas y contar con la garantía constructiva de Térmágus Kft.",
        footer_desc: "Estudio de diseño arquitectónico de planos de casas unifamiliares modernas, de calidad premium y listos para construir.",
        footer_company_info: "Datos Oficiales de la Empresa",
        footer_hq: "Sede:",
        footer_tax: "NIF/CIF:",
        footer_reg: "Nº de Registro Mercantil:",
        footer_bank: "Cuenta Bancaria:",
        footer_quicklinks: "Enlaces Rápidos",
        footer_contact: "Contacto",
        footer_hours: "L-V: 09:00 - 17:00",
        footer_rights: "© 2026 Home Vision S.L. Todos los derechos reservados.",
        footer_credit: "Web oldalt készítette:Katona Ruben(Térmágus KFT.)"
    }
};

// Mentett nyelv betöltése LocalStorage-ből
let currentLang = localStorage.getItem('homevision_lang') || 'hu';
if (!translations[currentLang]) currentLang = 'hu';

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

// Nyelv inicializálása azonnal
updateLanguage(currentLang);

if (langToggleBtn) {
    langToggleBtn.addEventListener('click', () => {
        // Ciklikus váltás: HU -> EN -> ES -> HU
        let nextLang = 'en';
        if (currentLang === 'hu') nextLang = 'en';
        else if (currentLang === 'en') nextLang = 'es';
        else if (currentLang === 'es') nextLang = 'hu';

        updateLanguage(nextLang);
    });
}

// --- Terv Vásárlása gomb logika (nordicfamily.html) ---
const buyPlanBtn = document.getElementById('buyPlanBtn');
if (buyPlanBtn) {
    buyPlanBtn.addEventListener('click', () => {
        openModal();
    });
}

// --- Mobil Hamburger Menü és Lenyíló Navigáció Kezelése ---
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const navLinks = document.querySelector('.nav-links');
const mobileBackdrop = document.querySelector('.mobile-nav-backdrop');

function toggleMobileMenu(isOpen) {
    const shouldOpen = isOpen !== undefined ? isOpen : !navLinks?.classList.contains('active');
    if (mobileMenuBtn) mobileMenuBtn.classList.toggle('active', shouldOpen);
    if (navLinks) navLinks.classList.toggle('active', shouldOpen);
    if (mobileBackdrop) mobileBackdrop.classList.toggle('active', shouldOpen);
    document.body.classList.toggle('menu-open', shouldOpen);
}

if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', () => {
        toggleMobileMenu();
    });
}

if (mobileBackdrop) {
    mobileBackdrop.addEventListener('click', () => {
        toggleMobileMenu(false);
    });
}

// Menüpontra kattintva a mobil menü záródjon be
if (navLinks) {
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            toggleMobileMenu(false);
        });
    });
}

// Escape billentyű lezárja a mobil menüt is
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && navLinks && navLinks.classList.contains('active')) {
        toggleMobileMenu(false);
    }
});

// Ablak átméretezésekor (ha visszavált desktop méretre) menü bezárása
window.addEventListener('resize', () => {
    if (window.innerWidth > 992 && navLinks && navLinks.classList.contains('active')) {
        toggleMobileMenu(false);
    }
});

