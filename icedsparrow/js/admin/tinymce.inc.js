function tinySetup() {
    tinyMCE.init({
        mode: "textareas",
        editor_selector: "rte",
        theme: "advanced",
        plugins: "paste,searchreplace",
        theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,forecolor,backcolor,|,search,replace",
        theme_advanced_buttons2: "",
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
        content_css: baseDir + "/css/tinymce.css",
        paste_text_sticky_default: true,
        paste_text_sticky: true,
        cleanup_on_startup: true,
        forced_root_block: "",
        force_p_newlines: false,
        force_br_newlines: true,
        convert_newlines_to_brs: true,
        remove_linebreaks: false,
        remove_redundant_brs: false,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
}
