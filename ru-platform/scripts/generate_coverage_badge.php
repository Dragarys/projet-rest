<?php

// Usage: php scripts/generate_coverage_badge.php coverage.txt docs/coverage-badge.svg
$in = $argv[1] ?? 'coverage.txt';
$out = $argv[2] ?? 'docs/coverage-badge.svg';
if (! file_exists($in)) {
    fwrite(STDERR, "Coverage input file not found: $in\n");
    exit(2);
}
$txt = file_get_contents($in);
if (preg_match('/Total:\s*([0-9]+(?:\.[0-9]+)?)\s*%/i', $txt, $m)) {
    $pct = $m[1];
} elseif (preg_match('/TOTAL:\s*([0-9]+(?:\.[0-9]+)?)%/i', $txt, $m)) {
    $pct = $m[1];
} else {
    // fallback: try to find line starting with 'Lines'
    if (preg_match('/Lines:\s*([0-9]+(?:\.[0-9]+)?)%/i', $txt, $m)) {
        $pct = $m[1];
    } else {
        fwrite(STDERR, "Could not parse coverage percentage from file\n");
        exit(3);
    }
}
$color = ($pct >= 90) ? '#4c1' : (($pct >= 75) ? '#97CA00' : (($pct >= 60) ? '#dfb317' : '#e05d44'));
$left = 'coverage';
$right = rtrim(rtrim(number_format((float) $pct, 1), '0'), '.').'%';
$svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"120\" height=\"20\">\n".
       "  <rect width=\"120\" height=\"20\" fill=\"#555\"/>\n".
       "  <rect x=\"67\" width=\"53\" height=\"20\" fill=\"$color\"/>\n".
       "  <g fill=\"#fff\" text-anchor=\"middle\" font-family=\"Verdana\" font-size=\"11\">\n".
       "    <text x=\"34\" y=\"14\">$left</text>\n".
       "    <text x=\"92\" y=\"14\">$right</text>\n".
       "  </g>\n</svg>\n";
if (! is_dir(dirname($out))) {
    mkdir(dirname($out), 0755, true);
}
file_put_contents($out, $svg);
fwrite(STDOUT, "Wrote coverage badge to $out (coverage: $pct%)\n");
exit(0);
