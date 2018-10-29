<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

class CityCcIsoColumnGenerateCommand extends Command
{
    protected static $defaultName = 'app:city:cc_iso_column_generate';
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Will run to all cities in the table, not only those with empty cc_iso')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $time_start = microtime(true); 

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        
        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('all')) {
            // ...
        }
        
        $count_dql = 'select count(c.id) from \App\Entity\City c';
        if ( ! $input->getOption('all') ) {
            $count_dql .= ' WHERE c.cc_fips != :empty_string';
        }

        $count_query = $this->em->createQuery($count_dql);

        if ( ! $input->getOption('all') ) {
            $count_query->setParameter('empty_string', '');
        }

        $total_cities = $count_query->getSingleScalarResult();
        $total_cities_readable = number_format($total_cities);
        
        $city_dql = "select c from \App\Entity\City c";
        if ( ! $input->getOption('all') ) {
            $city_dql .= " WHERE c.cc_fips != :empty_string";
        }
        $city_query = $this->em->createQuery($city_dql)
            // ->setMaxResults( 10 )
        ;
        if ( ! $input->getOption('all') ) {
            $city_query->setParameter('empty_string', '');
        }
        $iterableResult = $city_query->iterate();

        $processed_city = 0;
        $skipped_city_no_fips = 0;
        $skipped_country_nonexistent = 0;
        $skipped_country_iso_empty = 0;
        $skipped_country_iso_dashed = 0;
        $successful = 0;
        foreach ($iterableResult as $row) {
            $processed_city++;
            // do stuff with the data in the row, $row[0] is always the object
            $city = $row[0];
            $name = $city->getFullNameNd();
            $fips = $city->getCcFips();
            $io->text( '[' . number_format($processed_city) . '/' . $total_cities_readable . ']' . ' Processing: ' . $name );
            if ( '' === $fips ) {
                $skipped_city_no_fips++;
                $io->text('> No FIPS; skipping.');
            } else {
                $country_dql = "select c from \App\Entity\Country c where c.cc_fips = :fips";
                $country_query = $this->em->createQuery( $country_dql )
                    ->setParameter('fips', $fips)->setMaxResults( 1 )->getResult();
                $country = ( !empty($country_query) ) ? $country_query[0] : null;
                $iso = trim($country->getCcIso());
                if ( null === $country ) {
                    $skipped_country_nonexistent++;
                    $io->text('> No associated country found; skipping.');
                }
                elseif ( '' === $iso ) {
                    $skipped_country_iso_empty++;
                    $io->text('> Country has empty cc_iso; skipping.');
                }
                elseif ( '-' === $iso ) {
                    $skipped_country_iso_dashed++;
                    $io->text('> Country has dashed cc_iso; skipping.');
                }
                else {
                    $city->setCcIso($iso);
                    $this->em->persist($city);
                    $this->em->flush();
                    $successful++;
                    $io->text('> ISO detected: ' . $iso);
                }
                $io->newLine();
            }
            
        
            // detach all entities from Doctrine, so that Garbage-Collection can kick in immediately
            $this->em->clear();
        }

        $io->success('Total proccessed: ' . number_format( $processed_city ) . '( ' . number_format( $processed_city / $total_cities * 100 , 5 ) . '% )');
        $io->text('Skipped City with no cc_fips: ' . number_format( $skipped_city_no_fips ));
        $io->text('Skipped associated Country does not exist: ' . number_format( $skipped_country_nonexistent ));
        $io->text('Skipped Country cc_iso empty: ' . number_format( $skipped_country_iso_empty ));
        $io->text('Skipped Country cc_iso dashed: ' . number_format( $skipped_country_iso_dashed ));
        $io->success('Successful: ' . number_format( $successful ) . '( ' . number_format( $successful / $total_cities * 100, 5 ) . '% )');

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start)/60;
        $io->text('Total execution time: ' . number_format((float) $execution_time, 10) . ' mins.' );

    }
}
