(function () {
    tinymce.PluginManager.requireLangPack('advlist');
    tinymce.PluginManager.requireLangPack('autolink');
    tinymce.PluginManager.requireLangPack('autoresize');
    tinymce.PluginManager.requireLangPack('autosave');
    tinymce.PluginManager.requireLangPack('bbcode');
    tinymce.PluginManager.requireLangPack('charmap');
    tinymce.PluginManager.requireLangPack('code');
    tinymce.PluginManager.requireLangPack('codesample');
    tinymce.PluginManager.requireLangPack('colorpicker');
    tinymce.PluginManager.requireLangPack('contextmenu');
    tinymce.PluginManager.requireLangPack('directionality');
    tinymce.PluginManager.requireLangPack('emoticons');
    tinymce.PluginManager.requireLangPack('fullpage');
    tinymce.PluginManager.requireLangPack('fullscreen');
    tinymce.PluginManager.requireLangPack('hr');
    tinymce.PluginManager.requireLangPack('image');
    tinymce.PluginManager.requireLangPack('imagetools');
    tinymce.PluginManager.requireLangPack('importcss');
    tinymce.PluginManager.requireLangPack('insertdatetime');
    tinymce.PluginManager.requireLangPack('legacyoutput');
    tinymce.PluginManager.requireLangPack('link');
    tinymce.PluginManager.requireLangPack('lists');
    tinymce.PluginManager.requireLangPack('media');
    tinymce.PluginManager.requireLangPack('nonbreaking');
    tinymce.PluginManager.requireLangPack('noneditable');
    tinymce.PluginManager.requireLangPack('pagebreak');
    tinymce.PluginManager.requireLangPack('paste');
    tinymce.PluginManager.requireLangPack('preview');
    tinymce.PluginManager.requireLangPack('print');
    tinymce.PluginManager.requireLangPack('save');
    tinymce.PluginManager.requireLangPack('searchreplace');
    tinymce.PluginManager.requireLangPack('spellchecker');
    tinymce.PluginManager.requireLangPack('tabfocus');
    tinymce.PluginManager.requireLangPack('table');
    tinymce.PluginManager.requireLangPack('template');
    tinymce.PluginManager.requireLangPack('textcolor');
    tinymce.PluginManager.requireLangPack('textpattern');
    tinymce.PluginManager.requireLangPack('toc');
    tinymce.PluginManager.requireLangPack('visualblocks');
    tinymce.PluginManager.requireLangPack('visualchars');
    tinymce.PluginManager.requireLangPack('wordcount');

    tinymce.create('tinymce.plugins.PrestaShopTinyMCE', {
        init: function (ed, url) {

            ed.addCommand('mcePrestaShopLink', function () {
                if ($('#' + ed.id).hasClass('rte')) {
                    tb_show(null, 'admin/tinymce/link.php?editor=' + ed.id + '&plugin=' + encodeURIComponent(url) + '&instance=' + $('#id_language').val());
                } else {
                    window.PrestaShopLinkManager(ed.id, url);
                }
            });
            ed.addCommand('mcePrestaShopFileManager', function () {
                if ($('#' + ed.id).hasClass('rte')) {
                    tb_show(null, 'admin/tinymce/file_manager.php?editor=' + ed.id + '&plugin=' + encodeURIComponent(url) + '&instance=' + $('#id_language').val());
                } else {
                    window.PrestaShopFileManager(ed.id, url);
                }
            });

            ed.addButton('link', {
                title: 'Insert/edit link',
                cmd: 'mcePrestaShopLink',
                image: url + '/img/link.gif'
            });
            ed.addButton('image', {
                title: 'Insert/edit image',
                cmd: 'mcePrestaShopFileManager',
                image: url + '/img/image.gif'
            });
        },
        getInfo: function () {
            return {
                longname: 'PrestaShop TinyMCE',
                author: 'PrestaShop',
                authorurl: 'http://www.prestashop.com',
                infourl: 'http://www.prestashop.com',
                version: '1.0'
            };
        }
    });

    tinymce.PluginManager.add('prestashop', tinymce.plugins.PrestaShopTinyMCE);
})();
