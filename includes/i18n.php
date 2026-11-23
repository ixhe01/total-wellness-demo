<?php
/* Idioma: ?lang=es|en → cookie compartida entre contentfactoryla.com y el subdominio */
function lang(): string {
    static $l = null;
    if ($l !== null) return $l;
    $l = 'en';
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'es'], true)) {
        $l = $_GET['lang'];
        if (!headers_sent()) {
            $host = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? '');
            $parts = explode('.', $host);
            $domain = (count($parts) >= 2 && !filter_var($host, FILTER_VALIDATE_IP)) ? '.' . implode('.', array_slice($parts, -2)) : '';
            setcookie('twc_lang', $l, ['expires' => time() + 31536000, 'path' => '/', 'domain' => $domain, 'samesite' => 'Lax']);
        }
    } elseif (in_array($_COOKIE['twc_lang'] ?? '', ['en', 'es'], true)) {
        $l = $_COOKIE['twc_lang'];
    }
    return $l;
}
function t(string $en, string $es): string { return lang() === 'es' ? $es : $en; }

/** URL actual con otro idioma */
function lang_switch_url(string $to): string {
    $q = $_GET; $q['lang'] = $to;
    $path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
    return $path . '?' . http_build_query($q);
}

/* Traducciones de catálogo (la BD guarda inglés) */
const ES_CATALOG = [
    // categorías
    'Consultation' => 'Consulta', 'Aesthetics' => 'Estética', 'Skin & Laser' => 'Piel y Láser', 'IV Therapy' => 'Terapia IV',
    'Weight Management' => 'Control de Peso', 'Primary Care' => 'Medicina General',
    // servicios
    'Free Wellness Consultation' => 'Consulta de bienestar gratuita', 'Botox / Neurotoxin' => 'Bótox / Neurotoxina',
    'Dermal Fillers' => 'Rellenos dérmicos', 'Lip Enhancement' => 'Aumento de labios', 'Signature Medical Facial' => 'Facial médico signature',
    'Microneedling' => 'Microneedling', 'Chemical Peel' => 'Peeling químico', 'Laser Hair Removal' => 'Depilación láser',
    "Myers' Cocktail IV" => "IV Myers' Cocktail", 'Hydration Boost IV' => 'IV de hidratación', 'Immunity IV' => 'IV de inmunidad',
    'NAD+ Infusion' => 'Infusión de NAD+', 'Vitamin B12 Shot' => 'Inyección de vitamina B12',
    'Medical Weight Loss Consultation' => 'Consulta de pérdida de peso', 'Weight Loss Follow-up' => 'Seguimiento de peso',
    'Annual Physical' => 'Chequeo anual', 'Sick Visit' => 'Consulta por enfermedad', 'Lab Work / Blood Draw' => 'Laboratorio / toma de sangre',
    // descripciones
    'Meet our team, share your goals and get a personalized plan.' => 'Conoce al equipo, cuéntanos tus metas y recibe un plan personalizado.',
    'Soften fine lines and wrinkles. Price per unit.' => 'Suaviza líneas finas y arrugas. Precio por unidad.',
    'Restore volume to cheeks, lips and jawline.' => 'Devuelve volumen a pómulos, labios y mandíbula.',
    'Natural-looking definition and hydration.' => 'Definición e hidratación con resultado natural.',
    'Deep cleanse, exfoliation, extraction and hydration.' => 'Limpieza profunda, exfoliación, extracción e hidratación.',
    'Collagen induction for texture, scars and pores.' => 'Inducción de colágeno para textura, cicatrices y poros.',
    'Brighter, smoother, more even-toned skin.' => 'Piel más luminosa, suave y uniforme.',
    'Long-lasting smooth skin, any area.' => 'Piel suave por más tiempo, en cualquier zona.',
    'Vitamins, minerals and hydration for energy and recovery.' => 'Vitaminas, minerales e hidratación para energía y recuperación.',
    'Rapid rehydration with electrolytes.' => 'Rehidratación rápida con electrolitos.',
    'High-dose vitamin C and zinc support.' => 'Vitamina C en dosis alta y zinc.',
    'Cellular energy, focus and healthy aging.' => 'Energía celular, concentración y envejecimiento saludable.',
    'Quick energy and metabolism support.' => 'Energía rápida y apoyo al metabolismo.',
    'Labs review, body composition and a physician-guided plan.' => 'Revisión de laboratorios, composición corporal y plan guiado por médico.',
    'Weekly check-in, progress tracking and adjustments.' => 'Revisión semanal, seguimiento y ajustes.',
    'Preventive exam, screenings and lab orders.' => 'Examen preventivo, estudios y órdenes de laboratorio.',
    'Same-week care for non-emergency illness.' => 'Atención en la misma semana para enfermedades no urgentes.',
    'In-office blood draw. Results reviewed by your provider.' => 'Toma de sangre en clínica. Tu proveedor revisa los resultados.',
    // equipo
    'Aesthetic Injector' => 'Inyectora estética', 'Medical Director' => 'Directora médica', 'Skin Specialist' => 'Especialista en piel', 'IV Nurse' => 'Enfermero IV',
    'RN · Injectables & Skin' => 'RN · Inyectables y piel', 'MD / NP · Primary Care & Weight Loss' => 'MD / NP · Medicina general y peso',
    'Licensed Esthetician · Laser' => 'Cosmetóloga certificada · Láser', 'LVN · IV Therapy & Wellness' => 'LVN · Terapia IV y bienestar',
    'Encino, CA — address coming soon' => 'Encino, CA — dirección próximamente',
];
function tr(string $s): string { return lang() === 'es' ? (ES_CATALOG[$s] ?? $s) : $s; }
