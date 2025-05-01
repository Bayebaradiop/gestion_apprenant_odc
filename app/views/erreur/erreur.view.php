<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 - Page non trouvée | Orange Digital Center Sonatel</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    html, body {
      height: 100%;
    }

    body {
      background-color: #f8f9fa;
      color: #333;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      width: 100%;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background-color: white;
      border-radius: 0;
      padding: 40px 20px;
      box-shadow: none;
      text-align: center;
    }

    .logo {
      max-width: 180px;
      margin-bottom: 30px;
    }

    .error-code {
      font-size: 100px;
      font-weight: bold;
      color: #ff7900;
      margin-bottom: 20px;
    }

    h1 {
      font-size: 26px;
      margin-bottom: 20px;
    }

    p {
      font-size: 18px;
      line-height: 1.6;
      color: #666;
      max-width: 600px;
      margin-bottom: 30px;
    }

    .emoji {
      width: 200px;
      height: 200px;
      margin: 0 auto 30px;
    }

    .emoji img {
      width: 100%;
      height: 100%;
      animation: spin 3s ease-in-out infinite;
    }

    .btn {
      display: inline-block;
      background-color: #ff7900;
      color: white;
      text-decoration: none;
      padding: 12px 30px;
      border-radius: 30px;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #e56c00;
    }

    .dev-badge {
      display: inline-block;
      background-color: #42b983;
      color: white;
      padding: 6px 16px;
      border-radius: 16px;
      font-size: 13px;
      margin-top: 25px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      25% { transform: rotate(15deg); }
      75% { transform: rotate(-15deg); }
      100% { transform: rotate(0deg); }
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="assets/images/login/logo_odc.png" alt="Orange Digital Center Sonatel" class="logo" />
    <div class="error-code">404</div>
    <h1>Oups ! Cette page est introuvable</h1>
    <div class="emoji">
      <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f644/512.gif" alt="🙄" />
    </div>
    <p>La page que vous recherchez n'existe pas ou a été déplacée. Veuillez vérifier l'URL ou retourner à la page d'accueil.</p>
    <a href="/" class="btn">Retour à l'accueil</a>
    <div class="dev-badge">ECSA</div>
  </div>
</body>
</html>
