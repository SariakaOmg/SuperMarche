<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Saisie des achats – Supermarché</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --background: #ffffff;
      --foreground: #030213;
      --primary: #030213;
      --primary-foreground: #ffffff;
      --muted: #ececf0;
      --muted-foreground: #717182;
      --border: rgba(0,0,0,0.1);
      --input-background: #f3f3f5;
      --destructive: #d4183d;
      --success: #059669;
      --radius: 0.625rem;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      font-size: 16px;
      background: #f5f5fa;
      color: var(--foreground);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── TOPBAR ── */
    .topbar {
      background: var(--primary);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.5rem;
      height: 56px;
      flex-shrink: 0;
    }
    .topbar-brand { display: flex; align-items: center; gap: 0.6rem; font-weight: 600; }
    .topbar-right { display: flex; align-items: center; gap: 1.25rem; font-size: 0.875rem; }
    .caisse-badge {
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.25);
      border-radius: var(--radius);
      padding: 0.3rem 0.75rem;
      font-weight: 600;
      font-size: 0.85rem;
    }
    .logout-btn {
      background: rgba(255,255,255,0.12);
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      border-radius: var(--radius);
      padding: 0.3rem 0.75rem;
      font-size: 0.8rem;
      text-decoration: none;
      transition: background 0.15s;
    }
    .logout-btn:hover { background: rgba(255,255,255,0.2); }

    /* ── FLASH MESSAGES ── */
    .flash {
      margin: 1rem 1.5rem 0;
      padding: 0.75rem 1rem;
      border-radius: var(--radius);
      font-size: 0.875rem;
    }
    .flash-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .flash-error   { background: #fff0f3; color: var(--destructive); border: 1px solid #fac5d0; }

    /* ── LAYOUT ── */
    .layout {
      flex: 1;
      display: grid;
      grid-template-columns: 1fr 360px;
      gap: 1.25rem;
      padding: 1.25rem 1.5rem;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
    }

    /* ── CARD ── */
    .card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: calc(var(--radius) + 2px);
      padding: 1.5rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .card h3 {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* ── FORM ADD ── */
    .add-form {
      display: grid;
      grid-template-columns: 1fr auto auto;
      gap: 0.75rem;
      align-items: end;
    }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; }
    label { font-size: 0.8rem; font-weight: 500; color: var(--muted-foreground); }

    select, input[type="number"] {
      padding: 0.55rem 0.75rem;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background: var(--input-background);
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.15s;
    }
    select:focus, input[type="number"]:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(3,2,19,0.07);
    }

    /* ── BUTTONS ── */
    .btn {
      padding: 0.55rem 1rem;
      border: none;
      border-radius: var(--radius);
      font-size: 0.875rem;
      font-weight: 500;
      cursor: pointer;
      transition: opacity 0.15s;
      white-space: nowrap;
    }
    .btn-primary  { background: var(--primary); color: #fff; }
    .btn-primary:hover { opacity: 0.85; }
    .btn-danger   { background: transparent; border: 1px solid var(--border); color: var(--destructive); padding: 0.3rem 0.6rem; font-size: 0.8rem; }
    .btn-danger:hover  { background: #fff0f3; }
    .btn-success  { background: var(--success); color: #fff; width: 100%; padding: 0.75rem; font-size: 1rem; }
    .btn-success:hover { opacity: 0.88; }
    .btn-outline  { background: transparent; border: 1px solid var(--border); color: var(--foreground); width: 100%; padding: 0.6rem; font-size: 0.875rem; text-align: center; text-decoration: none; display: block; }
    .btn-outline:hover { background: var(--muted); }

    /* ── TABLE ── */
    .table-wrapper { overflow-x: auto; margin-top: 1rem; }
    table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
    thead th {
      text-align: left;
      padding: 0.6rem 0.75rem;
      color: var(--muted-foreground);
      font-weight: 500;
      border-bottom: 1px solid var(--border);
      font-size: 0.8rem;
    }
    tbody tr { border-bottom: 1px solid var(--border); transition: background 0.1s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #fafafa; }
    td { padding: 0.65rem 0.75rem; vertical-align: middle; }
    .badge-qty { background: var(--muted); border-radius: 999px; padding: 0.15rem 0.55rem; font-size: 0.8rem; font-weight: 600; }
    .text-right  { text-align: right; }
    .text-center { text-align: center; }

    .empty-state { text-align: center; color: var(--muted-foreground); padding: 2.5rem 1rem; font-size: 0.9rem; }
    .empty-state svg { display: block; margin: 0 auto 0.75rem; opacity: 0.3; }

    .recap { display: flex; flex-direction: column; gap: 1.25rem; }
    .info-row { display: flex; justify-content: space-between; font-size: 0.875rem; padding: 0.3rem 0; }
    .info-row .lbl { color: var(--muted-foreground); }
    .info-row .val { font-weight: 500; }
    .separator { border: none; border-top: 1px solid var(--border); margin: 0.25rem 0; }
    .total-row { display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 700; padding: 0.5rem 0; }
    .total-val { color: var(--success); }
    .actions { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; }

    .btn-success:disabled { opacity: 0.6; cursor: not-allowed; }
  </style>
</head>
<body>

  <header class="topbar">
    <div class="topbar-brand">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>
      SuperMarché
    </div>
    <div class="topbar-right">
      <span class="caisse-badge"> <?= esc($caisse['libelle'] ?? 'Caisse') ?></span>
      <a href="<?= base_url('/') ?>" class="logout-btn">Changer de caisse</a>
    </div>
  </header>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="flash flash-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="flash flash-error"><?= esc(session()->getFlashdata('error')) ?></div>
  <?php endif; ?>

  <div class="layout">

    <div style="display:flex; flex-direction:column; gap:1.25rem;">

      <div class="card">
        <h3>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
          </svg>
          Ajouter un produit
        </h3>

        <div id="stockAlert" style="display:none; margin-bottom:0.75rem; padding:0.6rem 0.9rem; background:#fff0f3; border:1px solid #fac5d0; border-radius:var(--radius); color:var(--destructive); font-size:0.85rem;"></div>

        <div class="add-form">
          <div class="form-group">
            <label for="produit_id">Produit</label>
            <select id="produit_id">
              <option value="">-- Sélectionner --</option>
              <?php foreach ($produits as $p): ?>
                <option value="<?= esc($p['id']) ?>"
                        data-prix="<?= esc($p['prix_unitaire']) ?>"
                        data-stock="<?= esc($p['quantite_stock']) ?>">
                  <?= esc($p['libelle']) ?> — <?= number_format($p['prix_unitaire'], 0, ',', ' ') ?> Ar
                  (stock: <?= esc($p['quantite_stock']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="quantite">Quantité</label>
            <input type="number" id="quantite" value="1" min="1" style="width:90px;" />
          </div>
          <button type="button" class="btn btn-primary" id="btnAjouter">+ Ajouter</button>
        </div>
      </div>

      <div class="card">
        <h3>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
            <rect x="9" y="3" width="6" height="4" rx="1"/>
          </svg>
          Articles de l'achat en cours
        </h3>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Désignation</th>
                <th class="text-center">Qté</th>
                <th class="text-right">Prix unit.</th>
                <th class="text-right">Sous-total</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="lignesBody">
              <tr id="emptyRow">
                <td colspan="6">
                  <div class="empty-state">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                      <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    Aucun article ajouté.
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div>
      <div class="card recap">
        <h3>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
            <line x1="1" y1="10" x2="23" y2="10"/>
          </svg>
          Récapitulatif
        </h3>

        <div>
          <div class="info-row">
            <span class="lbl">Caisse</span>
            <span class="val"><?= esc($caisse['libelle'] ?? '—') ?></span>
          </div>
          <div class="info-row">
            <span class="lbl">Nb articles</span>
            <span class="val" id="recapNbArticles">0</span>
          </div>
        </div>

        <hr class="separator" />

        <div class="total-row">
          <span>Total</span>
          <span class="total-val" id="recapTotal">0 Ar</span>
        </div>

        <div class="actions">
          <button class="btn btn-success" id="btnCloturer">
            ✓ Clôturer l'achat
          </button>
          <a href="<?= base_url('/') ?>" class="btn btn-outline">
            Changer de caisse
          </a>
        </div>
      </div>
    </div>

  </div>

  <form id="cloturerForm" action="<?= base_url('/achat/cloturer') ?>" method="POST" style="display:none;">
    <?= csrf_field() ?>
    <input type="hidden" name="caisse_id" value="<?= esc($caisse['id'] ?? '') ?>" />
    <div id="hiddenProduits"></div>
  </form>

  <script>
    var catalogueProduits = {};
    <?php foreach ($produits as $p): ?>
    catalogueProduits[<?= (int)$p['id'] ?>] = {
      id:     <?= (int)$p['id'] ?>,
      nom:    "<?= addslashes(esc($p['libelle'])) ?>",
      prix:   <?= (float)$p['prix_unitaire'] ?>,
      stock:  <?= (int)$p['quantite_stock'] ?>
    };
    <?php endforeach; ?>

    var lignes   = [];  
    var compteur = 0;

    function formatAr(n) {
      return n.toLocaleString('fr-FR') + ' Ar';
    }

    function renderTable() {
      var tbody    = document.getElementById('lignesBody');
      var emptyRow = document.getElementById('emptyRow');

      var rows = tbody.querySelectorAll('tr.ligne-produit');
      rows.forEach(function(r) { r.remove(); });

      if (lignes.length === 0) {
        emptyRow.style.display = '';
        updateRecap();
        return;
      }

      emptyRow.style.display = 'none';

      lignes.forEach(function(l, i) {
        var tr = document.createElement('tr');
        tr.className = 'ligne-produit';
        tr.innerHTML =
          '<td>' + (i + 1) + '</td>' +
          '<td>' + l.nom + '</td>' +
          '<td class="text-center"><span class="badge-qty">' + l.quantite + '</span></td>' +
          '<td class="text-right">' + formatAr(l.prix) + '</td>' +
          '<td class="text-right"><strong>' + formatAr(l.prix * l.quantite) + '</strong></td>' +
          '<td><button class="btn btn-danger" onclick="supprimerLigne(' + l.id + ')">✕</button></td>';
        tbody.appendChild(tr);
      });

      updateRecap();
    }

    function updateRecap() {
      var total = lignes.reduce(function(s, l) { return s + l.prix * l.quantite; }, 0);
      var nbArt = lignes.reduce(function(s, l) { return s + l.quantite; }, 0);
      document.getElementById('recapTotal').textContent      = formatAr(total);
      document.getElementById('recapNbArticles').textContent = nbArt;
    }

    function supprimerLigne(id) {
      lignes = lignes.filter(function(l) { return l.id !== id; });
      renderTable();
    }

    document.getElementById('btnAjouter').addEventListener('click', function() {
      var select    = document.getElementById('produit_id');
      var qteInput  = document.getElementById('quantite');
      var stockAlert = document.getElementById('stockAlert');
      stockAlert.style.display = 'none';

      var produitId = parseInt(select.value);
      var qte       = parseInt(qteInput.value) || 1;

      if (!produitId) { alert('Sélectionnez un produit.'); return; }
      if (qte < 1)    { alert('La quantité doit être ≥ 1.'); return; }

      var p = catalogueProduits[produitId];
      if (!p) { alert('Produit introuvable.'); return; }

      var dejaEnPanier = 0;
      var existant = lignes.find(function(l) { return l.produitId === produitId; });
      if (existant) dejaEnPanier = existant.quantite;

      if (dejaEnPanier + qte > p.stock) {
        stockAlert.textContent = 'Stock insuffisant pour « ' + p.nom + ' ». Disponible : ' + p.stock + ', déjà au panier : ' + dejaEnPanier + '.';
        stockAlert.style.display = 'block';
        return;
      }

      if (existant) {
        existant.quantite += qte;
      } else {
        compteur++;
        lignes.push({ id: compteur, produitId: produitId, nom: p.nom, prix: p.prix, quantite: qte });
      }

      renderTable();
      select.value   = '';
      qteInput.value = 1;
    });

    document.getElementById('btnCloturer').addEventListener('click', function() {
      if (lignes.length === 0) {
        alert('Aucun article dans l\'achat.');
        return;
      }

      if (!confirm('Clôturer cet achat et enregistrer en base de données ?')) return;

      var container = document.getElementById('hiddenProduits');
      container.innerHTML = '';
      lignes.forEach(function(l, i) {
        container.innerHTML +=
          '<input type="hidden" name="produits[' + i + '][produit_id]" value="' + l.produitId + '">' +
          '<input type="hidden" name="produits[' + i + '][quantite]"   value="' + l.quantite + '">';
      });

      document.getElementById('btnCloturer').disabled     = true;
      document.getElementById('btnCloturer').textContent  = '⏳ Enregistrement…';

      document.getElementById('cloturerForm').submit();
    });
  </script>
</body>
</html>
