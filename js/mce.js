(function () {
    tinymce.create("tinymce.plugins.vg_form", {

        init: function (ed, url) {
            ed.addCommand("vg_command", function (input) {
                if (input != null) {
                    ed.execCommand("mceInsertContent", 0, '[venby ' + input + ' ]');
                }
            });
        },
    });

    tinymce.PluginManager.add("vg_form", tinymce.plugins.vg_form);
})();

