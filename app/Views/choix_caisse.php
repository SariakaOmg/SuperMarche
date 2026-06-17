<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Choix de la caisse – Supermarché</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --background: #ffffff;
      --foreground: #030213;
      --primary: #030213;
      --primary-foreground: #ffffff;
      --secondary: #f0f0f5;
      --muted: #ececf0;
      --muted-foreground: #717182;
      --accent: #e9ebef;
      --border: rgba(0,0,0,0.1);
      --input-background: #f3f3f5;
      --destructive: #d4183d;
      --radius: 0.625rem;
      --font-size: 16px;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      font-size: var(--font-size);
      background: linear-gradient(135deg, #f5f5fa 0%, #e9ebef 100%);
      color: var(--foreground);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── TOPBAR ── */
    .topbar {
      background: var(--primary);
      color: var(--primary-foreground);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem;
      height: 56px;
      flex-shrink: 0;
    }

    .topbar-brand {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      font-weight: 600;
      font-size: 1rem;
    }

    .topbar-brand svg { opacity: 0.9; }

    .topbar-user {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.875rem;
      opacity: 0.9;
    }

    .logout-btn {
      background: rgba(255,255,255,0.12);
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      border-radius: var(--radius);
      padding: 0.3rem 0.75rem;
      font-size: 0.8rem;
      cursor: pointer;
      transition: background 0.15s;
      text-decoration: none;
    }

    .logout-btn:hover { background: rgba(255,255,255,0.2); }

    /* ── MAIN ── */
    .main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: calc(var(--radius) + 4px);
      padding: 2.5rem;
      width: 100%;
      max-width: 560px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    }

    .card-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .card-icon {
      width: 56px;
      height: 56px;
      background: var(--muted);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
    }

    .card-header h2 {
      font-size: 1.4rem;
      font-weight: 600;
      margin-bottom: 0.4rem;
    }

    .card-header p {
      color: var(--muted-foreground);
      font-size: 0.9rem;
    }

    /* ── CAISSE GRID ── */
    .caisses-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .caisse-option {
      border: 2px solid var(--border);
      border-radius: calc(var(--radius) + 2px);
      padding: 1.25rem;
      cursor: pointer;
      transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
      text-align: center;
      position: relative;
    }

    .caisse-option:hover {
      border-color: #999;
      background: var(--muted);
    }

    .caisse-option.selected {
      border-color: var(--primary);
      background: #f0f0f5;
      box-shadow: 0 0 0 3px rgba(3,2,19,0.08);
    }

    .caisse-option input[type="radio"] {
      position: absolute;
      opacity: 0;
      width: 0;
      height: 0;
    }

    .caisse-number {
      font-size: 2rem;
      font-weight: 700;
      line-height: 1;
      margin-bottom: 0.4rem;
    }

    .caisse-label {
      font-size: 0.875rem;
      color: var(--muted-foreground);
      font-weight: 500;
    }

    .caisse-status {
      font-size: 0.75rem;
      margin-top: 0.5rem;
      display: inline-block;
      padding: 0.15rem 0.5rem;
      border-radius: 999px;
    }

    .status-libre {
      background: #d1fae5;
      color: #065f46;
    }

    .status-occupe {
      background: #fee2e2;
      color: #991b1b;
    }

    .error-msg {
      color: var(--destructive);
      font-size: 0.85rem;
      margin-bottom: 1rem;
      display: none;
    }

    .error-msg.visible { display: block; }

    .btn {
      width: 100%;
      padding: 0.75rem 1rem;
      background: var(--primary);
      color: var(--primary-foreground);
      border: none;
      border-radius: var(--radius);
      font-size: 0.95rem;
      font-weight: 500;
      cursor: pointer;
      transition: opacity 0.15s;
    }

    .btn:hover { opacity: 0.85; }
  </style>
</head>
<body>

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-brand">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>
      SuperMarché
    </div>
    <div class="topbar-user">
      <!-- En CI : <?= session()->get('username') ?> -->
      <span>👤 caissier01</span>
      <!-- En CI : href="<?= base_url('auth/logout') ?>" -->
      <a href="#" class="logout-btn">Déconnexion</a>
    </div>
  </header>

  <!-- MAIN -->
  <main class="main">
    <div class="card">
      <div class="card-header">
        <div class="card-icon">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#030213" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="3" width="20" height="14" rx="2"/>
            <path d="M8 21h8M12 17v4"/>
          </svg>
        </div>
        <h2>Choisir une caisse</h2>
        <p>Sélectionnez la caisse que vous allez utiliser pour cette session.</p>
      </div>

      <form id="caisseForm" action="/saisieAchat" method="POST">
        <!-- <?= csrf_field() ?> -->

        <div class="caisses-grid" id="caissesGrid">
  
  <?php if (!empty($caisses) && is_array($caisses)): ?>
    <?php foreach ($caisses as $c): ?>
      <label class="caisse-option" data-id="<?= esc($c['id']) ?>">
        <input type="radio" name="caisse_id" value="<?= esc($c['id']) ?>" />
        
        <div class="caisse-number"><?= sprintf("%02d", $c['id']) ?></div>
        <div class="caisse-label"><?= esc($c['libelle']) ?></div>
        
        <span class="caisse-status status-libre">Libre</span>
      </label>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="grid-column: span 2; text-align: center; color: var(--muted-foreground);">
      Aucune caisse disponible.
    </p>
  <?php endif; ?>

</div>
        <span class="error-msg" id="errCaisse">Veuillez sélectionner une caisse.</span>

        <button type="submit" class="btn">Ouvrir la caisse</button>
      </form>
    </div>
  </main>

  <script>
    // Gestion visuelle de la sélection
    const options = document.querySelectorAll('.caisse-option');
    options.forEach(function (opt) {
      opt.addEventListener('click', function () {
        options.forEach(function (o) { o.classList.remove('selected'); });
        opt.classList.add('selected');
        opt.querySelector('input[type="radio"]').checked = true;
      });
    });

    // Validation avant soumission
    const form = document.getElementById('caisseForm');
    const errCaisse = document.getElementById('errCaisse');

    form.addEventListener('submit', function (e) {
      // 1. On empêche temporairement pour faire la vérification
        e.preventDefault();
        errCaisse.classList.remove('visible');
        
        const selected = document.querySelector('input[name="caisse_id"]:checked');
        
        // 2. Si rien n'est sélectionné, on affiche l'erreur et on arrête tout
        if (!selected) {
          errCaisse.classList.add('visible');
          return;
        }
        
        // 3. SI TOUT EST OK : On force la soumission du formulaire vers PHP !
        form.submit();
    });
  </script>
</body>
</html>
