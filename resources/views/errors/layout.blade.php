<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Error' }} · Ichhe Puran</title>
    <style>
        :root {
            --forest: #1F5D42;
            --forest-dark: #123828;
            --sage: #86A789;
            --mustard: #E9B949;
            --mustard-dark: #C99A2E;
            --cream: #F7F6F0;
            --pale-green: #EAF2E8;
            --charcoal: #24302A;
            --charcoal-soft: #5A665E;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, var(--forest) 0%, var(--forest-dark) 100%);
            font-family: -apple-system, "Segoe UI", ui-sans-serif, system-ui, sans-serif;
            color: var(--charcoal);
            padding: 24px;
            position: relative;
            overflow: hidden;
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(2px);
            opacity: 0.14;
            pointer-events: none;
        }
        .blob--1 { width: 420px; height: 420px; background: var(--mustard); top: -140px; right: -120px; }
        .blob--2 { width: 360px; height: 360px; background: var(--sage); bottom: -160px; left: -100px; }
        .card {
            position: relative;
            background: var(--cream);
            border-radius: 28px;
            max-width: 480px;
            width: 100%;
            padding: 56px 44px;
            text-align: center;
            box-shadow: 0 24px 60px rgba(18, 56, 40, 0.35);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 999px;
            background: var(--pale-green);
            color: var(--forest);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .code {
            font-family: ui-serif, Georgia, "Times New Roman", serif;
            font-weight: 800;
            font-size: 84px;
            line-height: 1;
            margin: 20px 0 8px;
            color: var(--forest);
        }
        h1 {
            font-family: ui-serif, Georgia, "Times New Roman", serif;
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 12px;
            color: var(--charcoal);
        }
        p.desc {
            margin: 0 0 32px;
            color: var(--charcoal-soft);
            font-size: 15px;
            line-height: 1.6;
        }
        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.15s ease;
        }
        .btn:hover { transform: scale(1.03); }
        .btn--primary { background: var(--mustard); color: var(--charcoal); }
        .btn--secondary { background: transparent; color: var(--forest); border: 1px solid rgba(31, 93, 66, 0.3); }
        .leaf {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--pale-green);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="blob blob--1" aria-hidden="true"></div>
    <div class="blob blob--2" aria-hidden="true"></div>

    <main class="card">
        <span class="leaf" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1F5D42" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"></path>
                <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path>
            </svg>
        </span>

        <div class="badge">{{ $badge ?? 'Ichhe Puran' }}</div>
        <div class="code">{{ $code }}</div>
        <h1>{{ $heading }}</h1>
        <p class="desc">{{ $message }}</p>

        <div class="actions">
            <a href="/" class="btn btn--primary">Go to homepage</a>
            @if(!empty($showAdminLink))
                <a href="/admin" class="btn btn--secondary">Go to admin</a>
            @endif
        </div>
    </main>
</body>
</html>
