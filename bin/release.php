<?php

require dirname(__DIR__) . "/vendor/autoload.php";

if(!isset($argv[1])) {
    echo "You must enter VERSION no\n";
    exit;
}

$version = $argv[1];

$dotenv = \Dotenv\Dotenv::create(dirname(__DIR__));
$dotenv->load();

$graphjs_website_path = getenv("GRAPHJS_WEBSITE_PATH");
$groups_frontend_path = getenv("GROUPS_FRONTEND_PATH");
$dist_dir = getenv("DIST_DIR");
$me = dirname(__DIR__);

echo "Starting\n";

file_put_contents($me."/VERSION", $version);

$groupsv2_op = "cd {$me} && NEWVERSION={$version} && modules=groupsv2 npm run build && cp {$dist_dir}/graph.js* {$groups_frontend_path}/site/vendor/graphjs/ && cd {$groups_frontend_path} && git commit -am \$NEWVERSION && git tag graphjs-\$NEWVERSION && git push && git push --tag";
exec($groupsv2_op);

$graphjs_website_op = "cd {$me} && NEWVERSION={$version} && modules=all npm run build && mkdir {$graphjs_website_path}/app/dist/\$NEWVERSION && cp {$dist_dir}/graph.js* {$graphjs_website_path}/app/dist/\$NEWVERSION && cd {$graphjs_website_path} && git add app/dist/\$NEWVERSION && git commit -am \$NEWVERSION && git tag graphjs-\$NEWVERSION && git push && git push --tags";
exec($graphjs_website_op);

echo "Complete...\n";