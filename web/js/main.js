/**
 * LauncherGen - Windows Application Launcher Batch File Generator
 * Copyright (C) 2016 Andrés Cordero
 * Web: https://github.com/Andrew67/launchergen
 */

var launcher = document.forms["launcher"].elements;
var generator = document.forms["generator"].elements;

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
    saveLauncherAppState(launcher_state.apps);
    url += "&launcher=" + encodeURIComponent(JSON.stringify(launcher_state));

    return url;
};

var setLauncherCharset = function(charset) {
    document.forms["launcher"].acceptCharset = charset;
    launcher["charset"].value = charset;
};

// Save the launcher app form state in the array specified by dst.
// Returns the effective amount of apps in the form.
// If the destination array is of a longer length, it is not truncated.
var saveLauncherAppState = function(dst) {
    for (var i = 0; i < launcher["name[]"].length; ++i) {
        dst[i] = {
            "name": launcher["name[]"][i].value,
            "path": launcher["path[]"][i].value,
            "flag": launcher["flag[]"][i].value
        };
    }
    return launcher["name[]"].length;
};

// Load the launcher app form state from the array specified by src, with name/path/flag elements.
// Loading begins in the index specified by idx, until form or array elements are no longer available.
// Returns the effective amount of apps in the form.
var loadLauncherAppState = function(src, idx) {
    for (var i = idx; i < src.length && i < launcher["name[]"].length; ++i) {
        launcher["name[]"][i].value = src[i]["name"];
        launcher["path[]"][i].value = src[i]["path"];
        launcher["flag[]"][i].value = src[i]["flag"];
    }
    return launcher["name[]"].length;
};

// This array keeps track of user inputs for fields even if they're truncated, in case they're restored during the same page view.
// Feeds through the saveLauncherAppState and loadLauncherAppState functions.
var app_state = [];

// Truncate amount of app fieldsets to the given amount.
// Saves state in app_state before truncating.
// Returns the amount of removed app fieldset nodes.
var truncateAppList = function(newsize) {
    if (newsize < 2) {
        console.error("truncateAppList requires a minimum value of 2");
        return 0;
    }

    saveLauncherAppState(app_state);

    var apps = document.getElementsByName("app");
    var nodesToRemove = [];
    // Catch the elements in a separate array, and then remove them.
    // Otherwise, the dynamic NodeList gets emptied as you go and the calculation is missed.
    // i = Number(...) is used due to some browsers (Edge) implementations of HTMLCollection.item returning null on string indexes.
    for (var i = Number(newsize); i < apps.length; ++i) {
        nodesToRemove.push(apps.item(i));
    }
    for (i = 0; i < nodesToRemove.length; ++i) {
        nodesToRemove[i].remove();
    }
    return nodesToRemove.length;
};

// Expands the amount of app fieldsets to the given amount.
// Restores from app_state, so previously truncated field contents are restored.
var expandAppList = function(newsize) {
    if (newsize > 20) {
        console.error("expandAppList requires a maximum value of 20");
        return;
    }

    var apps = document.getElementsByName("app");
    var start_idx = apps.length;
    var lastNode = apps.item(start_idx-1);
    // Clone a new node, empty the inputs, set the #s, append to list.
    for (var i = start_idx; i < newsize; ++i) {
        var newNode = lastNode.cloneNode(true);
        newNode.getElementsByTagName("legend").item(0).textContent = "Application #" + (i+1);

        var inputs = newNode.getElementsByTagName("input");
        inputs.item(0).value = "";
        inputs.item(0).setAttribute("placeholder", "App " + (i+1));
        inputs.item(1).value = "";
        inputs.item(2).value = "";
        lastNode.parentNode.insertBefore(newNode, launcher["generate"]);
    }

    loadLauncherAppState(app_state, start_idx);
};

// Enable dynamic form elements and bind event handlers
document.addEventListener("DOMContentLoaded", function() {
    // Export Profile
    document.getElementsByName("export")[0].disabled = false;
    launcher["generate-url"].addEventListener("click", function() {
        launcher["export-url"].value = buildExportUrl();
    });

    // Dynamic "display name encoding"
    generator["charset"].addEventListener("change", function() {
        setLauncherCharset(this.value);
    });

    // Dynamic "number of applications"
    generator["numapps"].addEventListener("change", function() {
        if (this.value > launcher["name[]"].length) {
            expandAppList(this.value);
        }
        else if (this.value < launcher["name[]"].length) {
            truncateAppList(this.value);
        }
    });

    // Remove the submit button in "generator options", as they're dynamic
    generator["change"].remove();
});