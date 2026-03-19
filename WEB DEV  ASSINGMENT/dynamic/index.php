<?php
// User full name
$fullName = "Alokwem Emmanuel Chidera";
// Set timezone to Nigeria 
date_default_timezone_set('Africa/Lagos');
$currentDateTime = date('l, F j, Y \\a\\t g:i A');
$tzAbbr = date('T');
// If requested as AJAX time update, return JSON only
if (isset($_GET['time']) && $_GET['time'] == '1') {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['time' => $currentDateTime, 'tz' => $tzAbbr]);
  exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="Welcome page for Alokwem Emmanuel Chidera — registration number and current server time">
  <title>Welcome - <?php echo htmlspecialchars($fullName); ?></title>
  <link rel="icon" href="/static/images/favicon.svg" type="image/svg+xml">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg-1: #0f172a;
      --bg-2: #071129;
      --surface: #ffffff;
      --accent: #7c3aed; /* violet */
      --accent-2: #06b6d4; /* teal */
      --muted: #64748b;
      --radius:16px;
      --shadow: 0 14px 40px rgba(2,6,23,0.12);
    }
    html,body{height:100%;}
    body{
      margin:0;
      font-family: Inter, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
      color:#0b1220;
      background: linear-gradient(180deg, rgba(12,18,34,1) 0%, rgba(13,25,45,1) 40%, rgba(236,244,255,1) 100%);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }
    /* Top navigation */
    .site-header{position:sticky;top:12px;display:flex;justify-content:center;padding:12px 18px;z-index:100}
    .nav-inner{background:linear-gradient(90deg, rgba(255,255,255,0.06), rgba(255,255,255,0.03));padding:8px 14px;border-radius:999px;box-shadow:0 6px 20px rgba(2,6,23,0.15);display:flex;gap:12px;align-items:center}
    .nav-inner a{color:rgba(255,255,255,0.92);text-decoration:none;padding:8px 10px;border-radius:8px;font-weight:600}
    .nav-inner a.static-link{background:transparent;color:rgba(255,255,255,0.8)}
    .nav-inner a.active{background:rgba(255,255,255,0.12);backdrop-filter: blur(4px)}

    /* Layout */
    .wrap{max-width:1100px;margin:48px auto;padding:28px}
    .grid{display:grid;grid-template-columns:1fr 360px;gap:28px;align-items:start}

    .hero{background:transparent;padding:28px;border-radius:12px;color:rgba(255,255,255,0.96)}
    .title{font-family:Poppins, Inter, sans-serif;font-size:2.1rem;margin:0 0 6px 0;color:#fff}
    .subtitle{color:var(--muted);font-size:1.05rem;margin:0 0 18px 0}
    .lead{font-size:1rem;line-height:1.6;color:rgba(255,255,255,0.9);max-width:68ch}

    /* Time card */
    .time-card{background:var(--surface);border-radius:var(--radius);padding:18px;box-shadow:var(--shadow);color:#071129}
    .time-label{font-size:0.95rem;color:var(--muted);margin:0}
    .time-value{font-size:1.05rem;font-weight:700;color:var(--accent);margin-top:6px}
    .meta{margin-top:12px;color:var(--muted);font-size:0.95rem}
    .actions{margin-top:16px;display:flex;gap:8px}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:10px;border:none;cursor:pointer;font-weight:600}
    .btn.primary{background:linear-gradient(90deg,var(--accent),var(--accent-2));color:white}
    .btn.ghost{background:transparent;border:1px solid rgba(8,17,33,0.06);color:#0b1220}

    /* Responsive */
    @media (max-width:980px){
      .grid{grid-template-columns:1fr}
      .time-card{order:2}
    }
  </style>
</head>
<body>
  <header class="site-header" role="banner">
    <nav aria-label="Main navigation" class="nav-inner">
      <a class="static-link" href="file:///c:/Users/SIXTUS/Desktop/WEB%20DEV%20%20ASSINGMENT/static/index.html">Static</a>
      <a class="active" href="http://127.0.0.1:8080">Dynamic</a>
      <a href="../README.md">Docs</a>
    </nav>
  </header>
  <main class="wrap" role="main">
    <div class="grid">
      <section class="hero" aria-labelledby="welcome-heading">
        <h1 id="welcome-heading" class="title">Welcome, <?php echo htmlspecialchars($fullName); ?>!</h1>
        <p class="subtitle">Information Technology student · web3 enthusiast</p>
        <p class="lead">I build accessible and responsive web experiences and explore blockchain technologies. This dynamic page shows a live server time (pulled from PHP) and provides a compact profile overview.</p>
      </section>

      <aside class="time-card" aria-labelledby="time-heading">
        <p id="time-heading" class="time-label">Current date & time</p>
        <div class="time-value" role="status" aria-live="polite"><?php echo $currentDateTime . ' (' . $tzAbbr . ')'; ?></div>
        <div class="meta">Registration number: <strong>2023914007</strong></div>
        <div class="meta" style="margin-top:8px">About: <?php echo htmlspecialchars($fullName); ?>  Curious builder focused on web3 and practical solutions.</div>
        <div class="actions">
          <button id="refreshBtn" class="btn primary">Refresh time</button>
          <a class="btn ghost" href="file:///c:/Users/SIXTUS/Desktop/WEB%20DEV%20%20ASSINGMENT/static/index.html">Open static page</a>
        </div>
      </aside>
    </div>
  </main>
  <script>
    async function fetchTime(){
      try{
        const res = await fetch(window.location.pathname + '?time=1');
        if(!res.ok) throw new Error('Network');
        const data = await res.json();
        const el = document.querySelector('.time-value');
        if(el) el.textContent = data.time + ' (' + data.tz + ')';
      }catch(e){console.warn('Time refresh failed', e)}
    }
    document.getElementById('refreshBtn').addEventListener('click', function(e){ fetchTime(); });
    // auto refresh every 30s
    setInterval(fetchTime,30000);
  </script>
</body>
</html>