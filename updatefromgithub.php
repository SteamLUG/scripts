<?php
        error_reporting(0);
        try
        {
                // Decode the payload json string
                $payload = json_decode($_REQUEST['payload']);
        }
        catch(Exception $e)
        {
                exit(0);
        }
        // Pushed to master?
        if ($payload->ref === 'refs/heads/master' || $payload->ref === 'refs/heads/live')
        {
                $url = str_replace('https://', 'git://', $payload->repository->url);
                $LOCAL_ROOT = "/var/www/cenobite.swordfischer.com/clones";
                $LOCAL_REPO_NAME = $payload->repository->name;
                $LOCAL_REPO = "{$LOCAL_ROOT}/{$LOCAL_REPO_NAME}";
                $REMOTE_REPO = $url;
                $BRANCH = str_replace('refs/heads/', '', $payload->ref);
                if (file_exists("{$LOCAL_REPO}.{$BRANCH}")) {
                   shell_exec("rm -rf {$LOCAL_REPO}.{$BRANCH}");
                }
                echo shell_exec("cd {$LOCAL_ROOT} && git clone {$REMOTE_REPO} {$LOCAL_REPO}.{$BRANCH} && cd {$LOCAL_REPO}.{$BRANCH} && git checkout {$BRANCH}");
                // Log the payload object
                file_put_contents('logs/github.txt', print_r($payload, TRUE), FILE_APPEND);

                // Prep the URL - replace https protocol with git protocol to prevent 'update-server-info' errors
        }
?>
