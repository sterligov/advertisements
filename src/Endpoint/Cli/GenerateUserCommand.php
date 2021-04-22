<?php declare(strict_types=1);


namespace App\Endpoint\Cli;


use App\Domain\Entity\User;
use App\Domain\UseCase\UserInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateUserCommand extends Command
{
    public function __construct(
        private UserInterface $user
    ) {
        parent::__construct();
    }

    protected static $defaultName = 'app:create-user';

    protected function configure(): void
    {
        $this
            ->setDescription('Create user')
            ->addOption('email', 'email', InputOption::VALUE_REQUIRED)
            ->addOption('password', 'password', InputOption::VALUE_REQUIRED)
            ->addOption('firstName', 'fname', InputOption::VALUE_REQUIRED)
            ->addOption('lastName', 'lname', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $user->setAttributes($input->getOptions());

        try {
            $this->user->create($user);
        } catch (\Throwable $e) {
            $output->write($e->getMessage(), true);

            return Command::FAILURE;
        }

        $output->write('CREATED', true);

        return Command::SUCCESS;
    }
}