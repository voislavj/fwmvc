htmlEditor = (expr) ->
    tinymce.init
        selector: expr,
        menubar: false,
        relative_urls: false,
        plugins: ['link', 'fullscreen'],
        toolbar: "formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | "+
                 "cut copy paste | bullist numlist | outdent indent | link unlink | fullscreen"