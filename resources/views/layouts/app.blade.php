<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="dark-style layout-navbar-fixed layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/"
    data-template="horizontal-menu-template"
>
    <head>
        <meta charset="utf-8"/>
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
        />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name')}}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}"/>

        <!-- SEO -->
        @include('layouts.sections.seo')

        <!-- Icons -->
        @vite([
                "resources/assets/vendor/fonts/fontawesome.scss",
                "resources/assets/vendor/fonts/flag-icons.scss",
                ])

        <!-- Core CSS -->
        @vite([
              "resources/assets/vendor/scss/rtl/core-dark.scss",
              "resources/assets/vendor/scss/rtl/theme-default-dark.scss",
              "resources/assets/css/demo.css",
              ])

        @vite([
               "resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss",
               "resources/assets/vendor/libs/typeahead-js/typeahead.scss"
               ])

        @vite(['resources/assets/vendor/js/helpers.js', 'resources/assets/js/config.js'])
        @vite(['resources/assets/css/app.css'])
        @stack('css')
        <!-- Google tag (gtag.js) -->
{{--        @if(setting('services.google.analytics_enable'))--}}
{{--            <script async defer--}}
{{--                    src="https://www.googletagmanager.com/gtag/js?id={{ setting('services.google.analytics_id') }}"></script>--}}
{{--            <script>--}}
{{--                window.dataLayer = window.dataLayer || [];--}}

{{--                function gtag() {--}}
{{--                    dataLayer.push(arguments);--}}
{{--                }--}}

{{--                gtag('js', new Date());--}}
{{--                gtag('config', '{{ setting('services.google.analytics_id') }}');--}}
{{--            </script>--}}
{{--        @endif--}}

       <script>
           window.recaptchaSiteKey = "{{ config('recaptcha.api_site_key') }}";

           function renderAuthRecaptchas() {
               if (typeof grecaptcha === 'undefined' || typeof grecaptcha.render !== 'function') {
                   return;
               }

               const loginContainer = document.getElementById('login-recaptcha');
               if (loginContainer && !loginContainer.hasChildNodes()) {
                   grecaptcha.render('login-recaptcha', {
                       sitekey: window.recaptchaSiteKey,
                       callback: function (token) {
                           const input = document.getElementById('login-g-recaptcha-response');
                           if (input) {
                               input.value = token;
                               input.dispatchEvent(new Event('input', { bubbles: true }));
                           }
                       }
                   });
               }

               const registerContainer = document.getElementById('register-recaptcha');
               if (registerContainer && !registerContainer.hasChildNodes()) {
                   grecaptcha.render('register-recaptcha', {
                       sitekey: window.recaptchaSiteKey,
                       callback: function (token) {
                           const loginInput = document.getElementById('login-g-recaptcha-response');
                           if (loginInput) {
                               loginInput.value = token;
                               loginInput.dispatchEvent(new Event('input', { bubbles: true }));
                           }
                       }
                   });
               }
           }

           function renderHomeRecaptcha() {
               if (typeof grecaptcha === 'undefined' || typeof grecaptcha.render !== 'function') {
                   return;
               }

               const homeContainer = document.getElementById('home-register-recaptcha');
               if (homeContainer && !homeContainer.hasChildNodes()) {
                   window.homeRegisterRecaptchaWidgetId = grecaptcha.render('home-register-recaptcha', {
                       sitekey: window.recaptchaSiteKey,
                   });
               }
           }

           function onRecaptchaLoad() {
               renderAuthRecaptchas();
               renderHomeRecaptcha();
           }

           document.addEventListener('DOMContentLoaded', function () {
               const authModal = document.getElementById('authModal');
               if (authModal) {
                   authModal.addEventListener('shown.bs.modal', function () {
                       renderAuthRecaptchas();
                   });
               }

               // Home page widget might be visible immediately
               renderHomeRecaptcha();

               // Re-run renderers after Livewire DOM updates (e.g. after validation errors)
               document.addEventListener('livewire:init', () => {
                   Livewire.hook('morph.updated', () => {
                       renderAuthRecaptchas();
                       renderHomeRecaptcha();
                   });

                   Livewire.hook('commit', ({ succeed }) => {
                        succeed(() => {
                           renderAuthRecaptchas();
                           renderHomeRecaptcha();
                       });
                   });
               });
           });
       </script>

       <script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-NNGSXD2R');</script>
        <!-- End Google Tag Manager -->

    </head>

    <body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NNGSXD2R"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
        <div id="preloader">
            <div class="text-center no-hover">
                <x-logo class="mx-auto"/>
                <div class="loader m-auto"></div>
            </div>
        </div>


        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                @include('layouts.sections.sidebar')

                <!-- Layout container -->
                <div class="layout-page"
                     x-data="{ is_coin: (typeof localStorage !== 'undefined' ? localStorage.getItem('isCoin') || '0' : '0') }"
                     x-on:update-coins.window="is_coin = $event.detail.isCoin">

                    @include('layouts.sections.navbar')
                    <livewire:user.live-cashouts/>

                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-fluid flex-grow-1 container-p-y">
                            {{ $slot }}
                        </div>
                        <!-- / Content -->

                        @include('layouts.sections.footer')
                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->

                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>

            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>

            @auth
                @if(!auth()->user()->isCompletedProfile())
                    <livewire:user.complete-profile-modal/>
                @endif

                <livewire:user.block-modal/>
                <livewire:user.bonus-modal/>
            @else
                <livewire:user.auth/>
            @endauth

            <livewire:live-chat/>
            <livewire:user.activity-modal/>
        </div>
    
        <x-toaster-hub/> <!-- 👈 -->
        
