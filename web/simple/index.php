<?php
/**
 * LauncherGen - Windows Application Launcher Batch File Generator
 * Copyright (C) 2015  Andrés Cordero
 * Web: https://github.com/Andrew67/launchergen
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Preservation of generator options / defaults
$numapps = (is_numeric($_GET['numapps']) && $_GET['numapps'] <= 20) ? $_GET['numapps'] : 5;
$charset = !empty($_GET['charset']) ? htmlentities($_GET['charset']) : 'us-ascii';
?>
<!DOCTYPE html>
<html>
<head>
    <title>LauncherGen Simple</title>
    <meta charset="utf-8">
    <style>
        label {
            display: block;
        }
    </style>
</head>
<body>
<h1>LauncherGen (Simple Version)</h1>
<h2>Windows Application Launcher Batch File Generator</h2>
<h3>Project information: <a href="https://github.com/Andrew67/launchergen">https://github.com/Andrew67/launchergen</a></h3>
<h4>Simple version limitations:</h4>
<ul>
    <li>Cannot import/export form data.</li>
    <li>Maximum of 20 applications.</li>
    <li>Default application is always application #1.</li>
    <li>Numeric input for application selection only.</li>
    <li>ASCII and Shift_JIS are the only supported encodings.</li>
    <li>Display names cannot contain the &quot; character.</li>
</ul>

<!-- BEGIN AD CODE -->
<iframe id="id01_965427" src="http://www.play-asia.com/paOScore/ads2/190%2C000000%2Cnone%2C0%2C0%2C0%2C0%2CFFFFFF%2C000000%2Cleft%2C0%2C1-391-765-70bpzf-062-781o-29333-90xvq9-44570px?inlinelogo=1&url=iframe_banner" style="border-style: none; border-width: 0px; border-color: #000000; padding: 0; margin: 0; scrolling: no; frameborder: 0;" scrolling="no" frameborder="0" width="570px" height="111"></iframe>
<script>
    var t = ""; t += window.location; t = t.replace( /#.*$/g, "" ).replace( /^.*:\/*/i, "" ).replace( /\./g, "[dot]" ).replace( /\//g, "[obs]" ).replace( /-/g, "[dash]" ); t = encodeURIComponent( encodeURIComponent( t ) ); var iframe = document.getElementById( "id01_965427" ); iframe.src = iframe.src.replace( "iframe_banner", t );
</script>
<!-- END AD CODE -->

<form method="GET" action="index.php">
    <fieldset>
        <legend>Generator Options <b>(Warning: changes here will empty your application settings below!)</b></legend>
        <label>Number of applications: <input type="number" name="numapps" value="<?=$numapps?>"></label>
        <label>Display name encoding:
            <select name="charset">
                <option value="us-ascii">English (US-ASCII)</option>
                <option value="shift_jis" <?php if ($charset == 'shift_jis') echo "selected"; ?>>Japanese (Shift_JIS)</option>
            </select>
        </label>
        <button type="submit">Change options</button>
    </fieldset>
</form>

<form method="POST" action="generate-bat.php" accept-charset="<?=$charset?>">
    <fieldset>
        <legend>Launcher Options</legend>
        <label>Header: <input type="text" name="header" placeholder="Application Launcher"></label>
        <label>List Header: <input type="text" name="list_header" placeholder="Available applications:"></label>
        <label>Prompt: <input type="text" name="prompt" placeholder="Select an application by typing the number and pressing &quot;Enter&quot; (default=1):"></label>
        <label>Background text color:
            <select name="bgcolor">
                <option value="0" selected>Black</option>
                <option value="1">Blue</option>
                <option value="2">Green</option>
                <option value="3">Aqua</option>
                <option value="4">Red</option>
                <option value="5">Purple</option>
                <option value="6">Yellow</option>
                <option value="7">White</option>
                <option value="8">Gray</option>
                <option value="9">Light blue</option>
                <option value="a">Light green</option>
                <option value="b">Light aqua</option>
                <option value="c">Light red</option>
                <option value="d">Light purple</option>
                <option value="e">Light yellow</option>
                <option value="f">Bright white</option>
            </select>
        </label>
        <label>Foreground text color:
            <select name="fgcolor">
                <option value="0">Black</option>
                <option value="1">Blue</option>
                <option value="2">Green</option>
                <option value="3">Aqua</option>
                <option value="4">Red</option>
                <option value="5">Purple</option>
                <option value="6">Yellow</option>
                <option value="7" selected>White</option>
                <option value="8">Gray</option>
                <option value="9">Light blue</option>
                <option value="a">Light green</option>
                <option value="b">Light aqua</option>
                <option value="c">Light red</option>
                <option value="d">Light purple</option>
                <option value="e">Light yellow</option>
                <option value="f">Bright white</option>
            </select>
        </label>
    </fieldset>
    <?php for ($i=1; $i<=$numapps; ++$i): ?>
    <fieldset>
        <legend>
            Application #<?=$i?>
            <?php if ($i == 1): ?> (default) <?php endif; ?>
        </legend>
        <label>Display name: <input type="text" name="name[]" placeholder="App <?=$i?>"></label>
        <label>Executable path: <input type="text" name="path[]" placeholder="C:\app.exe" size="64"></label>
        <label>Flags (optional): <input type="text" name="flag[]"></label>
    </fieldset>
    <?php endfor; ?>
    <button type="submit">Generate batch file</button>
</form>

</body>
</html>