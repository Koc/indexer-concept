<?php

namespace App\Command;

use App\Job\ReindexChangedProducts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncIndexCommand extends Command
{
    protected static $defaultName = 'app:sync-index';

    private $job;

    public function __construct(ReindexChangedProducts $job)
    {
        $this->job = $job;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->job->execute();
    }
}