{{--<!-- Google tag (gtag.js) -->--}}
{{--<script async src="https://www.googletagmanager.com/gtag/js?id=GTM-NNGSXD2R"></script>--}}
{{--<script>--}}
{{--  window.dataLayer = window.dataLayer || [];--}}
{{--  function gtag(){dataLayer.push(arguments);}--}}
{{--  gtag('js', new Date());--}}

{{--  gtag('config', 'GTM-NNGSXD2R');--}}
{{--</script>--}}

        @stack('modals')
        @include('layouts.modals.offers-api')

        @vite([
                'resources/assets/vendor/libs/jquery/jquery.js',
                'resources/assets/vendor/js/bootstrap.js',
                'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
                'resources/assets/vendor/libs/typeahead-js/typeahead.js',
                'resources/assets/vendor/js/menu.js'
                ])

        @vite(['resources/assets/js/main.js'])
        @vite(['resources/assets/js/app.js'])

        @stack('js')


{{--    <script>--}}

{{--        document.addEventListener('DOMContentLoaded',function (){--}}
{{--            let loginWidget = null;--}}
{{--            let registerWidget = null;--}}

{{--            function renderCaptchas() {--}}
{{--                if (typeof grecaptcha === 'undefined') return;--}}

{{--                if (!loginWidget && document.getElementById('login-recaptcha')) {--}}
{{--                    loginWidget = grecaptcha.render('login-recaptcha', {--}}
{{--                        sitekey: '{{ config('services.recaptcha.site_key') }}'--}}
{{--                    });--}}
{{--                }--}}

{{--                if (!registerWidget && document.getElementById('register-recaptcha')) {--}}
{{--                    registerWidget = grecaptcha.render('register-recaptcha', {--}}
{{--                        sitekey: '{{ config('services.recaptcha.site_key') }}'--}}
{{--                    });--}}
{{--                }--}}
{{--            }--}}

{{--            document.getElementById('authModal')--}}
{{--                .addEventListener('shown.bs.modal', renderCaptchas);--}}
{{--        })--}}
{{--    </script>--}}

{{--    <script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer></script>--}}
    </body>
</html>
