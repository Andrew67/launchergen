/**
 * LauncherGen - Windows Application Launcher Batch File Generator
 * Copyright (C) 2016 Andrés Cordero
 * Web: https://github.com/Andrew67/launchergen
 */

var launcher = document.forms["launcher"];
var generator = document.forms["generator"];

var buildExportUrl = function() {
    var url = "";
    url += window.location.href.split("?")[0];

    // Effective values, as opposed to generator options input (equal in theory once dynamic)
    url += "?numapps=" + encodeURIComponent("" + launcher["name[]"].length);
    url += "&charset=" + encodeURIComponent(launcher["charset"].value);

    // Launcher Options
    var launcher_state = {
        "header": launcher["header"].value,
        "list_header": launcher["list_header"].value,
        "prompt": launcher["prompt"].value,
        "bgcolor": launcher["bgcolor"].value,
        "fgcolor": launcher["fgcolor"].value,
        "apps": []
    };
    for (var i = 0; i < launcher["name[]"].length; ++i) {
        launcher_state.apps.push({
            "name": launcher["name[]"][i].value,
            "path": launcher["path[]"][i].value,
            "flag": launcher["flag[]"][i].value
        });
    }

    console.info(launcher_state);
    url += "&launcher=" + encodeURIComponent(JSON.stringify(launcher_state));

    return url;
};

var setLauncherCharset = function(charset) {
    launcher.acceptCharset = charset;
    launcher["charset"].value = charset;
};

// Enable dynamic form elements and bind event handlers
document.addEventListener("DOMContentLoaded", function() {
    // Export Profile
    launcher["export"].disabled = false;
    launcher["generate-url"].addEventListener("click", function() {
        launcher["export-url"].value = buildExportUrl();
    });

    // Dynamic "display name encoding"
    generator["charset"].addEventListener("change", function() {
        setLauncherCharset(this.value);
    });
});