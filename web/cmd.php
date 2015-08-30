<html>
<head></head>
<body>
<?
ini_set("error_log", __DIR__."/../app/logs/php-error-cmd.log");

function getStreamContents($stream) {
    $contents = explode("\n", stream_get_contents($stream));
    array_shift($contents);
    return implode("\n", $contents);
}

function execute($cmd, $args = array(), $print = true) {
    $descriptorspec = array(
       0 => array("pipe", "r"),
       1 => array("pipe", "w"),
       2 => array("pipe", "w")
    );

    $process = @proc_open($cmd, $descriptorspec, $pipes, __DIR__.'/..');

    if (is_resource($process)) {
        if ($print) echo '<h3>Command: `'.$cmd.'`</h3><br/>';
        if ($print) echo '<code>';

        foreach ($args as $arg) {
            // fwrite($pipes[0], $arg);
        }
        fclose($pipes[0]);

        $output = getStreamContents($pipes[1]);
        fclose($pipes[1]);
        if ($print) echo '<span style="color: blue;">--- output ---</span><br/>';
        if ($print) echo str_replace("\n", '<br/>', $output).'<br/>';
        if ($print) echo '<span style="color: blue;">--------------</span><br/><br/>';

        $errors = getStreamContents($pipes[2]);
        fclose($pipes[2]);
        if ($print) echo '<span style="color: red;">--- errors ---</span><br/>';
        if ($print) echo str_replace("\n", '<br/>', $errors).'<br/>';
        if ($print) echo '<span style="color: red;">--------------</span><br/><br/>';

        // Close any pipes before calling
        // proc_close in order to avoid a deadlock
        $return_value = proc_close($process);

        if ($print) echo '<span style="color: purple;">command returned '.$return_value.'</span><br/>';
        if ($print) echo '</code>';

        return array(
            $return_value,
            $output,
            $errors
        );
    }
}

function ls($dir, $print = false) {
    $lines = explode("\n", execute('ls -lah --time-style long-iso "'.$dir.'"', array(), $print)[1]);

    array_walk($lines, function(&$line, $i) {
        $matches = array();
        $r = preg_match(
            '#^(?<file_type>[-dlpscbD])'.
            '(?<o_permissions>[-rwxstST]{3})'.
            '(?<g_permissions>[-rwxstST]{3})'.
            '(?<u_permissions>[-rwxstST]{3})'.
            '(?<suffix>[\.+@])?\s+'.
            '(?<n_hard_links>\d+)\s+'.
            '(?<owner>[^\s\0]+)\s+'.
            '(?<group>[^\s\0]+)\s+'.
            '(?<size>[^\s]+)\s+'.
            '(?<date>\d{4}-\d\d-\d\d)\s+'.
            '(?<time>\d\d:\d\d)\s+'.
            '(?<file_name>(?:[^\0\n\/]+?(?=[^\\\]\s+->\s)[^\s]?)|[^\0\n\/]+)(?:\s->\s)?(?<file_link>.*)$#',
            $line, $matches
        );
        if ($r !== 1) return array();

        $line = array_intersect_key($matches, array_flip(array(
            'file_type',
            'o_permissions',
            'g_permissions',
            'u_permissions',
            'suffix',
            'n_hard_links',
            'owner',
            'group',
            'size',
            'date',
            'time',
            'file_name',
            'file_link'
        )));
    });

    return $lines;
}

function resolvePath($path) {
    return preg_replace(array('/[^\/]+\/\.\.\/?/', '/\/\.$/'), array('', ''), $path);
}

// execute('php app/console d:m:m -n');
execute('rm -fr app/cache/*');
execute('php app/console ca:cl -e prod');
// execute('php app/console assets:i --symlink -e prod');
// execute('php app/console asseti:d -e prod');

?>
</body>
</html>