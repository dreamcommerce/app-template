<!doctype html>
<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8">
    <script src="https://cdn.dcsaas.net/js/appstore-sdk.js"></script>

    <script>var app = new ShopApp(function(app) {
            app.init(null, function(params, app) {
                for(var x = 0; x < params.styles.length; ++x) {
                    var el = document.createElement('link');
                    el.rel = 'stylesheet';
                    el.type = 'text/css';
                    el.href = params.styles[x];
                    document.getElementsByTagName('head')[0].appendChild(el);
                }

                app.show(null ,function () {
                    app.adjustIframeSize();
                });

            }, function(errmsg, app) {
                alert(errmsg);
            });
        }, true);


    </script></head><body>

<main class="rwd-layout-width rwd-layout-container">
    <section class="rwd-layout-col-12">

        <p><?php echo App::escapeHtml($message); ?></p>

    </section></main>


<body></html>