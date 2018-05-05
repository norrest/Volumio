<?php
$output = shell_exec('sudo bash /sbin/mpd-update');
echo "<big><b><pre>$output</pre></b></big>";
?>
