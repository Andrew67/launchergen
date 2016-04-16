<?php
/**
 * LauncherGen - Windows Application Launcher Batch File Generator
 * Copyright (C) 2015-2016 Andrés Cordero
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

// App data struct
class App {
    public $name = '';
    public $path = '';
    public $flag = '';
}

// Preservation of generator options / defaults
$numapps = (!empty($_GET['numapps']) && is_numeric($_GET['numapps']) && $_GET['numapps'] <= 20 && $_GET['numapps'] >= 2)
    ? $_GET['numapps'] : 2;
$charset = !empty($_GET['charset']) ? htmlspecialchars($_GET['charset']) : 'us-ascii';

/* Load launcher data from an export
   Format is JSON within $_GET['launcher'] with fields:
   header, list_header, prompt, bgcolor, fgcolor, apps (array of):
   name, path, flag
*/
$settings = array('header', 'list_header', 'prompt', 'bgcolor', 'fgcolor');
// Seed with NULL to avoid "undefined variable" errors
foreach ($settings as $v) { ${$v} = null; }
// Defaults
$bgcolor = '0';
$fgcolor = '7';
$apps = array();

/** @var stdClass $launcher */
if (isset($_GET['launcher']) && ($launcher = json_decode($_GET['launcher'], false, 4)) !== null) {
    foreach ($settings as $h) {
        if (isset($launcher->$h)) ${$h} = htmlspecialchars($launcher->$h);
    }
    if (isset($launcher->apps) && is_array($launcher->apps) && count($launcher->apps) > 0) {
        foreach ($launcher->apps as $app_raw) {
            $app_new = new App();
            if (isset($app_raw->name)) $app_new->name = htmlspecialchars($app_raw->name);
            if (isset($app_raw->path)) $app_new->path = htmlspecialchars($app_raw->path);
            if (isset($app_raw->flag)) $app_new->flag = htmlspecialchars($app_raw->flag);
            $apps[] = $app_new;
        }
    }
}

