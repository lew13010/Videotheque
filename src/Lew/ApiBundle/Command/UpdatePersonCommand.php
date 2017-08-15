<?php
/**
 * Created by PhpStorm.
 * User: Lew
 * Date: 15/08/2017
 * Time: 10:50
 */

namespace Lew\ApiBundle\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UpdatePersonCommand extends Command implements ContainerAwareInterface
{
    private $container;
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('lew:update:person')
            ->setDescription('Updating persons')
            ->setHelp('This command allows you to update the data for persons on your databases')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($this->container->get('lew_api.service.api_request')->getUpdatePerson()){
            return true;
        }else{
            return false;
        }
    }
}