<?php
/** @var string $page_title */
$page_title = $page_title ?? CLINIC['name'] . ' | Medical · Wellness · Aesthetics in Los Angeles';
$base = base_url();
$home = $base . (($is_home ?? false) ? '' : '');
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow"><!-- DEMO: quitar al publicar -->
<title><?= e($page_title) ?></title>
<meta name="description" content="Total Wellness Clinic in Van Nuys & Encino: aesthetics, IV therapy, medical weight management and primary care under one roof.">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $base ?>assets/css/style.css?v=2">
<script>window.TW_API=<?= json_encode(app_url('api.php')) ?>;</script>
<style>:root{--logo:url("<?= e(img('logo_light', 700)) ?>")}</style>
</head>
<body class="<?= e($body_class ?? '') ?>">
<a class="skip" href="#main">Skip to content</a>

<div class="topbar">
  <div class="wrap topbar__in">
    <span class="topbar__loc">Van Nuys &nbsp;·&nbsp; Encino</span>
    <span class="topbar__links">
      <a href="tel:<?= CLINIC['phone_raw'] ?>"><?= CLINIC['phone'] ?></a>
      <a href="<?= $base ?>#contact">Contact Us</a>
      <a href="<?= $base ?>#locations">Locations</a>
      <a class="topbar__book" href="<?= app_url('book.php') ?>">Book Online Now</a>
    </span>
  </div>
</div>

<header class="header" id="top">
  <div class="wrap header__in">
    <a class="brand" href="<?= $base ?>" aria-label="Total Wellness Clinic — home">
      <span class="emblem" aria-hidden="true"></span>
      <span class="brand__txt"><span class="brand__name">TOTAL</span><span class="brand__sub">Wellness Clinic</span></span>
    </a>
    <nav class="nav" id="nav" aria-label="Main">
      <a href="<?= $base ?>#services">Services</a>
      <a href="<?= $base ?>#about">Our Story</a>
      <a href="<?= $base ?>#team">Meet the Team</a>
      <a href="<?= $base ?>#memberships">Memberships</a>
      <a href="<?= $base ?>#locations">Locations</a>
      <a href="<?= $base ?>#contact">Contact</a>
      <a class="btn btn--gold nav__cta" href="<?= app_url('book.php') ?>">Book Now</a>
    </nav>
    <button class="burger" aria-label="Open menu" aria-expanded="false" aria-controls="nav"><span></span><span></span><span></span></button>
  </div>
</header>
<main id="main">
