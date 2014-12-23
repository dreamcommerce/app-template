<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <script src="https://developers.shoper.pl/public/sdk.js"></script>

    <script>var app = new ShopApp(function (app) {
            app.init(null, function (params, app) {
                for (var x = 0; x < params.styles.length; ++x) {
                    var el = document.createElement('link');
                    el.rel = 'stylesheet';
                    el.type = 'text/css';
                    el.href = params.styles[x];
                    document.getElementsByTagName('head')[0].appendChild(el);
                }

                app.show(null ,function () {
                    app.adjustIframeSize();
                });


            }, function (errmsg, app) {
                alert(errmsg);
            });
        }, true);


    </script>
</head>
<body>

<main class="rwd-layout-width rwd-layout-container">
    <section class="rwd-layout-col-12">


        <div class="edition-form">

            <p>Hello World!</p>

            <pre><?PHP // todo fix me XSS
            var_dump($categories); ?></pre>

            <div class="clearfix"></div>
        </div>
    </section>
</main>


</body>
</html>