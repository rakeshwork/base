
$(document).ready(function() {

        tinymce.init(
            {
                selector:'textarea.text-editor',
                menubar: false,
                plugins: "table, hr, link, image, preview",
                image_advtab: true,
                image_class_list: [
                        {title: 'None', value: ''},
                        {title: 'Thumbnail', value: 'thumbnail'},
                        ],
                toolbar: "bold italic underline | link unlink | alignleft aligncenter alignright | hr | formatselect | bullist numlist | outdent indent blockquote removeformat | table | image | preview"
            }
        );

});