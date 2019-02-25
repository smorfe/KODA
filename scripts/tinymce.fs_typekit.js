(function() {
    tinymce.create('tinymce.plugins.fs_typekit', {
        init: function(ed, url) {
            ed.on('PreInit', function(e) {
                if( typeof ed.settings.fs_typekit_id == undefined || !ed.settings.fs_typekit_id.trim().length ) {
                    return false;
                }

                var jscript = "(function() {\n\
                    var config = {\n\
                        kitId: '"+ed.settings.fs_typekit_id+"'\n\
                    };\n\
                    var d = false;\n\
                    var tk = document.createElement('script');\n\
                    tk.src = '//use.typekit.net/' + config.kitId + '.js';\n\
                    tk.type = 'text/javascript';\n\
                    tk.async = 'true';\n\
                    tk.onload = tk.onreadystatechange = function() {\n\
                        var rs = this.readyState;\n\
                        if (d || rs && rs != 'complete' && rs != 'loaded') return;\n\
                        d = true;\n\
                        try { Typekit.load(config); } catch (e) {}\n\
                    };\n\
                    var s = document.getElementsByTagName('script')[0];\n\
                    s.parentNode.insertBefore(tk, s);\n\
                })();";

                var doc = ed.getDoc();

                // Create a script element and insert the TypeKit code into it
                var script = doc.createElement("script");
                script.type = "text/javascript";
                script.appendChild(doc.createTextNode(jscript));

                // Add the script to the header
                doc.getElementsByTagName('head')[0].appendChild(script);
            });
        }
    });

    tinymce.PluginManager.add('fs_typekit', tinymce.plugins.fs_typekit);

    console.log(d.settings.fs_typekit_id);
})();
