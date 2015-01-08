<!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <script src="https://cdn.dcsaas.net/js/appstore-sdk.js"></script>

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

            <p>Kategorie sklepu (<?php echo App::escapeHtml($categories->count); ?>):</p>
            <ul>
                <?php
                foreach($categories as $c){
                    // array access
                    if(isset($c['translations'][$_locale])){
                        ?>
                        <li>
                            <?php
                            // object property access
                            echo App::escapeHtml($c->translations->$_locale->name);

                            ?>

                            (id: <?php echo App::escapeHtml($c['category_id']); ?>)
                        </li>
                    <?php
                    }
                }
                ?>
            </ul>
            <div class="clearfix"></div>
        </div>
    </section>
</main>


</body>
</html>
