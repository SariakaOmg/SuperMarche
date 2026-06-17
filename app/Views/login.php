<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
</head>
<body>

<div class="login-page">
  <div class="login-card">

    <div class="login-logo">
      <div class="logo-icon">
        <!-- logo -->
      </div>
      <div>
        <h1>SysInfo</h1>
        <span>Système d'Information</span>
      </div>
    </div>

    <h2>Connexion</h2>
    <p class="subtitle">Connectez-vous à votre espace</p>

    <form action="<?= site_url('auth/login') ?>" method="post">
      <div class="field-group">
        <label>Adresse e-mail</label>
        <div class="input-wrap">
          <div class="icon"></div>
          <input type="email" name="email" placeholder="vous@exemple.com" value="admin@sysinfo.mg" />
        </div>
      </div>

      <div class="field-group">
        <label>Mot de passe</label>
        <div class="input-wrap">
          <div class="icon"></div>
          <input type="password" name="password" placeholder="••••••••" value="password" />
        </div>
      </div>

      <div class="remember-row">
        <label>
          <input type="checkbox" checked />
          Se souvenir de moi
        </label>
      </div>

      <button class="btn btn-primary btn-full" type="submit">Se connecter</button>

    </form>

  </div>
</div>

<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>