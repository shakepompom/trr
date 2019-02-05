<?php

namespace Deployer;

require 'vendor/deployer/deployer/recipe/symfony4.php';

set('application', 'theRussianRules');
set('repository', 'git@bitbucket.org:pluseg/russianrules-web.git');

inventory('config/deployer/hosts.yaml');

// Basic settings
set('default_stage', 'dev');
set('ssh_multiplexing', false);
set('allow_anonymous_stats', false);
set('git_tty', true);

// Shared and writable files/dirs between deploys
set('shared_dirs', ['var/log', 'var/sessions', 'public/uploads', 'var/spool', 'config/jwt']);
set('shared_files', ['.env']);

task('php-fpm:restart', function() {
    run('sudo systemctl restart nginx');
});

task('deploy:assets:install', function () {
    run('{{bin/php}} {{bin/console}} assets:install {{console_options}} {{release_path}}/public');
})->desc('Install bundle assets');

// Frontend
task('deploy:yarn', function () {
    run("cd {{release_path}} && yarn && yarn build");
});

// Additional pre and post deploy jobs
before('deploy:symlink', 'database:migrate');
before('deploy:symlink', 'deploy:yarn');
after('deploy:failed', 'deploy:unlock');
after('deploy:symlink', 'php-fpm:restart');