// Drop-down helper
function selected($expected, $actual) {
    if ($expected == $actual) return "selected";
    return "";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>LauncherGen</title>
    <meta charset="utf-8">
</head>
<body>
<h1>LauncherGen</h1>
<h2>Windows Application Launcher Batch File Generator</h2>
<h3>Project information: <a href="https://github.com/Andrew67/launchergen">https://github.com/Andrew67/launchergen</a></h3>
<h4>Limitations / Known Issues:</h4>
<ul>
    <li>Cannot export form data.</li>
    <li>Maximum of 20 applications, minimum 2.</li>
    <li>Default application is always application #1.</li>
    <li>Numeric input for application selection only.</li>
    <li>Encoding-specific issues:
        <ul>
            <li>Display names cannot contain the &quot; or &amp; characters.</li>
            <li>"English (US-ASCII)" is the recommended option, unless your system locale is set to Japanese.</li>
            <li>If you specify characters not included in the selected encoding, the batch file will fail.</li>
            <li><i>Windows XP/Vista:</i> The "Unicode (UTF-8)" encoding causes the batch file to fail.</li>
            <li>The "Japanese (Shift_JIS)" and "Western European (ISO-8859-1)" options may fail when the system locale differs.</li>
        </ul>
    </li>
</ul>

<!-- BEGIN AD CODE -->
<iframe id="id01_965427" src="http://www.play-asia.com/paOScore/ads2/190%2C000000%2Cnone%2C0%2C0%2C0%2C0%2CFFFFFF%2C000000%2Cleft%2C0%2C1-391-765-70bpzf-062-781o-29333-90xvq9-44570px?inlinelogo=1&amp;url=launchergen%255Bdot%255Dandrew67%255Bdot%255Dcom%255Bobs%255D" style="border: 0 none #000000; padding: 0; margin: 0; overflow: hidden;" width="570" height="111" scrolling="no"></iframe>
<!-- END AD CODE -->

<form name="generator" method="GET" action="index.php">
    <fieldset>
        <legend>Generator Options <b>(Warning: changes here will empty your application settings below!)</b></legend>
        <label>Number of applications: <input type="number" name="numapps" value="<?=$numapps?>"></label><br>
        <label>Display name encoding:
            <select name="charset">
                <option value="us-ascii" <?=selected('us-ascii', $charset)?>>English (US-ASCII)</option>
                <option value="shift_jis" <?=selected('shift_jis', $charset)?>>Japanese (Shift_JIS)</option>
                <option value="iso-8859-1" <?=selected('iso-8859-1', $charset)?>>Western European (ISO-8859-1)</option>
                <option value="utf-8" <?=selected('utf-8', $charset)?>>Unicode (UTF-8)</option>
            </select>
        </label><br>
        <button type="submit">Change options</button>
    </fieldset>
</form>

<form name="launcher" method="POST" action="generate-bat.php" accept-charset="<?=$charset?>">
    <input type="hidden" name="charset" value="<?=$charset?>">
    <fieldset>
        <legend>Launcher Options</legend>
        <label>Header: <input type="text" name="header" placeholder="Application Launcher" value="<?=$header?>"></label><br>
        <label>List Header: <input type="text" name="list_header" placeholder="Available applications:" value="<?=$list_header?>"></label><br>
        <label>Prompt: <input type="text" name="prompt" placeholder="Select an application by typing the number and pressing &quot;Enter&quot; (default=1):" size="50" value="<?=$prompt?>"></label><br>
        <label>Background text color:
            <select name="bgcolor">
                <option value="0" <?=selected('0', $bgcolor)?>>Black</option>
                <option value="1" <?=selected('1', $bgcolor)?>>Blue</option>
                <option value="2" <?=selected('2', $bgcolor)?>>Green</option>
                <option value="3" <?=selected('3', $bgcolor)?>>Aqua</option>
                <option value="4" <?=selected('4', $bgcolor)?>>Red</option>
                <option value="5" <?=selected('5', $bgcolor)?>>Purple</option>
                <option value="6" <?=selected('6', $bgcolor)?>>Yellow</option>
                <option value="7" <?=selected('7', $bgcolor)?>>White</option>
                <option value="8" <?=selected('8', $bgcolor)?>>Gray</option>
                <option value="9" <?=selected('9', $bgcolor)?>>Light blue</option>
                <option value="a" <?=selected('a', $bgcolor)?>>Light green</option>
                <option value="b" <?=selected('b', $bgcolor)?>>Light aqua</option>
                <option value="c" <?=selected('c', $bgcolor)?>>Light red</option>
                <option value="d" <?=selected('d', $bgcolor)?>>Light purple</option>
                <option value="e" <?=selected('e', $bgcolor)?>>Light yellow</option>
                <option value="f" <?=selected('f', $bgcolor)?>>Bright white</option>
            </select>
        </label><br>
        <label>Foreground text color:
            <select name="fgcolor">
                <option value="0" <?=selected('0', $fgcolor)?>>Black</option>
                <option value="1" <?=selected('1', $fgcolor)?>>Blue</option>
                <option value="2" <?=selected('2', $fgcolor)?>>Green</option>
                <option value="3" <?=selected('3', $fgcolor)?>>Aqua</option>
                <option value="4" <?=selected('4', $fgcolor)?>>Red</option>
                <option value="5" <?=selected('5', $fgcolor)?>>Purple</option>
                <option value="6" <?=selected('6', $fgcolor)?>>Yellow</option>
                <option value="7" <?=selected('7', $fgcolor)?>>White</option>
                <option value="8" <?=selected('8', $fgcolor)?>>Gray</option>
                <option value="9" <?=selected('9', $fgcolor)?>>Light blue</option>
                <option value="a" <?=selected('a', $fgcolor)?>>Light green</option>
                <option value="b" <?=selected('b', $fgcolor)?>>Light aqua</option>
                <option value="c" <?=selected('c', $fgcolor)?>>Light red</option>
                <option value="d" <?=selected('d', $fgcolor)?>>Light purple</option>
                <option value="e" <?=selected('e', $fgcolor)?>>Light yellow</option>
                <option value="f" <?=selected('f', $fgcolor)?>>Bright white</option>
            </select>
        </label>
    </fieldset>
    <?php for ($i=1; $i<=$numapps; ++$i): ?>
    <?php $app = isset($apps[$i-1]) ? $apps[$i-1] : new App(); ?>
    <fieldset>
        <legend>
            Application #<?=$i?>
            <?php if ($i == 1): ?> (default) <?php endif; ?>
        </legend>
        <label>Display name: <input type="text" name="name[]" placeholder="App <?=$i?>" value="<?=$app->name?>"></label><br>
        <label>Executable path: <input type="text" name="path[]" placeholder="C:\app.exe" size="64" value="<?=$app->path?>"></label><br>
        <label>Flags (optional): <input type="text" name="flag[]" value="<?=$app->flag?>"></label>
    </fieldset>
    <?php endfor; ?>
    <button type="submit">Generate batch file</button>
</form>

</body>
</html>