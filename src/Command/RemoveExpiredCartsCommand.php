<?php

namespace App\Command;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveExpiredCartsCommand extends Command
{
    /**
     * @var EntityManagerInterface 
     */
    private $entityManager;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    protected static $defaultName = 'app:remove-expired-carts';
    protected static $defaultDescription = 'Removes carts that have been inactive for a defined period';

    public function __construct(EntityManagerInterface $entityManager, OrderRepository $orderRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Removes carts that have been inactive for a defined period')
            ->addArgument(
                'days',
                InputArgument::OPTIONAL,
                'The number of days a cart can remain inactive',
                2
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $days = $input->getArgument('days');

        if ($days <= 0) {
            $io->error('The number of days must be a positive integer.');
            return Command::FAILURE;
        }

        $limitDate = new \DateTime("- $days days");
        $expiredCartsCount = 0;

        while ($carts = $this->orderRepository->findCartNotModifiedSince($limitDate)) {
            foreach ($carts as $cart){
                $this->entityManager->remove($cart);
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
            $expiredCartsCount += count($carts);
        };

        if($expiredCartsCount) {
            $io->success("$expiredCartsCount expired cart(s) have been removed.");  
        } else {
            $io->info('No expired carts found to remove.');
        }

        return Command::SUCCESS;
    }
}
