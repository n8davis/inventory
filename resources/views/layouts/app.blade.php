<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Urban Squirrel">
    <title>{{env("APP_NAME")}}</title>
    <!-- Favicon -->
    <link href="/inventory/public/images/brand/favicon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="/inventory/public/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="/inventory/public/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="/inventory/public/css/argon.css?v=1.0.1" rel="stylesheet">
    <link type="text/css" href="/inventory/public/css/d.css" rel="stylesheet">
    <!-- Shopify JS -->
    <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
</head>

<body>
    @yield('content')

    <!-- Shopify -->
    <script>
        ShopifyApp.init({
            apiKey: token,
            shopOrigin: "https://" + shop,
            debug: false,
            forceRedirect: true
        });
        let url = '/inventory/?shop=' + shop,
            pagination = {};

        if(typeof(page) !== "undefined"){
            pagination = {
                next: {
                    href: url + "&page=" + (page + 1),
                    loading:true
                },
                previous: {
                    href: url + "&page=" + (page - 1),
                    loading:true
                }
            };
        }
        ShopifyApp.Bar.initialize({
            buttons: {
                primary: {
                    label: "Inventory",
                    href: url
                },
                secondary: [
                    { label: "Sections",
                        type: "dropdown",
                        links: [
                            { label: "Configuration", href: "/inventory/configurations?shop=" + shop, target: "app" },
                            { label: "Connections", href: "/inventory/connections?shop=" + shop, target: "app" }
                        ]
                    },
                ],
            },
            title: title,
            pagination: pagination
        });
    </script>
    <!-- Core -->
    <script src="/inventory/public/vendor/jquery/jquery.min.js"></script>
    <script src="/inventory/public/vendor/popper/popper.min.js"></script>
    <script src="/inventory/public/vendor/bootstrap/bootstrap.min.js"></script>
    <script src="/inventory/public/vendor/headroom/headroom.min.js"></script>
    <!-- Argon JS -->
    <script src="/inventory/public/js/argon.js?v=1.0.1"></script>
</body>

</html>