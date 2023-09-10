<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $get('title', 'Native PHP') ?> | Error</title>
    <style>
      body {
        font-family: 'Lato', sans-serif;
        color: #555;
        margin: 0;
      }

      #main {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .fof {
        text-align: center;
      }

      .fof h1 {
        font-size: 2.5rem;
        display: inline-block;
        padding-right: 1rem;
        animation: type .5s alternate infinite;
      }

      @keyframes type {
        from {
          box-shadow: inset -0.2rem 0px 0px #555;
        }

        to {
          box-shadow: inset -0.2rem 0px 0px transparent;
        }
      }
    </style>
  </head>
  <body>
    <div id="main">
      <div class="fof">
        <h1><?= $get('message', 'Default Title') ?></h1>
      </div>
    </div>
  </body>
</html>