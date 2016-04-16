/**
 * LauncherGen - Windows Application Launcher Batch File Generator
 * Copyright (C) 2016 Andrés Cordero
 * Web: https://github.com/Andrew67/launchergen
 */

var buildExportUrl = function() {
    var url = "";
    url += window.location.href.split("?")[0];

    // Effective values, as opposed to generator options input (equal in theory once dynamic)
    url += "?numapps=" + encodeURIComponent("" + document.forms["launcher"]["name[]"].length);
    url += "&charset=" + encodeURIComponent(document.forms["launcher"]["charset"].value);

    // Launcher Options
    var launcher = {
        "header": document.forms["launcher"]["header"].value,
        "list_header": document.forms["launcher"]["list_header"].value,
        "prompt": document.forms["launcher"]["prompt"].value,
        "bgcolor": document.forms["launcher"]["bgcolor"].value,
        "fgcolor": document.forms["launcher"]["fgcolor"].value,
        "apps": []
    };
    for (var i = 0; i < document.forms["launcher"]["name[]"].length; ++i) {
        launcher.apps.push({
            "name": document.forms["launcher"]["name[]"][i].value,
            "path": document.forms["launcher"]["path[]"][i].value,
            "flag": document.forms["launcher"]["flag[]"][i].value
        });
    }

    console.info(launcher);
    url += "&launcher=" + encodeURIComponent(JSON.stringify(launcher));

    return url;
};

var setLauncherCharset = function(charset) {
    document.forms["launcher"].acceptCharset = charset;
    document.forms["launcher"]["charset"].value = charset;
};

// Enable dynamic form elements and bind event handlers
document.addEventListener("DOMContentLoaded", function() {
    // Export Profile
    document.forms["launcher"]["export"].disabled = false;
    document.forms["launcher"]["generate-url"].addEventListener("click", function() {
        document.forms["launcher"]["export-url"].value = buildExportUrl();
    });

    // Dynamic "display name encoding"
    document.forms["generator"]["charset"].addEventListener("change", function() {
        setLauncherCharset(this.value);
    });
});