<?php


namespace Wevioo\WeviooCacheBundle\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;


class ConfigCacheCommand extends Command
{
    protected static $defaultName = 'config-cache';

    protected function configure()
    {
        $this->setDescription('Create a new configuration');
        $this->setHelp('This command allows you to create a new configuration for cache');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');

        $typeQuestion = new Question("Enter the type of storage you want to use for the cache : (redis) ", "redis");
        $type = $helper->ask($input, $output, $typeQuestion);

        if($type == 'redis'){
            $hostQuestion = new Question("Enter the host : (localhost) ", "localhost");
            $host = $helper->ask($input, $output, $hostQuestion);
            $output->writeln($host);
            $portQuestion = new Question("Enter the port : (6379) ", "6379");
            $port = $helper->ask($input, $output, $portQuestion);
            $output->writeln($port);

            $array = [
                $type => ['host' => $host, 'port' => $port],
            ];

            $yaml = Yaml::dump($array);

            file_put_contents('./config/wevioo_cache.yaml', $yaml);
        }
        else if($type == 'mysql'){
            $hostQuestion = new Question("Enter the host : (localhost) ", "localhost");
            $host = $helper->ask($input, $output, $hostQuestion);
            $output->writeln($host);

            $portQuestion = new Question("Enter the port : (3306) ", "3306");
            $port = $helper->ask($input, $output, $portQuestion);
            $output->writeln($port);

            $userQuestion = new Question("Enter the database user : (root) ", "root");
            $user = $helper->ask($input, $output, $userQuestion);
            $output->writeln($user);

            $pwdQuestion = new Question('Enter the database password : (" ") ', '');
            $pwdQuestion->setHidden(true);
            $pwd = $helper->ask($input, $output, $pwdQuestion);
            //$output->writeln($pwd);

            $dbQuestion = new Question('Enter the database name : (cache_data) ', 'cache_data');
            $db = $helper->ask($input, $output, $dbQuestion);
            $output->writeln($db);

            $array = [
                 $type => ['host' => $host, 'port' => $port, 'user' => $user, 'password' => $pwd, 'db_name' => $db],
            ];

            $yaml = Yaml::dump($array);

            file_put_contents('./config/wevioo_cache.yaml', $yaml);
        }
        else {
            $output->writeln('<error>Error type</error>');
            return 0;
        }
        return 0;
    }

}