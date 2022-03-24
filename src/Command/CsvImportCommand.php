<?php

namespace App\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;


class CsvImportCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CsvImportCommand constructor.
     *
     * @param EntityManagerInterface $em
     *
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }
    
    /**
     * Configure
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('csv:import-imdbmovies')
            ->setDescription('Imports the IMDB file')
            ->addArgument('rows', InputArgument::REQUIRED, 'process  all/some rows (all|50)')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $iterateRows = $input->getArgument('rows');
        if (!is_numeric($iterateRows) && ($iterateRows !== 'all')) {
            $output->writeln('You wrote [' . $iterateRows . '], only acepts "all" or a number [all|50] ');
            return Command::FAILURE;
        } 

        $paramsProcess = [
            'entityModel' => 'Movie',
            'entityCols' => ['title', 'date_published', 'genre', 'duration', 'production_company' ],
            'validateEntity' => ['title', 'date_published', 'genre'], 
            'expectedValidateEntityPos' => [],           
            'entityColsPos' => [],
            'entityRelatedModel' => ['director' => 'Director', 'actors' => 'Actor'],
            'entityRelatedCols' => ['director', 'actors'],
            'entityRelatedColsPos' => [],
            'fieldsRelated' => ['director' => 'name', 'actors' => 'name'],            
            'relationshipModel' => ['director' => 'MovieDirector', 'actors' => 'MovieActor'],
            
        ];
        $processData = [
            'movie' => 0,
            'movieExcludes' => 0,
            'actors' => 0,
            'actorsExcludes' => 0,
            'director' => 0,
            'directorExcludes' => 0
        ];

        $output->writeln([
            '=====================',
            'Import CSV IMDbmovies',
            '=====================',
            '',
        ]);

        $output->writeln('Search for the file: IMDbmovies.csv');

        $finder = new Finder();
        $finder->files()
        ->in('../')
        ->name('IMDbmovies.csv');

        $output->writeln('Read and process the file .... ');
        if (is_numeric($iterateRows)) {
            $output->writeln('Processing '. $iterateRows .' rows');
        }
        $progressBar = new ProgressBar($output);
        $csv = null;
        foreach ($finder as $file) { $csv = $file; }
        
        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            $i = 0;            
            while (($data = fgetcsv($handle, null, ",", '"')) !== FALSE) {
                $i++;
                if ($i == 1) { // header, get the positions of columns to use
                    foreach($paramsProcess['entityCols'] as $ec){
                        $paramsProcess['entityColsPos'][] = array_search($ec, $data);
                    }
                    foreach($paramsProcess['entityRelatedCols'] as $ec){
                        $paramsProcess['entityRelatedColsPos'][] = array_search($ec, $data);
                    }
                    foreach($paramsProcess['validateEntity'] as $ec){
                        $paramsProcess['expectedValidateEntityPos'][] = array_search($ec, $data);
                    }                    
                    continue; 
                }  
                  
                if (is_numeric($iterateRows)) {
                    if ($i === $iterateRows){
                        break;
                    }
                } 
                $this->generateData($paramsProcess, $data, $processData,$output);
                $processData['movie'] = $processData['movie'] + 1;
                
            }
            fclose($handle);
        }
        $progressBar->finish();
        $output->writeln(' ');
        $output->writeln('----------- File processed ---------');
        $output->writeln($processData['movie'] . ' rows');
        $output->writeln($processData['movie'] . ' new movies');
        $output->writeln($processData['movieExcludes'] . ' excluded movies');
        $output->writeln($processData['actors'] . ' new actors');
        $output->writeln($processData['actorsExcludes'] . ' excluded actors');
        $output->writeln($processData['director'] . ' new directors');
        $output->writeln($processData['directorExcludes'] . ' excluded directors');
        $output->writeln('-----------------------------------');
        return Command::SUCCESS;
    }

    private function generateData($paramsProcess, $data, &$processData, $output){
        // first process relates
        foreach( $paramsProcess['entityRelatedColsPos']  as $idx => $pos){
            $related = $paramsProcess['entityRelatedCols'][$idx]; // actors - director
            $field = $paramsProcess['fieldsRelated'][$related]; // name - name

            // get entity name
            $entityName = $paramsProcess['entityRelatedModel'][$related];

            // get the values of related, comma separates
            $value = strpos($data[$pos], ',') !== false ? explode(',', $data[$pos]) : [$data[$pos]] ;
            foreach ($value as $v) {
                // validate exist by name 
                $qb = $this->em->createQueryBuilder();              
                $qb->select('e')
                ->from("App\\Entity\\{$entityName}", 'e')
                ->where("lower(e.{$field}) = lower(?1)")
                ->setParameter(1, $v);
                        
                $query = $qb->getQuery();
                $result = $query->getArrayResult();
                $pathName = "App\\Entity\\{$entityName}";
                if (count($result) === 0){
                    $processData[$related] = (isset($processData[$related]) ? $processData[$related] : 0) + 1;
                    $modelRel = ( new $pathName());
                    $setter = "set{$field}";
                    $modelRel->$setter($v);
                    $this->em->persist($modelRel);
                    $relateds[$related][] = ['id'=> $modelRel->getId(), 'entityModel' => $modelRel, 'name' => $pathName];
                } else {
                    $modelRel = $this->em->getRepository($pathName)->find($result[0]['id']);
                    $relateds[$related][] = ['id'=> $result[0]['id'], 'entityModel' => $modelRel, 'name' => $pathName];
                    $processData[$related . 'Excludes'] = (isset($processData[$related . 'Excludes']) ? $processData[$related . 'Excludes'] : 0) + 1;
                }
            }            
        }
        
        // process movies
        $entityNameM = $paramsProcess['entityModel'];
        $pathNameM = "App\\Entity\\{$entityNameM}"; //$entityM
        $validateM = $paramsProcess['validateEntity'];

        // validate exist
        $qb = $this->em->createQueryBuilder();

        $cond = [];
        foreach($validateM as $idx => $v){
            $vd = $data[$paramsProcess['expectedValidateEntityPos'][$idx]];
            if (stripos($v, 'date') !== false){                
                if ($this->isValidDate($vd)){
                    $cond[] = "m.{$this->snakeToCamel($v)} = '{$vd}'";
                } 
            } else {
                $cond[] = "lower(m.{$v}) = lower('" . str_replace("'", "''", $vd) ."')";
            }            
        }
        
        $qb->select('m')
            ->select('count(m.id)')
            ->from($pathNameM, 'm')
            ->where(implode(' AND ', $cond));
                            
        $queryM = $qb->getQuery();
        $resultM = $queryM->getSingleScalarResult();
        $pdm = strtolower($paramsProcess['entityModel']);      

        // insert movie if does not exist
        if ($resultM === 0){
            $entityM = ( new $pathNameM());
            // setters
            foreach(  $paramsProcess['entityColsPos']  as $key => $pos){
                $field = $paramsProcess['entityCols'][$key];
                $setter = "set{$this->snakeToCamel($field)}";
                if (stripos($field, 'date') !== false){
                    if ($data[$pos]){
                        if ($this->isValidDate($data[$pos])){
                            $entityM->$setter(\DateTime::createFromFormat('Y-m-d', $data[$pos]) );
                        }
                    }
                } else {
                    $entityM->$setter($data[$pos]);
                }
            }    
            $this->em->persist($entityM);
            
            // relateds
            foreach( $paramsProcess['entityRelatedColsPos']  as $idx => $pos){
                $related = $paramsProcess['entityRelatedCols'][$idx]; // actors - director
                $nameRel = "App\\Entity\\" . $paramsProcess['relationshipModel'][$related]; // moviedirector - movieactor
                foreach( $relateds[$related] as $r){
                    $entityRel = ( new $nameRel());
                    foreach( [$paramsProcess['entityRelatedModel'][$related]] as $rf){
                        $setterR = "set{$rf}";
                        $entityRel->$setterR($r['entityModel']);
                        
                    }
                    $entityRel->setMovie($entityM);
                    $this->em->persist($entityRel);
                    
                }
            }   
              
            $processData[$pdm] = (isset($processData[$pdm]) ? $processData[$pdm] : 0) + 1;
        } else {
            $processData[$pdm . 'Excludes'] = (isset($processData[$pdm . 'Excludes']) ? $processData[$pdm . 'Excludes'] : 0) + 1;
        }
     
        $this->em->flush();
    }

    function camel_to_snake($input)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
    function snakeToCamel($input)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

    function isValidDate($date, $format = 'Y-m-d'){
        $dt = \DateTime::createFromFormat($format, $date);
        return $dt && $dt->format($format) === $date;
      }
    
}
