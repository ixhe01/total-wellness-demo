<?php
/** @var string $page_title */
$L = lang(); // antes de cualquier salida (puede fijar cookie)
$page_title = $page_title ?? t('Total Wellness Clinic | Medical · Wellness · Aesthetics in Los Angeles', 'Total Wellness Clinic | Medicina · Bienestar · Estética en Los Ángeles');
$base = base_url();
$I18N = [ // textos para JS
    'sending' => t('Sending…', 'Enviando…'), 'thanks' => t('Thank you! We’ll be in touch within one business day.', '¡Gracias! Te contactaremos en un día hábil.'),
    'error' => t('Something went wrong. Please call us.', 'Algo salió mal. Por favor llámanos.'),
];
?><!doctype html>
<html lang="<?= $L ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow"><!-- DEMO: quitar al publicar -->
<meta name="theme-color" content="#FCF5EB">
<title><?= e($page_title) ?></title>
<meta name="description" content="<?= e(t('Total Wellness Clinic in Van Nuys & Encino: aesthetics, IV therapy, medical weight management and primary care under one roof.', 'Total Wellness Clinic en Van Nuys y Encino: estética, terapia IV, control de peso y medicina general en un solo lugar.')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $base ?>assets/css/style.css?v=4">
<script>window.TW_API=<?= json_encode(app_url('api.php')) ?>;window.TW_LANG=<?= json_encode($L) ?>;window.TW_T=<?= json_encode($I18N) ?>;</script>
<style>:root{--logo:url("<?= e(img('logo_light', 700)) ?>")}</style>
</head>
<body class="<?= e($body_class ?? '') ?>">
<a class="skip" href="#main"><?= t('Skip to content', 'Ir al contenido') ?></a>

<div class="topbar">
  <div class="wrap topbar__in">
    <span class="topbar__loc">Van Nuys &nbsp;·&nbsp; Encino</span>
    <span class="topbar__links">
      <a href="tel:<?= CLINIC['phone_raw'] ?>"><?= CLINIC['phone'] ?></a>
      <a href="<?= $base ?>#contact"><?= t('Contact Us', 'Contacto') ?></a>
      <a href="<?= $base ?>#locations"><?= t('Locations', 'Ubicaciones') ?></a>
      <a class="topbar__book" href="<?= app_url('book.php') ?>"><?= t('Book Online Now', 'Agenda en línea') ?></a>
    </span>
  </div>
</div>

<header class="header" id="top">
  <div class="wrap header__in">
    <a class="brand" href="<?= $base ?>" aria-label="Total Wellness Clinic">
      <span class="emblem" aria-hidden="true"></span>
      <span class="brand__txt"><span class="brand__name">TOTAL</span><span class="brand__sub">Wellness Clinic</span></span>
    </a>
    <nav class="nav" id="nav" aria-label="Main">
      <a href="<?= $base ?>#services"><?= t('Services', 'Servicios') ?></a>
      <a href="<?= $base ?>#about"><?= t('Our Story', 'Nosotros') ?></a>
      <a href="<?= $base ?>#team"><?= t('Meet the Team', 'Equipo') ?></a>
      <a href="<?= $base ?>#memberships"><?= t('Memberships', 'Membresías') ?></a>
      <a href="<?= $base ?>#locations"><?= t('Locations', 'Ubicaciones') ?></a>
      <a href="<?= $base ?>#contact"><?= t('Contact', 'Contacto') ?></a>
      <a class="btn btn--gold nav__cta" href="<?= app_url('book.php') ?>"><?= t('Book Now', 'Agendar') ?></a>
      <span class="nav__lang"><a href="<?= e(lang_switch_url('en')) ?>" class="<?= $L === 'en' ? 'on' : '' ?>">English</a><a href="<?= e(lang_switch_url('es')) ?>" class="<?= $L === 'es' ? 'on' : '' ?>">Español</a></span>
    </nav>
    <div class="header__tools">
      <a class="lang" href="<?= e(lang_switch_url($L === 'es' ? 'en' : 'es')) ?>" title="<?= $L === 'es' ? 'View in English' : 'Ver en español' ?>"><span class="lang__globe" aria-hidden="true"></span><?= $L === 'es' ? 'EN' : 'ES' ?></a>
      <button class="burger" aria-label="<?= t('Open menu', 'Abrir menú') ?>" aria-expanded="false" aria-controls="nav"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
<main id="main">
