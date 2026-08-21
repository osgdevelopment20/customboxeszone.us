<script src="{{asset('js/jscroll.js')}}"></script>

<script type="text/javascript">
    // for pagination comment this portion And for on scroll load uncomment this portion
    var spinnerPath = "{{asset('loader.gif')}}";
    $('ul.pagination').hide();
    $(function () {
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<div class="ale text-center"><img class="center-block text-center spin" src="' + spinnerPath + '" alt="Loading..." /></div>', // MAKE SURE THAT YOU PUT THE CORRECT IMG PATH
            padding: 0,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function () {
                $('ul.pagination').hide();
            }
        });
    });
</script>
