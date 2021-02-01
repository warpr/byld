<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>byld</title>
        <style>
         body {
             background: black;
             font-family: Helvetica;
         }

         body, section, h1 {
             margin: 0;
             padding: 0;
             border: 0;
         }

         section {
             display: flex;
             justify-content: flex-end;
             align-items: flex-end;
             height: 100vh;
         }

         h1 {
             font-size: 32px;
             color: #b44;
             padding: 2px 10px;
         }

         span { color: #666; }
        </style>
    </head>
    <body>
        <input type="hidden" name="version" value="<?= phpversion() ?>" />
        <section>
            <h1>byld<span>.app</span></h1>
        </section>
    </body>
</html>
